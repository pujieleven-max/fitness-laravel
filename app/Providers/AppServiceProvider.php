<?php

namespace App\Providers;

use App\Models\Booking;
use App\Models\Member;
use App\Models\Setting;
use App\Models\Supplement;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL; // <-- Tambahkan baris ini

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Paksa skema HTTPS jika diakses lewat Cloudflare Tunnel / Proxy
        if (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') {
            URL::forceScheme('https');
        }

        View::composer('*', function ($view) {
            try {
                if (Schema::hasTable('settings')) {
                    $setting = Setting::current();
                } else {
                    $setting = new Setting([
                        'app_name' => 'FitZone Gym',
                        'app_tagline' => 'POS & Fitness Management',
                        'phone' => '0812-3456-7890',
                        'email' => 'info@fitzone.com',
                        'address' => 'Jl. Gatot Subroto No. 88, Jakarta Selatan',
                        'receipt_footer' => "Terima kasih telah berlatih di FitZone Gym!\nTetap disiplin & raih target kebugaran Anda.",
                        'social_instagram' => '@fitzone_gym',
                        'currency_symbol' => 'Rp',
                    ]);
                }
            } catch (\Throwable $e) {
                $setting = new Setting([
                    'app_name' => 'FitZone Gym',
                    'app_tagline' => 'POS & Fitness Management',
                    'phone' => '0812-3456-7890',
                    'email' => 'info@fitzone.com',
                    'address' => 'Jl. Gatot Subroto No. 88, Jakarta Selatan',
                    'receipt_footer' => "Terima kasih telah berlatih di FitZone Gym!\nTetap disiplin & raih target kebugaran Anda.",
                    'social_instagram' => '@fitzone_gym',
                    'currency_symbol' => 'Rp',
                ]);
            }

            $navCounts = [
                'low_stock' => 0,
                'expiring_supplements' => 0,
                'today_bookings' => 0,
                'expiring_members' => 0,
                'total_alerts' => 0,
            ];

            try {
                if (Auth::check()) {
                    $today = Carbon::today();
                    $lowStockCount = Supplement::where('is_active', true)
                        ->where(function ($q) {
                            $q->whereColumn('stock', '<=', 'min_stock_alert')
                              ->orWhere('stock', '<=', 0);
                        })->count();

                    $expiringSuppCount = Supplement::where('is_active', true)
                        ->whereNotNull('expiry_date')
                        ->where('expiry_date', '<=', Carbon::now()->addDays(30))
                        ->count();

                    $todayBookingsCount = Booking::whereDate('booking_date', $today)
                        ->whereIn('status', ['pending', 'confirmed'])
                        ->count();

                    $expiringMembersCount = Member::where('status', 'active')
                        ->whereNotNull('membership_expires_at')
                        ->whereBetween('membership_expires_at', [$today, Carbon::now()->addDays(7)])
                        ->count();

                    $navCounts = [
                        'low_stock' => $lowStockCount,
                        'expiring_supplements' => $expiringSuppCount,
                        'today_bookings' => $todayBookingsCount,
                        'expiring_members' => $expiringMembersCount,
                        'total_alerts' => $lowStockCount + $expiringSuppCount + $expiringMembersCount,
                    ];
                }
            } catch (\Throwable $e) {
                // Ignore if tables are not migrated yet
            }

            $view->with('appSetting', $setting)->with('navCounts', $navCounts);
        });
    }
}
