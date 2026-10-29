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
        $yesterday = Carbon::yesterday();
        $startOfMonth = Carbon::now()->startOfMonth();
        $startOfLastMonth = Carbon::now()->subMonthNoOverflow()->startOfMonth();
        $endOfLastMonth = Carbon::now()->subMonthNoOverflow()->endOfMonth();

        // Common metrics
        $todayOrdersCount = Order::whereDate('created_at', $today)->where('status', 'completed')->count();
        $todayOrdersRevenue = (float) Order::whereDate('created_at', $today)->where('status', 'completed')->sum('total_amount');
        $yesterdayOrdersRevenue = (float) Order::whereDate('created_at', $yesterday)->where('status', 'completed')->sum('total_amount');

        $todayGrowthPercent = $yesterdayOrdersRevenue > 0
            ? round((($todayOrdersRevenue - $yesterdayOrdersRevenue) / $yesterdayOrdersRevenue) * 100, 1)
            : ($todayOrdersRevenue > 0 ? 100.0 : 0.0);

        $avgOrderValueToday = $todayOrdersCount > 0 ? round($todayOrdersRevenue / $todayOrdersCount) : 0;

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
            ->take(8)
            ->get();

        $expiringSupplements = Supplement::where('is_active', true)
            ->whereNotNull('expiry_date')
            ->where('expiry_date', '<=', Carbon::now()->addDays(30))
            ->orderBy('expiry_date', 'asc')
            ->take(8)
            ->get();

        // Members expiring within 7 days (or expired within last 3 days)
        $expiringMembers = Member::whereNotNull('membership_expires_at')
            ->whereBetween('membership_expires_at', [Carbon::today()->subDays(3), Carbon::today()->addDays(7)])
            ->orderBy('membership_expires_at', 'asc')
            ->take(8)
            ->get();

        // Recent orders
        $recentOrders = Order::with(['user', 'items'])
            ->latest()
            ->take(7)
            ->get();

        if ($user->isOwner()) {
            // Month metrics
            $monthOrdersCount = Order::where('created_at', '>=', $startOfMonth)
                ->where('status', 'completed')
                ->count();

            $monthOrdersRevenue = (float) Order::where('created_at', '>=', $startOfMonth)
                ->where('status', 'completed')
                ->sum('total_amount');

            $lastMonthOrdersRevenue = (float) Order::whereBetween('created_at', [$startOfLastMonth, $endOfLastMonth])
                ->where('status', 'completed')
                ->sum('total_amount');

            $monthGrowthPercent = $lastMonthOrdersRevenue > 0
                ? round((($monthOrdersRevenue - $lastMonthOrdersRevenue) / $lastMonthOrdersRevenue) * 100, 1)
                : ($monthOrdersRevenue > 0 ? 100.0 : 0.0);

            // Total stock asset value & retail potential
            $stockValuation = Supplement::where('is_active', true)
                ->select(
                    DB::raw('SUM(stock * purchase_price) as total_cost'),
                    DB::raw('SUM(stock * selling_price) as total_retail'),
                    DB::raw('SUM(stock) as total_units')
                )
                ->first();

            $totalStockValue = (float) ($stockValuation->total_cost ?? 0);
            $totalStockRetailValue = (float) ($stockValuation->total_retail ?? 0);
            $totalStockUnits = (int) ($stockValuation->total_units ?? 0);

            // Estimated profit for this month: SUM((unit_price - cost_price) * quantity)
            $estimatedMonthProfit = (float) (OrderItem::whereHas('order', function ($q) use ($startOfMonth) {
                    $q->where('created_at', '>=', $startOfMonth)->where('status', 'completed');
                })
                ->select(DB::raw('SUM((unit_price - cost_price) * quantity) as total_profit'))
                ->value('total_profit') ?? 0);

            $profitMarginPercent = $monthOrdersRevenue > 0
                ? round(($estimatedMonthProfit / $monthOrdersRevenue) * 100, 1)
                : 0.0;

            $totalMembers = Member::count();
            $activeMembers = Member::where('status', 'active')->count();

            // Helper to build sales & profit trend array
            $buildTrend = function (int $days) {
                $trend = [];
                for ($i = $days - 1; $i >= 0; $i--) {
                    $date = Carbon::today()->subDays($i);
                    $dailyRevenue = (float) Order::whereDate('created_at', $date)
                        ->where('status', 'completed')
                        ->sum('total_amount');

                    $dailyProfit = (float) (OrderItem::whereHas('order', function ($q) use ($date) {
                            $q->whereDate('created_at', $date)->where('status', 'completed');
                        })
                        ->select(DB::raw('SUM((unit_price - cost_price) * quantity) as profit'))
                        ->value('profit') ?? 0);

                    $dailyCount = Order::whereDate('created_at', $date)
                        ->where('status', 'completed')
                        ->count();

                    $trend[] = [
                        'date' => $date->translatedFormat('d M'),
                        'total' => $dailyRevenue,
                        'profit' => $dailyProfit,
                        'count' => $dailyCount,
                    ];
                }
                return $trend;
            };

            $salesTrend = $buildTrend(7);
            $salesTrend30Days = $buildTrend(30);

            // Revenue composition by item_type this month
            $rawCategoryBreakdown = OrderItem::whereHas('order', function ($q) use ($startOfMonth) {
                    $q->where('created_at', '>=', $startOfMonth)->where('status', 'completed');
                })
                ->select(
                    'item_type',
                    DB::raw('SUM(subtotal) as revenue'),
                    DB::raw('SUM(quantity) as qty'),
                    DB::raw('SUM((unit_price - cost_price) * quantity) as profit')
                )
                ->groupBy('item_type')
                ->get()
                ->keyBy('item_type');

            $totalCategoryRev = max(1, (float) $rawCategoryBreakdown->sum('revenue'));
            $revenueByCategory = [
                'supplement' => [
                    'label' => 'Suplemen & Nutrisi',
                    'icon' => 'fa-bottle-droplet',
                    'color' => 'emerald',
                    'revenue' => (float) ($rawCategoryBreakdown['supplement']->revenue ?? 0),
                    'qty' => (int) ($rawCategoryBreakdown['supplement']->qty ?? 0),
                    'percent' => round(((float) ($rawCategoryBreakdown['supplement']->revenue ?? 0) / $totalCategoryRev) * 100, 1),
                ],
                'gym_package' => [
                    'label' => 'Membership & Tiket Gym',
                    'icon' => 'fa-id-card-clip',
                    'color' => 'cyan',
                    'revenue' => (float) ($rawCategoryBreakdown['gym_package']->revenue ?? 0),
                    'qty' => (int) ($rawCategoryBreakdown['gym_package']->qty ?? 0),
                    'percent' => round(((float) ($rawCategoryBreakdown['gym_package']->revenue ?? 0) / $totalCategoryRev) * 100, 1),
                ],
                'booking' => [
                    'label' => 'Booking PT & Kelas Studio',
                    'icon' => 'fa-person-running',
                    'color' => 'amber',
                    'revenue' => (float) ($rawCategoryBreakdown['booking']->revenue ?? 0),
                    'qty' => (int) ($rawCategoryBreakdown['booking']->qty ?? 0),
                    'percent' => round(((float) ($rawCategoryBreakdown['booking']->revenue ?? 0) / $totalCategoryRev) * 100, 1),
                ],
            ];

            // Payment Method Mix this month
            $paymentMethodMix = Order::where('created_at', '>=', $startOfMonth)
                ->where('status', 'completed')
                ->select('payment_method', DB::raw('COUNT(*) as count'), DB::raw('SUM(total_amount) as total'))
                ->groupBy('payment_method')
                ->orderByDesc('total')
                ->get();

            // Top Selling Supplements
            $topSupplements = OrderItem::where('item_type', 'supplement')
                ->whereNotNull('supplement_id')
                ->whereHas('order', function ($q) {
                    $q->where('status', 'completed');
                })
                ->select('supplement_id', 'name', DB::raw('SUM(quantity) as total_sold'), DB::raw('SUM(subtotal) as total_revenue'))
                ->groupBy('supplement_id', 'name')
                ->orderByDesc('total_sold')
                ->take(5)
                ->get();

            return view('dashboard.owner', compact(
                'todayOrdersCount',
                'todayOrdersRevenue',
                'yesterdayOrdersRevenue',
                'todayGrowthPercent',
                'avgOrderValueToday',
                'monthOrdersCount',
                'monthOrdersRevenue',
                'lastMonthOrdersRevenue',
                'monthGrowthPercent',
                'estimatedMonthProfit',
                'profitMarginPercent',
                'totalStockValue',
                'totalStockRetailValue',
                'totalStockUnits',
                'totalMembers',
                'activeMembers',
                'todayBookings',
                'lowStockSupplements',
                'expiringSupplements',
                'expiringMembers',
                'recentOrders',
                'salesTrend',
                'salesTrend30Days',
                'revenueByCategory',
                'paymentMethodMix',
                'topSupplements'
            ));
        }

        // Cashier Metrics
        $cashierOrdersCountToday = Order::whereDate('created_at', $today)
            ->where('user_id', $user->id)
            ->where('status', 'completed')
            ->count();

        $cashierTodayCash = (float) Order::whereDate('created_at', $today)
            ->where('user_id', $user->id)
            ->where('payment_method', 'cash')
            ->where('status', 'completed')
            ->sum('total_amount');

        $cashierTodayNonCash = (float) Order::whereDate('created_at', $today)
            ->where('user_id', $user->id)
            ->whereIn('payment_method', ['qris', 'transfer', 'debit'])
            ->where('status', 'completed')
            ->sum('total_amount');

        $cashierTotalToday = $cashierTodayCash + $cashierTodayNonCash;

        return view('dashboard.kasir', compact(
            'todayOrdersCount',
            'todayOrdersRevenue',
            'cashierOrdersCountToday',
            'cashierTodayCash',
            'cashierTodayNonCash',
            'cashierTotalToday',
            'todayBookings',
            'lowStockSupplements',
            'expiringSupplements',
            'expiringMembers',
            'recentOrders'
        ));
    }
}
