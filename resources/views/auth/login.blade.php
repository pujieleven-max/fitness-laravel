<!DOCTYPE html>
<html lang="id" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk Sistem - {{ $appSetting->app_name ?? 'FitZone Gym' }}</title>
    
    <!-- Theme Initialization Script (Prevents Flash of Unstyled Theme) -->
    <script>
        (function() {
            const savedTheme = localStorage.getItem('theme');
            if (savedTheme === 'light') {
                document.documentElement.classList.remove('dark');
            } else {
                document.documentElement.classList.add('dark');
            }
        })();
    </script>

    <!-- Google Fonts: Plus Jakarta Sans, Outfit, JetBrains Mono -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;500;600;700&family=Outfit:wght@400;500;600;700;800;900&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                        display: ['"Outfit"', 'sans-serif'],
                        mono: ['"JetBrains Mono"', 'monospace'],
                    },
                    colors: {
                        dark: {
                            950: '#060911',
                            900: '#0B0F19',
                            850: '#0e1422',
                            800: '#131b2e',
                            700: '#1e293b',
                        }
                    }
                }
            }
        }
    </script>

    <!-- FontAwesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.3/dist/cdn.min.js"></script>

    <style>
        [x-cloak] { display: none !important; }

        :root {
            --primary-accent: {{ $appSetting->theme_config['primary'] ?? '#10B981' }};
            --primary-hover: {{ $appSetting->theme_config['primary_hover'] ?? '#059669' }};
            --primary-glow: {{ $appSetting->theme_config['glow'] ?? 'rgba(16, 185, 129, 0.4)' }};
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        .font-display {
            font-family: 'Outfit', sans-serif;
        }

        .font-mono {
            font-family: 'JetBrains Mono', monospace;
        }

        /* Ambient floating animations */
        @keyframes floatSlow {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-8px); }
        }
        @keyframes pulseGlow {
            0%, 100% { opacity: 0.2; transform: scale(1); }
            50% { opacity: 0.4; transform: scale(1.06); }
        }
        .animate-float-slow {
            animation: floatSlow 6s ease-in-out infinite;
        }
        .animate-glow-pulse {
            animation: pulseGlow 7s ease-in-out infinite;
        }

        /* Glassmorphic Shell */
        .glass-shell {
            background: rgba(11, 15, 25, 0.88);
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
            border: 1px solid rgba(255, 255, 255, 0.08);
            box-shadow: 0 25px 70px -15px rgba(0, 0, 0, 0.7);
        }
        html:not(.dark) .glass-shell {
            background: #FFFFFF !important;
            border: 1px solid #E2E8F0 !important;
            box-shadow: 0 20px 50px -10px rgba(15, 23, 42, 0.08) !important;
        }

        /* Glass Floating Widgets */
        .glass-widget {
            background: rgba(6, 9, 17, 0.78);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.12);
            box-shadow: 0 12px 30px -8px rgba(0, 0, 0, 0.5);
        }

        /* Light Mode Specific Adjustments */
        html:not(.dark) body {
            background-color: #F8FAFC !important;
            color: #0F172A !important;
        }
        html:not(.dark) .auth-panel {
            background-color: #FFFFFF !important;
        }
        html:not(.dark) .auth-input {
            background-color: #F8FAFC !important;
            border-color: #CBD5E1 !important;
            color: #0F172A !important;
        }
        html:not(.dark) .auth-input:focus {
            background-color: #FFFFFF !important;
            border-color: var(--primary-accent) !important;
            box-shadow: 0 0 0 1px var(--primary-accent) !important;
        }
        html:not(.dark) .auth-input::placeholder {
            color: #94A3B8 !important;
        }
        html:not(.dark) .text-dynamic-title {
            color: #0F172A !important;
        }
        html:not(.dark) .text-dynamic-sub {
            color: #334155 !important;
        }
        html:not(.dark) .text-dynamic-muted {
            color: #64748B !important;
        }
        html:not(.dark) .border-dynamic {
            border-color: #E2E8F0 !important;
        }
        html:not(.dark) .bg-dynamic-subtle {
            background-color: #F8FAFC !important;
        }

        /* Glowing primary button */
        .btn-glow-primary {
            background: linear-gradient(135deg, {{ $appSetting->theme_config['primary'] ?? '#10B981' }} 0%, {{ $appSetting->theme_config['primary_hover'] ?? '#059669' }} 100%);
            box-shadow: 0 8px 25px -4px var(--primary-glow);
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .btn-glow-primary:hover:not(:disabled) {
            box-shadow: 0 12px 35px -2px var(--primary-glow);
            transform: translateY(-2px);
        }
        .btn-glow-primary:active:not(:disabled) {
            transform: translateY(0px);
        }
        .btn-glow-primary:disabled {
            opacity: 0.7;
            cursor: not-allowed;
        }
    </style>
</head>
<body class="bg-[#060911] text-slate-100 min-h-screen relative flex flex-col justify-between selection:bg-emerald-500 selection:text-slate-950 transition-colors duration-200 overflow-x-hidden"
      x-data="{
          email: '{{ old('email') }}',
          password: '',
          showPassword: false,
          remember: true,
          isSubmitting: false,
          capsLockOn: false,
          showHelpModal: false,
          darkMode: document.documentElement.classList.contains('dark'),
          
          checkCapsLock(event) {
              this.capsLockOn = event.getModifierState && event.getModifierState('CapsLock');
          },

          toggleTheme() {
              this.darkMode = !this.darkMode;
              if (this.darkMode) {
                  document.documentElement.classList.add('dark');
                  localStorage.setItem('theme', 'dark');
              } else {
                  document.documentElement.classList.remove('dark');
                  localStorage.setItem('theme', 'light');
              }
          }
      }">

    <!-- Ambient Glowing Background Elements -->
    <div class="fixed inset-0 pointer-events-none overflow-hidden z-0">
        <div class="absolute -top-40 -left-40 w-[550px] h-[550px] rounded-full bg-emerald-500/15 blur-[140px] animate-glow-pulse"></div>
        <div class="absolute -bottom-40 -right-40 w-[550px] h-[550px] rounded-full bg-cyan-500/15 blur-[140px] animate-glow-pulse" style="animation-delay: -3.5s;"></div>
        <div class="absolute top-1/2 left-1/3 -translate-y-1/2 w-[350px] h-[350px] rounded-full bg-purple-500/10 blur-[120px] pointer-events-none"></div>
    </div>

    <!-- Top Navigation Header -->
    <header class="relative z-30 px-4 sm:px-8 lg:px-12 py-4 sm:py-5 flex items-center justify-between">
        
        <!-- Brand Identity Pill -->
        <a href="{{ url('/') }}" class="flex items-center gap-3 group">
            @if(!empty($appSetting->logo_url))
                <div class="w-10 h-10 rounded-2xl bg-slate-900 border border-emerald-500/30 p-1.5 shadow-lg shadow-emerald-500/10 group-hover:scale-105 transition-transform flex items-center justify-center">
                    <img src="{{ $appSetting->logo_url }}" alt="Logo" class="w-full h-full object-contain">
                </div>
            @else
                <div class="w-10 h-10 rounded-2xl bg-gradient-to-tr from-emerald-600 to-teal-400 flex items-center justify-center text-white shadow-lg shadow-emerald-500/20 group-hover:scale-105 transition-transform">
                    <i class="fa-solid fa-dumbbell text-lg"></i>
                </div>
            @endif
            <div>
                <span class="font-display font-black text-lg tracking-tight text-white text-dynamic-title flex items-center gap-1.5">
                    {{ $appSetting->app_name ?? 'FITZONE GYM' }}
                </span>
                <span class="text-[10px] font-mono text-slate-400 text-dynamic-muted block -mt-0.5">
                    Portal Operasional & Kasir
                </span>
            </div>
        </a>

        <!-- Right Quick Actions -->
        <div class="flex items-center gap-2 sm:gap-3">
            <!-- Back to Landing Page -->
            <a href="{{ route('landing') }}"
               class="px-3.5 py-2 rounded-2xl border border-slate-700/60 dark:border-white/[0.08] bg-slate-900/80 hover:bg-slate-800 text-slate-300 hover:text-white text-xs font-semibold flex items-center gap-2 transition-all shadow-sm">
                <i class="fa-solid fa-arrow-left text-xs text-emerald-400"></i>
                <span class="font-display text-xs">Website Gym</span>
            </a>

            <!-- System Live Status Indicator -->
            <div class="hidden md:flex items-center gap-2 px-3 py-1.5 rounded-full bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-xs font-medium">
                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                <span class="text-[11px] font-mono">Sistem Online</span>
            </div>

            <!-- Light / Dark Mode Switcher Button -->
            <button @click="toggleTheme()" 
                    type="button" 
                    :title="darkMode ? 'Beralih ke Mode Terang' : 'Beralih ke Mode Gelap'"
                    class="p-2 sm:px-3 sm:py-2 rounded-2xl border transition-all duration-200 flex items-center gap-2 text-xs font-bold shadow-sm cursor-pointer
                           bg-white hover:bg-slate-100 text-slate-700 border-slate-200
                           dark:bg-slate-900/90 dark:hover:bg-slate-800 dark:text-amber-300 dark:border-white/[0.08] hover:scale-105 active:scale-95">
                <template x-if="darkMode">
                    <div class="flex items-center gap-1.5">
                        <i class="fa-solid fa-sun text-amber-400 text-xs"></i>
                        <span class="hidden lg:inline text-[11px] font-display text-slate-300">Terang</span>
                    </div>
                </template>
                <template x-if="!darkMode">
                    <div class="flex items-center gap-1.5">
                        <i class="fa-solid fa-moon text-indigo-600 text-xs"></i>
                        <span class="hidden lg:inline text-[11px] font-display text-slate-700">Gelap</span>
                    </div>
                </template>
            </button>
        </div>
    </header>

    <!-- Main Content Area: Split-Screen Hero & Auth Card -->
    <main class="relative z-10 flex-1 flex items-center justify-center px-4 sm:px-6 lg:px-8 py-4 sm:py-6">
        <div class="w-full max-w-5xl glass-shell rounded-[2.25rem] overflow-hidden grid grid-cols-1 lg:grid-cols-12 min-h-[580px]">
            
            <!-- LEFT COLUMN: High-Impact Athletic Hero Showcase (5 cols on lg) -->
            <div class="lg:col-span-5 xl:col-span-5 relative hidden lg:flex flex-col justify-between p-8 xl:p-10 overflow-hidden bg-[#060911]">
                
                <!-- Background Hero Image with Layered Overlays -->
                <div class="absolute inset-0 z-0">
                    <img src="{{ asset('images/login-hero.jpg') }}" alt="{{ $appSetting->app_name ?? 'FitZone Gym' }}" 
                         class="w-full h-full object-cover object-center scale-105 transform transition-transform duration-1000 ease-out hover:scale-100">
                    <!-- Layered Overlays for Optimal Legibility -->
                    <div class="absolute inset-0 bg-gradient-to-t from-[#060911] via-[#060911]/75 to-[#060911]/45"></div>
                    <div class="absolute inset-0 bg-gradient-to-r from-[#060911]/30 via-[#060911]/50 to-[#060911]"></div>
                </div>

                <!-- Top Floating Micro-Widgets -->
                <div class="relative z-10 flex justify-between items-start gap-2">
                    <div class="glass-widget px-3.5 py-2 rounded-2xl animate-float-slow flex items-center gap-2.5">
                        <div class="w-7 h-7 rounded-lg bg-emerald-500/20 text-emerald-400 flex items-center justify-center shrink-0 border border-emerald-500/30">
                            <i class="fa-solid fa-bolt text-xs"></i>
                        </div>
                        <div>
                            <div class="text-[10px] font-extrabold uppercase tracking-wider text-emerald-400 font-display">Fast Checkout POS</div>
                            <div class="text-[11px] font-bold text-white font-mono">&lt; 1 Detik Cetak Struk</div>
                        </div>
                    </div>

                    <div class="glass-widget px-3 py-2 rounded-2xl flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-cyan-400 animate-ping"></span>
                        <span class="text-[10px] font-mono font-bold text-cyan-300">Terminal Aktif</span>
                    </div>
                </div>

                <!-- Center Body & Operational Highlights -->
                <div class="relative z-10 my-auto py-6 space-y-4">
                    
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/[0.08] backdrop-blur-md border border-white/[0.12] text-[11px] font-bold text-emerald-400 font-display tracking-wider uppercase">
                        <i class="fa-solid fa-shield-halved text-[10px]"></i>
                        <span>Portal Resmi Tim Gym</span>
                    </div>

                    <h2 class="text-2xl xl:text-3xl font-black text-white font-display tracking-tight leading-snug">
                        Kelola Transaksi Kasir & Member Gym Lebih Cepat.
                    </h2>

                    <p class="text-xs text-slate-300 leading-relaxed font-normal">
                        Akses aman untuk kasir frontdesk dan manajemen: proses tiket harian, perpanjangan paket membership, rekap kas, hingga kalender personal trainer.
                    </p>

                    <!-- Feature Badges -->
                    <div class="space-y-2 pt-1">
                        <div class="flex items-center gap-2.5 p-2.5 rounded-xl bg-white/[0.05] border border-white/[0.08] backdrop-blur-sm">
                            <div class="w-7 h-7 rounded-lg bg-emerald-500/20 text-emerald-400 flex items-center justify-center shrink-0">
                                <i class="fa-solid fa-cash-register text-xs"></i>
                            </div>
                            <div>
                                <span class="text-xs font-bold text-white block">Kasir POS & Multi Pembayaran</span>
                                <span class="text-[10px] text-slate-400 block">Tunai, QRIS statis, dan transfer bank langsung.</span>
                            </div>
                        </div>

                        <div class="flex items-center gap-2.5 p-2.5 rounded-xl bg-white/[0.05] border border-white/[0.08] backdrop-blur-sm">
                            <div class="w-7 h-7 rounded-lg bg-cyan-500/20 text-cyan-400 flex items-center justify-center shrink-0">
                                <i class="fa-solid fa-id-card text-xs"></i>
                            </div>
                            <div>
                                <span class="text-xs font-bold text-white block">Manajemen Member & PT Booking</span>
                                <span class="text-[10px] text-slate-400 block">Cek status aktif member dan presensi harian otomatis.</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Bottom Trust Badge -->
                <div class="relative z-10 pt-3 border-t border-white/[0.1] flex items-center justify-between text-xs text-slate-400">
                    <span class="text-[11px] font-medium text-slate-300 flex items-center gap-1.5">
                        <i class="fa-solid fa-user-shield text-emerald-400 text-xs"></i>
                        Akses Khusus Staf & Owner
                    </span>
                    <span class="font-mono text-[10px] text-emerald-400">Enkripsi SHA-256</span>
                </div>

            </div>

            <!-- RIGHT COLUMN: Clean, Focused & Streamlined Authentication Form (7 cols on lg) -->
            <div class="lg:col-span-7 xl:col-span-7 auth-panel p-6 sm:p-10 xl:p-12 flex flex-col justify-between transition-colors">
                
                <div>
                    <!-- Mobile Brand Identity Header (Visible on smaller screens) -->
                    <div class="lg:hidden text-center mb-6">
                        @if(!empty($appSetting->logo_url))
                            <div class="inline-flex w-12 h-12 rounded-2xl bg-slate-900 border border-emerald-500/30 p-2 shadow-lg shadow-emerald-500/20 mb-2.5">
                                <img src="{{ $appSetting->logo_url }}" alt="Logo" class="w-full h-full object-contain">
                            </div>
                        @else
                            <div class="inline-flex w-12 h-12 rounded-2xl bg-gradient-to-tr from-emerald-600 to-teal-400 items-center justify-center text-white shadow-lg shadow-emerald-500/20 mb-2.5">
                                <i class="fa-solid fa-dumbbell text-xl"></i>
                            </div>
                        @endif
                        <h1 class="text-xl font-black tracking-tight text-white text-dynamic-title font-display">{{ $appSetting->app_name ?? 'FITZONE GYM' }}</h1>
                        <p class="text-xs text-slate-400 text-dynamic-muted mt-0.5">Portal Operasional & Kasir POS</p>
                    </div>

                    <!-- Welcome Heading -->
                    <div class="mb-6">
                        <div class="inline-flex items-center gap-1.5 text-[11px] font-bold text-emerald-500 dark:text-emerald-400 uppercase tracking-wider font-display mb-1">
                            <i class="fa-solid fa-lock text-[10px]"></i>
                            <span>Otentikasi Aman</span>
                        </div>
                        <h2 class="text-2xl sm:text-3xl font-extrabold text-white text-dynamic-title font-display tracking-tight">
                            Masuk ke Sistem
                        </h2>
                        <p class="text-xs sm:text-sm text-slate-400 text-dynamic-muted mt-1.5 leading-relaxed">
                            Masukkan email dan kata sandi akun Anda untuk mengakses dashboard dan terminal kasir gym.
                        </p>
                    </div>

                    <!-- Flash Messages & Validation Errors -->
                    @if ($errors->any())
                        <div class="mb-5 p-4 rounded-2xl bg-rose-500/10 border border-rose-500/30 text-rose-300 text-xs shadow-lg shadow-rose-950/20">
                            <div class="flex items-center gap-2 font-bold mb-1 text-rose-400">
                                <i class="fa-solid fa-circle-exclamation text-sm"></i>
                                <span>Gagal Masuk:</span>
                            </div>
                            <ul class="list-disc list-inside space-y-0.5 text-rose-200">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if (session('info'))
                        <div class="mb-5 p-3.5 rounded-2xl bg-cyan-500/10 border border-cyan-500/30 text-cyan-300 text-xs flex items-center gap-2.5">
                            <i class="fa-solid fa-circle-info text-cyan-400 text-sm shrink-0"></i>
                            <span>{{ session('info') }}</span>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="mb-5 p-3.5 rounded-2xl bg-rose-500/10 border border-rose-500/30 text-rose-300 text-xs flex items-center gap-2.5">
                            <i class="fa-solid fa-triangle-exclamation text-rose-400 text-sm shrink-0"></i>
                            <span>{{ session('error') }}</span>
                        </div>
                    @endif

                    <!-- Clean Login Form -->
                    <form method="POST" action="{{ route('login') }}" @submit="isSubmitting = true" class="space-y-4">
                        @csrf

                        <!-- Email Input -->
                        <div>
                            <label for="email" class="block text-xs font-bold uppercase tracking-wider text-slate-300 text-dynamic-sub mb-1.5 font-display">
                                Alamat Email
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                    <i class="fa-solid fa-envelope text-xs"></i>
                                </div>
                                <input id="email" 
                                       type="email" 
                                       name="email" 
                                       x-model="email" 
                                       required 
                                       autofocus
                                       autocomplete="email"
                                       placeholder="nama@gym.com"
                                       class="auth-input w-full pl-10 pr-4 py-3 rounded-2xl bg-slate-900/90 border {{ $errors->has('email') ? 'border-rose-500/80 ring-1 ring-rose-500/40' : 'border-slate-700/80' }} text-white placeholder-slate-500 text-sm focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 transition-all shadow-inner">
                            </div>
                        </div>

                        <!-- Password Input with Show/Hide Toggle & CapsLock Warning -->
                        <div>
                            <div class="flex items-center justify-between mb-1.5">
                                <label for="password" class="block text-xs font-bold uppercase tracking-wider text-slate-300 text-dynamic-sub font-display">
                                    Kata Sandi
                                </label>
                                <button type="button" 
                                        @click="showHelpModal = true"
                                        class="text-[11px] font-semibold text-emerald-500 dark:text-emerald-400 hover:underline transition-colors cursor-pointer">
                                    Lupa Kata Sandi?
                                </button>
                            </div>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                    <i class="fa-solid fa-lock text-xs"></i>
                                </div>
                                <input id="password" 
                                       :type="showPassword ? 'text' : 'password'" 
                                       name="password" 
                                       x-model="password" 
                                       @keydown="checkCapsLock($event)"
                                       @keyup="checkCapsLock($event)"
                                       required
                                       autocomplete="current-password"
                                       placeholder="••••••••"
                                       class="auth-input w-full pl-10 pr-11 py-3 rounded-2xl bg-slate-900/90 border {{ $errors->has('password') ? 'border-rose-500/80 ring-1 ring-rose-500/40' : 'border-slate-700/80' }} text-white placeholder-slate-500 text-sm focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 transition-all shadow-inner font-mono">
                                
                                <!-- Show / Hide Password Eye -->
                                <button type="button" 
                                        @click="showPassword = !showPassword" 
                                        :title="showPassword ? 'Sembunyikan kata sandi' : 'Tampilkan kata sandi'"
                                        class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-white transition-colors cursor-pointer">
                                    <i :class="showPassword ? 'fa-solid fa-eye-slash text-emerald-400' : 'fa-solid fa-eye'" class="text-xs"></i>
                                </button>
                            </div>

                            <!-- Caps Lock Alert -->
                            <div x-show="capsLockOn" x-cloak class="mt-1.5 flex items-center gap-1.5 text-xs text-amber-500 dark:text-amber-400 font-medium">
                                <i class="fa-solid fa-triangle-exclamation text-xs"></i>
                                <span>Peringatan: Tombol Caps Lock aktif</span>
                            </div>
                        </div>

                        <!-- Remember Me & Security Status -->
                        <div class="flex items-center justify-between text-xs pt-1">
                            <label class="flex items-center gap-2 cursor-pointer text-slate-400 text-dynamic-muted hover:text-white select-none">
                                <input type="checkbox" name="remember" x-model="remember" value="1"
                                       class="w-4 h-4 rounded border-slate-700 bg-slate-900 text-emerald-500 focus:ring-emerald-500 transition-all">
                                <span>Ingat saya di perangkat ini</span>
                            </label>

                            <span class="text-[11px] text-slate-400 text-dynamic-muted flex items-center gap-1.5 font-mono">
                                <i class="fa-solid fa-shield-halved text-[10px] text-emerald-400"></i>
                                <span>Sesi Terenkripsi</span>
                            </span>
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" 
                                :disabled="isSubmitting"
                                class="btn-glow-primary w-full py-3.5 px-5 rounded-2xl text-slate-950 font-black text-sm uppercase tracking-wider flex items-center justify-center gap-2.5 transition-all mt-3 cursor-pointer">
                            <template x-if="!isSubmitting">
                                <div class="flex items-center gap-2">
                                    <span>Masuk ke FitZone</span>
                                    <i class="fa-solid fa-arrow-right text-xs"></i>
                                </div>
                            </template>
                            <template x-if="isSubmitting">
                                <div class="flex items-center gap-2">
                                    <i class="fa-solid fa-circle-notch fa-spin text-sm"></i>
                                    <span>Memverifikasi Akun...</span>
                                </div>
                            </template>
                        </button>
                    </form>

                    <!-- Support Notice -->
                    <div class="mt-6 pt-5 border-t border-slate-800/80 border-dynamic flex items-center justify-between gap-3 text-xs text-slate-400 text-dynamic-muted">
                        <div class="flex items-center gap-2">
                            <i class="fa-solid fa-headset text-emerald-500/80 text-sm"></i>
                            <span>Kendala login? Hubungi Administrator gym.</span>
                        </div>
                        <button type="button" @click="showHelpModal = true" class="text-xs font-bold text-emerald-500 dark:text-emerald-400 hover:underline">
                            Bantuan
                        </button>
                    </div>

                </div>

                <!-- Footer Copyright & Version -->
                <div class="pt-5 mt-5 border-t border-slate-800/60 border-dynamic text-xs text-slate-400 text-dynamic-muted flex flex-col sm:flex-row items-center justify-between gap-2">
                    <span>{{ $appSetting->app_name ?? 'FitZone Gym' }} &bull; POS & Manajemen Fitness</span>
                    <span class="font-mono text-[11px]">&copy; {{ date('Y') }} All Rights Reserved</span>
                </div>

            </div>

        </div>
    </main>

    <!-- Footer System Notice -->
    <footer class="relative z-10 py-3 text-center text-[11px] text-slate-400 text-dynamic-muted font-mono">
        Dilindungi oleh otentikasi sesi Laravel terenkripsi &bull; {{ $appSetting->app_name ?? 'FitZone Gym' }} v2.0
    </footer>

    <!-- Password Reset / Help Modal -->
    <div x-show="showHelpModal" 
         x-cloak 
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/70 backdrop-blur-sm"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
        
        <div @click.away="showHelpModal = false" 
             class="w-full max-w-md rounded-3xl p-6 sm:p-7 glass-shell relative shadow-2xl"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95">
            
            <div class="flex items-center justify-between pb-4 border-b border-slate-700/60 border-dynamic">
                <div class="flex items-center gap-2.5">
                    <div class="w-9 h-9 rounded-xl bg-emerald-500/10 text-emerald-400 flex items-center justify-center border border-emerald-500/20">
                        <i class="fa-solid fa-key text-sm"></i>
                    </div>
                    <div>
                        <h3 class="font-display font-black text-white text-dynamic-title text-base">Bantuan Akses Akun</h3>
                        <p class="text-[11px] text-slate-400 text-dynamic-muted">Reset kata sandi & otentikasi</p>
                    </div>
                </div>
                <button type="button" @click="showHelpModal = false" class="text-slate-400 hover:text-white p-1 rounded-lg">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>

            <div class="py-4 space-y-3 text-xs text-slate-300 text-dynamic-sub leading-relaxed">
                <p>
                    Demi menjaga keamanan data transaksi finansial kasir dan kerahasiaan member, akun operasional tidak menyediakan reset mandiri.
                </p>
                <div class="p-3.5 rounded-2xl bg-white/[0.04] border border-slate-700/50 border-dynamic space-y-2">
                    <span class="font-bold text-white text-dynamic-title block">Kontak Manajemen Gym:</span>
                    @if(!empty($appSetting->phone))
                        <div class="flex items-center justify-between">
                            <span class="text-slate-400 text-dynamic-muted flex items-center gap-2">
                                <i class="fa-brands fa-whatsapp text-emerald-400"></i> WhatsApp/Telepon:
                            </span>
                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $appSetting->phone) }}" target="_blank" class="font-mono font-bold text-emerald-400 hover:underline">
                                {{ $appSetting->phone }}
                            </a>
                        </div>
                    @endif
                    @if(!empty($appSetting->email))
                        <div class="flex items-center justify-between">
                            <span class="text-slate-400 text-dynamic-muted flex items-center gap-2">
                                <i class="fa-solid fa-envelope text-cyan-400"></i> Email Admin:
                            </span>
                            <a href="mailto:{{ $appSetting->email }}" class="font-mono font-bold text-cyan-400 hover:underline">
                                {{ $appSetting->email }}
                            </a>
                        </div>
                    @endif
                </div>
                <p class="text-[11px] text-slate-400 text-dynamic-muted">
                    Owner atau Super Admin dapat mengubah kata sandi Anda melalui menu <span class="font-bold text-white text-dynamic-title">Manajemen Pengguna</span>.
                </p>
            </div>

            <div class="pt-3 border-t border-slate-700/60 border-dynamic flex justify-end">
                <button type="button" 
                        @click="showHelpModal = false"
                        class="px-5 py-2.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-white text-xs font-bold transition-all">
                    Tutup
                </button>
            </div>
        </div>
    </div>

</body>
</html>
