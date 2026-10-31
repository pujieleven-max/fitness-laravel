<!DOCTYPE html>
<html lang="id" class="dark scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $appSetting->app_name ?? 'FitZone Gym' }} — {{ $appSetting->app_tagline ?? 'Pusat Kebugaran Modern, Kelas Studio & Suplemen Resmi' }}</title>

    <!-- Theme Initialization Script -->
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
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;500;600;700;800&family=Outfit:wght@400;500;600;700;800;900&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

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

    <!-- FontAwesome 6 Icons -->
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
            background-color: #060911;
            color: #f1f5f9;
        }

        .font-display { font-family: 'Outfit', sans-serif; }
        .font-mono { font-family: 'JetBrains Mono', monospace; }

        /* Glassmorphic Surface */
        .glass-panel {
            background: rgba(15, 23, 42, 0.78);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.09);
            box-shadow: 0 12px 32px -4px rgba(0, 0, 0, 0.4);
        }

        .glass-card-hover {
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .glass-card-hover:hover {
            transform: translateY(-4px);
            border-color: var(--primary-accent);
            box-shadow: 0 20px 40px -15px var(--primary-glow);
        }

        /* Primary CTA Button with guaranteed readable text */
        .btn-theme-primary {
            background: linear-gradient(135deg, var(--primary-accent) 0%, var(--primary-hover) 100%);
            color: #020617 !important;
            font-weight: 800;
            box-shadow: 0 8px 24px -4px var(--primary-glow);
            transition: all 0.22s ease;
        }
        .btn-theme-primary:hover {
            box-shadow: 0 12px 32px -2px var(--primary-glow);
            transform: translateY(-1px);
            filter: brightness(1.06);
        }
        .btn-theme-primary * {
            color: #020617 !important;
        }

        /* ===================================================
           HIGH-CONTRAST SEMANTIC BADGE SYSTEM (DARK & LIGHT)
           =================================================== */
        .badge-emerald {
            background-color: rgba(16, 185, 129, 0.18);
            color: #6EE7B7;
            border: 1px solid rgba(16, 185, 129, 0.4);
        }
        .badge-cyan {
            background-color: rgba(6, 182, 212, 0.18);
            color: #67E8F9;
            border: 1px solid rgba(6, 182, 212, 0.4);
        }
        .badge-amber {
            background-color: rgba(245, 158, 11, 0.18);
            color: #FCD34D;
            border: 1px solid rgba(245, 158, 11, 0.4);
        }
        .badge-purple {
            background-color: rgba(139, 92, 246, 0.18);
            color: #C4B5FD;
            border: 1px solid rgba(139, 92, 246, 0.4);
        }
        .badge-rose {
            background-color: rgba(244, 63, 94, 0.18);
            color: #FDA4AF;
            border: 1px solid rgba(244, 63, 94, 0.4);
        }
        .badge-neutral {
            background-color: #1E293B;
            color: #E2E8F0;
            border: 1px solid #334155;
        }
        .badge-solid-emerald {
            background: linear-gradient(135deg, #10B981, #059669);
            color: #FFFFFF !important;
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 4px 14px rgba(16, 185, 129, 0.35);
        }
        .badge-solid-amber {
            background: linear-gradient(135deg, #F59E0B, #D97706);
            color: #FFFFFF !important;
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 4px 14px rgba(245, 158, 11, 0.35);
        }

        /* Always-dark overlay card (for cards sitting on top of photos) */
        .photo-overlay-card {
            background: rgba(6, 9, 17, 0.88) !important;
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.16) !important;
            color: #F8FAFC !important;
        }
        .photo-overlay-card h3,
        .photo-overlay-card span:not(.badge-emerald):not(.text-emerald-400) {
            color: #F8FAFC !important;
        }

        /* Section Background Overlay for Program, Trainer & Facilities */
        .program-section-overlay {
            background: linear-gradient(180deg, #060911 0%, rgba(6, 9, 17, 0.84) 50%, #060911 100%);
        }
        html:not(.dark) .program-section-overlay {
            background: linear-gradient(180deg, #F8FAFC 0%, rgba(248, 250, 252, 0.88) 50%, #F8FAFC 100%) !important;
        }

        /* Range Slider Styling */
        input[type="range"] {
            accent-color: var(--primary-accent);
        }

        /* ===================================================
           COMPREHENSIVE LIGHT MODE OVERRIDES
           =================================================== */
        html:not(.dark) body {
            background-color: #F8FAFC !important;
            color: #0F172A !important;
        }
        html:not(.dark) .glass-panel {
            background: #FFFFFF !important;
            border: 1px solid #E2E8F0 !important;
            box-shadow: 0 10px 25px -5px rgba(15, 23, 42, 0.06) !important;
            color: #0F172A !important;
        }
        html:not(.dark) header.nav-glass {
            background: rgba(255, 255, 255, 0.95) !important;
            border-bottom: 1px solid #E2E8F0 !important;
        }

        /* Light mode badge overrides for crisp readability */
        html:not(.dark) .badge-emerald {
            background-color: #D1FAE5 !important;
            color: #065F46 !important;
            border-color: #6EE7B7 !important;
        }
        html:not(.dark) .badge-cyan {
            background-color: #CFFAFE !important;
            color: #155E75 !important;
            border-color: #67E8F9 !important;
        }
        html:not(.dark) .badge-amber {
            background-color: #FEF3C7 !important;
            color: #92400E !important;
            border-color: #FCD34D !important;
        }
        html:not(.dark) .badge-purple {
            background-color: #EDE9FE !important;
            color: #5B21B6 !important;
            border-color: #C4B5FD !important;
        }
        html:not(.dark) .badge-rose {
            background-color: #FFE4E6 !important;
            color: #9F1239 !important;
            border-color: #FDA4AF !important;
        }
        html:not(.dark) .badge-neutral {
            background-color: #F1F5F9 !important;
            color: #1E293B !important;
            border-color: #CBD5E1 !important;
        }

        /* Light mode backgrounds & text contrast */
        html:not(.dark) [class*="bg-[#0B0F19]"]:not(.photo-overlay-card),
        html:not(.dark) [class*="bg-[#060911]"]:not(.photo-overlay-card),
        html:not(.dark) [class*="bg-slate-950"]:not(.photo-overlay-card) {
            background-color: #FFFFFF !important;
            border-color: #E2E8F0 !important;
        }
        html:not(.dark) [class*="bg-slate-900"]:not(.btn-theme-primary):not(.photo-overlay-card) {
            background-color: #F1F5F9 !important;
            border-color: #CBD5E1 !important;
            color: #0F172A !important;
        }
        html:not(.dark) [class*="bg-slate-800"]:not(.btn-theme-primary) {
            background-color: #E2E8F0 !important;
            color: #0F172A !important;
        }
        html:not(.dark) h1:not(.keep-white),
        html:not(.dark) h2:not(.keep-white),
        html:not(.dark) h3:not(.keep-white),
        html:not(.dark) h4:not(.keep-white) {
            color: #0F172A !important;
        }
        html:not(.dark) .photo-overlay-card h3,
        .keep-white,
        html:not(.dark) .keep-white {
            color: #FFFFFF !important;
        }
        .keep-slate-200,
        html:not(.dark) .keep-slate-200 {
            color: #E2E8F0 !important;
        }
        html:not(.dark) .text-white:not(.btn-theme-primary):not(.badge-solid-emerald):not(.badge-solid-amber):not(.keep-white) {
            color: #0F172A !important;
        }
        html:not(.dark) .text-slate-200:not(.keep-white):not(.keep-slate-200),
        html:not(.dark) .text-slate-300:not(.keep-white):not(.keep-slate-200) { color: #1E293B !important; }
        html:not(.dark) .text-slate-400:not(.keep-white):not(.keep-slate-200) { color: #334155 !important; }
        html:not(.dark) .text-slate-500:not(.keep-white):not(.keep-slate-200) { color: #475569 !important; }

        /* Crisp Neon Text Colors on Light Backgrounds */
        html:not(.dark) .text-emerald-400,
        html:not(.dark) .text-emerald-300 { color: #047857 !important; }
        html:not(.dark) .text-cyan-400,
        html:not(.dark) .text-cyan-300    { color: #0E7490 !important; }
        html:not(.dark) .text-amber-400,
        html:not(.dark) .text-amber-300   { color: #B45309 !important; }
        html:not(.dark) .text-purple-400,
        html:not(.dark) .text-purple-300  { color: #6D28D9 !important; }
        html:not(.dark) .text-rose-400,
        html:not(.dark) .text-rose-300    { color: #BE123C !important; }

        html:not(.dark) input:not([type="range"]), html:not(.dark) select, html:not(.dark) textarea {
            background-color: #FFFFFF !important;
            border-color: #CBD5E1 !important;
            color: #0F172A !important;
        }
    </style>
</head>
@php
    $rawPhone = preg_replace('/[^0-9]/', '', $appSetting->phone ?? '081234567890');
    if (str_starts_with($rawPhone, '0')) {
        $waNumber = '62' . substr($rawPhone, 1);
    } elseif (str_starts_with($rawPhone, '8')) {
        $waNumber = '62' . $rawPhone;
    } else {
        $waNumber = $rawPhone ?: '6281234567890';
    }
    $currency = $appSetting->currency_symbol ?? 'Rp';

    $trainerPrices = $trainers->pluck('hourly_rate', 'id');
    $trainerNames = $trainers->pluck('name', 'id');
    $classPrices = $classes->pluck('price_per_session', 'id');
    $classNames = $classes->mapWithKeys(fn($c) => [$c->id => $c->name . ' (' . $c->schedule_day . ' ' . substr($c->start_time, 0, 5) . ')']);
    $dailyPassDefaultPrice = $packages->where('type', 'daily')->min('price') ?? 35000;
@endphp
<body class="min-h-screen flex flex-col selection:bg-emerald-500 selection:text-slate-950 overflow-x-hidden"
      x-data="{
          mobileMenu: false,
          darkMode: document.documentElement.classList.contains('dark'),

          // Active Section Tabs
          programTab: 'classes',
          packageFilter: 'all',
          classDayFilter: 'all',
          suppCategoryFilter: 'all',
          suppSearch: '',
          activeFaq: 1,

          // Contextual Modals & Drawers
          memberModalOpen: false,
          bookingModalOpen: {{ $errors->any() ? 'true' : 'false' }},
          cartDrawerOpen: false,
          ticketModalOpen: {{ session('booking_success') ? 'true' : 'false' }},

          // Toast Notification
          toastMessage: '',
          toastVisible: false,
          showToast(msg) {
              this.toastMessage = msg;
              this.toastVisible = true;
              setTimeout(() => { this.toastVisible = false; }, 3200);
          },

          // Pre-Order Cart State
          cart: JSON.parse(localStorage.getItem('fitzone_public_cart') || '[]'),
          saveCart() {
              localStorage.setItem('fitzone_public_cart', JSON.stringify(this.cart));
          },
          addToCart(item) {
              const existing = this.cart.find(i => i.id === item.id && i.type === item.type);
              if (existing) {
                  existing.qty += 1;
              } else {
                  this.cart.push({ ...item, qty: 1 });
              }
              this.saveCart();
              this.showToast(`${item.name} ditambahkan ke keranjang!`);
          },
          updateQty(index, delta) {
              this.cart[index].qty += delta;
              if (this.cart[index].qty <= 0) {
                  this.cart.splice(index, 1);
              }
              this.saveCart();
          },
          clearCart() {
              this.cart = [];
              this.saveCart();
          },
          get cartCount() {
              return this.cart.reduce((sum, i) => sum + i.qty, 0);
          },
          get cartTotal() {
              return this.cart.reduce((sum, i) => sum + (i.price * i.qty), 0);
          },
          formatRupiah(num) {
              return new Intl.NumberFormat('id-ID').format(Math.round(num || 0));
          },
          checkoutCartWhatsApp() {
              if (this.cart.length === 0) return;
              let lines = [`Halo Kasir *{{ addslashes($appSetting->app_name ?? 'FitZone Gym') }}*, saya ingin memesan:`];
              this.cart.forEach((item, idx) => {
                  lines.push(`${idx + 1}. *${item.name}* (${item.qty}x @ {{ $currency }} ${this.formatRupiah(item.price)}) = {{ $currency }} ${this.formatRupiah(item.price * item.qty)}`);
              });
              lines.push(`\n*Total Estimasi: {{ $currency }} ${this.formatRupiah(this.cartTotal)}*`);
              lines.push(`Mohon info ketersediaan & pembayaran (QRIS/Tunai). Terima kasih!`);
              window.open(`https://wa.me/{{ $waNumber }}?text=${encodeURIComponent(lines.join('\n'))}`, '_blank');
          },

          // BMI & TDEE Calculator State
          bmiGender: 'male',
          bmiAge: 26,
          bmiWeight: 70,
          bmiHeight: 172,
          bmiActivity: 1.55,
          bmiGoal: 'cut',

          // Member / Booking Status Checker State
          statusQuery: '',
          statusLoading: false,
          statusResult: null,
          statusError: '',

          // Online Booking Modal State
          bookingType: 'personal_trainer',
          selectedTrainerId: '{{ $trainers->first()?->id ?? '' }}',
          selectedClassId: '{{ $classes->first()?->id ?? '' }}',
          bookingDate: '{{ date('Y-m-d') }}',
          bookingTime: '16:00',
          customerName: '',
          customerPhone: '',
          memberCode: '',
          bookingNotes: '',

          trainerPrices: @js($trainerPrices),
          trainerNames: @js($trainerNames),
          classPrices: @js($classPrices),
          classNames: @js($classNames),
          dailyPassPrice: {{ (float) $dailyPassDefaultPrice }},

          get estimatedBookingPrice() {
              if (this.bookingType === 'personal_trainer') {
                  return this.trainerPrices[this.selectedTrainerId] || 150000;
              }
              if (this.bookingType === 'fitness_class') {
                  return this.classPrices[this.selectedClassId] || 50000;
              }
              return this.dailyPassPrice;
          },

          get selectedServiceSummary() {
              if (this.bookingType === 'personal_trainer') {
                  return this.trainerNames[this.selectedTrainerId] || 'Personal Trainer 1-on-1';
              }
              if (this.bookingType === 'fitness_class') {
                  return this.classNames[this.selectedClassId] || 'Kelas Studio Fitness';
              }
              return 'Tiket Masuk Harian (Daily Pass Gym)';
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
          },

          get bmiScore() {
              const h = this.bmiHeight / 100;
              if (!h || h <= 0) return 0;
              return (this.bmiWeight / (h * h)).toFixed(1);
          },

          get bmiPercentagePosition() {
              const s = parseFloat(this.bmiScore);
              const pct = ((s - 14) / (36 - 14)) * 100;
              return Math.min(95, Math.max(5, Math.round(pct)));
          },

          get bmiCategory() {
              const s = parseFloat(this.bmiScore);
              if (s < 18.5) return { label: 'Underweight (Kurus)', color: 'text-cyan-400', badge: 'badge-cyan', advice: 'Fokus pada surplus kalori terukur (+350 kkal), latihan beban progresif (Hypertrophy), dan konsumsi Whey Protein + Creatine.' };
              if (s < 25) return { label: 'Ideal & Proporsional', color: 'text-emerald-400', badge: 'badge-emerald', advice: 'Komposisi tubuh ideal! Pertahankan massa otot dengan kombinasi Strength Training 3-4x/minggu dan protein harian yang cukup.' };
              if (s < 30) return { label: 'Overweight (Berlebih)', color: 'text-amber-400', badge: 'badge-amber', advice: 'Disarankan defisit kalori 400-500 kkal, gabung kelas HIIT / Spinning RPM 3x seminggu, serta suplemen Whey Isolate rendah kalori.' };
              return { label: 'Obesitas', color: 'text-rose-400', badge: 'badge-rose', advice: 'Sangat disarankan didampingi Personal Trainer 1-on-1 untuk program Fat Loss bertahap yang aman bagi persendian.' };
          },

          get tdeeCalories() {
              let bmr = (10 * this.bmiWeight) + (6.25 * this.bmiHeight) - (5 * this.bmiAge);
              bmr = this.bmiGender === 'male' ? bmr + 5 : bmr - 161;
              return Math.round(bmr * parseFloat(this.bmiActivity));
          },

          get targetCalories() {
              if (this.bmiGoal === 'cut') return Math.max(1200, this.tdeeCalories - 450);
              if (this.bmiGoal === 'bulk') return this.tdeeCalories + 350;
              return this.tdeeCalories;
          },

          get targetProtein() {
              return Math.round(this.bmiWeight * (this.bmiGoal === 'Maintain' ? 1.6 : 2.0));
          },

          async checkMemberOrBooking(preset = null) {
              if (preset) this.statusQuery = preset;
              this.memberModalOpen = true;
              if (!this.statusQuery || this.statusQuery.trim().length < 3) {
                  this.statusError = 'Masukkan minimal 3 karakter (Contoh: MBR-1001 atau No. HP).';
                  this.statusResult = null;
                  return;
              }
              this.statusLoading = true;
              this.statusError = '';
              this.statusResult = null;
              try {
                  const res = await fetch(`{{ route('public.checkStatus') }}?query=${encodeURIComponent(this.statusQuery.trim())}`, {
                      headers: { 'Accept': 'application/json' }
                  });
                  const json = await res.json();
                  if (res.ok && json.found) {
                      this.statusResult = json;
                  } else {
                      this.statusError = json.message || 'Data tidak ditemukan.';
                  }
              } catch (e) {
                  this.statusError = 'Terjadi gangguan jaringan saat memeriksa status.';
              } finally {
                  this.statusLoading = false;
              }
          },

          openBookingModal(type = 'personal_trainer', id = null, notes = '') {
              this.bookingType = type;
              if (type === 'personal_trainer' && id) {
                  this.selectedTrainerId = String(id);
              } else if (type === 'fitness_class' && id) {
                  this.selectedClassId = String(id);
              }
              if (notes) {
                  this.bookingNotes = notes;
              }
              this.bookingModalOpen = true;
          }
      }">

    <!-- Ambient Glowing Background Orbs -->
    <div class="fixed inset-0 pointer-events-none overflow-hidden z-0">
        <div class="absolute -top-48 -left-48 w-[600px] h-[600px] rounded-full blur-[150px] opacity-20" style="background: var(--primary-accent);"></div>
        <div class="absolute top-1/3 -right-48 w-[500px] h-[500px] rounded-full bg-cyan-500/15 blur-[140px]"></div>
    </div>

    <!-- ==========================================
         FLOATING TOAST NOTIFICATION
         ========================================== -->
    <div x-show="toastVisible" x-cloak
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-y-4 scale-95"
         x-transition:enter-end="opacity-100 translate-y-0 scale-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 translate-y-0 scale-100"
         x-transition:leave-end="opacity-0 translate-y-4 scale-95"
         class="fixed bottom-24 right-6 z-50 max-w-sm px-4 py-3.5 rounded-2xl photo-overlay-card shadow-2xl flex items-center gap-3">
        <div class="w-8 h-8 rounded-xl bg-emerald-500 text-slate-950 flex items-center justify-center shrink-0 font-bold">
            <i class="fa-solid fa-check"></i>
        </div>
        <div class="text-xs font-semibold leading-snug keep-white" x-text="toastMessage"></div>
        <button @click="toastVisible = false; cartDrawerOpen = true" class="text-[11px] font-mono font-bold text-emerald-400 underline shrink-0 ml-1 cursor-pointer">
            Keranjang
        </button>
    </div>

    <!-- ==========================================
         1. MINIMALIST & CLEAN TOP NAVBAR
         ========================================== -->
    <header class="nav-glass sticky top-0 z-40 bg-[#0B0F19]/90 backdrop-blur-xl border-b border-white/[0.07] transition-all">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 sm:h-20 flex items-center justify-between gap-4">
            
            <!-- Left: Clean Brand Identity -->
            <a href="{{ route('landing') }}" class="flex items-center gap-3 group shrink-0">
                @if(!empty($appSetting->logo_url))
                    <img src="{{ $appSetting->logo_url }}" alt="Logo" class="w-10 h-10 rounded-xl object-contain bg-slate-900 border border-white/15 p-1 shadow-md group-hover:scale-105 transition-transform">
                @else
                    <div class="w-10 h-10 rounded-xl btn-theme-primary flex items-center justify-center shadow-md group-hover:scale-105 transition-transform">
                        <i class="fa-solid fa-dumbbell text-lg"></i>
                    </div>
                @endif
                <span class="font-display font-black text-lg sm:text-xl tracking-tight text-white">
                    {{ $appSetting->app_name ?? 'FITZONE GYM' }}
                </span>
            </a>

            <!-- Center: Simple, Airy 4-Link Menu -->
            <nav class="hidden md:flex items-center gap-7 text-sm font-semibold text-slate-300">
                <a href="#paket" class="hover:text-emerald-400 transition-colors">Paket Gym</a>
                <a href="#program" class="hover:text-emerald-400 transition-colors">Kelas & Trainer</a>
                <a href="#suplemen" class="hover:text-emerald-400 transition-colors">Suplemen</a>
                <button type="button" @click="memberModalOpen = true" class="hover:text-emerald-400 transition-colors flex items-center gap-1.5 cursor-pointer">
                    <span>Cek Member</span>
                </button>
            </nav>

            <!-- Right: Minimalist Action Controls (Cart + Theme + Primary CTA) -->
            <div class="flex items-center gap-2">
                <!-- Cart Icon Button -->
                <button type="button" @click="cartDrawerOpen = true"
                        class="relative w-10 h-10 rounded-xl bg-slate-900/90 hover:bg-slate-800 border border-white/[0.08] flex items-center justify-center text-slate-200 transition-all cursor-pointer"
                        title="Keranjang Pesanan">
                    <i class="fa-solid fa-bag-shopping text-sm"></i>
                    <span x-show="cartCount > 0" x-cloak
                          x-text="cartCount"
                          class="absolute -top-1.5 -right-1.5 min-w-[20px] h-5 px-1 rounded-full badge-solid-emerald font-mono font-black text-[10px] flex items-center justify-center"></span>
                </button>

                <!-- Theme Toggle Icon -->
                <button @click="toggleTheme()" type="button"
                        :title="darkMode ? 'Mode Terang' : 'Mode Gelap'"
                        class="w-10 h-10 rounded-xl bg-slate-900/90 hover:bg-slate-800 border border-white/[0.08] flex items-center justify-center text-slate-300 transition-all cursor-pointer">
                    <i :class="darkMode ? 'fa-solid fa-sun text-amber-400' : 'fa-solid fa-moon text-slate-700'" class="text-sm"></i>
                </button>

                <!-- Staff Login Icon Button -->
                @auth
                    <a href="{{ route('dashboard') }}"
                       class="w-10 h-10 rounded-xl bg-slate-900/90 hover:bg-slate-800 border border-white/[0.08] flex items-center justify-center text-emerald-400 hover:text-emerald-300 transition-all"
                       title="Dashboard Staff ({{ auth()->user()->name }})"
                       aria-label="Dashboard Staff">
                        <i class="fa-solid fa-gauge-high text-sm"></i>
                    </a>
                @else
                    <a href="{{ route('login') }}"
                       class="w-10 h-10 rounded-xl bg-slate-900/90 hover:bg-slate-800 border border-white/[0.08] flex items-center justify-center text-slate-300 hover:text-emerald-400 transition-all"
                       title="Login Staff"
                       aria-label="Login Staff">
                        <i class="fa-solid fa-user-shield text-sm"></i>
                    </a>
                @endauth

                <!-- Single Primary CTA -->
                <button type="button" @click="openBookingModal('personal_trainer')"
                        class="hidden sm:inline-flex btn-theme-primary px-4 py-2.5 rounded-xl text-xs uppercase tracking-wider items-center gap-2 cursor-pointer">
                    <i class="fa-solid fa-calendar-check"></i>
                    <span>Booking Sesi</span>
                </button>

                <!-- Mobile Hamburger -->
                <button @click="mobileMenu = !mobileMenu" class="md:hidden w-10 h-10 rounded-xl bg-slate-900 border border-white/[0.08] flex items-center justify-center text-slate-300">
                    <i :class="mobileMenu ? 'fa-solid fa-xmark' : 'fa-solid fa-bars'"></i>
                </button>
            </div>
        </div>

        <!-- Mobile Simple Dropdown -->
        <div x-show="mobileMenu" x-cloak x-transition
             class="md:hidden border-t border-white/[0.08] bg-[#0B0F19] px-4 py-4 space-y-2">
            <a @click="mobileMenu = false" href="#paket" class="block px-3 py-2.5 rounded-xl text-sm font-bold text-slate-200 hover:bg-slate-900">Paket & Harga</a>
            <a @click="mobileMenu = false" href="#program" class="block px-3 py-2.5 rounded-xl text-sm font-bold text-slate-200 hover:bg-slate-900">Jadwal Kelas & Trainer</a>
            <a @click="mobileMenu = false" href="#suplemen" class="block px-3 py-2.5 rounded-xl text-sm font-bold text-slate-200 hover:bg-slate-900">Toko Suplemen</a>
            <button type="button" @click="mobileMenu = false; memberModalOpen = true" class="w-full text-left px-3 py-2.5 rounded-xl text-sm font-bold text-emerald-400 hover:bg-slate-900">
                Cek Kartu Member / Booking
            </button>
            <div class="pt-2">
                <button type="button" @click="mobileMenu = false; openBookingModal('personal_trainer')" class="w-full btn-theme-primary py-3 rounded-xl text-xs uppercase tracking-wider">
                    Booking Sesi Online
                </button>
            </div>
        </div>
    </header>

    <!-- ==========================================
         MAIN CONTENT FLOW
         ========================================== -->
    <main class="relative z-10 flex-1">

        <!-- SECTION 1: HERO + QUICK INTENT DOCK -->
        <section class="relative pt-8 pb-14 lg:pt-14 lg:pb-20">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-10">
                
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12 items-center">
                    <!-- Left Copy (7 Cols) -->
                    <div class="lg:col-span-7 space-y-6">
                        <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full badge-emerald text-xs font-extrabold uppercase tracking-wider font-display">
                            <i class="fa-solid fa-bolt"></i>
                            <span>Pusat Kebugaran, Studio Kelas & Nutrisi Resmi</span>
                        </div>

                        <h1 class="text-4xl sm:text-5xl xl:text-6xl font-black text-white font-display tracking-tight leading-[1.08]">
                            Latihan Lebih Terarah, Hasil Lebih Nyata di
                            <span class="text-emerald-400">
                                {{ $appSetting->app_name ?? 'FitZone Gym' }}
                            </span>
                        </h1>

                        <p class="text-sm sm:text-base lg:text-lg text-slate-300 leading-relaxed max-w-2xl">
                            Mulai dari <strong class="text-white">Tiket Harian {{ $currency }} {{ number_format($dailyPassDefaultPrice, 0, ',', '.') }}</strong>, paket bulanan hemat tanpa kontrak rumit, jadwal kelas studio berenergi tinggi, hingga bimbingan Personal Trainer & suplemen 100% original.
                        </p>

                        <!-- Primary Action Buttons -->
                        <div class="flex flex-wrap items-center gap-3 pt-1">
                            <a href="#paket"
                               class="btn-theme-primary px-7 py-4 rounded-2xl text-xs sm:text-sm uppercase tracking-wider flex items-center gap-2.5">
                                <i class="fa-solid fa-fire-flame-curved"></i>
                                <span>Lihat Paket & Harga</span>
                            </a>

                            <button type="button" @click="openBookingModal('personal_trainer')"
                                    class="px-6 py-4 rounded-2xl bg-slate-900 hover:bg-slate-800 border border-white/[0.12] text-white font-bold text-xs sm:text-sm flex items-center gap-2.5 transition-all cursor-pointer">
                                <i class="fa-solid fa-calendar-plus text-emerald-400"></i>
                                <span>Reservasi Sesi / Trial</span>
                            </button>
                        </div>

                        <!-- Trust Metrics Strip -->
                        <div class="pt-4 grid grid-cols-4 gap-3 border-t border-white/[0.08] max-w-xl">
                            <div>
                                <div class="text-xl sm:text-2xl font-black text-white font-display">{{ $stats['active_members'] }}+</div>
                                <div class="text-xs font-medium text-slate-400">Member Aktif</div>
                            </div>
                            <div>
                                <div class="text-xl sm:text-2xl font-black text-white font-display">{{ $stats['total_trainers'] }}</div>
                                <div class="text-xs font-medium text-slate-400">Coach Pro</div>
                            </div>
                            <div>
                                <div class="text-xl sm:text-2xl font-black text-white font-display">{{ $stats['total_classes'] }}</div>
                                <div class="text-xs font-medium text-slate-400">Kelas / Minggu</div>
                            </div>
                            <div>
                                <div class="text-xl sm:text-2xl font-black text-white font-display">{{ $stats['total_supplements'] }}+</div>
                                <div class="text-xs font-medium text-slate-400">Suplemen Ori</div>
                            </div>
                        </div>
                    </div>

                    <!-- Right Visual Card (5 Cols) -->
                    <div class="lg:col-span-5">
                        <div class="relative rounded-[2rem] overflow-hidden border border-white/[0.12] shadow-2xl bg-[#0B0F19] aspect-[4/4.2]">
                            <img src="{{ asset('images/login-hero.jpg') }}" alt="{{ $appSetting->app_name }}"
                                 class="w-full h-full object-cover object-center">
                            <div class="absolute inset-0 bg-gradient-to-t from-[#060911] via-[#060911]/30 to-transparent"></div>

                            <!-- Top Pill Overlay (Always High-Contrast on Photo) -->
                            <div class="absolute top-4 left-4 right-4 flex items-center justify-between gap-2">
                                <span class="px-3.5 py-1.5 rounded-full photo-overlay-card text-xs font-mono font-bold flex items-center gap-2">
                                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                                    <span>06:00 – 22:00 WIB</span>
                                </span>
                                <span class="px-3.5 py-1.5 rounded-full badge-solid-emerald font-mono font-extrabold text-xs">
                                    Free Locker & Shower
                                </span>
                            </div>

                            <!-- Bottom Interactive Overlay -->
                            <div class="absolute bottom-4 left-4 right-4 p-4 sm:p-5 rounded-2xl photo-overlay-card flex items-center justify-between gap-3">
                                <div>
                                    <span class="text-[11px] font-mono uppercase text-emerald-400 font-bold block">Sudah Punya Kartu Member?</span>
                                    <h3 class="text-sm font-extrabold font-display">Cek Masa Aktif & Jadwal Booking Anda</h3>
                                </div>
                                <button type="button" @click="memberModalOpen = true"
                                        class="btn-theme-primary px-4 py-2.5 rounded-xl text-xs shrink-0 cursor-pointer">
                                    Cek Status
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- QUICK INTENT DOCK (4 Action Shortcuts) -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 pt-2">
                    <a href="#paket" class="p-4 rounded-2xl glass-panel glass-card-hover flex items-center justify-between group">
                        <div class="flex items-center gap-3.5">
                            <div class="w-11 h-11 rounded-xl badge-amber flex items-center justify-center text-lg shrink-0">
                                <i class="fa-solid fa-ticket"></i>
                            </div>
                            <div>
                                <h3 class="text-xs font-extrabold uppercase tracking-wider text-white font-display">Daftar Membership</h3>
                                <p class="text-xs text-slate-400">Harian, Bulanan & VIP</p>
                            </div>
                        </div>
                        <i class="fa-solid fa-arrow-down text-xs text-slate-400 group-hover:text-amber-400 transition-colors"></i>
                    </a>

                    <a href="#program" @click="programTab = 'classes'" class="p-4 rounded-2xl glass-panel glass-card-hover flex items-center justify-between group">
                        <div class="flex items-center gap-3.5">
                            <div class="w-11 h-11 rounded-xl badge-cyan flex items-center justify-center text-lg shrink-0">
                                <i class="fa-solid fa-calendar-days"></i>
                            </div>
                            <div>
                                <h3 class="text-xs font-extrabold uppercase tracking-wider text-white font-display">Jadwal Kelas & PT</h3>
                                <p class="text-xs text-slate-400">{{ $classes->count() }} Kelas & {{ $trainers->count() }} Coach</p>
                            </div>
                        </div>
                        <i class="fa-solid fa-arrow-down text-xs text-slate-400 group-hover:text-cyan-400 transition-colors"></i>
                    </a>

                    <a href="#suplemen" class="p-4 rounded-2xl glass-panel glass-card-hover flex items-center justify-between group">
                        <div class="flex items-center gap-3.5">
                            <div class="w-11 h-11 rounded-xl badge-purple flex items-center justify-center text-lg shrink-0">
                                <i class="fa-solid fa-bottle-droplet"></i>
                            </div>
                            <div>
                                <h3 class="text-xs font-extrabold uppercase tracking-wider text-white font-display">Belanja Suplemen</h3>
                                <p class="text-xs text-slate-400">Whey, Creatine, RTD</p>
                            </div>
                        </div>
                        <i class="fa-solid fa-arrow-down text-xs text-slate-400 group-hover:text-purple-400 transition-colors"></i>
                    </a>

                    <a href="#kalkulator" class="p-4 rounded-2xl glass-panel glass-card-hover flex items-center justify-between group">
                        <div class="flex items-center gap-3.5">
                            <div class="w-11 h-11 rounded-xl badge-emerald flex items-center justify-center text-lg shrink-0">
                                <i class="fa-solid fa-calculator"></i>
                            </div>
                            <div>
                                <h3 class="text-xs font-extrabold uppercase tracking-wider text-white font-display">Kalkulator BMI</h3>
                                <p class="text-xs text-slate-400">Cek Kalori & Protein</p>
                            </div>
                        </div>
                        <i class="fa-solid fa-arrow-down text-xs text-slate-400 group-hover:text-emerald-400 transition-colors"></i>
                    </a>
                </div>

            </div>
        </section>

        <!-- ==========================================
             SECTION 2: PAKET MEMBERSHIP & TIKET HARIAN
             ========================================== -->
        <section id="paket" class="py-16 lg:py-20 bg-[#0B0F19]/50 border-y border-white/[0.06]">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
                
                <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
                    <div class="space-y-2">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full badge-amber text-xs font-bold uppercase tracking-wider">
                            <i class="fa-solid fa-tag"></i> Paket & Harga Resmi
                        </span>
                        <h2 class="text-2xl sm:text-4xl font-black text-white font-display tracking-tight">
                            Tiket Harian & Paket Langganan Gym
                        </h2>
                        <p class="text-xs sm:text-sm text-slate-400">
                            Pilih tiket sekali datang (Daily Pass) atau berlangganan bulanan untuk akses tanpa batas setiap hari.
                        </p>
                    </div>

                    <!-- Segmented Filter Control -->
                    <div class="inline-flex p-1.5 rounded-2xl bg-slate-900 border border-white/[0.08] self-start">
                        <button type="button" @click="packageFilter = 'all'"
                                :class="packageFilter === 'all' ? 'btn-theme-primary' : 'text-slate-400 hover:text-white'"
                                class="px-3.5 py-2 rounded-xl text-xs font-bold transition-all cursor-pointer">
                            Semua ({{ $packages->count() }})
                        </button>
                        <button type="button" @click="packageFilter = 'daily'"
                                :class="packageFilter === 'daily' ? 'btn-theme-primary' : 'text-slate-400 hover:text-white'"
                                class="px-3.5 py-2 rounded-xl text-xs font-bold transition-all cursor-pointer">
                            Harian
                        </button>
                        <button type="button" @click="packageFilter = 'monthly'"
                                :class="packageFilter === 'monthly' ? 'btn-theme-primary' : 'text-slate-400 hover:text-white'"
                                class="px-3.5 py-2 rounded-xl text-xs font-bold transition-all cursor-pointer">
                            Bulanan
                        </button>
                        <button type="button" @click="packageFilter = 'annual'"
                                :class="packageFilter === 'annual' ? 'btn-theme-primary' : 'text-slate-400 hover:text-white'"
                                class="px-3.5 py-2 rounded-xl text-xs font-bold transition-all cursor-pointer">
                            VIP Tahunan
                        </button>
                    </div>
                </div>

                <!-- Packages Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($packages as $pkg)
                        @php
                            $isFeatured = $pkg->duration_days >= 90 && $pkg->duration_days < 365;
                            $isVip = $pkg->duration_days >= 365;
                            $perDayCost = $pkg->duration_days > 0 ? round($pkg->price / $pkg->duration_days) : $pkg->price;
                        @endphp
                        <div x-show="packageFilter === 'all' || packageFilter === '{{ $pkg->type }}'"
                             x-transition
                             class="relative rounded-3xl p-6 flex flex-col justify-between glass-panel glass-card-hover {{ $isFeatured ? 'border-2 border-emerald-500 shadow-xl shadow-emerald-500/10' : ($isVip ? 'border-2 border-amber-500/70' : '') }}">
                            
                            @if($isFeatured)
                                <div class="absolute -top-3.5 right-5 px-3.5 py-1 rounded-full badge-solid-emerald font-black text-[11px] uppercase tracking-wider">
                                    <i class="fa-solid fa-fire mr-1"></i> Paling Populer
                                </div>
                            @elseif($isVip)
                                <div class="absolute -top-3.5 right-5 px-3.5 py-1 rounded-full badge-solid-amber font-black text-[11px] uppercase tracking-wider">
                                    <i class="fa-solid fa-crown mr-1"></i> Best Value VIP
                                </div>
                            @endif

                            <div class="space-y-4">
                                <div class="flex items-center justify-between">
                                    <span class="px-3 py-1 rounded-lg text-xs font-mono font-bold uppercase
                                        {{ $pkg->type === 'daily' ? 'badge-cyan' : ($pkg->type === 'annual' ? 'badge-amber' : 'badge-emerald') }}">
                                        {{ $pkg->duration_days }} Hari Aktif
                                    </span>
                                    <span class="px-2.5 py-1 rounded-md badge-neutral text-[11px] font-mono font-semibold">{{ $pkg->code }}</span>
                                </div>

                                <div>
                                    <h3 class="text-lg font-extrabold text-white font-display">{{ $pkg->name }}</h3>
                                    <p class="text-xs text-slate-400 mt-1 leading-relaxed min-h-[34px]">
                                        {{ $pkg->description ?? 'Akses bebas seluruh alat gym, cardio zone, loker, dan kamar mandi air hangat.' }}
                                    </p>
                                </div>

                                <div class="p-4 rounded-2xl bg-slate-900/90 border border-white/[0.06] flex items-baseline justify-between">
                                    <div>
                                        <span class="text-xs font-bold text-slate-400 font-mono">{{ $currency }}</span>
                                        <span class="text-2xl sm:text-3xl font-black text-white font-mono tracking-tight">
                                            {{ number_format($pkg->price, 0, ',', '.') }}
                                        </span>
                                    </div>
                                    <span class="px-2.5 py-1 rounded-lg badge-emerald text-[11px] font-mono font-bold">
                                        ~{{ $currency }} {{ number_format($perDayCost, 0, ',', '.') }}/hari
                                    </span>
                                </div>
                            </div>

                            <!-- Dual Action Buttons -->
                            <div class="pt-5 mt-5 border-t border-white/[0.07] grid grid-cols-5 gap-2">
                                <button type="button"
                                        @click="addToCart({ id: 'pkg-{{ $pkg->id }}', type: 'package', name: '{{ addslashes($pkg->name) }}', price: {{ (float) $pkg->price }} })"
                                        class="col-span-2 py-3 px-3 rounded-xl bg-slate-900 hover:bg-slate-800 border border-white/10 text-slate-200 font-bold text-xs flex items-center justify-center gap-1.5 transition-all cursor-pointer">
                                    <i class="fa-solid fa-cart-plus text-emerald-400"></i>
                                    <span>+ Keranjang</span>
                                </button>

                                <a href="https://wa.me/{{ $waNumber }}?text={{ urlencode('Halo Admin ' . ($appSetting->app_name ?? 'FitZone Gym') . ', saya ingin mendaftar paket *' . $pkg->name . '* (' . $currency . ' ' . number_format($pkg->price, 0, ',', '.') . ').') }}"
                                   target="_blank"
                                   class="col-span-3 btn-theme-primary py-3 px-4 rounded-xl text-xs uppercase tracking-wider flex items-center justify-center gap-1.5">
                                    <i class="fa-brands fa-whatsapp text-sm"></i>
                                    <span>Daftar Paket</span>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>

            </div>
        </section>

        <!-- ==========================================
             SECTION 3: UNIFIED PROGRAM HUB (KELAS, TRAINER, FASILITAS)
             ========================================== -->
        <section id="program" class="relative py-16 lg:py-24 overflow-hidden">
            <!-- Section Background Image + Adaptive Overlay -->
            <div class="absolute inset-0 pointer-events-none z-0">
                <img src="https://images.unsplash.com/photo-1534438327276-14e5300c3a48?auto=format&fit=crop&w=1920&q=80"
                     onerror="this.src='{{ asset('images/login-hero.jpg') }}'"
                     alt="Gym Studio & Facilities Background"
                     class="w-full h-full object-cover object-center opacity-35 scale-105">
                <div class="absolute inset-0 program-section-overlay"></div>
            </div>

            <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
                
                <!-- Section Header + 3-Way Tab Switcher -->
                <div class="flex flex-col lg:flex-row lg:items-end justify-between gap-6">
                    <div class="space-y-2">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full badge-cyan text-xs font-bold uppercase tracking-wider">
                            <i class="fa-solid fa-dumbbell"></i> Program & Fasilitas
                        </span>
                        <h2 class="text-2xl sm:text-4xl font-black text-white font-display tracking-tight">
                            Kelas Studio, Personal Trainer & Fasilitas
                        </h2>
                        <p class="text-xs sm:text-sm text-slate-400">
                            Pilih tab di samping untuk melihat jadwal kelas mingguan, profil coach privat, atau fasilitas gym.
                        </p>
                    </div>

                    <!-- Primary Hub Tabs -->
                    <div class="inline-flex p-1.5 rounded-2xl bg-slate-900 border border-white/[0.08] self-start">
                        <button type="button" @click="programTab = 'classes'"
                                :class="programTab === 'classes' ? 'btn-theme-primary' : 'text-slate-300 hover:text-white'"
                                class="px-4 py-2.5 rounded-xl text-xs font-bold flex items-center gap-2 transition-all cursor-pointer">
                            <i class="fa-solid fa-calendar-days"></i>
                            <span>Jadwal Kelas ({{ $classes->count() }})</span>
                        </button>
                        <button type="button" @click="programTab = 'trainers'"
                                :class="programTab === 'trainers' ? 'btn-theme-primary' : 'text-slate-300 hover:text-white'"
                                class="px-4 py-2.5 rounded-xl text-xs font-bold flex items-center gap-2 transition-all cursor-pointer">
                            <i class="fa-solid fa-person-running"></i>
                            <span>Personal Trainer ({{ $trainers->count() }})</span>
                        </button>
                        <button type="button" @click="programTab = 'facilities'"
                                :class="programTab === 'facilities' ? 'btn-theme-primary' : 'text-slate-300 hover:text-white'"
                                class="px-4 py-2.5 rounded-xl text-xs font-bold flex items-center gap-2 transition-all cursor-pointer">
                            <i class="fa-solid fa-layer-group"></i>
                            <span>Fasilitas Gym</span>
                        </button>
                    </div>
                </div>

                <!-- TAB 1: JADWAL KELAS STUDIO -->
                <div x-show="programTab === 'classes'" x-transition class="space-y-6">
                    <!-- Day Filter Bar -->
                    <div class="flex flex-wrap items-center justify-between gap-3 p-4 rounded-2xl glass-panel">
                        <span class="text-xs font-bold text-slate-300 flex items-center gap-2">
                            <i class="fa-solid fa-filter text-emerald-400"></i>
                            <span>Filter Hari Latihan:</span>
                        </span>
                        <div class="flex flex-wrap gap-1.5">
                            @foreach(['all' => 'Semua Hari', 'Senin' => 'Senin', 'Selasa' => 'Selasa', 'Rabu' => 'Rabu', 'Kamis' => 'Kamis', 'Jumat' => 'Jumat', 'Sabtu' => 'Sabtu'] as $key => $label)
                                <button type="button" @click="classDayFilter = '{{ $key }}'"
                                        :class="classDayFilter === '{{ $key }}' ? 'btn-theme-primary' : 'badge-neutral hover:border-emerald-500/50'"
                                        class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition-all cursor-pointer">
                                    {{ $label }}
                                </button>
                            @endforeach
                        </div>
                    </div>

                    @php
                        $classBgPool = [
                            'https://images.unsplash.com/photo-1518611012118-696072aa579a?auto=format&fit=crop&w=900&q=80',
                            'https://images.unsplash.com/photo-1571019614242-c5c5dee9f50b?auto=format&fit=crop&w=900&q=80',
                            'https://images.unsplash.com/photo-1540497077202-7c8a3999166f?auto=format&fit=crop&w=900&q=80',
                            'https://images.unsplash.com/photo-1599058917212-d750089bc07e?auto=format&fit=crop&w=900&q=80',
                            'https://images.unsplash.com/photo-1534258936925-c58bed479fcb?auto=format&fit=crop&w=900&q=80',
                            'https://images.unsplash.com/photo-1574680096145-d05b474e2155?auto=format&fit=crop&w=900&q=80',
                        ];
                    @endphp

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($classes as $idx => $cls)
                            @php
                                $clsLower = strtolower($cls->name . ' ' . $cls->category);
                                if (str_contains($clsLower, 'yoga') || str_contains($clsLower, 'pilates') || str_contains($clsLower, 'mind')) {
                                    $clsImg = 'https://images.unsplash.com/photo-1518611012118-696072aa579a?auto=format&fit=crop&w=900&q=80';
                                } elseif (str_contains($clsLower, 'rpm') || str_contains($clsLower, 'spin') || str_contains($clsLower, 'cycle')) {
                                    $clsImg = 'https://images.unsplash.com/photo-1534258936925-c58bed479fcb?auto=format&fit=crop&w=900&q=80';
                                } elseif (str_contains($clsLower, 'hiit') || str_contains($clsLower, 'burn') || str_contains($clsLower, 'cross')) {
                                    $clsImg = 'https://images.unsplash.com/photo-1599058917212-d750089bc07e?auto=format&fit=crop&w=900&q=80';
                                } elseif (str_contains($clsLower, 'zumba') || str_contains($clsLower, 'dance') || str_contains($clsLower, 'cardio')) {
                                    $clsImg = 'https://images.unsplash.com/photo-1524594152303-9fd13543fe6e?auto=format&fit=crop&w=900&q=80';
                                } elseif (str_contains($clsLower, 'box') || str_contains($clsLower, 'muay') || str_contains($clsLower, 'combat')) {
                                    $clsImg = 'https://images.unsplash.com/photo-1549719386-74dfcbf7dbed?auto=format&fit=crop&w=900&q=80';
                                } else {
                                    $clsImg = $classBgPool[$idx % count($classBgPool)];
                                }
                            @endphp
                            <div x-show="classDayFilter === 'all' || classDayFilter === '{{ $cls->schedule_day }}'"
                                 class="rounded-3xl glass-panel glass-card-hover overflow-hidden flex flex-col justify-between group">
                                
                                <!-- Visual Background Image Header -->
                                <div>
                                    <div class="relative h-44 overflow-hidden bg-slate-950">
                                        <img src="{{ $clsImg }}"
                                             onerror="this.src='{{ asset('images/login-hero.jpg') }}'"
                                             alt="{{ $cls->name }}"
                                             class="w-full h-full object-cover object-center group-hover:scale-110 transition-transform duration-700">
                                        <div class="absolute inset-0 bg-gradient-to-t from-[#0B0F19] via-[#0B0F19]/45 to-black/30"></div>

                                        <!-- Floating Top Badges -->
                                        <div class="absolute top-3.5 left-3.5 right-3.5 flex items-center justify-between gap-2">
                                            <span class="px-3 py-1 rounded-lg photo-overlay-card text-[11px] font-extrabold uppercase tracking-wide keep-white">
                                                {{ $cls->category }}
                                            </span>
                                            <span class="px-3 py-1 rounded-lg badge-solid-emerald font-mono text-xs font-bold">
                                                {{ $cls->schedule_day }} &bull; {{ substr($cls->start_time, 0, 5) }}
                                            </span>
                                        </div>

                                        <!-- Bottom Title Over Image -->
                                        <div class="absolute bottom-3 left-4 right-4">
                                            <h3 class="text-lg font-extrabold text-white keep-white font-display drop-shadow">{{ $cls->name }}</h3>
                                            <p class="text-xs text-slate-200 keep-white mt-0.5 font-medium flex items-center gap-1.5">
                                                <i class="fa-solid fa-location-dot text-emerald-400"></i>
                                                <span>{{ $cls->room ?? 'Studio Utama' }}</span>
                                                <span>&bull;</span>
                                                <span>Kapasitas {{ $cls->capacity }} Kursi</span>
                                            </p>
                                        </div>
                                    </div>

                                    <!-- Card Body -->
                                    <div class="p-5 pb-4">
                                        <div class="p-3.5 rounded-2xl bg-slate-900/90 border border-white/[0.06] flex items-center justify-between">
                                            <div class="flex items-center gap-2.5">
                                                <div class="w-9 h-9 rounded-xl badge-cyan font-black text-xs flex items-center justify-center">
                                                    {{ strtoupper(substr($cls->trainer->name ?? 'C', 0, 2)) }}
                                                </div>
                                                <div>
                                                    <span class="text-[11px] text-slate-400 block">Instruktur</span>
                                                    <strong class="text-xs text-white">{{ $cls->trainer->name ?? 'Coach Studio' }}</strong>
                                                </div>
                                            </div>
                                            <div class="text-right">
                                                <span class="text-[11px] text-slate-400 block">Tarif / Sesi</span>
                                                <strong class="text-sm font-mono font-black text-emerald-400">{{ $currency }} {{ number_format($cls->price_per_session, 0, ',', '.') }}</strong>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="px-5 pb-5">
                                    <button type="button"
                                            @click="openBookingModal('fitness_class', {{ $cls->id }})"
                                            class="btn-theme-primary w-full py-3 rounded-xl text-xs uppercase tracking-wider flex items-center justify-center gap-2 cursor-pointer">
                                        <i class="fa-solid fa-calendar-check"></i>
                                        <span>Reservasi Kursi Kelas</span>
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- TAB 2: PERSONAL TRAINERS -->
                @php
                    $trainerBgPool = [
                        'https://images.unsplash.com/photo-1534438327276-14e5300c3a48?auto=format&fit=crop&w=800&q=80',
                        'https://images.unsplash.com/photo-1581009146145-b5ef050c2e1e?auto=format&fit=crop&w=800&q=80',
                        'https://images.unsplash.com/photo-1571019613454-1cb2f99b2d8b?auto=format&fit=crop&w=800&q=80',
                        'https://images.unsplash.com/photo-1517838277536-f5f99be501cd?auto=format&fit=crop&w=800&q=80',
                    ];
                @endphp
                <div x-show="programTab === 'trainers'" x-cloak x-transition class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    @foreach($trainers as $tIdx => $trainer)
                        @php
                            $tBg = $trainerBgPool[$tIdx % count($trainerBgPool)];
                        @endphp
                        <div class="rounded-3xl glass-panel glass-card-hover overflow-hidden flex flex-col justify-between group">
                            <div>
                                <!-- Trainer Cover Background Banner -->
                                <div class="relative h-36 overflow-hidden bg-slate-950">
                                    <img src="{{ $tBg }}"
                                         onerror="this.src='{{ asset('images/login-hero.jpg') }}'"
                                         alt="{{ $trainer->name }}"
                                         class="w-full h-full object-cover object-center group-hover:scale-110 transition-transform duration-700">
                                    <div class="absolute inset-0 bg-gradient-to-t from-[#0B0F19] via-[#0B0F19]/40 to-black/25"></div>

                                    <div class="absolute top-3 right-3">
                                        <span class="inline-block px-2.5 py-1 rounded-lg badge-solid-emerald text-[10px] font-mono uppercase font-bold">
                                            <i class="fa-solid fa-certificate mr-1"></i> Certified PT
                                        </span>
                                    </div>
                                </div>

                                <!-- Trainer Profile & Bio -->
                                <div class="p-5 pt-0 space-y-3.5 relative">
                                    <div class="flex items-end gap-3.5 -mt-8 relative z-10">
                                        @if(!empty($trainer->photo))
                                            <img src="{{ asset('storage/' . $trainer->photo) }}" alt="{{ $trainer->name }}"
                                                 class="w-16 h-16 rounded-2xl object-cover border-2 border-emerald-500 shadow-xl shrink-0 bg-slate-900">
                                        @else
                                            <div class="w-16 h-16 rounded-2xl badge-solid-emerald font-display font-black text-xl flex items-center justify-center shrink-0 shadow-xl">
                                                {{ strtoupper(substr(str_replace('Coach ', '', $trainer->name), 0, 2)) }}
                                            </div>
                                        @endif
                                        <div class="pb-0.5 min-w-0">
                                            <h3 class="text-base font-extrabold text-white font-display truncate">{{ $trainer->name }}</h3>
                                            <span class="text-[11px] text-emerald-400 font-bold block truncate">Personal Coach 1-on-1</span>
                                        </div>
                                    </div>

                                    <div class="px-3 py-2 rounded-xl badge-cyan text-xs font-bold">
                                        <i class="fa-solid fa-bolt mr-1"></i> {{ $trainer->specialization ?? 'Strength & Conditioning' }}
                                    </div>

                                    <p class="text-xs text-slate-400 leading-relaxed">
                                        {{ $trainer->bio }}
                                    </p>
                                </div>
                            </div>

                            <div class="px-5 pb-5 pt-3 border-t border-white/[0.07] space-y-3">
                                <div class="flex items-center justify-between text-xs">
                                    <span class="text-slate-400 font-medium">Sesi Privat 1-on-1</span>
                                    <strong class="text-white font-mono text-sm">{{ $currency }} {{ number_format($trainer->hourly_rate, 0, ',', '.') }}/jam</strong>
                                </div>
                                <button type="button"
                                        @click="openBookingModal('personal_trainer', {{ $trainer->id }})"
                                        class="btn-theme-primary w-full py-2.5 rounded-xl text-xs uppercase tracking-wider flex items-center justify-center gap-2 cursor-pointer">
                                    <i class="fa-solid fa-user-check"></i>
                                    <span>Booking Coach Ini</span>
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- TAB 3: FASILITAS GYM -->
                <div x-show="programTab === 'facilities'" x-cloak x-transition class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Facility 1 -->
                    <div class="rounded-3xl glass-panel glass-card-hover overflow-hidden group flex flex-col justify-between">
                        <div>
                            <div class="relative h-48 overflow-hidden bg-slate-950">
                                <img src="https://images.unsplash.com/photo-1583454110551-21f2fa2afe61?auto=format&fit=crop&w=900&q=80"
                                     onerror="this.src='{{ asset('images/login-hero.jpg') }}'"
                                     alt="Free Weights & Mesin Presisi"
                                     class="w-full h-full object-cover object-center group-hover:scale-110 transition-transform duration-700">
                                <div class="absolute inset-0 bg-gradient-to-t from-[#0B0F19] via-[#0B0F19]/40 to-transparent"></div>
                                <div class="absolute top-3.5 left-3.5 w-11 h-11 rounded-2xl badge-solid-emerald flex items-center justify-center text-lg shadow-lg">
                                    <i class="fa-solid fa-dumbbell"></i>
                                </div>
                                <div class="absolute bottom-3 left-4 right-4">
                                    <span class="text-[10px] font-mono uppercase tracking-wider text-emerald-400 font-bold block">Zona Strength & Hypertrophy</span>
                                    <h3 class="text-lg font-extrabold text-white keep-white font-display">Free Weights & Mesin Presisi</h3>
                                </div>
                            </div>
                            <div class="p-6 pt-4">
                                <p class="text-xs text-slate-400 leading-relaxed">
                                    Power rack, bench press, dumbbell lengkap hingga 50kg, serta mesin isolasi otot biomekanik yang aman untuk pemula maupun atlet profesional.
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Facility 2 -->
                    <div class="rounded-3xl glass-panel glass-card-hover overflow-hidden group flex flex-col justify-between">
                        <div>
                            <div class="relative h-48 overflow-hidden bg-slate-950">
                                <img src="https://images.unsplash.com/photo-1540497077202-7c8a3999166f?auto=format&fit=crop&w=900&q=80"
                                     onerror="this.src='{{ asset('images/login-hero.jpg') }}'"
                                     alt="Cardio Theater & Turf Area"
                                     class="w-full h-full object-cover object-center group-hover:scale-110 transition-transform duration-700">
                                <div class="absolute inset-0 bg-gradient-to-t from-[#0B0F19] via-[#0B0F19]/40 to-transparent"></div>
                                <div class="absolute top-3.5 left-3.5 w-11 h-11 rounded-2xl photo-overlay-card flex items-center justify-center text-lg text-cyan-400 shadow-lg">
                                    <i class="fa-solid fa-heart-pulse"></i>
                                </div>
                                <div class="absolute bottom-3 left-4 right-4">
                                    <span class="text-[10px] font-mono uppercase tracking-wider text-cyan-400 font-bold block">Zona Endurance & Functional</span>
                                    <h3 class="text-lg font-extrabold text-white keep-white font-display">Cardio Theater & Turf Area</h3>
                                </div>
                            </div>
                            <div class="p-6 pt-4">
                                <p class="text-xs text-slate-400 leading-relaxed">
                                    Treadmill komersial, elliptical, stationary bike, dan area functional training berlantai sintetis untuk HIIT, battle rope & ketangkasan.
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Facility 3 -->
                    <div class="rounded-3xl glass-panel glass-card-hover overflow-hidden group flex flex-col justify-between">
                        <div>
                            <div class="relative h-48 overflow-hidden bg-slate-950">
                                <img src="https://images.unsplash.com/photo-1571902943202-507ec2618e8f?auto=format&fit=crop&w=900&q=80"
                                     onerror="this.src='{{ asset('images/login-hero.jpg') }}'"
                                     alt="Locker Room, Hot Shower & Shake Bar"
                                     class="w-full h-full object-cover object-center group-hover:scale-110 transition-transform duration-700">
                                <div class="absolute inset-0 bg-gradient-to-t from-[#0B0F19] via-[#0B0F19]/40 to-transparent"></div>
                                <div class="absolute top-3.5 left-3.5 w-11 h-11 rounded-2xl badge-solid-amber flex items-center justify-center text-lg shadow-lg">
                                    <i class="fa-solid fa-shower"></i>
                                </div>
                                <div class="absolute bottom-3 left-4 right-4">
                                    <span class="text-[10px] font-mono uppercase tracking-wider text-amber-400 font-bold block">Kenyamanan & Recovery</span>
                                    <h3 class="text-lg font-extrabold text-white keep-white font-display">Locker Room, Hot Shower & Shake Bar</h3>
                                </div>
                            </div>
                            <div class="p-6 pt-4">
                                <p class="text-xs text-slate-400 leading-relaxed">
                                    Kamar ganti bersih dengan air hangat, loker aman, Wi-Fi cepat, dan bar suplemen protein siap minum seusai sesi latihan Anda.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </section>

        <!-- ==========================================
             SECTION 4: OFFICIAL SUPPLEMENT & SHAKE STORE
             ========================================== -->
        <section id="suplemen" class="py-16 lg:py-20 bg-[#0B0F19]/50 border-y border-white/[0.06]">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
                
                <div class="flex flex-col lg:flex-row lg:items-end justify-between gap-4">
                    <div class="space-y-2">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full badge-purple text-xs font-bold uppercase tracking-wider">
                            <i class="fa-solid fa-bottle-droplet"></i> Official Nutrition Store
                        </span>
                        <h2 class="text-2xl sm:text-4xl font-black text-white font-display tracking-tight">
                            Toko Suplemen & Minuman Protein Resmi
                        </h2>
                        <p class="text-xs sm:text-sm text-slate-400">
                            Stok terhubung real-time dengan gudang POS kami. Tambahkan ke keranjang untuk pre-order dan ambil langsung di kasir!
                        </p>
                    </div>

                    <!-- Search + Cart Trigger -->
                    <div class="flex items-center gap-2.5 w-full lg:w-auto">
                        <div class="relative flex-1 lg:w-64">
                            <i class="fa-solid fa-magnifying-glass text-xs text-slate-400 absolute left-3.5 top-1/2 -translate-y-1/2"></i>
                            <input type="text" x-model="suppSearch"
                                   placeholder="Cari Whey, Creatine, Bar..."
                                   class="w-full pl-9 pr-4 py-2.5 rounded-xl bg-slate-900 border border-white/[0.1] text-white text-xs focus:outline-none focus:border-emerald-500">
                        </div>
                        <button type="button" @click="cartDrawerOpen = true"
                                class="px-4 py-2.5 rounded-xl badge-emerald text-xs font-bold flex items-center gap-2 shrink-0 cursor-pointer">
                            <i class="fa-solid fa-bag-shopping"></i>
                            <span>Keranjang (<span x-text="cartCount"></span>)</span>
                        </button>
                    </div>
                </div>

                <!-- Category Filter Pills -->
                <div class="flex flex-wrap gap-2">
                    <button type="button" @click="suppCategoryFilter = 'all'"
                            :class="suppCategoryFilter === 'all' ? 'btn-theme-primary' : 'badge-neutral'"
                            class="px-3.5 py-2 rounded-xl text-xs font-bold transition-all cursor-pointer">
                        Semua ({{ $supplements->count() }})
                    </button>
                    @foreach($supplementCategories as $cat)
                        <button type="button" @click="suppCategoryFilter = '{{ $cat->id }}'"
                                :class="suppCategoryFilter === '{{ $cat->id }}' ? 'btn-theme-primary' : 'badge-neutral'"
                                class="px-3.5 py-2 rounded-xl text-xs font-bold transition-all cursor-pointer">
                            {{ $cat->name }}
                        </button>
                    @endforeach
                </div>

                <!-- Supplements Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                    @foreach($supplements as $supp)
                        <div x-show="(suppCategoryFilter === 'all' || suppCategoryFilter === '{{ $supp->category_id }}') && ('{{ strtolower(addslashes($supp->name . ' ' . $supp->brand . ' ' . $supp->flavor)) }}'.includes(suppSearch.toLowerCase()))"
                             x-transition
                             class="p-5 rounded-3xl glass-panel glass-card-hover flex flex-col justify-between space-y-4">
                            
                            <div class="space-y-3">
                                <div class="flex items-center justify-between gap-2">
                                    <span class="text-[11px] font-bold uppercase px-2.5 py-1 rounded-lg badge-neutral truncate">
                                        {{ $supp->brand ?: ($supp->category->name ?? 'Nutrition') }}
                                    </span>

                                    @if($supp->stock > $supp->min_stock_alert)
                                        <span class="px-2.5 py-1 rounded-lg badge-emerald text-[11px] font-mono font-bold">
                                            Stok: {{ $supp->stock }}
                                        </span>
                                    @elseif($supp->stock > 0)
                                        <span class="px-2.5 py-1 rounded-lg badge-amber text-[11px] font-mono font-bold">
                                            Sisa {{ $supp->stock }}!
                                        </span>
                                    @else
                                        <span class="px-2.5 py-1 rounded-lg badge-rose text-[11px] font-mono font-bold">
                                            Habis
                                        </span>
                                    @endif
                                </div>

                                <div class="h-32 rounded-2xl bg-slate-900/90 border border-white/[0.06] flex items-center justify-center p-3 relative">
                                    @if(!empty($supp->image))
                                        <img src="{{ asset('storage/' . $supp->image) }}" alt="{{ $supp->name }}" class="max-h-full object-contain">
                                    @else
                                        <div class="text-center">
                                            <i class="fa-solid fa-bottle-droplet text-2xl text-emerald-400 mb-1"></i>
                                            <span class="block text-[11px] font-mono font-medium text-slate-400">{{ $supp->unit }}</span>
                                        </div>
                                    @endif
                                    @if($supp->flavor)
                                        <span class="absolute bottom-2 right-2 text-[10px] font-mono font-bold px-2.5 py-0.5 rounded-md badge-cyan">
                                            {{ $supp->flavor }}
                                        </span>
                                    @endif
                                </div>

                                <div>
                                    <h3 class="text-xs sm:text-sm font-extrabold text-white font-display line-clamp-2 min-h-[36px]">{{ $supp->name }}</h3>
                                    <div class="text-base font-black text-emerald-400 font-mono mt-1">
                                        {{ $currency }} {{ number_format($supp->selling_price, 0, ',', '.') }}
                                    </div>
                                </div>
                            </div>

                            <button type="button"
                                    @click="addToCart({ id: 'supp-{{ $supp->id }}', type: 'supplement', name: '{{ addslashes($supp->name) }}', price: {{ (float) $supp->selling_price }} })"
                                    class="w-full py-2.5 px-3 rounded-xl {{ $supp->stock > 0 ? 'btn-theme-primary' : 'badge-neutral' }} text-xs font-bold flex items-center justify-center gap-2 cursor-pointer">
                                <i class="fa-solid fa-cart-plus"></i>
                                <span>{{ $supp->stock > 0 ? 'Tambah ke Pesanan' : 'Pre-Order Stok' }}</span>
                            </button>
                        </div>
                    @endforeach
                </div>

            </div>
        </section>

        <!-- ==========================================
             SECTION 5: SLIDER-BASED SMART BMI & MACRO ANALYZER
             ========================================== -->
        <section id="kalkulator" class="relative py-16 lg:py-24 overflow-hidden">
            <!-- Section Background Image + Adaptive Overlay -->
            <div class="absolute inset-0 pointer-events-none z-0">
                <img src="https://images.unsplash.com/photo-1517838277536-f5f99be501cd?auto=format&fit=crop&w=1920&q=80"
                     onerror="this.src='{{ asset('images/login-hero.jpg') }}'"
                     alt="Kalkulator BMI & Nutrisi Harian Background"
                     class="w-full h-full object-cover object-center opacity-35 scale-105">
                <div class="absolute inset-0 program-section-overlay"></div>
                <div class="absolute -left-24 top-1/2 -translate-y-1/2 w-96 h-96 rounded-full blur-3xl opacity-15" style="background: var(--primary-accent);"></div>
                <div class="absolute -right-24 top-1/2 -translate-y-1/2 w-96 h-96 bg-cyan-500 rounded-full blur-3xl opacity-10"></div>
            </div>

            <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
                
                <!-- Section Heading for High Contrast Readability -->
                <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
                    <div class="space-y-2">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full badge-emerald text-xs font-bold uppercase tracking-wider">
                            <i class="fa-solid fa-calculator"></i> Smart Body & Macro Analyzer
                        </span>
                        <h2 class="text-2xl sm:text-4xl font-black text-white font-display tracking-tight">
                            Kalkulator BMI & Nutrisi Harian
                        </h2>
                        <p class="text-xs sm:text-sm text-slate-300 font-medium">
                            Hitung Indeks Massa Tubuh, kebutuhan kalori harian (TDEE), dan target protein sesuai tujuan latihan Anda.
                        </p>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-stretch">
                    
                    <!-- Left: Interactive Sliders (6 Cols) -->
                    <div class="lg:col-span-6 rounded-3xl glass-panel overflow-hidden shadow-2xl flex flex-col justify-between">
                        <!-- Visual Cover Header inside Calculator Card -->
                        <div class="relative h-36 overflow-hidden bg-black">
                            <img src="https://images.unsplash.com/photo-1571019613454-1cb2f99b2d8b?auto=format&fit=crop&w=1000&q=80"
                                 onerror="this.src='{{ asset('images/login-hero.jpg') }}'"
                                 alt="Body Analyzer"
                                 class="w-full h-full object-cover object-center">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/60 to-black/30"></div>

                            <div class="absolute inset-0 p-6 flex items-end justify-between gap-3">
                                <div>
                                    <span class="inline-block px-3 py-1 rounded-full badge-solid-emerald text-[10px] font-mono uppercase font-bold">
                                        <i class="fa-solid fa-sliders mr-1"></i> Body Analyzer
                                    </span>
                                    <h3 class="text-xl sm:text-2xl font-black text-white keep-white font-display mt-1.5 drop-shadow-md">
                                        Kalkulator BMI & Nutrisi Harian
                                    </h3>
                                </div>
                                <div class="flex gap-1.5 photo-overlay-card p-1 rounded-xl shrink-0">
                                    <button type="button" @click="bmiGender = 'male'"
                                            :class="bmiGender === 'male' ? 'btn-theme-primary' : 'text-white keep-white hover:opacity-80'"
                                            class="px-3.5 py-1.5 rounded-lg text-xs font-extrabold cursor-pointer transition-all">Pria</button>
                                    <button type="button" @click="bmiGender = 'female'"
                                            :class="bmiGender === 'female' ? 'btn-theme-primary' : 'text-white keep-white hover:opacity-80'"
                                            class="px-3.5 py-1.5 rounded-lg text-xs font-extrabold cursor-pointer transition-all">Wanita</button>
                                </div>
                            </div>
                        </div>

                        <div class="p-6 sm:p-8 pt-5 space-y-6">
                            <!-- Range Sliders -->
                            <div class="space-y-4">
                                <div>
                                    <div class="flex justify-between text-xs font-bold mb-1.5">
                                        <span class="text-white font-bold">Berat Badan</span>
                                        <span class="px-2.5 py-0.5 rounded-md badge-emerald font-mono font-extrabold" x-text="bmiWeight + ' KG'"></span>
                                    </div>
                                    <input type="range" x-model.number="bmiWeight" min="35" max="160" step="1" class="w-full h-2 bg-slate-800 rounded-lg cursor-pointer">
                                </div>

                                <div>
                                    <div class="flex justify-between text-xs font-bold mb-1.5">
                                        <span class="text-white font-bold">Tinggi Badan</span>
                                        <span class="px-2.5 py-0.5 rounded-md badge-cyan font-mono font-extrabold" x-text="bmiHeight + ' CM'"></span>
                                    </div>
                                    <input type="range" x-model.number="bmiHeight" min="135" max="210" step="1" class="w-full h-2 bg-slate-800 rounded-lg cursor-pointer">
                                </div>

                                <div>
                                    <div class="flex justify-between text-xs font-bold mb-1.5">
                                        <span class="text-white font-bold">Usia</span>
                                        <span class="px-2.5 py-0.5 rounded-md badge-amber font-mono font-extrabold" x-text="bmiAge + ' Tahun'"></span>
                                    </div>
                                    <input type="range" x-model.number="bmiAge" min="15" max="75" step="1" class="w-full h-2 bg-slate-800 rounded-lg cursor-pointer">
                                </div>
                            </div>

                            <div class="grid grid-cols-3 gap-2 pt-1">
                                <button type="button" @click="bmiGoal = 'cut'"
                                        :class="bmiGoal === 'cut' ? 'btn-theme-primary' : 'badge-neutral'"
                                        class="py-2.5 rounded-xl text-xs font-bold cursor-pointer">Fat Loss</button>
                                <button type="button" @click="bmiGoal = 'Maintain'"
                                        :class="bmiGoal === 'Maintain' ? 'btn-theme-primary' : 'badge-neutral'"
                                        class="py-2.5 rounded-xl text-xs font-bold cursor-pointer">Stay Fit</button>
                                <button type="button" @click="bmiGoal = 'bulk'"
                                        :class="bmiGoal === 'bulk' ? 'btn-theme-primary' : 'badge-neutral'"
                                        class="py-2.5 rounded-xl text-xs font-bold cursor-pointer">Muscle Gain</button>
                            </div>
                        </div>
                    </div>

                    <!-- Right: Visual Gauge & Macro Recommendation (6 Cols) -->
                    <div class="lg:col-span-6 p-6 sm:p-8 rounded-3xl glass-panel border border-emerald-500/30 space-y-6 relative overflow-hidden shadow-2xl flex flex-col justify-between">
                        <!-- Subtle Card Background Image Watermark -->
                        <img src="https://images.unsplash.com/photo-1534438327276-14e5300c3a48?auto=format&fit=crop&w=900&q=80"
                             onerror="this.src='{{ asset('images/login-hero.jpg') }}'"
                             alt="BMI Result Background"
                             class="absolute inset-0 w-full h-full object-cover object-center opacity-10 pointer-events-none">

                        <div class="relative z-10 space-y-6">
                            <div class="flex items-center justify-between">
                                <div>
                                    <span class="text-xs font-bold text-slate-300 block">Kategori Indeks Massa Tubuh</span>
                                    <span class="inline-block mt-1.5 px-3.5 py-1 rounded-full text-xs font-extrabold" :class="bmiCategory.badge" x-text="bmiCategory.label"></span>
                                </div>
                                <div class="text-right">
                                    <span class="text-xs font-mono font-bold text-slate-300 block">Skor BMI</span>
                                    <span class="text-4xl font-black font-mono" :class="bmiCategory.color" x-text="bmiScore"></span>
                                </div>
                            </div>

                            <!-- Visual BMI Spectrum Bar -->
                            <div class="space-y-1.5">
                                <div class="h-3 rounded-full bg-gradient-to-r from-cyan-400 via-emerald-400 via-amber-400 to-rose-500 relative">
                                    <div class="w-4 h-4 rounded-full bg-white border-2 border-slate-950 shadow-lg absolute -top-0.5 -translate-x-1/2 transition-all duration-200"
                                         :style="`left: ${bmiPercentagePosition}%`"></div>
                                </div>
                                <div class="flex justify-between text-[11px] font-mono font-bold text-slate-300">
                                    <span>18.5 (Kurus)</span>
                                    <span>22.0 (Ideal)</span>
                                    <span>25.0 (Berlebih)</span>
                                    <span>30+ (Obesitas)</span>
                                </div>
                            </div>

                            <!-- Macro Targets -->
                            <div class="grid grid-cols-3 gap-3">
                                <div class="p-3.5 rounded-2xl bg-slate-900/95 border border-white/10">
                                    <span class="text-[11px] font-bold text-slate-300 block">TDEE Harian</span>
                                    <strong class="text-base sm:text-lg font-black text-white font-mono" x-text="tdeeCalories + ' kkal'"></strong>
                                </div>
                                <div class="p-3.5 rounded-2xl bg-slate-900/95 border border-emerald-500/40">
                                    <span class="text-[11px] text-emerald-400 font-extrabold block">Target Kalori</span>
                                    <strong class="text-base sm:text-lg font-black text-emerald-400 font-mono" x-text="targetCalories + ' kkal'"></strong>
                                </div>
                                <div class="p-3.5 rounded-2xl bg-slate-900/95 border border-cyan-500/40">
                                    <span class="text-[11px] text-cyan-400 font-extrabold block">Target Protein</span>
                                    <strong class="text-base sm:text-lg font-black text-cyan-400 font-mono" x-text="targetProtein + 'g / hari'"></strong>
                                </div>
                            </div>

                            <p class="text-xs text-slate-200 font-medium leading-relaxed p-4 rounded-2xl bg-slate-900/95 border border-white/10" x-text="bmiCategory.advice"></p>

                            <div class="flex items-center justify-end gap-3">
                                <button type="button" @click="openBookingModal('personal_trainer', null, 'Konsultasi Target BMI ' + bmiScore + ' (' + bmiCategory.label + ')')"
                                        class="btn-theme-primary px-5 py-3 rounded-xl text-xs uppercase tracking-wider flex items-center justify-center gap-2 cursor-pointer">
                                    <i class="fa-solid fa-calendar-check"></i>
                                    <span>Jadwalkan Konsultasi PT</span>
                                </button>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </section>

        <!-- ==========================================
             SECTION 6: FAQ & LOCATION CONTACT
             ========================================== -->
        <section id="lokasi" class="py-16 lg:py-20 bg-[#0B0F19]/60 border-t border-white/[0.06]">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                    
                    <!-- FAQ Accordion (7 Cols) -->
                    <div class="lg:col-span-7 space-y-4">
                        <h2 class="text-2xl font-black text-white font-display">Pertanyaan Umum (FAQ)</h2>

                        @php
                            $faqs = [
                                1 => ['q' => 'Apakah pemula bisa langsung latihan tanpa pengalaman?', 'a' => 'Tentu! Tim instruktur kami siap memandu pengenalan alat secara gratis di hari pertama Anda, atau Anda dapat memesan sesi Personal Trainer 1-on-1 untuk program intensif.'],
                                2 => ['q' => 'Bolehkah datang latihan harian (Daily Pass) tanpa daftar bulanan?', 'a' => 'Sangat boleh. Tiket Harian (Daily Pass) tersedia langsung di kasir frontdesk maupun melalui tombol Booking Online di halaman ini.'],
                                3 => ['q' => 'Pembayaran apa saja yang diterima di kasir POS?', 'a' => 'Kami menerima pembayaran Tunai (Cash), QRIS (seluruh e-wallet & mobile banking), serta Transfer Bank dengan cetak struk instan.'],
                            ];
                        @endphp
                        @foreach($faqs as $idx => $faq)
                            <div class="rounded-2xl glass-panel overflow-hidden">
                                <button type="button" @click="activeFaq = activeFaq === {{ $idx }} ? null : {{ $idx }}"
                                        class="w-full p-4 sm:p-5 text-left flex items-center justify-between gap-4 font-bold text-xs sm:text-sm text-white cursor-pointer">
                                    <span>{{ $faq['q'] }}</span>
                                    <i class="fa-solid text-emerald-400" :class="activeFaq === {{ $idx }} ? 'fa-minus' : 'fa-plus'"></i>
                                </button>
                                <div x-show="activeFaq === {{ $idx }}" x-transition class="px-5 pb-5 text-xs text-slate-400 leading-relaxed">
                                    {{ $faq['a'] }}
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Location Card (5 Cols) -->
                    <div class="lg:col-span-5 p-6 rounded-3xl glass-panel border border-emerald-500/25 flex flex-col justify-between space-y-5">
                        <div class="space-y-3">
                            <span class="inline-block px-3 py-1 rounded-full badge-cyan text-xs font-mono uppercase font-bold">Lokasi & Kontak</span>
                            <h3 class="text-xl font-black text-white font-display">{{ $appSetting->app_name ?? 'FitZone Gym' }}</h3>
                            <p class="text-xs text-slate-300 leading-relaxed">
                                <i class="fa-solid fa-location-dot text-emerald-400 mr-1.5"></i>
                                {{ $appSetting->address ?? 'Jl. Gatot Subroto No. 88, Jakarta Selatan' }}
                            </p>
                            <div class="grid grid-cols-2 gap-2.5 pt-2 text-xs">
                                <div class="p-3 rounded-xl bg-slate-900 border border-white/5">
                                    <span class="text-[11px] text-slate-400 block">WhatsApp Kasir</span>
                                    <strong class="text-white font-mono">{{ $appSetting->phone ?? '0812-3456-7890' }}</strong>
                                </div>
                                <div class="p-3 rounded-xl bg-slate-900 border border-white/5">
                                    <span class="text-[11px] text-slate-400 block">Instagram</span>
                                    <strong class="text-white font-mono">{{ $appSetting->social_instagram ?? '@fitzone_gym' }}</strong>
                                </div>
                            </div>
                        </div>

                        <a href="https://wa.me/{{ $waNumber }}?text={{ urlencode('Halo Admin ' . ($appSetting->app_name ?? 'FitZone Gym') . ', mohon info lokasi Google Maps dan jam ramai gym hari ini.') }}"
                           target="_blank"
                           class="btn-theme-primary w-full py-3.5 rounded-xl text-xs uppercase tracking-wider flex items-center justify-center gap-2">
                            <i class="fa-brands fa-whatsapp text-base"></i>
                            <span>Chat Resepsionis via WhatsApp</span>
                        </a>
                    </div>

                </div>
            </div>
        </section>

    </main>

    <!-- ==========================================
         MODAL 1: CONTEXTUAL ONLINE BOOKING MODAL
         ========================================== -->
    <div x-show="bookingModalOpen" x-cloak
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/80 backdrop-blur-md overflow-y-auto">
        <div @click.away="bookingModalOpen = false"
             x-transition
             class="w-full max-w-xl rounded-3xl glass-panel border border-emerald-500/30 p-6 sm:p-8 space-y-5 my-8 shadow-2xl">
            
            <div class="flex items-center justify-between border-b border-white/10 pb-4">
                <div>
                    <span class="inline-block px-2.5 py-0.5 rounded badge-emerald text-[10px] font-mono uppercase font-bold">Instant Reservation</span>
                    <h3 class="text-xl font-black text-white font-display mt-1">Reservasi Sesi Latihan & Kelas</h3>
                </div>
                <button type="button" @click="bookingModalOpen = false" class="w-9 h-9 rounded-xl bg-slate-900 text-slate-400 hover:text-white flex items-center justify-center cursor-pointer">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            @if ($errors->any())
                <div class="p-3.5 rounded-xl badge-rose text-xs">
                    <ul class="list-disc list-inside space-y-0.5">
                        @foreach ($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('public.booking.store') }}" method="POST" class="space-y-4">
                @csrf

                <!-- Service Selector Tabs -->
                <div class="grid grid-cols-3 gap-2">
                    <label class="cursor-pointer">
                        <input type="radio" name="booking_type" value="personal_trainer" x-model="bookingType" class="hidden">
                        <div class="p-3 rounded-xl border text-center transition-all"
                             :class="bookingType === 'personal_trainer' ? 'badge-emerald font-bold' : 'bg-slate-900 border-white/10 text-slate-400'">
                            <i class="fa-solid fa-person-running block mb-1"></i>
                            <span class="text-[11px]">Personal Trainer</span>
                        </div>
                    </label>
                    <label class="cursor-pointer">
                        <input type="radio" name="booking_type" value="fitness_class" x-model="bookingType" class="hidden">
                        <div class="p-3 rounded-xl border text-center transition-all"
                             :class="bookingType === 'fitness_class' ? 'badge-cyan font-bold' : 'bg-slate-900 border-white/10 text-slate-400'">
                            <i class="fa-solid fa-users-rays block mb-1"></i>
                            <span class="text-[11px]">Kelas Studio</span>
                        </div>
                    </label>
                    <label class="cursor-pointer">
                        <input type="radio" name="booking_type" value="gym_slot" x-model="bookingType" class="hidden">
                        <div class="p-3 rounded-xl border text-center transition-all"
                             :class="bookingType === 'gym_slot' ? 'badge-amber font-bold' : 'bg-slate-900 border-white/10 text-slate-400'">
                            <i class="fa-solid fa-dumbbell block mb-1"></i>
                            <span class="text-[11px]">Daily Pass</span>
                        </div>
                    </label>
                </div>

                <!-- Dynamic Target Selection -->
                <div x-show="bookingType === 'personal_trainer'">
                    <label class="block text-xs font-bold text-slate-300 mb-1">Pilih Personal Trainer</label>
                    <select name="trainer_id" x-model="selectedTrainerId" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-900 border border-slate-700 text-white text-xs font-semibold">
                        @foreach($trainers as $t)
                            <option value="{{ $t->id }}">{{ $t->name }} — {{ $t->specialization }} ({{ $currency }} {{ number_format($t->hourly_rate, 0, ',', '.') }})</option>
                        @endforeach
                    </select>
                </div>

                <div x-show="bookingType === 'fitness_class'" x-cloak>
                    <label class="block text-xs font-bold text-slate-300 mb-1">Pilih Kelas Studio</label>
                    <select name="fitness_class_id" x-model="selectedClassId" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-900 border border-slate-700 text-white text-xs font-semibold">
                        @foreach($classes as $c)
                            <option value="{{ $c->id }}">{{ $c->name }} ({{ $c->schedule_day }}, {{ substr($c->start_time, 0, 5) }}) — {{ $currency }} {{ number_format($c->price_per_session, 0, ',', '.') }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-300 mb-1">Tanggal Latihan</label>
                        <input type="date" name="booking_date" x-model="bookingDate" min="{{ date('Y-m-d') }}" required
                               class="w-full px-3.5 py-2.5 rounded-xl bg-slate-900 border border-slate-700 text-white text-xs font-mono">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-300 mb-1">Jam Kedatangan</label>
                        <input type="time" name="start_time" x-model="bookingTime" required
                               class="w-full px-3.5 py-2.5 rounded-xl bg-slate-900 border border-slate-700 text-white text-xs font-mono">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-300 mb-1">Nama Lengkap <span class="text-rose-400">*</span></label>
                        <input type="text" name="customer_name" x-model="customerName" required placeholder="Nama Anda"
                               class="w-full px-3.5 py-2.5 rounded-xl bg-slate-900 border border-slate-700 text-white text-xs">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-300 mb-1">Nomor WhatsApp <span class="text-rose-400">*</span></label>
                        <input type="text" name="customer_phone" x-model="customerPhone" required placeholder="0812-xxxx-xxxx"
                               class="w-full px-3.5 py-2.5 rounded-xl bg-slate-900 border border-slate-700 text-white text-xs font-mono">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-300 mb-1">Kode Member (Opsional)</label>
                        <input type="text" name="member_code" x-model="memberCode" placeholder="Contoh: MBR-1001"
                               class="w-full px-3.5 py-2.5 rounded-xl bg-slate-900 border border-slate-700 text-white text-xs font-mono">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-300 mb-1">Catatan Tambahan</label>
                        <input type="text" name="notes" x-model="bookingNotes" placeholder="Target latihan / request..."
                               class="w-full px-3.5 py-2.5 rounded-xl bg-slate-900 border border-slate-700 text-white text-xs">
                    </div>
                </div>

                <!-- Live Price Summary Box -->
                <div class="p-3.5 rounded-2xl bg-slate-900/90 border border-emerald-500/30 flex items-center justify-between">
                    <div>
                        <span class="text-[11px] font-mono uppercase text-slate-400 block">Ringkasan Layanan</span>
                        <strong class="text-xs text-white font-display" x-text="selectedServiceSummary"></strong>
                    </div>
                    <div class="text-right">
                        <span class="text-[11px] text-slate-400 block">Estimasi Biaya</span>
                        <strong class="text-base font-black text-emerald-400 font-mono" x-text="'{{ $currency }} ' + formatRupiah(estimatedBookingPrice)"></strong>
                    </div>
                </div>

                <button type="submit"
                        class="btn-theme-primary w-full py-3.5 rounded-2xl text-xs uppercase tracking-wider flex items-center justify-center gap-2 cursor-pointer">
                    <i class="fa-solid fa-check-double"></i>
                    <span>Konfirmasi & Dapatkan Kode Booking</span>
                </button>
            </form>
        </div>
    </div>

    <!-- ==========================================
         MODAL 2: CEK STATUS MEMBER & KODE BOOKING
         ========================================== -->
    <div x-show="memberModalOpen" x-cloak
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/80 backdrop-blur-md">
        <div @click.away="memberModalOpen = false"
             x-transition
             class="w-full max-w-lg rounded-3xl glass-panel border border-cyan-500/30 p-6 sm:p-8 space-y-5 shadow-2xl">
            
            <div class="flex items-center justify-between border-b border-white/10 pb-4">
                <div>
                    <span class="inline-block px-2.5 py-0.5 rounded badge-cyan text-[10px] font-mono uppercase font-bold">Self-Service Portal</span>
                    <h3 class="text-xl font-black text-white font-display mt-1">Cek Kartu Member & Status Booking</h3>
                </div>
                <button type="button" @click="memberModalOpen = false" class="w-9 h-9 rounded-xl bg-slate-900 text-slate-400 hover:text-white flex items-center justify-center cursor-pointer">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <div class="space-y-3">
                <form @submit.prevent="checkMemberOrBooking()" class="flex gap-2">
                    <input type="text" x-model="statusQuery"
                           placeholder="Ketik MBR-1001, BK-..., atau No. WhatsApp..."
                           class="flex-1 px-4 py-3 rounded-xl bg-slate-900 border border-slate-700 text-white text-xs font-mono focus:outline-none focus:border-cyan-500">
                    <button type="submit" :disabled="statusLoading"
                            class="btn-theme-primary px-5 py-3 rounded-xl text-xs uppercase shrink-0 cursor-pointer">
                        <span x-text="statusLoading ? '...' : 'Cek'"></span>
                    </button>
                </form>

                <div class="flex items-center gap-2 text-xs text-slate-400">
                    <span>Contoh cepat:</span>
                    <button type="button" @click="checkMemberOrBooking('MBR-1001')" class="px-2 py-0.5 rounded badge-emerald font-mono font-bold cursor-pointer">MBR-1001</button>
                    <button type="button" @click="checkMemberOrBooking('MBR-1002')" class="px-2 py-0.5 rounded badge-cyan font-mono font-bold cursor-pointer">MBR-1002</button>
                </div>
            </div>

            <div x-show="statusError" class="p-3.5 rounded-xl badge-rose text-xs font-semibold" x-text="statusError"></div>

            <!-- Member Result -->
            <template x-if="statusResult && statusResult.type === 'member'">
                <div class="p-5 rounded-2xl bg-slate-900 border border-emerald-500/30 space-y-3">
                    <div class="flex items-center justify-between">
                        <div>
                            <span class="text-xs font-mono font-bold text-emerald-400" x-text="statusResult.data.member_code"></span>
                            <h4 class="text-base font-extrabold text-white font-display" x-text="statusResult.data.name"></h4>
                        </div>
                        <span class="px-3 py-1 rounded-full text-xs font-extrabold uppercase"
                              :class="statusResult.data.is_active ? 'badge-emerald' : 'badge-rose'"
                              x-text="statusResult.data.is_active ? 'Aktif (' + statusResult.data.days_remaining + ' Hari)' : 'Expired'"></span>
                    </div>
                    <div class="grid grid-cols-2 gap-2 pt-2 border-t border-slate-800 text-xs">
                        <div><span class="text-slate-400 block text-[11px]">Tipe Paket</span><strong class="text-white" x-text="statusResult.data.membership_type"></strong></div>
                        <div><span class="text-slate-400 block text-[11px]">Berlaku Sampai</span><strong class="text-emerald-400 font-mono" x-text="statusResult.data.expires_at"></strong></div>
                    </div>
                </div>
            </template>

            <!-- Booking Result -->
            <template x-if="statusResult && statusResult.type === 'booking'">
                <div class="p-5 rounded-2xl bg-slate-900 border border-cyan-500/30 space-y-3">
                    <div class="flex items-center justify-between">
                        <div>
                            <span class="text-xs font-mono font-bold text-cyan-400" x-text="statusResult.data.booking_code"></span>
                            <h4 class="text-base font-extrabold text-white font-display" x-text="statusResult.data.service_name"></h4>
                        </div>
                        <span class="px-3 py-1 rounded-full text-xs font-mono font-bold uppercase badge-cyan" x-text="statusResult.data.status"></span>
                    </div>
                    <div class="text-xs text-slate-300 font-mono" x-text="statusResult.data.booking_date + ' • ' + statusResult.data.time_range"></div>
                </div>
            </template>
        </div>
    </div>

    <!-- ==========================================
         MODAL 3: PRE-ORDER CART SLIDE-OVER DRAWER
         ========================================== -->
    <div x-show="cartDrawerOpen" x-cloak
         class="fixed inset-0 z-50 flex justify-end bg-black/75 backdrop-blur-sm">
        <div @click.away="cartDrawerOpen = false"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="translate-x-full"
             x-transition:enter-end="translate-x-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="translate-x-0"
             x-transition:leave-end="translate-x-full"
             class="w-full max-w-md bg-[#0B0F19] border-l border-white/10 h-full flex flex-col justify-between p-6 shadow-2xl">
            
            <div class="space-y-4 overflow-y-auto flex-1 pr-1">
                <div class="flex items-center justify-between border-b border-white/10 pb-4">
                    <div class="flex items-center gap-2.5">
                        <i class="fa-solid fa-bag-shopping text-emerald-400 text-lg"></i>
                        <h3 class="text-lg font-black text-white font-display">Keranjang Pesanan</h3>
                    </div>
                    <button type="button" @click="cartDrawerOpen = false" class="w-8 h-8 rounded-lg bg-slate-900 text-slate-400 hover:text-white flex items-center justify-center cursor-pointer">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>

                <template x-if="cart.length === 0">
                    <div class="py-16 text-center space-y-3 text-slate-400">
                        <i class="fa-solid fa-basket-shopping text-4xl text-slate-500"></i>
                        <p class="text-xs">Keranjang pesanan Anda masih kosong.<br>Pilih Paket Gym atau Suplemen untuk mulai memesan.</p>
                    </div>
                </template>

                <div class="space-y-3">
                    <template x-for="(item, index) in cart" :key="item.id">
                        <div class="p-3.5 rounded-2xl bg-slate-900 border border-white/[0.07] flex items-center justify-between gap-3">
                            <div class="min-w-0 flex-1">
                                <span class="inline-block px-2 py-0.5 rounded badge-emerald text-[10px] font-mono uppercase font-bold mb-1" x-text="item.type === 'package' ? 'Paket Gym' : 'Suplemen'"></span>
                                <h4 class="text-xs font-bold text-white truncate" x-text="item.name"></h4>
                                <span class="text-xs font-mono text-slate-400" x-text="'{{ $currency }} ' + formatRupiah(item.price)"></span>
                            </div>
                            <div class="flex items-center gap-2">
                                <button type="button" @click="updateQty(index, -1)" class="w-7 h-7 rounded-lg bg-slate-800 text-white text-xs font-bold cursor-pointer">-</button>
                                <span class="text-xs font-mono font-bold text-white w-5 text-center" x-text="item.qty"></span>
                                <button type="button" @click="updateQty(index, 1)" class="w-7 h-7 rounded-lg bg-slate-800 text-white text-xs font-bold cursor-pointer">+</button>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

            <div x-show="cart.length > 0" class="pt-4 border-t border-white/10 space-y-3">
                <div class="flex items-center justify-between">
                    <span class="text-xs text-slate-400">Total Estimasi</span>
                    <strong class="text-xl font-black text-emerald-400 font-mono" x-text="'{{ $currency }} ' + formatRupiah(cartTotal)"></strong>
                </div>
                <div class="grid grid-cols-3 gap-2">
                    <button type="button" @click="clearCart()" class="py-3 rounded-xl badge-rose text-xs font-bold cursor-pointer">
                        Kosongkan
                    </button>
                    <button type="button" @click="checkoutCartWhatsApp()" class="col-span-2 btn-theme-primary py-3 rounded-xl text-xs uppercase flex items-center justify-center gap-2 cursor-pointer">
                        <i class="fa-brands fa-whatsapp text-sm"></i>
                        <span>Pesan via WhatsApp</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- ==========================================
         MODAL 4: E-TICKET BOOKING CONFIRMATION
         ========================================== -->
    @if(session('booking_success'))
        @php
            $bs = session('booking_success');
            $waTicketText = "Halo Kasir " . ($appSetting->app_name ?? 'FitZone Gym') . ", berikut E-Ticket Reservasi Online saya:\n- Kode Booking: *{$bs['code']}*\n- Nama: *{$bs['name']}*\n- Layanan: *{$bs['service']}*\n- Jadwal: {$bs['date']} ({$bs['time']})\nMohon konfirmasi slot saya. Terima kasih!";
        @endphp
        <div x-show="ticketModalOpen" x-cloak
             class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/80 backdrop-blur-md">
            <div class="w-full max-w-md rounded-3xl glass-panel border-2 border-emerald-500 p-6 sm:p-8 space-y-5 text-center shadow-2xl">
                <div class="w-14 h-14 mx-auto rounded-2xl badge-solid-emerald flex items-center justify-center text-2xl font-black">
                    <i class="fa-solid fa-check"></i>
                </div>
                <div>
                    <span class="inline-block px-3 py-1 rounded-full badge-emerald text-xs font-mono uppercase font-bold">E-Ticket Reservasi Berhasil</span>
                    <h3 class="text-2xl font-black text-white font-mono mt-2">{{ $bs['code'] }}</h3>
                    <p class="text-xs text-slate-400 mt-1">Tunjukkan kode ini di kasir frontdesk saat kedatangan</p>
                </div>

                <div class="p-4 rounded-2xl bg-slate-900 border border-white/10 text-left space-y-2 text-xs font-mono">
                    <div class="flex justify-between"><span class="text-slate-400">Nama Peserta:</span><strong class="text-white">{{ $bs['name'] }}</strong></div>
                    <div class="flex justify-between"><span class="text-slate-400">Layanan:</span><strong class="text-emerald-400">{{ $bs['service'] }}</strong></div>
                    <div class="flex justify-between"><span class="text-slate-400">Tanggal & Jam:</span><strong class="text-white">{{ $bs['date'] }} &bull; {{ $bs['time'] }}</strong></div>
                    <div class="flex justify-between border-t border-white/10 pt-2"><span class="text-slate-400">Estimasi Biaya:</span><strong class="text-white">{{ $currency }} {{ $bs['price'] }}</strong></div>
                </div>

                <div class="flex flex-col gap-2">
                    <a href="https://wa.me/{{ $waNumber }}?text={{ urlencode($waTicketText) }}" target="_blank"
                       class="btn-theme-primary w-full py-3.5 rounded-xl text-xs uppercase flex items-center justify-center gap-2">
                        <i class="fa-brands fa-whatsapp text-sm"></i>
                        <span>Kirim E-Ticket ke WhatsApp Kasir</span>
                    </a>
                    <button type="button" @click="ticketModalOpen = false" class="py-2.5 text-xs text-slate-400 hover:text-white cursor-pointer">
                        Tutup E-Ticket
                    </button>
                </div>
            </div>
        </div>
    @endif

    <!-- ==========================================
         FOOTER
         ========================================== -->
    <footer class="relative z-10 bg-[#060911] border-t border-white/[0.08] py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-slate-400">
            <div class="flex items-center gap-2.5">
                <i class="fa-solid fa-dumbbell text-emerald-400"></i>
                <strong class="text-white font-display">{{ $appSetting->app_name ?? 'FitZone Gym' }}</strong>
                <span>&bull; {{ $appSetting->address ?? 'Jakarta' }}</span>
            </div>
            <div class="flex items-center gap-4 font-mono text-xs">
                <button type="button" @click="memberModalOpen = true" class="hover:text-emerald-400 cursor-pointer">Cek Member</button>
                <span>&bull;</span>
                <button type="button" @click="openBookingModal('personal_trainer')" class="hover:text-emerald-400 cursor-pointer">Booking Online</button>
                <span>&bull;</span>
                @auth
                    <a href="{{ route('dashboard') }}" class="text-emerald-400 font-bold hover:underline">Dashboard POS</a>
                @else
                    <a href="{{ route('login') }}" class="text-emerald-400 font-bold hover:underline">Login Staff</a>
                @endauth
            </div>
        </div>
    </footer>

</body>
</html>
