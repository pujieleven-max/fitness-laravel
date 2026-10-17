<?php

namespace App\Providers;

use App\Models\Setting;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

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

            $view->with('appSetting', $setting);
        });
    }
}
