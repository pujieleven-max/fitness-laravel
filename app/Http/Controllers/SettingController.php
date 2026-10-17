<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    /**
     * Show application settings configuration page.
     */
    public function index()
    {
        $setting = Setting::current();
        return view('settings.index', compact('setting'));
    }

    /**
     * Update application settings and logo.
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'app_name' => 'required|string|max:100',
            'app_tagline' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:100',
            'address' => 'nullable|string|max:500',
            'receipt_footer' => 'nullable|string|max:1000',
            'social_instagram' => 'nullable|string|max:100',
            'currency_symbol' => 'nullable|string|max:10',
            'theme_color' => 'nullable|string|in:emerald,cyan,amber,rose,violet',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,webp,svg|max:2048',
            'remove_logo' => 'nullable|boolean',
        ]);

        $setting = Setting::first() ?? new Setting();

        // Handle Logo Removal
        if ($request->boolean('remove_logo')) {
            if ($setting->logo_path && Storage::disk('public')->exists($setting->logo_path)) {
                Storage::disk('public')->delete($setting->logo_path);
            }
            $setting->logo_path = null;
        }

        // Handle New Logo Upload
        if ($request->hasFile('logo')) {
            if ($setting->logo_path && Storage::disk('public')->exists($setting->logo_path)) {
                Storage::disk('public')->delete($setting->logo_path);
            }
            $path = $request->file('logo')->store('logos', 'public');
            $setting->logo_path = $path;
        }

        $setting->app_name = $validated['app_name'];
        $setting->app_tagline = $validated['app_tagline'] ?? null;
        $setting->phone = $validated['phone'] ?? null;
        $setting->email = $validated['email'] ?? null;
        $setting->address = $validated['address'] ?? null;
        $setting->receipt_footer = $validated['receipt_footer'] ?? null;
        $setting->social_instagram = $validated['social_instagram'] ?? null;
        $setting->currency_symbol = $validated['currency_symbol'] ?? 'Rp';
        $setting->theme_color = $validated['theme_color'] ?? 'emerald';
        $setting->save();

        Setting::clearCache();

        return back()->with('success', 'Pengaturan aplikasi dan identitas gym berhasil disimpan!');
    }
}
