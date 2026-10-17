@extends('layouts.app')

@section('title', 'Pengaturan Aplikasi')
@section('page-title', 'Pengaturan & Identitas Aplikasi')
@section('page-subtitle', 'Kelola nama gym, warna tema aksen, logo resmi, alamat cabang, kontak WhatsApp kasir, dan format struk cetak POS')

@section('content')
<div class="space-y-6" x-data="{
    appName: '{{ addslashes($setting->app_name) }}',
    appTagline: '{{ addslashes($setting->app_tagline ?? 'POS & Fitness Management') }}',
    phone: '{{ addslashes($setting->phone ?? '0812-3456-7890') }}',
    email: '{{ addslashes($setting->email ?? 'info@fitzone.com') }}',
    address: '{{ addslashes($setting->address ?? 'Jl. Gatot Subroto No. 88, Jakarta Selatan') }}',
    receiptFooter: '{{ addslashes($setting->receipt_footer ?? 'Terima kasih telah berlatih di tempat kami!') }}',
    instagram: '{{ addslashes($setting->social_instagram ?? '@fitzone_gym') }}',
    currency: '{{ addslashes($setting->currency_symbol ?? 'Rp') }}',
    themeColor: '{{ $setting->theme_color ?? 'emerald' }}',
    logoPreview: '{{ $setting->logo_url ? $setting->logo_url : '' }}',
    removeLogo: false,

    previewImage(e) {
        const file = e.target.files[0];
        if (file) {
            this.removeLogo = false;
            const reader = new FileReader();
            reader.onload = (event) => {
                this.logoPreview = event.target.result;
            };
            reader.readAsDataURL(file);
        }
    },
    clearLogoInput() {
        this.logoPreview = '';
        this.removeLogo = true;
        const input = document.getElementById('logo_input');
        if (input) input.value = '';
    }
}">

    <!-- Alert / Tips Bar with Cyber Glassmorphic Look -->
    <div class="p-5 rounded-3xl glass-panel border border-white/[0.08] flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 shadow-xl">
        <div class="flex items-center gap-3.5">
            <div class="w-11 h-11 rounded-2xl bg-amber-500/20 text-amber-400 flex items-center justify-center shrink-0 border border-amber-500/30 shadow-lg shadow-amber-500/10">
                <i class="fa-solid fa-crown text-lg"></i>
            </div>
            <div>
                <h4 class="text-sm font-bold text-white flex items-center gap-2 font-display">
                    <span>Pengaturan Khusus Owner Gym</span>
                    <span class="text-[10px] font-extrabold uppercase px-2 py-0.5 rounded-full bg-amber-500/20 text-amber-300 border border-amber-500/30">Privileged</span>
                </h4>
                <p class="text-xs text-slate-400 mt-0.5">
                    Perubahan nama aplikasi, logo, warna tema, alamat, dan nomor kontak akan langsung diterapkan di seluruh sistem (Sidebar, Halaman Login, dan Struk Thermal Kasir POS).
                </p>
            </div>
        </div>
    </div>

    <!-- Main Content 2 Columns -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
        
        <!-- Left: Form Input Settings (8 Cols) -->
        <div class="lg:col-span-7 xl:col-span-8 space-y-6">
            <form action="{{ route('settings.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @method('PUT')

                <!-- Card 1: Identitas Gym & Logo -->
                <div class="p-6 rounded-3xl glass-panel border border-white/[0.07] space-y-5 shadow-2xl">
                    <div class="flex items-center gap-3 pb-3 border-b border-white/[0.06]">
                        <div class="w-9 h-9 rounded-xl bg-emerald-500/20 text-emerald-400 flex items-center justify-center font-bold">
                            <i class="fa-solid fa-id-badge text-sm"></i>
                        </div>
                        <div>
                            <h3 class="text-sm font-bold text-white font-display">Identitas Utama Gym & Logo</h3>
                            <p class="text-[11px] text-slate-400">Nama resmi fitness center, slogan, dan logo brand</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="sm:col-span-2">
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5 font-display">
                                Nama Aplikasi / Gym <span class="text-rose-400">*</span>
                            </label>
                            <input type="text" name="app_name" x-model="appName" required
                                   class="w-full px-4 py-2.5 rounded-xl bg-slate-900/90 border border-slate-700/80 text-white text-sm font-bold focus:outline-none focus:border-emerald-500 transition-colors shadow-inner"
                                   placeholder="Contoh: FitZone Gym & Fitness Center">
                        </div>

                        <div class="sm:col-span-2">
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5 font-display">
                                Tagline / Slogan Aplikasi
                            </label>
                            <input type="text" name="app_tagline" x-model="appTagline"
                                   class="w-full px-4 py-2.5 rounded-xl bg-slate-900/90 border border-slate-700/80 text-white text-xs focus:outline-none focus:border-emerald-500 transition-colors"
                                   placeholder="Contoh: Sistem POS, Inventori Suplemen & Manajemen Booking">
                        </div>
                    </div>

                    <!-- Logo Upload Section -->
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-2 font-display">
                            Logo Brand Gym
                        </label>

                        <div class="flex flex-col sm:flex-row items-center gap-4 p-4 rounded-2xl bg-slate-900/80 border border-white/[0.06]">
                            <!-- Current / Preview Logo Box -->
                            <div class="w-20 h-20 rounded-2xl bg-[#060911] border border-white/[0.1] flex items-center justify-center p-2 overflow-hidden shrink-0 relative group shadow-md">
                                <template x-if="logoPreview">
                                    <img :src="logoPreview" alt="Logo Preview" class="w-full h-full object-contain">
                                </template>
                                <template x-if="!logoPreview">
                                    <div class="text-center text-slate-500">
                                        <i class="fa-solid fa-dumbbell text-2xl mb-1 text-slate-600"></i>
                                        <span class="block text-[9px] font-bold uppercase">Default</span>
                                    </div>
                                </template>
                            </div>

                            <div class="flex-1 space-y-2 text-center sm:text-left">
                                <input type="file" id="logo_input" name="logo" accept="image/png,image/jpeg,image/webp,image/svg+xml"
                                       @change="previewImage($event)"
                                       class="block w-full text-xs text-slate-400 file:mr-3 file:py-2 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-emerald-500/20 file:text-emerald-300 hover:file:bg-emerald-500/30 file:cursor-pointer cursor-pointer">
                                
                                <p class="text-[11px] text-slate-500">
                                    Format didukung: PNG, JPG, WebP, SVG. Disarankan berlatar transparan (Maks. 2MB).
                                </p>

                                @if($setting->logo_path)
                                    <div class="flex items-center gap-2 pt-1">
                                        <input type="checkbox" id="remove_logo" name="remove_logo" value="1" x-model="removeLogo"
                                               class="w-4 h-4 rounded border-slate-700 bg-slate-900 text-rose-500 focus:ring-rose-500">
                                        <label for="remove_logo" class="text-xs text-rose-400 font-semibold cursor-pointer">
                                            Hapus logo saat ini dan gunakan icon dumbbell bawaan
                                        </label>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card 2: Tema Warna Aksen Brand (Color Palette Modern) -->
                <div class="p-6 rounded-3xl glass-panel border border-white/[0.07] space-y-5 shadow-2xl">
                    <div class="flex items-center gap-3 pb-3 border-b border-white/[0.06]">
                        <div class="w-9 h-9 rounded-xl bg-purple-500/20 text-purple-400 flex items-center justify-center font-bold">
                            <i class="fa-solid fa-palette text-sm"></i>
                        </div>
                        <div>
                            <h3 class="text-sm font-bold text-white font-display">Tema Warna Aksen Brand</h3>
                            <p class="text-[11px] text-slate-400">Pilih palet warna aksen modern untuk tombol, navigasi, dan identitas visual</p>
                        </div>
                    </div>

                    <!-- 5 Modern Theme Options -->
                    <div class="grid grid-cols-2 sm:grid-cols-5 gap-3">
                        <!-- Emerald -->
                        <label class="cursor-pointer">
                            <input type="radio" name="theme_color" value="emerald" x-model="themeColor" class="hidden">
                            <div class="p-3.5 rounded-2xl border text-center transition-all duration-200"
                                 :class="themeColor === 'emerald' ? 'bg-emerald-500/15 border-emerald-500 shadow-lg shadow-emerald-500/20 scale-[1.02]' : 'bg-slate-900/60 border-slate-800 hover:border-slate-700'">
                                <div class="w-8 h-8 mx-auto rounded-full bg-emerald-500 shadow-md shadow-emerald-500/50 flex items-center justify-center text-slate-950 text-xs">
                                    <i class="fa-solid fa-check" x-show="themeColor === 'emerald'"></i>
                                </div>
                                <span class="block mt-2 text-xs font-bold text-white">Emerald</span>
                                <span class="text-[10px] text-slate-400">Cyber Green</span>
                            </div>
                        </label>

                        <!-- Cyan -->
                        <label class="cursor-pointer">
                            <input type="radio" name="theme_color" value="cyan" x-model="themeColor" class="hidden">
                            <div class="p-3.5 rounded-2xl border text-center transition-all duration-200"
                                 :class="themeColor === 'cyan' ? 'bg-cyan-500/15 border-cyan-500 shadow-lg shadow-cyan-500/20 scale-[1.02]' : 'bg-slate-900/60 border-slate-800 hover:border-slate-700'">
                                <div class="w-8 h-8 mx-auto rounded-full bg-cyan-400 shadow-md shadow-cyan-400/50 flex items-center justify-center text-slate-950 text-xs">
                                    <i class="fa-solid fa-check" x-show="themeColor === 'cyan'"></i>
                                </div>
                                <span class="block mt-2 text-xs font-bold text-white">Cyan</span>
                                <span class="text-[10px] text-slate-400">Electric Blue</span>
                            </div>
                        </label>

                        <!-- Amber -->
                        <label class="cursor-pointer">
                            <input type="radio" name="theme_color" value="amber" x-model="themeColor" class="hidden">
                            <div class="p-3.5 rounded-2xl border text-center transition-all duration-200"
                                 :class="themeColor === 'amber' ? 'bg-amber-500/15 border-amber-500 shadow-lg shadow-amber-500/20 scale-[1.02]' : 'bg-slate-900/60 border-slate-800 hover:border-slate-700'">
                                <div class="w-8 h-8 mx-auto rounded-full bg-amber-400 shadow-md shadow-amber-400/50 flex items-center justify-center text-slate-950 text-xs">
                                    <i class="fa-solid fa-check" x-show="themeColor === 'amber'"></i>
                                </div>
                                <span class="block mt-2 text-xs font-bold text-white">Amber</span>
                                <span class="text-[10px] text-slate-400">Sunset Gold</span>
                            </div>
                        </label>

                        <!-- Rose -->
                        <label class="cursor-pointer">
                            <input type="radio" name="theme_color" value="rose" x-model="themeColor" class="hidden">
                            <div class="p-3.5 rounded-2xl border text-center transition-all duration-200"
                                 :class="themeColor === 'rose' ? 'bg-rose-500/15 border-rose-500 shadow-lg shadow-rose-500/20 scale-[1.02]' : 'bg-slate-900/60 border-slate-800 hover:border-slate-700'">
                                <div class="w-8 h-8 mx-auto rounded-full bg-rose-500 shadow-md shadow-rose-500/50 flex items-center justify-center text-white text-xs">
                                    <i class="fa-solid fa-check" x-show="themeColor === 'rose'"></i>
                                </div>
                                <span class="block mt-2 text-xs font-bold text-white">Crimson</span>
                                <span class="text-[10px] text-slate-400">Hardcore Red</span>
                            </div>
                        </label>

                        <!-- Violet -->
                        <label class="cursor-pointer col-span-2 sm:col-span-1">
                            <input type="radio" name="theme_color" value="violet" x-model="themeColor" class="hidden">
                            <div class="p-3.5 rounded-2xl border text-center transition-all duration-200"
                                 :class="themeColor === 'violet' ? 'bg-violet-500/15 border-violet-500 shadow-lg shadow-violet-500/20 scale-[1.02]' : 'bg-slate-900/60 border-slate-800 hover:border-slate-700'">
                                <div class="w-8 h-8 mx-auto rounded-full bg-violet-500 shadow-md shadow-violet-500/50 flex items-center justify-center text-white text-xs">
                                    <i class="fa-solid fa-check" x-show="themeColor === 'violet'"></i>
                                </div>
                                <span class="block mt-2 text-xs font-bold text-white">Violet</span>
                                <span class="text-[10px] text-slate-400">Modern Studio</span>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- Card 3: Kontak & Alamat -->
                <div class="p-6 rounded-3xl glass-panel border border-white/[0.07] space-y-5 shadow-2xl">
                    <div class="flex items-center gap-3 pb-3 border-b border-white/[0.06]">
                        <div class="w-9 h-9 rounded-xl bg-cyan-500/20 text-cyan-400 flex items-center justify-center font-bold">
                            <i class="fa-solid fa-location-dot text-sm"></i>
                        </div>
                        <div>
                            <h3 class="text-sm font-bold text-white font-display">Kontak & Lokasi Cabang Gym</h3>
                            <p class="text-[11px] text-slate-400">Dicetak di bagian atas nota struk kasir & informasi kontak</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5 font-display">
                                No. Telepon / WhatsApp Kasir
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-500">
                                    <i class="fa-solid fa-phone text-xs"></i>
                                </div>
                                <input type="text" name="phone" x-model="phone"
                                       class="w-full pl-10 pr-3.5 py-2.5 rounded-xl bg-slate-900/90 border border-slate-700/80 text-white font-mono text-xs focus:outline-none focus:border-cyan-500"
                                       placeholder="Contoh: 0812-3456-7890">
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5 font-display">
                                Email Resmi Gym
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-500">
                                    <i class="fa-solid fa-envelope text-xs"></i>
                                </div>
                                <input type="email" name="email" x-model="email"
                                       class="w-full pl-10 pr-3.5 py-2.5 rounded-xl bg-slate-900/90 border border-slate-700/80 text-white text-xs focus:outline-none focus:border-cyan-500"
                                       placeholder="Contoh: info@fitzone.com">
                            </div>
                        </div>

                        <div class="sm:col-span-2">
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5 font-display">
                                Akun Instagram / Media Sosial
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-500">
                                    <i class="fa-brands fa-instagram text-xs"></i>
                                </div>
                                <input type="text" name="social_instagram" x-model="instagram"
                                       class="w-full pl-10 pr-3.5 py-2.5 rounded-xl bg-slate-900/90 border border-slate-700/80 text-white font-mono text-xs focus:outline-none focus:border-cyan-500"
                                       placeholder="Contoh: @fitzone_gym">
                            </div>
                        </div>

                        <div class="sm:col-span-2">
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5 font-display">
                                Alamat Lengkap Gym
                            </label>
                            <textarea name="address" x-model="address" rows="3"
                                      class="w-full px-4 py-2.5 rounded-xl bg-slate-900/90 border border-slate-700/80 text-white text-xs leading-relaxed focus:outline-none focus:border-cyan-500"
                                      placeholder="Contoh: Jl. Gatot Subroto No. 88, RT 01 / RW 02, Jakarta Selatan"></textarea>
                        </div>
                    </div>
                </div>

                <!-- Card 4: Konfigurasi Struk Kasir & Finansial -->
                <div class="p-6 rounded-3xl glass-panel border border-white/[0.07] space-y-5 shadow-2xl">
                    <div class="flex items-center gap-3 pb-3 border-b border-white/[0.06]">
                        <div class="w-9 h-9 rounded-xl bg-amber-500/20 text-amber-400 flex items-center justify-center font-bold">
                            <i class="fa-solid fa-receipt text-sm"></i>
                        </div>
                        <div>
                            <h3 class="text-sm font-bold text-white font-display">Konfigurasi Struk Kasir & Finansial</h3>
                            <p class="text-[11px] text-slate-400">Pesan penutup pada struk belanja thermal & format mata uang</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5 font-display">
                                Simbol Mata Uang
                            </label>
                            <input type="text" name="currency_symbol" x-model="currency"
                                   class="w-full px-4 py-2.5 rounded-xl bg-slate-900/90 border border-slate-700/80 text-white font-mono text-xs focus:outline-none focus:border-amber-500"
                                   placeholder="Rp">
                        </div>

                        <div class="sm:col-span-2">
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5 font-display">
                                Pesan Footer Struk Belanja Kasir
                            </label>
                            <textarea name="receipt_footer" x-model="receiptFooter" rows="2"
                                      class="w-full px-4 py-2 rounded-xl bg-slate-900/90 border border-slate-700/80 text-white text-xs focus:outline-none focus:border-amber-500"
                                      placeholder="Contoh: Terima kasih telah berlatih di FitZone Gym! Tetap semangat."></textarea>
                            <span class="text-[10px] text-slate-500 mt-1 block">Teks ini akan dicetak di baris paling bawah setiap nota struk.</span>
                        </div>
                    </div>
                </div>

                <!-- Save Action Button -->
                <div class="flex items-center justify-end gap-3 pt-2">
                    <a href="{{ route('dashboard') }}" class="px-5 py-2.5 rounded-xl bg-slate-900/90 hover:bg-slate-800 text-slate-400 hover:text-white text-xs font-bold transition-colors">
                        Batal
                    </a>
                    <button type="submit"
                            class="btn-theme-primary px-7 py-3 rounded-2xl text-slate-950 font-black text-xs uppercase tracking-wider flex items-center gap-2 transition-all">
                        <i class="fa-solid fa-floppy-disk"></i>
                        <span>Simpan Pengaturan & Tema</span>
                    </button>
                </div>
            </form>
        </div>

        <!-- Right: Real-time Live Previews (4 Cols) -->
        <div class="lg:col-span-5 xl:col-span-4 space-y-6 lg:sticky lg:top-24">
            
            <!-- Live Preview 1: Header / Sidebar Brand Preview -->
            <div class="p-6 rounded-3xl glass-panel border border-white/[0.08] shadow-2xl space-y-4">
                <div class="flex items-center justify-between pb-3 border-b border-white/[0.06]">
                    <div class="flex items-center gap-2">
                        <i class="fa-solid fa-eye text-emerald-400 text-xs"></i>
                        <span class="text-xs font-bold text-white uppercase tracking-wider font-display">Preview Header & Sidebar</span>
                    </div>
                    <span class="text-[10px] font-mono text-emerald-400 bg-emerald-500/10 px-2 py-0.5 rounded-md border border-emerald-500/30">Live</span>
                </div>

                <!-- Simulating Sidebar Brand Card -->
                <div class="p-4 rounded-2xl bg-[#060911] border border-white/[0.08] flex items-center gap-3.5 shadow-inner">
                    <div class="w-12 h-12 rounded-2xl flex items-center justify-center text-white shadow-lg shrink-0 overflow-hidden transition-all duration-300"
                         :class="{
                             'bg-gradient-to-tr from-emerald-600 to-teal-400 shadow-emerald-500/30': themeColor === 'emerald',
                             'bg-gradient-to-tr from-cyan-600 to-blue-400 shadow-cyan-500/30': themeColor === 'cyan',
                             'bg-gradient-to-tr from-amber-600 to-orange-400 shadow-amber-500/30': themeColor === 'amber',
                             'bg-gradient-to-tr from-rose-600 to-pink-400 shadow-rose-500/30': themeColor === 'rose',
                             'bg-gradient-to-tr from-violet-600 to-purple-400 shadow-violet-500/30': themeColor === 'violet'
                         }">
                        <template x-if="logoPreview && !removeLogo">
                            <img :src="logoPreview" alt="Logo" class="w-full h-full object-cover">
                        </template>
                        <template x-if="!logoPreview || removeLogo">
                            <i class="fa-solid fa-dumbbell text-xl"></i>
                        </template>
                    </div>
                    <div class="min-w-0">
                        <h4 class="font-extrabold text-base text-white tracking-tight truncate font-display" x-text="appName || 'NAMA GYM'"></h4>
                        <p class="text-[11px] text-slate-400 truncate" x-text="appTagline || 'Slogan Aplikasi'"></p>
                    </div>
                </div>
            </div>

            <!-- Live Preview 2: Thermal POS Receipt Simulation -->
            <div class="p-6 rounded-3xl glass-panel border border-white/[0.08] shadow-2xl space-y-4">
                <div class="flex items-center justify-between pb-3 border-b border-white/[0.06]">
                    <div class="flex items-center gap-2">
                        <i class="fa-solid fa-receipt text-cyan-400 text-xs"></i>
                        <span class="text-xs font-bold text-white uppercase tracking-wider font-display">Simulasi Struk Kasir POS</span>
                    </div>
                    <span class="text-[10px] font-mono text-slate-400 bg-slate-800 px-2 py-0.5 rounded-md">Thermal 58mm</span>
                </div>

                <!-- Thermal Paper Receipt Lookalike -->
                <div class="bg-white text-slate-900 p-5 rounded-2xl font-mono text-xs shadow-2xl space-y-3 border-t-4 transition-all duration-300"
                     :class="{
                         'border-emerald-500': themeColor === 'emerald',
                         'border-cyan-500': themeColor === 'cyan',
                         'border-amber-500': themeColor === 'amber',
                         'border-rose-500': themeColor === 'rose',
                         'border-violet-500': themeColor === 'violet'
                     }">
                    <!-- Receipt Header -->
                    <div class="text-center space-y-1">
                        <template x-if="logoPreview && !removeLogo">
                            <div class="flex justify-center mb-1">
                                <img :src="logoPreview" alt="Receipt Logo" class="h-10 max-w-[130px] object-contain">
                            </div>
                        </template>

                        <div class="font-extrabold text-sm uppercase tracking-wider" x-text="appName || 'FITZONE GYM'"></div>
                        <div class="text-[10px] text-slate-700 leading-tight" x-text="address || 'Jl. Lokasi Cabang Gym'"></div>
                        <div class="text-[10px] text-slate-700" x-text="'Telp: ' + (phone || '0812-xxxx-xxxx')"></div>
                        <template x-if="instagram">
                            <div class="text-[9px] text-slate-500" x-text="'IG: ' + instagram"></div>
                        </template>
                    </div>

                    <!-- Dashed separator -->
                    <div class="border-t border-dashed border-slate-400 my-2"></div>

                    <!-- Meta -->
                    <div class="text-[10px] space-y-0.5 text-slate-600">
                        <div class="flex justify-between">
                            <span>No. Invoice:</span>
                            <span class="font-bold text-slate-900">INV-20261016-001</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Waktu:</span>
                            <span>{{ date('d/m/Y H:i') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Kasir:</span>
                            <span>{{ Auth::user()->name }}</span>
                        </div>
                    </div>

                    <!-- Dashed separator -->
                    <div class="border-t border-dashed border-slate-400 my-2"></div>

                    <!-- Dummy Items -->
                    <div class="text-[10px] space-y-1.5">
                        <div class="flex justify-between font-bold">
                            <span>1x Daily Pass Gym</span>
                            <span>35.000</span>
                        </div>
                        <div class="flex justify-between">
                            <span>1x Whey Isolate 2Lbs</span>
                            <span>450.000</span>
                        </div>
                    </div>

                    <!-- Dashed separator -->
                    <div class="border-t border-dashed border-slate-400 my-2"></div>

                    <!-- Totals -->
                    <div class="text-[11px] space-y-1">
                        <div class="flex justify-between font-extrabold text-slate-900">
                            <span>TOTAL:</span>
                            <span x-text="(currency || 'Rp') + ' 485.000'"></span>
                        </div>
                        <div class="flex justify-between text-[10px] text-slate-600">
                            <span>BAYAR (CASH):</span>
                            <span x-text="(currency || 'Rp') + ' 500.000'"></span>
                        </div>
                        <div class="flex justify-between text-[10px] text-slate-600">
                            <span>KEMBALIAN:</span>
                            <span x-text="(currency || 'Rp') + ' 15.000'"></span>
                        </div>
                    </div>

                    <!-- Dashed separator -->
                    <div class="border-t border-dashed border-slate-400 my-2"></div>

                    <!-- Footer message -->
                    <div class="text-center text-[10px] text-slate-600 italic leading-snug whitespace-pre-line"
                         x-text="receiptFooter || 'Terima kasih telah berlatih bersama kami!'">
                    </div>
                </div>

            </div>

        </div>

    </div>

</div>
@endsection
