<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\StockMutation;
use App\Models\Supplement;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SupplementController extends Controller
{
    public function index(Request $request)
    {
        $query = Supplement::with('category')->latest();

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('status')) {
            if ($request->status === 'out_of_stock') {
                $query->where('stock', '<=', 0);
            } elseif ($request->status === 'low_stock') {
                $query->where('stock', '>', 0)->whereColumn('stock', '<=', 'min_stock_alert');
            } elseif ($request->status === 'in_stock') {
                $query->whereColumn('stock', '>', 'min_stock_alert');
            } elseif ($request->status === 'expiring_soon') {
                $query->whereNotNull('expiry_date')
                      ->where('expiry_date', '<=', Carbon::now()->addDays(30));
            }
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%")
                  ->orWhere('brand', 'like', "%{$search}%")
                  ->orWhere('flavor', 'like', "%{$search}%");
            });
        }

        $supplements = $query->paginate(12)->withQueryString();
        $categories = Category::all();

        // Summary counts
        $totalProducts = Supplement::where('is_active', true)->count();
        $lowStockCount = Supplement::where('is_active', true)
            ->where('stock', '>', 0)
            ->whereColumn('stock', '<=', 'min_stock_alert')
            ->count();
        $outOfStockCount = Supplement::where('is_active', true)->where('stock', '<=', 0)->count();
        $expiringCount = Supplement::where('is_active', true)
            ->whereNotNull('expiry_date')
            ->where('expiry_date', '<=', Carbon::now()->addDays(30))
            ->count();

        return view('supplements.index', compact(
            'supplements',
            'categories',
            'totalProducts',
            'lowStockCount',
            'outOfStockCount',
            'expiringCount'
        ));
    }

    public function create()
    {
        $categories = Category::all();
        // Generate auto SKU
        $latest = Supplement::latest('id')->first();
        $nextId = $latest ? $latest->id + 1 : 1;
        $suggestedSku = 'SUP-' . str_pad($nextId, 4, '0', STR_PAD_LEFT);

        return view('supplements.create', compact('categories', 'suggestedSku'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'sku' => 'required|string|unique:supplements,sku',
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'brand' => 'nullable|string|max:100',
            'unit' => 'required|string|max:50',
            'flavor' => 'nullable|string|max:100',
            'purchase_price' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'min_stock_alert' => 'required|integer|min:0',
            'expiry_date' => 'nullable|date',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('supplements', 'public');
            $validated['image'] = $path;
        }

        try {
            DB::beginTransaction();

            $supplement = Supplement::create($validated);

            // Record initial stock mutation if stock > 0
            if ($supplement->stock > 0) {
                StockMutation::create([
                    'supplement_id' => $supplement->id,
                    'user_id' => Auth::id(),
                    'type' => 'in',
                    'quantity' => $supplement->stock,
                    'stock_before' => 0,
                    'stock_after' => $supplement->stock,
                    'reference_type' => 'restock',
                    'reference_id' => 'INIT-' . $supplement->sku,
                    'cost_per_unit' => $supplement->purchase_price,
                    'notes' => 'Inisialisasi stok awal produk baru',
                ]);
            }

            DB::commit();

            return redirect()->route('supplements.index')
                ->with('success', "Suplemen '{$supplement->name}' berhasil ditambahkan!");
        } catch (\Throwable $e) {
            DB::rollBack();

            return back()->withInput()->with('error', 'Gagal menambahkan suplemen: ' . $e->getMessage());
        }
    }

    public function edit(Supplement $supplement)
    {
        $categories = Category::all();

        return view('supplements.edit', compact('supplement', 'categories'));
    }

    public function update(Request $request, Supplement $supplement)
    {
        $validated = $request->validate([
            'sku' => 'required|string|unique:supplements,sku,' . $supplement->id,
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'brand' => 'nullable|string|max:100',
            'unit' => 'required|string|max:50',
            'flavor' => 'nullable|string|max:100',
            'purchase_price' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
            'min_stock_alert' => 'required|integer|min:0',
            'expiry_date' => 'nullable|date',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('supplements', 'public');
            $validated['image'] = $path;
        }

        $supplement->update($validated);

        return redirect()->route('supplements.index')
            ->with('success', "Data suplemen '{$supplement->name}' berhasil diperbarui.");
    }

    public function destroy(Supplement $supplement)
    {
        if (! Auth::user()->isOwner()) {
            return back()->with('error', 'Hanya Owner yang berhak menghapus produk suplemen.');
        }

        $name = $supplement->name;
        $supplement->delete();

        return redirect()->route('supplements.index')
            ->with('success', "Suplemen '{$name}' berhasil dihapus.");
    }

    public function restock(Request $request, Supplement $supplement)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
            'cost_per_unit' => 'nullable|numeric|min:0',
            'supplier_notes' => 'nullable|string|max:255',
        ]);

        $qty = (int) $request->quantity;
        $cost = $request->filled('cost_per_unit') ? (float) $request->cost_per_unit : $supplement->purchase_price;
        $stockBefore = $supplement->stock;
        $stockAfter = $stockBefore + $qty;

        try {
            DB::beginTransaction();

            // Update supplement stock and optionally purchase price
            $supplement->update([
                'stock' => $stockAfter,
                'purchase_price' => $cost > 0 ? $cost : $supplement->purchase_price,
            ]);

            StockMutation::create([
                'supplement_id' => $supplement->id,
                'user_id' => Auth::id(),
                'type' => 'in',
                'quantity' => $qty,
                'stock_before' => $stockBefore,
                'stock_after' => $stockAfter,
                'reference_type' => 'restock',
                'cost_per_unit' => $cost,
                'notes' => $request->supplier_notes ?: 'Penerimaan restock barang masuk',
            ]);

            DB::commit();

            return back()->with('success', "Berhasil menambahkan {$qty} {$supplement->unit} ke stok '{$supplement->name}'. Stok sekarang: {$stockAfter}.");
        } catch (\Throwable $e) {
            DB::rollBack();

            return back()->with('error', 'Gagal menambah stok: ' . $e->getMessage());
        }
    }

    public function adjustStock(Request $request, Supplement $supplement)
    {
        $request->validate([
            'actual_stock' => 'required|integer|min:0',
            'reason' => 'required|in:damage,expired,manual_audit',
            'notes' => 'required|string|max:255',
        ]);

        $actualStock = (int) $request->actual_stock;
        $stockBefore = $supplement->stock;
        $diff = $actualStock - $stockBefore;

        if ($diff === 0) {
            return back()->with('info', 'Jumlah stok fisik sama dengan stok sistem, tidak ada perubahan yang dicatat.');
        }

        try {
            DB::beginTransaction();

            $supplement->update(['stock' => $actualStock]);

            StockMutation::create([
                'supplement_id' => $supplement->id,
                'user_id' => Auth::id(),
                'type' => 'adjustment',
                'quantity' => abs($diff),
                'stock_before' => $stockBefore,
                'stock_after' => $actualStock,
                'reference_type' => $request->reason,
                'notes' => "Penyesuaian Opname: {$request->notes} (" . ($diff > 0 ? "+{$diff}" : "{$diff}") . ")",
            ]);

            DB::commit();

            return back()->with('success', "Penyesuaian stok untuk '{$supplement->name}' berhasil disimpan. Stok sistem sekarang: {$actualStock}.");
        } catch (\Throwable $e) {
            DB::rollBack();

            return back()->with('error', 'Gagal melakukan penyesuaian stok: ' . $e->getMessage());
        }
    }

    public function stockHistory(Request $request)
    {
        $query = StockMutation::with(['supplement.category', 'user'])->latest();

        if ($request->filled('supplement_id')) {
            $query->where('supplement_id', $request->supplement_id);
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('reference_type')) {
            $query->where('reference_type', $request->reference_type);
        }

        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        }

        $mutations = $query->paginate(20)->withQueryString();
        $supplements = Supplement::orderBy('name')->get();

        return view('supplements.history', compact('mutations', 'supplements'));
    }
}
