<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Category;
use App\Models\GymPackage;
use App\Models\Member;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\StockMutation;
use App\Models\Supplement;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PosController extends Controller
{
    public function index()
    {
        $categories = Category::all();

        $supplements = Supplement::with('category')
            ->where('is_active', true)
            ->orderBy('name', 'asc')
            ->get();

        $gymPackages = GymPackage::where('is_active', true)
            ->orderBy('price', 'asc')
            ->get();

        $members = Member::where('status', 'active')
            ->orderBy('name', 'asc')
            ->get();

        $unpaidBookings = Booking::with(['trainer', 'fitnessClass'])
            ->where('payment_status', 'unpaid')
            ->where('status', '!=', 'cancelled')
            ->orderBy('booking_date', 'asc')
            ->get();

        return view('pos.index', compact('categories', 'supplements', 'gymPackages', 'members', 'unpaidBookings'));
    }

    public function storeOrder(Request $request)
    {
        $request->validate([
            'items' => 'required|array|min:1',
            'items.*.type' => 'required|in:supplement,gym_package,booking',
            'items.*.id' => 'required',
            'items.*.name' => 'required|string',
            'items.*.price' => 'required|numeric|min:0',
            'items.*.quantity' => 'required|integer|min:1',
            'payment_method' => 'required|in:cash,qris,transfer,debit',
            'cash_received' => 'nullable|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
            'tax' => 'nullable|numeric|min:0',
            'customer_name' => 'nullable|string',
            'customer_type' => 'required|in:walk_in,member',
            'member_id' => 'nullable|exists:members,id',
            'notes' => 'nullable|string',
        ]);

        $items = $request->input('items');
        $discount = (float) $request->input('discount', 0);
        $tax = (float) $request->input('tax', 0);
        $paymentMethod = $request->input('payment_method');
        $cashReceived = (float) $request->input('cash_received', 0);
        $memberId = $request->input('member_id');
        $customerType = $request->input('customer_type', 'walk_in');

        // Check stock availability first
        foreach ($items as $item) {
            if ($item['type'] === 'supplement') {
                $supplement = Supplement::find($item['id']);
                if (! $supplement) {
                    return response()->json([
                        'success' => false,
                        'message' => "Produk suplemen tidak ditemukan.",
                    ], 422);
                }
                if ($supplement->stock < $item['quantity']) {
                    return response()->json([
                        'success' => false,
                        'message' => "Stok suplemen '{$supplement->name}' tidak mencukupi! Sisa stok: {$supplement->stock}, diminta: {$item['quantity']}.",
                    ], 422);
                }
            }
        }

        // Calculate subtotal
        $subtotal = 0;
        foreach ($items as $item) {
            $subtotal += ($item['price'] * $item['quantity']);
        }

        $totalAmount = max(0, $subtotal - $discount + $tax);

        if ($paymentMethod === 'cash') {
            if ($cashReceived < $totalAmount) {
                return response()->json([
                    'success' => false,
                    'message' => "Jumlah uang tunai yang diterima (Rp " . number_format($cashReceived, 0, ',', '.') . ") kurang dari total belanja (Rp " . number_format($totalAmount, 0, ',', '.') . ").",
                ], 422);
            }
            $cashChange = $cashReceived - $totalAmount;
        } else {
            $cashReceived = $totalAmount;
            $cashChange = 0;
        }

        // Customer name resolution
        $customerName = $request->input('customer_name');
        if ($customerType === 'member' && $memberId) {
            $member = Member::find($memberId);
            if ($member) {
                $customerName = $member->name . ' (' . $member->member_code . ')';
            }
        }
        if (empty($customerName)) {
            $customerName = 'Tamu Umum (Walk-in)';
        }

        try {
            DB::beginTransaction();

            // Generate invoice number: INV-YYYYMMDD-XXXX
            $datePrefix = date('Ymd');
            $latestOrder = Order::whereDate('created_at', Carbon::today())->latest('id')->first();
            $sequence = $latestOrder ? (int) substr($latestOrder->invoice_no, -4) + 1 : 1;
            $invoiceNo = 'INV-' . $datePrefix . '-' . str_pad($sequence, 4, '0', STR_PAD_LEFT);

            $order = Order::create([
                'invoice_no' => $invoiceNo,
                'user_id' => Auth::id(),
                'member_id' => $customerType === 'member' ? $memberId : null,
                'customer_name' => $customerName,
                'customer_type' => $customerType,
                'subtotal' => $subtotal,
                'discount' => $discount,
                'tax' => $tax,
                'total_amount' => $totalAmount,
                'payment_method' => $paymentMethod,
                'cash_received' => $cashReceived,
                'cash_change' => $cashChange,
                'status' => 'completed',
                'notes' => $request->input('notes'),
            ]);

            foreach ($items as $item) {
                $costPrice = 0;
                $supplementId = null;
                $gymPackageId = null;
                $bookingId = null;

                if ($item['type'] === 'supplement') {
                    $supplement = Supplement::lockForUpdate()->find($item['id']);
                    $costPrice = $supplement->purchase_price;
                    $supplementId = $supplement->id;

                    $stockBefore = $supplement->stock;
                    $stockAfter = $stockBefore - $item['quantity'];

                    // Update supplement stock
                    $supplement->update(['stock' => $stockAfter]);

                    // Record stock mutation
                    StockMutation::create([
                        'supplement_id' => $supplement->id,
                        'user_id' => Auth::id(),
                        'type' => 'out',
                        'quantity' => $item['quantity'],
                        'stock_before' => $stockBefore,
                        'stock_after' => $stockAfter,
                        'reference_type' => 'pos_sale',
                        'reference_id' => $order->invoice_no,
                        'cost_per_unit' => $costPrice,
                        'notes' => 'Penjualan POS Invoice #' . $order->invoice_no,
                    ]);
                } elseif ($item['type'] === 'gym_package') {
                    $pkg = GymPackage::find($item['id']);
                    $gymPackageId = $pkg ? $pkg->id : null;

                    // If member bought a package, update member expiry
                    if ($memberId && $pkg && $pkg->duration_days > 1) {
                        $member = Member::find($memberId);
                        if ($member) {
                            $startDate = ($member->membership_expires_at && $member->membership_expires_at->isFuture())
                                ? $member->membership_expires_at
                                : Carbon::today();
                            $member->update([
                                'membership_type' => $pkg->type,
                                'membership_start' => $member->membership_start ?: Carbon::today(),
                                'membership_expires_at' => $startDate->copy()->addDays($pkg->duration_days),
                                'status' => 'active',
                            ]);
                        }
                    }
                } elseif ($item['type'] === 'booking') {
                    $booking = Booking::find($item['id']);
                    if ($booking) {
                        $bookingId = $booking->id;
                        $booking->update([
                            'payment_status' => 'paid',
                            'status' => ($booking->status === 'pending') ? 'confirmed' : $booking->status,
                        ]);
                    }
                }

                OrderItem::create([
                    'order_id' => $order->id,
                    'item_type' => $item['type'],
                    'supplement_id' => $supplementId,
                    'gym_package_id' => $gymPackageId,
                    'booking_id' => $bookingId,
                    'name' => $item['name'],
                    'unit_price' => $item['price'],
                    'cost_price' => $costPrice,
                    'quantity' => $item['quantity'],
                    'subtotal' => $item['price'] * $item['quantity'],
                ]);
            }

            DB::commit();

            $order->load(['items', 'user', 'member']);

            return response()->json([
                'success' => true,
                'message' => 'Transaksi berhasil diproses!',
                'order' => $order,
                'receipt_url' => route('pos.receipt', $order->id),
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Gagal memproses transaksi: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function receipt(Order $order)
    {
        $order->load(['items', 'user', 'member']);

        return view('pos.receipt', compact('order'));
    }

    public function ordersHistory(Request $request)
    {
        $query = Order::with(['items', 'user', 'member'])->latest();

        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        }

        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('invoice_no', 'like', "%{$search}%")
                  ->orWhere('customer_name', 'like', "%{$search}%");
            });
        }

        $orders = $query->paginate(15)->withQueryString();

        $todayTotal = Order::whereDate('created_at', Carbon::today())
            ->where('status', 'completed')
            ->sum('total_amount');

        return view('pos.orders', compact('orders', 'todayTotal'));
    }

    public function cancelOrder(Request $request, Order $order)
    {
        $user = Auth::user();
        if (! $user->isOwner()) {
            return back()->with('error', 'Hanya Owner yang dapat membatalkan transaksi.');
        }

        if ($order->status === 'cancelled') {
            return back()->with('info', 'Transaksi ini sudah dibatalkan sebelumnya.');
        }

        try {
            DB::beginTransaction();

            $order->update(['status' => 'cancelled']);

            // Revert supplement stock
            foreach ($order->items as $item) {
                if ($item->item_type === 'supplement' && $item->supplement_id) {
                    $supplement = Supplement::lockForUpdate()->find($item->supplement_id);
                    if ($supplement) {
                        $stockBefore = $supplement->stock;
                        $stockAfter = $stockBefore + $item->quantity;
                        $supplement->update(['stock' => $stockAfter]);

                        StockMutation::create([
                            'supplement_id' => $supplement->id,
                            'user_id' => Auth::id(),
                            'type' => 'in',
                            'quantity' => $item->quantity,
                            'stock_before' => $stockBefore,
                            'stock_after' => $stockAfter,
                            'reference_type' => 'manual_audit',
                            'reference_id' => $order->invoice_no,
                            'notes' => 'Pengembalian stok pembatalan Invoice #' . $order->invoice_no,
                        ]);
                    }
                }
            }

            DB::commit();

            return back()->with('success', "Transaksi #{$order->invoice_no} berhasil dibatalkan dan stok telah dikembalikan.");
        } catch (\Throwable $e) {
            DB::rollBack();

            return back()->with('error', 'Gagal membatalkan transaksi: ' . $e->getMessage());
        }
    }
}
