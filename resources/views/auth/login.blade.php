<!DOCTYPE html>
<html lang="id" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk Portal - {{ $appSetting->app_name ?? 'FitZone Gym' }}</title>
    
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
            50% { transform: translateY(-10px); }
        }
        @keyframes floatReverse {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(8px); }
        }
        @keyframes pulseGlow {
            0%, 100% { opacity: 0.25; transform: scale(1); }
            50% { opacity: 0.45; transform: scale(1.08); }
        }
        .animate-float-slow {
            animation: floatSlow 7s ease-in-out infinite;
        }
        .animate-float-delayed {
            animation: floatReverse 8s ease-in-out infinite;
        }
        .animate-glow-pulse {
            animation: pulseGlow 6s ease-in-out infinite;
        }

        /* Glassmorphic Shell */
        .glass-shell {
            background: rgba(11, 15, 25, 0.85);
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
            border: 1px solid rgba(255, 255, 255, 0.08);
            box-shadow: 0 30px 80px -20px rgba(0, 0, 0, 0.7);
        }
        html:not(.dark) .glass-shell {
            background: #FFFFFF !important;
            border: 1px solid #E2E8F0 !important;
            box-shadow: 0 25px 60px -15px rgba(15, 23, 42, 0.08) !important;
        }

        /* Glass Floating Widgets */
        .glass-widget {
            background: rgba(6, 9, 17, 0.75);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.12);
            box-shadow: 0 16px 36px -10px rgba(0, 0, 0, 0.5);
        }

        /* Light Mode Overrides */
        html:not(.dark) body {
            background-color: #F8FAFC !important;
            color: #0F172A !important;
        }
        html:not(.dark) .auth-panel {
            background-color: #FFFFFF !important;
        }
        html:not(.dark) .auth-input {
            background-color: #FFFFFF !important;
            border-color: #CBD5E1 !important;
            color: #0F172A !important;
        }
        html:not(.dark) .auth-input:focus {
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
            color: #475569 !important;
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
            box-shadow: 0 8px 30px -4px var(--primary-glow);
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .btn-glow-primary:hover {
            box-shadow: 0 14px 40px -2px var(--primary-glow);
            transform: translateY(-2px) scale(1.01);
        }
        .btn-glow-primary:active {
            transform: translateY(0px) scale(0.99);
        }
    </style>
</head>
<body class="bg-[#060911] text-slate-100 min-h-screen relative flex flex-col justify-between selection:bg-emerald-500 selection:text-slate-950 transition-colors duration-200 overflow-x-hidden"
      x-data="{
          selectedRole: 'kasir',
          email: '{{ old('email', 'kasir@fitzone.com') }}',
          password: 'password',
          showPassword: false,
          remember: true,
          isSubmitting: false,
          darkMode: document.documentElement.classList.contains('dark'),
          
          selectRole(role) {
              this.selectedRole = role;
              if (role === 'kasir') {
                  this.email = 'kasir@fitzone.com';
                  this.password = 'password';
              } else if (role === 'owner') {
                  this.email = 'owner@fitzone.com';
                  this.password = 'password';
              }
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
        <div class="absolute -top-40 -left-40 w-[600px] h-[600px] rounded-full bg-emerald-500/15 blur-[140px] animate-glow-pulse"></div>
        <div class="absolute -bottom-40 -right-40 w-[600px] h-[600px] rounded-full bg-cyan-500/15 blur-[140px] animate-glow-pulse" style="animation-delay: -3s;"></div>
        <div class="absolute top-1/3 right-1/4 w-[400px] h-[400px] rounded-full bg-purple-500/10 blur-[120px] pointer-events-none"></div>
    </div>

    <!-- Top Navigation Header -->
    <header class="relative z-30 px-5 sm:px-8 lg:px-12 py-5 flex items-center justify-between">
        
        <!-- Brand Identity Pill -->
        <a href="{{ url('/') }}" class="flex items-center gap-3 group">
            @if(!empty($appSetting->logo_url))
                <div class="w-10 h-10 rounded-2xl bg-slate-900 border border-emerald-500/30 p-1.5 shadow-lg shadow-emerald-500/20 group-hover:scale-105 transition-transform flex items-center justify-center">
                    <img src="{{ $appSetting->logo_url }}" alt="Logo" class="w-full h-full object-contain">
                </div>
            @else
                <div class="w-10 h-10 rounded-2xl bg-gradient-to-tr from-emerald-600 to-teal-400 flex items-center justify-center text-white shadow-lg shadow-emerald-500/30 group-hover:scale-105 transition-transform">
                    <i class="fa-solid fa-dumbbell text-lg"></i>
                </div>
            @endif
            <div>
                <span class="font-display font-black text-lg tracking-tight text-white text-dynamic-title flex items-center gap-1.5">
                    {{ $appSetting->app_name ?? 'FITZONE GYM' }}
                </span>
                <span class="text-[10px] font-mono text-slate-400 text-dynamic-muted block -mt-0.5">
                    {{ $appSetting->app_tagline ?? 'POS & Fitness Management' }}
                </span>
            </div>
        </a>

        <!-- Right Quick Actions (Live Status & Theme Toggle) -->
        <div class="flex items-center gap-2.5 sm:gap-3">
            <!-- Link to Public Landing Page -->
            <a href="{{ route('landing') }}"
               class="px-3 py-2 rounded-2xl border border-white/[0.08] bg-slate-900/90 hover:bg-slate-800 text-slate-300 hover:text-white text-xs font-bold flex items-center gap-2 transition-all shadow-md">
                <i class="fa-solid fa-globe text-emerald-400 text-xs"></i>
                <span class="hidden sm:inline font-display text-[11px]">Lihat Website Gym</span>
            </a>

            <!-- System Status -->
            <div class="hidden sm:flex items-center gap-2 px-3 py-1.5 rounded-full bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-xs font-semibold">
                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                <span class="text-[11px] font-mono">Terminal POS Online</span>
            </div>

            <!-- Light / Dark Mode Switcher Button -->
            <button @click="toggleTheme()" 
                    type="button" 
                    :title="darkMode ? 'Beralih ke Mode Terang' : 'Beralih ke Mode Gelap'"
                    class="px-3 py-2 rounded-2xl border transition-all duration-200 flex items-center gap-2 text-xs font-bold shadow-md cursor-pointer
                           bg-white hover:bg-slate-100 text-slate-700 border-slate-200
                           dark:bg-slate-900/90 dark:hover:bg-slate-800 dark:text-amber-300 dark:border-white/[0.08] hover:scale-105 active:scale-95">
                <template x-if="darkMode">
                    <div class="flex items-center gap-1.5">
                        <i class="fa-solid fa-sun text-amber-400 text-xs"></i>
                        <span class="hidden md:inline text-[11px] font-display text-slate-300">Mode Terang</span>
                    </div>
                </template>
                <template x-if="!darkMode">
                    <div class="flex items-center gap-1.5">
                        <i class="fa-solid fa-moon text-cyan-600 text-xs"></i>
                        <span class="hidden md:inline text-[11px] font-display text-slate-700">Mode Gelap</span>
                    </div>
                </template>
            </button>
        </div>
    </header>

    <!-- Main Content Area: Split-Screen Hero & Auth Card -->
    <main class="relative z-10 flex-1 flex items-center justify-center px-4 sm:px-6 lg:px-8 py-4 sm:py-6">
        <div class="w-full max-w-6xl glass-shell rounded-[2.5rem] overflow-hidden grid grid-cols-1 lg:grid-cols-12 min-h-[640px]">
            
            <!-- LEFT COLUMN: High-Impact Athletic Hero Showcase (6 cols on lg) -->
            <div class="lg:col-span-6 xl:col-span-6 relative hidden lg:flex flex-col justify-between p-10 xl:p-12 overflow-hidden bg-[#060911]">
                
                <!-- Background Hero Image with Deep Cinematic Gradients -->
                <div class="absolute inset-0 z-0">
                    <img src="{{ asset('images/login-hero.jpg') }}" alt="FitZone Gym Showcase" 
                         class="w-full h-full object-cover object-center scale-105 transform transition-transform duration-1000 ease-out hover:scale-100">
                    <!-- Layered Overlays for High Legibility -->
                    <div class="absolute inset-0 bg-gradient-to-t from-[#060911] via-[#060911]/60 to-[#060911]/30"></div>
                    <div class="absolute inset-0 bg-gradient-to-r from-transparent via-[#060911]/40 to-[#060911]"></div>
                </div>

                <!-- Top Floating Card Widget -->
                <div class="relative z-10 flex justify-between items-start">
                    <div class="glass-widget px-4 py-3 rounded-2xl animate-float-slow flex items-center gap-3">
                        <div class="w-9 h-9 rounded-xl bg-emerald-500/20 text-emerald-400 flex items-center justify-center shrink-0 border border-emerald-500/30">
                            <i class="fa-solid fa-bolt text-sm"></i>
                        </div>
                        <div>
                            <div class="text-[11px] font-extrabold uppercase tracking-wider text-emerald-400 font-display">Fast Checkout POS</div>
                            <div class="text-xs font-bold text-white font-mono">&lt; 1 Detik Cetak Struk</div>
                        </div>
                    </div>

                    <div class="glass-widget px-4 py-2.5 rounded-2xl animate-float-delayed flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-cyan-400 animate-ping"></span>
                        <span class="text-xs font-mono font-bold text-cyan-300">Live Inventory Sync</span>
                    </div>
                </div>

                <!-- Center Body Copy & Key Feature Highlights -->
                <div class="relative z-10 my-auto py-8 space-y-5">
                    
                    <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-white/[0.08] backdrop-blur-md border border-white/[0.12] text-xs font-bold text-emerald-400 font-display tracking-wider uppercase">
                        <i class="fa-solid fa-shield-halved text-[11px]"></i>
                        <span>Platform Gym & POS Terpadu</span>
                    </div>

                    <h2 class="text-3xl xl:text-4xl font-black text-white font-display tracking-tight leading-tight">
                        Kendali Penuh Penjualan, Stok & Booking Gym Anda.
                    </h2>

                    <p class="text-sm text-slate-300 xl:text-base leading-relaxed max-w-lg font-normal">
                        Mulai dari kasir POS suplemen, tiket masuk harian, membership bulanan/tahunan, hingga jadwal sesi Personal Trainer secara otomatis.
                    </p>

                    <!-- Feature Badges Grid -->
                    <div class="grid grid-cols-2 gap-3 pt-2">
                        <div class="p-3 rounded-2xl bg-white/[0.05] border border-white/[0.08] backdrop-blur-sm">
                            <div class="flex items-center gap-2 text-xs font-bold text-white mb-0.5">
                                <i class="fa-solid fa-cash-register text-emerald-400"></i>
                                <span>POS & QRIS Multi-Bayar</span>
                            </div>
                            <p class="text-[11px] text-slate-400">Dukung tunai, QRIS statis, dan transfer bank otomatis.</p>
                        </div>

                        <div class="p-3 rounded-2xl bg-white/[0.05] border border-white/[0.08] backdrop-blur-sm">
                            <div class="flex items-center gap-2 text-xs font-bold text-white mb-0.5">
                                <i class="fa-solid fa-calendar-check text-cyan-400"></i>
                                <span>Booking & Kalender</span>
                            </div>
                            <p class="text-[11px] text-slate-400">Matriks jadwal mingguan coach & kelas studio gym.</p>
                        </div>
                    </div>
                </div>

                <!-- Bottom Social Proof & Trust Footer -->
                <div class="relative z-10 pt-4 border-t border-white/[0.1] flex items-center justify-between text-xs text-slate-400">
                    <div class="flex items-center gap-2">
                        <div class="flex -space-x-2">
                            <div class="w-7 h-7 rounded-full bg-emerald-500 border-2 border-slate-900 flex items-center justify-center text-[10px] font-bold text-slate-950 font-mono">OW</div>
                            <div class="w-7 h-7 rounded-full bg-cyan-500 border-2 border-slate-900 flex items-center justify-center text-[10px] font-bold text-slate-950 font-mono">KS</div>
                            <div class="w-7 h-7 rounded-full bg-amber-500 border-2 border-slate-900 flex items-center justify-center text-[10px] font-bold text-slate-950 font-mono">TR</div>
                        </div>
                        <span class="text-[11px] font-medium text-slate-300">Akses Tim Frontdesk & Owner</span>
                    </div>

                    <div class="flex items-center gap-1.5 font-mono text-[11px] text-emerald-400 font-bold">
                        <i class="fa-solid fa-lock text-[10px]"></i>
                        <span>Auth Proteksi SHA-256</span>
                    </div>
                </div>

            </div>

            <!-- RIGHT COLUMN: Interactive High-End Authentication Card (6 cols on lg) -->
            <div class="lg:col-span-6 xl:col-span-6 auth-panel p-6 sm:p-10 xl:p-12 flex flex-col justify-between transition-colors">
                
                <div>
                    <!-- Mobile Brand Showcase (Visible only on small screens) -->
                    <div class="lg:hidden text-center mb-6">
                        @if(!empty($appSetting->logo_url))
                            <div class="inline-flex w-14 h-14 rounded-2xl bg-slate-900 border border-emerald-500/30 p-2 shadow-xl shadow-emerald-500/20 mb-3">
                                <img src="{{ $appSetting->logo_url }}" alt="Logo" class="w-full h-full object-contain">
                            </div>
                        @else
                            <div class="inline-flex w-14 h-14 rounded-2xl bg-gradient-to-tr from-emerald-600 to-teal-400 items-center justify-center text-white shadow-xl shadow-emerald-500/30 mb-3">
                                <i class="fa-solid fa-dumbbell text-2xl"></i>
                            </div>
                        @endif
                        <h1 class="text-2xl font-black tracking-tight text-white text-dynamic-title font-display">{{ $appSetting->app_name ?? 'FITZONE GYM' }}</h1>
                        <p class="text-xs text-slate-400 text-dynamic-muted mt-0.5">{{ $appSetting->app_tagline ?? 'POS & Fitness Management' }}</p>
                    </div>

                    <!-- Card Header Title -->
                    <div class="mb-6">
                        <h2 class="text-2xl sm:text-3xl font-extrabold text-white text-dynamic-title font-display tracking-tight flex items-center gap-2">
                            <span>Masuk ke Akun</span>
                            <span class="inline-block animate-wave text-xl">👋</span>
                        </h2>
                        <p class="text-xs sm:text-sm text-slate-400 text-dynamic-muted mt-1.5">
                            Silakan masukkan email dan kata sandi Anda untuk mengakses terminal kasir atau dashboard.
                        </p>
                    </div>

                    <!-- Fast Role Switcher Tabs (Kasir vs Owner) -->
                    <div class="mb-6 p-1.5 rounded-2xl bg-slate-900/90 dark:bg-slate-900/90 bg-dynamic-subtle border border-slate-800 border-dynamic grid grid-cols-2 gap-1.5 text-xs font-bold">
                        <button type="button" @click="selectRole('kasir')" 
                                :class="selectedRole === 'kasir' ? 'bg-gradient-to-r from-emerald-500 to-teal-400 text-slate-950 font-black shadow-md shadow-emerald-500/20 scale-[1.01]' : 'text-slate-400 text-dynamic-muted hover:text-white'"
                                class="py-2.5 px-3 rounded-xl transition-all duration-200 flex items-center justify-center gap-2">
                            <i class="fa-solid fa-cash-register text-xs"></i>
                            <span>Kasir Frontdesk</span>
                        </button>

                        <button type="button" @click="selectRole('owner')" 
                                :class="selectedRole === 'owner' ? 'bg-gradient-to-r from-amber-500 to-orange-400 text-slate-950 font-black shadow-md shadow-amber-500/20 scale-[1.01]' : 'text-slate-400 text-dynamic-muted hover:text-white'"
                                class="py-2.5 px-3 rounded-xl transition-all duration-200 flex items-center justify-center gap-2">
                            <i class="fa-solid fa-crown text-xs"></i>
                            <span>Owner Gym</span>
                        </button>
                    </div>

                    <!-- Flash Messages & Validation Errors -->
                    @if ($errors->any())
                        <div class="mb-5 p-4 rounded-2xl bg-rose-500/10 border border-rose-500/30 text-rose-300 text-xs shadow-lg shadow-rose-950/20">
                            <div class="flex items-center gap-2 font-bold mb-1">
                                <i class="fa-solid fa-circle-exclamation text-rose-400 text-sm"></i>
                                <span>Gagal Masuk:</span>
                            </div>
                            <ul class="list-disc list-inside space-y-0.5">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if (session('info'))
                        <div class="mb-5 p-3.5 rounded-2xl bg-cyan-500/10 border border-cyan-500/30 text-cyan-300 text-xs flex items-center gap-2.5">
                            <i class="fa-solid fa-circle-info text-cyan-400 text-sm"></i>
                            <span>{{ session('info') }}</span>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="mb-5 p-3.5 rounded-2xl bg-rose-500/10 border border-rose-500/30 text-rose-300 text-xs flex items-center gap-2.5">
                            <i class="fa-solid fa-triangle-exclamation text-rose-400 text-sm"></i>
                            <span>{{ session('error') }}</span>
                        </div>
                    @endif

                    <!-- Login Form -->
                    <form method="POST" action="{{ route('login') }}" @submit="isSubmitting = true" class="space-y-4">
                        @csrf

                        <!-- Email Input -->
                        <div>
                            <label for="email" class="block text-xs font-bold uppercase tracking-wider text-slate-300 text-dynamic-sub mb-1.5 font-display flex items-center justify-between">
                                <span>Alamat Email</span>
                                <span class="text-[10px] font-mono text-emerald-400 font-normal lowercase" x-text="selectedRole === 'kasir' ? 'Role: Kasir' : 'Role: Owner'"></span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-500">
                                    <i class="fa-solid fa-envelope text-xs"></i>
                                </div>
                                <input id="email" type="email" name="email" x-model="email" required autofocus
                                       placeholder="nama@fitzone.com"
                                       class="auth-input w-full pl-10 pr-4 py-3 rounded-2xl bg-slate-900/90 border border-slate-700/80 text-white placeholder-slate-500 text-sm focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 transition-colors shadow-inner">
                            </div>
                        </div>

                        <!-- Password Input with Show/Hide Toggle Button -->
                        <div>
                            <label for="password" class="block text-xs font-bold uppercase tracking-wider text-slate-300 text-dynamic-sub mb-1.5 font-display">
                                <span>Kata Sandi</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-500">
                                    <i class="fa-solid fa-lock text-xs"></i>
                                </div>
                                <input id="password" :type="showPassword ? 'text' : 'password'" name="password" x-model="password" required
                                       placeholder="••••••••"
                                       class="auth-input w-full pl-10 pr-11 py-3 rounded-2xl bg-slate-900/90 border border-slate-700/80 text-white placeholder-slate-500 text-sm focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 transition-colors shadow-inner font-mono">
                                
                                <!-- Show / Hide Password Eye -->
                                <button type="button" @click="showPassword = !showPassword" 
                                        :title="showPassword ? 'Sembunyikan kata sandi' : 'Tampilkan kata sandi'"
                                        class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-white transition-colors cursor-pointer">
                                    <i :class="showPassword ? 'fa-solid fa-eye-slash text-emerald-400' : 'fa-solid fa-eye'" class="text-xs"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Remember Me & Security Notice -->
                        <div class="flex items-center justify-between text-xs pt-1">
                            <label class="flex items-center gap-2 cursor-pointer text-slate-400 text-dynamic-muted hover:text-white select-none">
                                <input type="checkbox" name="remember" x-model="remember" value="1"
                                       class="w-4 h-4 rounded border-slate-700 bg-slate-900 text-emerald-500 focus:ring-emerald-500 transition-all">
                                <span>Ingat saya di perangkat ini</span>
                            </label>

                            <span class="text-[11px] text-slate-500 flex items-center gap-1 font-mono">
                                <i class="fa-solid fa-shield-halved text-[10px] text-emerald-400"></i>
                                <span>Sesi Terenkripsi</span>
                            </span>
                        </div>

                        <!-- Primary Submit Button -->
                        <button type="submit" 
                                :disabled="isSubmitting"
                                class="btn-glow-primary w-full py-3.5 px-5 rounded-2xl text-slate-950 font-black text-sm uppercase tracking-wider flex items-center justify-center gap-2.5 transition-all mt-2 cursor-pointer">
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

                    <!-- Quick Demo Credentials Drawer / Pills -->
                    <div class="mt-8 pt-6 border-t border-slate-800 border-dynamic">
                        <div class="flex items-center justify-between mb-3">
                            <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400 text-dynamic-muted font-display">
                                Akses Cepat Akun Demo (Klik untuk Isi Otomatis)
                            </span>
                            <span class="text-[10px] px-2 py-0.5 rounded-md bg-emerald-500/10 text-emerald-400 font-mono font-bold">1-Click</span>
                        </div>

                        <div class="grid grid-cols-2 gap-2.5">
                            <!-- Kasir Demo Button -->
                            <button type="button" @click="selectRole('kasir')"
                                    :class="selectedRole === 'kasir' ? 'ring-2 ring-emerald-500 bg-emerald-500/15' : 'bg-slate-900/60 dark:bg-slate-900/60 bg-dynamic-subtle border-slate-800 border-dynamic hover:border-emerald-500/40'"
                                    class="p-3 rounded-2xl border text-left transition-all group flex flex-col justify-between">
                                <div class="flex items-center justify-between mb-1">
                                    <span class="text-xs font-bold text-white text-dynamic-title flex items-center gap-1.5 group-hover:text-emerald-400 transition-colors">
                                        <i class="fa-solid fa-cash-register text-emerald-400 text-xs"></i>
                                        <span>Kasir</span>
                                    </span>
                                    <i class="fa-solid fa-circle-check text-xs text-emerald-400" x-show="selectedRole === 'kasir'"></i>
                                </div>
                                <span class="text-[11px] text-slate-400 text-dynamic-muted font-mono truncate">kasir@fitzone.com</span>
                            </button>

                            <!-- Owner Demo Button -->
                            <button type="button" @click="selectRole('owner')"
                                    :class="selectedRole === 'owner' ? 'ring-2 ring-amber-500 bg-amber-500/15' : 'bg-slate-900/60 dark:bg-slate-900/60 bg-dynamic-subtle border-slate-800 border-dynamic hover:border-amber-500/40'"
                                    class="p-3 rounded-2xl border text-left transition-all group flex flex-col justify-between">
                                <div class="flex items-center justify-between mb-1">
                                    <span class="text-xs font-bold text-white text-dynamic-title flex items-center gap-1.5 group-hover:text-amber-400 transition-colors">
                                        <i class="fa-solid fa-crown text-amber-400 text-xs"></i>
                                        <span>Owner</span>
                                    </span>
                                    <i class="fa-solid fa-circle-check text-xs text-amber-400" x-show="selectedRole === 'owner'"></i>
                                </div>
                                <span class="text-[11px] text-slate-400 text-dynamic-muted font-mono truncate">owner@fitzone.com</span>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Footer Copyright -->
                <div class="pt-6 mt-6 border-t border-slate-800/60 border-dynamic text-center text-xs text-slate-400 text-dynamic-muted flex flex-col sm:flex-row items-center justify-between gap-2">
                    <span>{{ $appSetting->app_name ?? 'FitZone Gym' }} &bull; POS & Inventory</span>
                    <span class="font-mono text-[11px]">&copy; {{ date('Y') }} All Rights Reserved</span>
                </div>

            </div>

        </div>
    </main>

    <!-- Footer Security Notice -->
    <footer class="relative z-10 py-3 text-center text-[11px] text-slate-400 text-dynamic-muted font-mono">
        Dilindungi oleh protokol otentikasi sesi Laravel terenkripsi &bull; Versi 2.0 Modern Gym POS
    </footer>

</body>
</html>
