<?php

namespace App\Models;

use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class Setting extends Model
{
    use LogsActivity;

    protected $fillable = [
        'app_name',
        'app_tagline',
        'logo_path',
        'phone',
        'email',
        'address',
        'receipt_footer',
        'social_instagram',
        'currency_symbol',
        'theme_color',
    ];

    /**
     * Retrieve the current active settings record (singleton).
     */
    public static function current(): self
    {
        return Cache::remember('app_setting_current', 3600, function () {
            return self::firstOrCreate([], [
                'app_name' => 'FitZone Gym',
                'app_tagline' => 'POS & Fitness Management',
                'phone' => '0812-3456-7890',
                'email' => 'info@fitzone.com',
                'address' => 'Jl. Gatot Subroto No. 88, Jakarta Selatan',
                'receipt_footer' => "Terima kasih telah berlatih di tempat kami!\nTetap disiplin & raih target kebugaran Anda.",
                'social_instagram' => '@fitzone_gym',
                'currency_symbol' => 'Rp',
                'theme_color' => 'emerald',
            ]);
        });
    }

    /**
     * Invalidate cached settings upon update.
     */
    public static function clearCache(): void
    {
        Cache::forget('app_setting_current');
    }

    /**
     * Get accessible URL for gym logo or null.
     */
    public function getLogoUrlAttribute(): ?string
    {
        if (! empty($this->logo_path)) {
            return asset('storage/' . $this->logo_path);
        }

        return null;
    }

    /**
     * Theme color palettes configuration for modern styling.
     */
    public function getThemeConfigAttribute(): array
    {
        $themes = [
            'emerald' => [
                'key' => 'emerald',
                'name' => 'Cyber Emerald',
                'primary' => '#10B981',
                'primary_hover' => '#059669',
                'gradient_from' => 'from-emerald-500',
                'gradient_to' => 'to-teal-400',
                'gradient_class' => 'bg-gradient-to-r from-emerald-500 to-teal-400',
                'text' => 'text-emerald-400',
                'bg_subtle' => 'bg-emerald-500/10',
                'border' => 'border-emerald-500/30',
                'glow' => 'rgba(16, 185, 129, 0.4)',
                'badge' => 'bg-emerald-500/20 text-emerald-300 border-emerald-500/30',
            ],
            'cyan' => [
                'key' => 'cyan',
                'name' => 'Electric Cyan',
                'primary' => '#06B6D4',
                'primary_hover' => '#0891B2',
                'gradient_from' => 'from-cyan-500',
                'gradient_to' => 'to-blue-500',
                'gradient_class' => 'bg-gradient-to-r from-cyan-500 to-blue-500',
                'text' => 'text-cyan-400',
                'bg_subtle' => 'bg-cyan-500/10',
                'border' => 'border-cyan-500/30',
                'glow' => 'rgba(6, 182, 212, 0.4)',
                'badge' => 'bg-cyan-500/20 text-cyan-300 border-cyan-500/30',
            ],
            'amber' => [
                'key' => 'amber',
                'name' => 'Sunset Gold',
                'primary' => '#F59E0B',
                'primary_hover' => '#D97706',
                'gradient_from' => 'from-amber-500',
                'gradient_to' => 'to-orange-500',
                'gradient_class' => 'bg-gradient-to-r from-amber-500 to-orange-500',
                'text' => 'text-amber-400',
                'bg_subtle' => 'bg-amber-500/10',
                'border' => 'border-amber-500/30',
                'glow' => 'rgba(245, 158, 11, 0.4)',
                'badge' => 'bg-amber-500/20 text-amber-300 border-amber-500/30',
            ],
            'rose' => [
                'key' => 'rose',
                'name' => 'Crimson Pulse',
                'primary' => '#F43F5E',
                'primary_hover' => '#E11D48',
                'gradient_from' => 'from-rose-500',
                'gradient_to' => 'to-pink-500',
                'gradient_class' => 'bg-gradient-to-r from-rose-500 to-pink-500',
                'text' => 'text-rose-400',
                'bg_subtle' => 'bg-rose-500/10',
                'border' => 'border-rose-500/30',
                'glow' => 'rgba(244, 63, 94, 0.4)',
                'badge' => 'bg-rose-500/20 text-rose-300 border-rose-500/30',
            ],
            'violet' => [
                'key' => 'violet',
                'name' => 'Neon Violet',
                'primary' => '#8B5CF6',
                'primary_hover' => '#7C3AED',
                'gradient_from' => 'from-violet-500',
                'gradient_to' => 'to-purple-500',
                'gradient_class' => 'bg-gradient-to-r from-violet-500 to-purple-500',
                'text' => 'text-violet-400',
                'bg_subtle' => 'bg-violet-500/10',
                'border' => 'border-violet-500/30',
                'glow' => 'rgba(139, 92, 246, 0.4)',
                'badge' => 'bg-violet-500/20 text-violet-300 border-violet-500/30',
            ],
        ];

        return $themes[$this->theme_color ?? 'emerald'] ?? $themes['emerald'];
    }
}
