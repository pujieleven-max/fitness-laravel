<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Member;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Supplement;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $today = Carbon::today();
        $startOfMonth = Carbon::now()->startOfMonth();

        // Common metrics
        $todayOrdersCount = Order::whereDate('created_at', $today)->count();
        $todayOrdersRevenue = Order::whereDate('created_at', $today)->where('status', 'completed')->sum('total_amount');
        
        $todayBookings = Booking::with(['trainer', 'fitnessClass', 'member'])
            ->whereDate('booking_date', $today)
            ->orderBy('start_time', 'asc')
            ->get();

        $lowStockSupplements = Supplement::with('category')
            ->where('is_active', true)
            ->where(function ($q) {
                $q->whereColumn('stock', '<=', 'min_stock_alert')
                  ->orWhere('stock', '<=', 0);
            })
            ->orderBy('stock', 'asc')
            ->take(5)
            ->get();

        $expiringSupplements = Supplement::where('is_active', true)
            ->whereNotNull('expiry_date')
            ->where('expiry_date', '<=', Carbon::now()->addDays(30))
            ->orderBy('expiry_date', 'asc')
            ->take(5)
            ->get();

        // Recent orders
        $recentOrders = Order::with(['user', 'items'])
            ->latest()
            ->take(6)
            ->get();

        if ($user->isOwner()) {
            // Month metrics
            $monthOrdersRevenue = Order::where('created_at', '>=', $startOfMonth)
                ->where('status', 'completed')
                ->sum('total_amount');

            // Total stock asset value
            $totalStockValue = Supplement::where('is_active', true)
                ->select(DB::raw('SUM(stock * purchase_price) as total_value'))
                ->value('total_value') ?? 0;

            // Estimated profit for this month: SUM((unit_price - cost_price) * quantity)
            $estimatedMonthProfit = OrderItem::whereHas('order', function ($q) use ($startOfMonth) {
                    $q->where('created_at', '>=', $startOfMonth)->where('status', 'completed');
                })
                ->select(DB::raw('SUM((unit_price - cost_price) * quantity) as total_profit'))
                ->value('total_profit') ?? 0;

            $totalMembers = Member::count();
            $activeMembers = Member::where('status', 'active')->count();

            // 7 Days Sales Trend
            $salesTrend = [];
            for ($i = 6; $i >= 0; $i--) {
                $date = Carbon::today()->subDays($i);
                $dailyTotal = Order::whereDate('created_at', $date)
                    ->where('status', 'completed')
                    ->sum('total_amount');

                $salesTrend[] = [
                    'date' => $date->translatedFormat('d M'),
                    'total' => (float) $dailyTotal,
                ];
            }

            // Top Selling Supplements
            $topSupplements = OrderItem::where('item_type', 'supplement')
                ->whereNotNull('supplement_id')
                ->select('supplement_id', 'name', DB::raw('SUM(quantity) as total_sold'), DB::raw('SUM(subtotal) as total_revenue'))
                ->groupBy('supplement_id', 'name')
                ->orderByDesc('total_sold')
                ->take(5)
                ->get();

            return view('dashboard.owner', compact(
                'todayOrdersCount',
                'todayOrdersRevenue',
                'monthOrdersRevenue',
                'estimatedMonthProfit',
                'totalStockValue',
                'totalMembers',
                'activeMembers',
                'todayBookings',
                'lowStockSupplements',
                'expiringSupplements',
                'recentOrders',
                'salesTrend',
                'topSupplements'
            ));
        }

        // Cashier Metrics
        $cashierTodayCash = Order::whereDate('created_at', $today)
            ->where('user_id', $user->id)
            ->where('payment_method', 'cash')
            ->where('status', 'completed')
            ->sum('total_amount');

        $cashierTodayNonCash = Order::whereDate('created_at', $today)
            ->where('user_id', $user->id)
            ->whereIn('payment_method', ['qris', 'transfer', 'debit'])
            ->where('status', 'completed')
            ->sum('total_amount');

        $cashierTotalToday = $cashierTodayCash + $cashierTodayNonCash;

        return view('dashboard.kasir', compact(
            'todayOrdersCount',
            'todayOrdersRevenue',
            'cashierTodayCash',
            'cashierTodayNonCash',
            'cashierTotalToday',
            'todayBookings',
            'lowStockSupplements',
            'recentOrders'
        ));
    }
}
