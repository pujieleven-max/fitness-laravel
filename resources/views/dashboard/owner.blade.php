@extends('layouts.app')

@section('title', 'Dashboard Owner')
@section('page-title', 'Executive Command Center')
@section('page-subtitle', 'Pantau Performa Bisnis, Laba Kotor, Inventori Suplemen & Operasional Gym Secara Real-time')

@section('content')
@php
    $hour = (int) date('H');
    $greeting = $hour < 11 ? 'Selamat Pagi' : ($hour < 15 ? 'Selamat Siang' : ($hour < 19 ? 'Selamat Sore' : 'Selamat Malam'));
    $memberActiveRatio = $totalMembers > 0 ? round(($activeMembers / $totalMembers) * 100) : 0;
    $maxSoldSupp = $topSupplements->max('total_sold') ?: 1;
    $defaultAlertTab = $lowStockSupplements->isNotEmpty() ? 'low_stock' : ($expiringSupplements->isNotEmpty() ? 'expiring_supp' : 'expiring_member');
@endphp

<div class="space-y-6">

    <!-- 1. Executive Greeting & Quick Action Dock -->
    <div class="p-5 sm:p-6 rounded-3xl glass-panel relative overflow-hidden">
        <div class="absolute -right-16 -top-16 w-64 h-64 rounded-full blur-3xl pointer-events-none opacity-20" style="background: var(--primary-accent);"></div>
        
        <div class="flex flex-col xl:flex-row xl:items-center justify-between gap-5 relative z-10">
            <div class="space-y-1.5">
                <div class="flex flex-wrap items-center gap-2">
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold bg-emerald-500/15 text-emerald-400 border border-emerald-500/30">
                        <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                        <span>Sistem Operasional Aktif</span>
                    </span>
                    <span class="text-xs text-slate-400 font-mono">
                        <i class="fa-regular fa-calendar mr-1"></i> {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}
                    </span>
                </div>
                <h2 class="text-xl sm:text-2xl font-black text-white font-display tracking-tight">
                    {{ $greeting }}, {{ Auth::user()->name }}!
                </h2>
                <p class="text-xs sm:text-sm text-slate-400">
                    Berikut ringkasan eksekutif performa kasir, margin keuntungan, dan radar operasional <strong class="text-slate-200">{{ $appSetting->app_name ?? 'FitZone Gym' }}</strong> hari ini.
                </p>
            </div>

            <!-- Quick Action Dock -->
            <div class="flex flex-wrap items-center gap-2 sm:gap-2.5">
                <a href="{{ route('pos.index') }}" 
                   class="btn-theme-primary px-4 py-2.5 rounded-xl text-slate-950 font-black text-xs uppercase tracking-wider flex items-center gap-2 shadow-lg">
                    <i class="fa-solid fa-cash-register"></i>
                    <span>Terminal POS</span>
                </a>
                <a href="{{ route('bookings.create') }}" 
                   class="px-3.5 py-2.5 rounded-xl bg-slate-900 hover:bg-slate-800 text-slate-200 border border-slate-800 text-xs font-bold flex items-center gap-2 transition-all hover:-translate-y-0.5">
                    <i class="fa-solid fa-calendar-plus text-cyan-400"></i>
                    <span>Booking Baru</span>
                </a>
                <a href="{{ route('supplements.create') }}" 
                   class="px-3.5 py-2.5 rounded-xl bg-slate-900 hover:bg-slate-800 text-slate-200 border border-slate-800 text-xs font-bold flex items-center gap-2 transition-all hover:-translate-y-0.5">
                    <i class="fa-solid fa-bottle-droplet text-emerald-400"></i>
                    <span>+ Suplemen</span>
                </a>
                <a href="{{ route('members.index') }}" 
                   class="px-3.5 py-2.5 rounded-xl bg-slate-900 hover:bg-slate-800 text-slate-200 border border-slate-800 text-xs font-bold flex items-center gap-2 transition-all hover:-translate-y-0.5">
                    <i class="fa-solid fa-id-card-clip text-purple-400"></i>
                    <span>Member</span>
                </a>
                <a href="{{ route('reports.index') }}" 
                   class="px-3.5 py-2.5 rounded-xl bg-amber-500/15 hover:bg-amber-500/25 text-amber-300 border border-amber-500/30 text-xs font-bold flex items-center gap-2 transition-all hover:-translate-y-0.5">
                    <i class="fa-solid fa-chart-line text-amber-400"></i>
                    <span>Laporan Laba</span>
                </a>
            </div>
        </div>
    </div>

    <!-- 2. Top Key Metrics Bento Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 sm:gap-5">
        
        <!-- Card 1: Omset Hari Ini -->
        <div class="p-5 sm:p-6 rounded-3xl glass-panel glass-panel-hover relative overflow-hidden group flex flex-col justify-between">
            <div class="absolute -right-6 -bottom-6 w-28 h-28 bg-emerald-500/10 rounded-full blur-2xl group-hover:scale-150 transition-transform duration-500"></div>
            <div>
                <div class="flex items-center justify-between text-slate-400 mb-3">
                    <span class="text-[11px] font-extrabold uppercase tracking-wider font-display">Omset Hari Ini</span>
                    <div class="w-10 h-10 rounded-2xl bg-emerald-500/15 border border-emerald-500/30 text-emerald-400 flex items-center justify-center shadow-md shadow-emerald-500/10">
                        <i class="fa-solid fa-coins text-sm"></i>
                    </div>
                </div>
                <div class="text-2xl sm:text-3xl font-black text-white font-display tracking-tight">
                    Rp {{ number_format($todayOrdersRevenue, 0, ',', '.') }}
                </div>
            </div>

            <div class="mt-4 pt-3 border-t border-slate-800/80 flex items-center justify-between text-xs">
                <div class="flex items-center gap-1.5">
                    @if($todayGrowthPercent >= 0)
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-emerald-500/20 text-emerald-300 font-bold border border-emerald-500/30 text-[10px]">
                            <i class="fa-solid fa-arrow-trend-up"></i> +{{ $todayGrowthPercent }}%
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-rose-500/20 text-rose-300 font-bold border border-rose-500/30 text-[10px]">
                            <i class="fa-solid fa-arrow-trend-down"></i> {{ $todayGrowthPercent }}%
                        </span>
                    @endif
                    <span class="text-[11px] text-slate-400">vs kemarin</span>
                </div>
                <span class="text-[11px] font-mono font-bold text-slate-300">
                    {{ $todayOrdersCount }} Struk
                </span>
            </div>
        </div>

        <!-- Card 2: Omset Bulan Ini -->
        <div class="p-5 sm:p-6 rounded-3xl glass-panel glass-panel-hover relative overflow-hidden group flex flex-col justify-between">
            <div class="absolute -right-6 -bottom-6 w-28 h-28 bg-cyan-500/10 rounded-full blur-2xl group-hover:scale-150 transition-transform duration-500"></div>
            <div>
                <div class="flex items-center justify-between text-slate-400 mb-3">
                    <span class="text-[11px] font-extrabold uppercase tracking-wider font-display">Omset Bulan Ini</span>
                    <div class="w-10 h-10 rounded-2xl bg-cyan-500/15 border border-cyan-500/30 text-cyan-400 flex items-center justify-center shadow-md shadow-cyan-500/10">
                        <i class="fa-solid fa-wallet text-sm"></i>
                    </div>
                </div>
                <div class="text-2xl sm:text-3xl font-black text-white font-display tracking-tight">
                    Rp {{ number_format($monthOrdersRevenue, 0, ',', '.') }}
                </div>
            </div>

            <div class="mt-4 pt-3 border-t border-slate-800/80 flex items-center justify-between text-xs">
                <div class="flex items-center gap-1.5">
                    @if($monthGrowthPercent >= 0)
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-cyan-500/20 text-cyan-300 font-bold border border-cyan-500/30 text-[10px]">
                            <i class="fa-solid fa-arrow-trend-up"></i> +{{ $monthGrowthPercent }}%
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-rose-500/20 text-rose-300 font-bold border border-rose-500/30 text-[10px]">
                            <i class="fa-solid fa-arrow-trend-down"></i> {{ $monthGrowthPercent }}%
                        </span>
                    @endif
                    <span class="text-[11px] text-slate-400">vs bln lalu</span>
                </div>
                <span class="text-[11px] font-mono font-bold text-cyan-400">
                    {{ $monthOrdersCount }} Transaksi
                </span>
            </div>
        </div>

        <!-- Card 3: Estimasi Keuntungan / Margin Laba -->
        <div class="p-5 sm:p-6 rounded-3xl glass-panel glass-panel-hover relative overflow-hidden group flex flex-col justify-between">
            <div class="absolute -right-6 -bottom-6 w-28 h-28 bg-amber-500/10 rounded-full blur-2xl group-hover:scale-150 transition-transform duration-500"></div>
            <div>
                <div class="flex items-center justify-between text-slate-400 mb-3">
                    <span class="text-[11px] font-extrabold uppercase tracking-wider font-display">Est. Laba Kotor Bulan Ini</span>
                    <div class="w-10 h-10 rounded-2xl bg-amber-500/15 border border-amber-500/30 text-amber-400 flex items-center justify-center shadow-md shadow-amber-500/10">
                        <i class="fa-solid fa-chart-pie text-sm"></i>
                    </div>
                </div>
                <div class="text-2xl sm:text-3xl font-black text-amber-400 font-display tracking-tight">
                    Rp {{ number_format($estimatedMonthProfit, 0, ',', '.') }}
                </div>
            </div>

            <div class="mt-4 pt-3 border-t border-slate-800/80 space-y-1.5">
                <div class="flex items-center justify-between text-[11px]">
                    <span class="text-slate-400">Margin Laba Kotor</span>
                    <span class="font-mono font-extrabold text-amber-400">{{ $profitMarginPercent }}%</span>
                </div>
                <div class="w-full h-1.5 rounded-full bg-slate-800 overflow-hidden">
                    <div class="h-full rounded-full bg-gradient-to-r from-amber-500 to-yellow-400 transition-all duration-500"
                         style="width: {{ min(100, max(5, $profitMarginPercent)) }}%"></div>
                </div>
            </div>
        </div>

        <!-- Card 4: Nilai Aset Stok & Member Aktif -->
        <div class="p-5 sm:p-6 rounded-3xl glass-panel glass-panel-hover relative overflow-hidden group flex flex-col justify-between">
            <div class="absolute -right-6 -bottom-6 w-28 h-28 bg-purple-500/10 rounded-full blur-2xl group-hover:scale-150 transition-transform duration-500"></div>
            <div>
                <div class="flex items-center justify-between text-slate-400 mb-3">
                    <span class="text-[11px] font-extrabold uppercase tracking-wider font-display">Aset Stok & Member</span>
                    <div class="w-10 h-10 rounded-2xl bg-purple-500/15 border border-purple-500/30 text-purple-400 flex items-center justify-center shadow-md shadow-purple-500/10">
                        <i class="fa-solid fa-boxes-stacked text-sm"></i>
                    </div>
                </div>
                <div class="text-2xl sm:text-3xl font-black text-white font-display tracking-tight">
                    Rp {{ number_format($totalStockValue, 0, ',', '.') }}
                </div>
            </div>

            <div class="mt-4 pt-3 border-t border-slate-800/80 flex items-center justify-between text-xs">
                <a href="{{ route('members.index') }}" class="flex items-center gap-1.5 hover:underline">
                    <span class="px-2 py-0.5 rounded-full bg-emerald-500/20 text-emerald-300 font-mono font-bold border border-emerald-500/30 text-[10px]">
                        {{ $activeMembers }}/{{ $totalMembers }}
                    </span>
                    <span class="text-[11px] text-slate-400">Member Aktif ({{ $memberActiveRatio }}%)</span>
                </a>
                <span class="text-[11px] font-mono text-purple-400 font-bold" title="Total Unit Stok Suplemen">
                    {{ number_format($totalStockUnits, 0, ',', '.') }} Unit
                </span>
            </div>
        </div>
    </div>

    <!-- 3. Interactive Operational Alert Hub (Tabbed Action Center) -->
    @if($lowStockSupplements->isNotEmpty() || $expiringSupplements->isNotEmpty() || $expiringMembers->isNotEmpty())
        <div x-data="{ alertTab: '{{ $defaultAlertTab }}' }"
             class="p-5 sm:p-6 rounded-3xl glass-panel border border-amber-500/30">
            
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-slate-800/80">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-2xl bg-amber-500/15 border border-amber-500/30 text-amber-400 flex items-center justify-center shrink-0">
                        <i class="fa-solid fa-satellite-dish animate-pulse"></i>
                    </div>
                    <div>
                        <h3 class="text-sm sm:text-base font-extrabold text-white font-display">Pusat Perhatian Operasional</h3>
                        <p class="text-xs text-slate-400">Item penting yang memerlukan tindakan cepat untuk menjaga kelancaran operasional</p>
                    </div>
                </div>

                <!-- Segmented Tab Buttons -->
                <div class="flex flex-wrap items-center gap-2">
                    <button @click="alertTab = 'low_stock'"
                            type="button"
                            :class="alertTab === 'low_stock' ? 'bg-amber-500 text-slate-950 font-extrabold shadow-md' : 'bg-slate-900 text-slate-300 hover:bg-slate-800 border border-slate-800'"
                            class="px-3 py-1.5 rounded-xl text-xs font-semibold transition-all flex items-center gap-2">
                        <i class="fa-solid fa-triangle-exclamation"></i>
                        <span>Stok Menipis</span>
                        <span class="px-1.5 py-0.2 rounded-full text-[10px] font-mono font-bold"
                              :class="alertTab === 'low_stock' ? 'bg-slate-950/20 text-slate-950' : 'bg-amber-500/20 text-amber-300'">
                            {{ $lowStockSupplements->count() }}
                        </span>
                    </button>

                    <button @click="alertTab = 'expiring_supp'"
                            type="button"
                            :class="alertTab === 'expiring_supp' ? 'bg-rose-500 text-white font-extrabold shadow-md' : 'bg-slate-900 text-slate-300 hover:bg-slate-800 border border-slate-800'"
                            class="px-3 py-1.5 rounded-xl text-xs font-semibold transition-all flex items-center gap-2">
                        <i class="fa-solid fa-clock"></i>
                        <span>Suplemen Expired</span>
                        <span class="px-1.5 py-0.2 rounded-full text-[10px] font-mono font-bold"
                              :class="alertTab === 'expiring_supp' ? 'bg-black/20 text-white' : 'bg-rose-500/20 text-rose-300'">
                            {{ $expiringSupplements->count() }}
                        </span>
                    </button>

                    <button @click="alertTab = 'expiring_member'"
                            type="button"
                            :class="alertTab === 'expiring_member' ? 'bg-purple-500 text-white font-extrabold shadow-md' : 'bg-slate-900 text-slate-300 hover:bg-slate-800 border border-slate-800'"
                            class="px-3 py-1.5 rounded-xl text-xs font-semibold transition-all flex items-center gap-2">
                        <i class="fa-solid fa-id-card-clip"></i>
                        <span>Member Hampir Habis</span>
                        <span class="px-1.5 py-0.2 rounded-full text-[10px] font-mono font-bold"
                              :class="alertTab === 'expiring_member' ? 'bg-black/20 text-white' : 'bg-purple-500/20 text-purple-300'">
                            {{ $expiringMembers->count() }}
                        </span>
                    </button>
                </div>
            </div>

            <!-- Tab 1: Low Stock Supplements -->
            <div x-show="alertTab === 'low_stock'" class="pt-4">
                @if($lowStockSupplements->isNotEmpty())
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
                        @foreach($lowStockSupplements as $item)
                            <div class="p-3.5 rounded-2xl bg-slate-900/90 border border-slate-800 flex items-center justify-between gap-3">
                                <div class="min-w-0">
                                    <h4 class="text-xs font-bold text-white truncate">{{ $item->name }}</h4>
                                    <p class="text-[11px] text-slate-400 mt-0.5">
                                        Min. Alert: {{ $item->min_stock_alert }} {{ $item->unit }}
                                    </p>
                                </div>
                                <div class="text-right shrink-0">
                                    <span class="inline-flex px-2 py-0.5 rounded-lg text-[11px] font-mono font-bold {{ $item->stock <= 0 ? 'bg-rose-500/20 text-rose-300 border border-rose-500/30' : 'bg-amber-500/20 text-amber-300 border border-amber-500/30' }}">
                                        {{ $item->stock <= 0 ? 'HABIS (0)' : $item->stock . ' ' . $item->unit }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="mt-3 flex justify-end">
                        <a href="{{ route('supplements.index', ['status' => 'low_stock']) }}" 
                           class="inline-flex items-center gap-2 text-xs font-bold text-amber-400 hover:text-amber-300">
                            <span>Kelola & Restock Suplemen Sekarang</span>
                            <i class="fa-solid fa-arrow-right"></i>
                        </a>
                    </div>
                @else
                    <div class="py-4 text-center text-xs text-emerald-400">
                        <i class="fa-solid fa-circle-check mr-1"></i> Semua stok produk suplemen berada di atas batas minimum.
                    </div>
                @endif
            </div>

            <!-- Tab 2: Expiring Supplements -->
            <div x-show="alertTab === 'expiring_supp'" x-cloak class="pt-4">
                @if($expiringSupplements->isNotEmpty())
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
                        @foreach($expiringSupplements as $exp)
                            <div class="p-3.5 rounded-2xl bg-slate-900/90 border border-slate-800 flex items-center justify-between gap-3">
                                <div class="min-w-0">
                                    <h4 class="text-xs font-bold text-white truncate">{{ $exp->name }}</h4>
                                    <p class="text-[11px] text-slate-400 mt-0.5">Stok: {{ $exp->stock }} {{ $exp->unit }}</p>
                                </div>
                                <span class="px-2 py-1 rounded-lg text-[11px] font-mono font-bold bg-rose-500/20 text-rose-300 border border-rose-500/30 shrink-0">
                                    {{ $exp->expiry_date->format('d M Y') }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                    <div class="mt-3 flex justify-end">
                        <a href="{{ route('supplements.index', ['status' => 'expiring_soon']) }}" 
                           class="inline-flex items-center gap-2 text-xs font-bold text-rose-400 hover:text-rose-300">
                            <span>Tinjau Produk Mendekati Kadaluarsa</span>
                            <i class="fa-solid fa-arrow-right"></i>
                        </a>
                    </div>
                @else
                    <div class="py-4 text-center text-xs text-emerald-400">
                        <i class="fa-solid fa-circle-check mr-1"></i> Tidak ada suplemen yang mendekati tanggal kadaluarsa dalam 30 hari ke depan.
                    </div>
                @endif
            </div>

            <!-- Tab 3: Expiring Members -->
            <div x-show="alertTab === 'expiring_member'" x-cloak class="pt-4">
                @if($expiringMembers->isNotEmpty())
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
                        @foreach($expiringMembers as $mem)
                            @php
                                $waPhone = preg_replace('/[^0-9]/', '', $mem->phone ?? '');
                                if (str_starts_with($waPhone, '0')) {
                                    $waPhone = '62' . substr($waPhone, 1);
                                }
                                $waMsg = urlencode("Halo Kak {$mem->name}, pengingat ramah dari " . ($appSetting->app_name ?? 'FitZone Gym') . " bahwa masa aktif membership Anda berakhir pada " . ($mem->membership_expires_at ? $mem->membership_expires_at->format('d M Y') : '-') . ". Yuk perpanjang di kasir kami agar latihan tetap semangat!");
                            @endphp
                            <div class="p-3.5 rounded-2xl bg-slate-900/90 border border-slate-800 flex items-center justify-between gap-2">
                                <div class="min-w-0">
                                    <h4 class="text-xs font-bold text-white truncate">{{ $mem->name }}</h4>
                                    <p class="text-[11px] text-rose-400 font-mono mt-0.5">
                                        Exp: {{ $mem->membership_expires_at ? $mem->membership_expires_at->format('d M Y') : '-' }}
                                    </p>
                                </div>
                                @if($waPhone)
                                    <a href="https://wa.me/{{ $waPhone }}?text={{ $waMsg }}" target="_blank"
                                       title="Kirim Pengingat WhatsApp"
                                       class="px-2.5 py-1.5 rounded-xl bg-emerald-500/20 hover:bg-emerald-500/30 text-emerald-300 border border-emerald-500/30 text-[11px] font-bold flex items-center gap-1 shrink-0 transition-colors">
                                        <i class="fa-brands fa-whatsapp text-sm"></i>
                                        <span>Ingatkan</span>
                                    </a>
                                @endif
                            </div>
                        @endforeach
                    </div>
                    <div class="mt-3 flex justify-end">
                        <a href="{{ route('members.index') }}" 
                           class="inline-flex items-center gap-2 text-xs font-bold text-purple-400 hover:text-purple-300">
                            <span>Kelola Semua Langganan Member</span>
                            <i class="fa-solid fa-arrow-right"></i>
                        </a>
                    </div>
                @else
                    <div class="py-4 text-center text-xs text-emerald-400">
                        <i class="fa-solid fa-circle-check mr-1"></i> Tidak ada member aktif yang masa langganannya habis dalam 7 hari ke depan.
                    </div>
                @endif
            </div>
        </div>
    @endif

    <!-- 4. Interactive Analytics Bento: Dual-Range Sales & Profit Chart + Revenue Composition -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5 sm:gap-6"
         x-data="{
             chartRange: '7d',
             trend7d: {{ Js::from($salesTrend) }},
             trend30d: {{ Js::from($salesTrend30Days) }},
             get activeData() {
                 return this.chartRange === '7d' ? this.trend7d : this.trend30d;
             },
             get periodRevenue() {
                 return this.activeData.reduce((sum, item) => sum + Number(item.total || 0), 0);
             },
             get periodProfit() {
                 return this.activeData.reduce((sum, item) => sum + Number(item.profit || 0), 0);
             },
             get periodOrders() {
                 return this.activeData.reduce((sum, item) => sum + Number(item.count || 0), 0);
             },
             formatRp(val) {
                 return 'Rp ' + new Intl.NumberFormat('id-ID').format(Math.round(val));
             },
             switchRange(range) {
                 this.chartRange = range;
                 if (window.ownerSalesChart) {
                     const src = range === '7d' ? this.trend7d : this.trend30d;
                     window.ownerSalesChart.data.labels = src.map(d => d.date);
                     window.ownerSalesChart.data.datasets[0].data = src.map(d => d.total);
                     window.ownerSalesChart.data.datasets[1].data = src.map(d => d.profit);
                     window.ownerSalesChart.update();
                 }
             }
         }">

        <!-- Left (2/3): Interactive Sales & Profit Trend Chart -->
        <div class="lg:col-span-2 p-6 sm:p-7 rounded-3xl glass-panel shadow-2xl flex flex-col justify-between">
            <div>
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-5">
                    <div>
                        <div class="flex items-center gap-2">
                            <h3 class="font-extrabold text-white text-base sm:text-lg font-display">Tren Omset & Estimasi Laba</h3>
                            <span class="inline-flex items-center gap-1.5 text-[10px] font-bold text-emerald-400 bg-emerald-500/10 px-2.5 py-0.5 rounded-full border border-emerald-500/20">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                                Real-time
                            </span>
                        </div>
                        <p class="text-xs text-slate-400 mt-0.5">Perbandingan grafik pendapatan kotor vs estimasi margin laba harian</p>
                    </div>

                    <!-- Period Switcher (7 Hari vs 30 Hari) -->
                    <div class="inline-flex p-1 rounded-xl bg-slate-900 border border-slate-800 self-start sm:self-auto">
                        <button @click="switchRange('7d')"
                                type="button"
                                :class="chartRange === '7d' ? 'btn-theme-primary text-slate-950 font-extrabold shadow' : 'text-slate-400 hover:text-white'"
                                class="px-3 py-1.5 rounded-lg text-xs font-semibold transition-all">
                            7 Hari
                        </button>
                        <button @click="switchRange('30d')"
                                type="button"
                                :class="chartRange === '30d' ? 'btn-theme-primary text-slate-950 font-extrabold shadow' : 'text-slate-400 hover:text-white'"
                                class="px-3 py-1.5 rounded-lg text-xs font-semibold transition-all">
                            30 Hari
                        </button>
                    </div>
                </div>

                <!-- Dynamic Period Summary Bar -->
                <div class="grid grid-cols-3 gap-3 mb-5 p-3.5 rounded-2xl bg-slate-900/70 border border-slate-800/80">
                    <div>
                        <div class="text-[10px] font-bold uppercase tracking-wider text-slate-400 flex items-center gap-1.5">
                            <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                            <span>Omset Periode</span>
                        </div>
                        <div class="text-sm sm:text-base font-black text-white font-mono mt-0.5" x-text="formatRp(periodRevenue)"></div>
                    </div>
                    <div>
                        <div class="text-[10px] font-bold uppercase tracking-wider text-slate-400 flex items-center gap-1.5">
                            <span class="w-2 h-2 rounded-full bg-amber-400"></span>
                            <span>Est. Laba Kotor</span>
                        </div>
                        <div class="text-sm sm:text-base font-black text-amber-400 font-mono mt-0.5" x-text="formatRp(periodProfit)"></div>
                    </div>
                    <div>
                        <div class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Total Struk</div>
                        <div class="text-sm sm:text-base font-black text-cyan-400 font-mono mt-0.5">
                            <span x-text="periodOrders"></span> <span class="text-xs font-normal text-slate-400">Transaksi</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="h-64 sm:h-72 relative">
                <canvas id="salesTrendChart"></canvas>
            </div>
        </div>

        <!-- Right (1/3): Revenue Composition & Payment Methods -->
        <div class="p-6 sm:p-7 rounded-3xl glass-panel shadow-2xl flex flex-col justify-between space-y-6">
            
            <!-- Revenue Stream Breakdown -->
            <div>
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h3 class="font-extrabold text-white text-base font-display">Sumber Pendapatan</h3>
                        <p class="text-xs text-slate-400">Komposisi omset bulan {{ date('F') }}</p>
                    </div>
                    <div class="w-9 h-9 rounded-xl bg-cyan-500/15 border border-cyan-500/30 text-cyan-400 flex items-center justify-center">
                        <i class="fa-solid fa-layer-group text-sm"></i>
                    </div>
                </div>

                <div class="space-y-3.5">
                    @foreach($revenueByCategory as $key => $cat)
                        <div class="p-3.5 rounded-2xl bg-slate-900/80 border border-slate-800/80 space-y-2">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-8 h-8 rounded-lg flex items-center justify-center text-xs
                                        {{ $cat['color'] === 'emerald' ? 'bg-emerald-500/15 text-emerald-400' : ($cat['color'] === 'cyan' ? 'bg-cyan-500/15 text-cyan-400' : 'bg-amber-500/15 text-amber-400') }}">
                                        <i class="fa-solid {{ $cat['icon'] }}"></i>
                                    </div>
                                    <div>
                                        <h4 class="text-xs font-bold text-white">{{ $cat['label'] }}</h4>
                                        <span class="text-[10px] text-slate-400">{{ $cat['qty'] }} item terjual</span>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <div class="text-xs font-bold font-mono text-white">Rp {{ number_format($cat['revenue'], 0, ',', '.') }}</div>
                                    <div class="text-[10px] font-mono font-bold {{ $cat['color'] === 'emerald' ? 'text-emerald-400' : ($cat['color'] === 'cyan' ? 'text-cyan-400' : 'text-amber-400') }}">
                                        {{ $cat['percent'] }}%
                                    </div>
                                </div>
                            </div>
                            <div class="w-full h-1.5 rounded-full bg-slate-800 overflow-hidden">
                                <div class="h-full rounded-full transition-all duration-500 {{ $cat['color'] === 'emerald' ? 'bg-emerald-500' : ($cat['color'] === 'cyan' ? 'bg-cyan-500' : 'bg-amber-500') }}"
                                     style="width: {{ max(3, $cat['percent']) }}%"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Payment Method Mix -->
            <div class="pt-4 border-t border-slate-800/80">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-extrabold uppercase tracking-wider text-slate-400 font-display">Metode Pembayaran Bulan Ini</span>
                    <a href="{{ route('reports.index') }}" class="text-[11px] font-bold text-emerald-400 hover:underline">Detail &rarr;</a>
                </div>
                <div class="grid grid-cols-2 gap-2.5">
                    @forelse($paymentMethodMix as $pm)
                        <div class="p-2.5 rounded-xl bg-slate-900/80 border border-slate-800 flex items-center justify-between">
                            <div>
                                <span class="text-[10px] font-extrabold uppercase text-slate-300 block">{{ $pm->payment_method }}</span>
                                <span class="text-[11px] font-mono font-bold text-emerald-400">Rp {{ number_format($pm->total, 0, ',', '.') }}</span>
                            </div>
                            <span class="px-2 py-0.5 rounded bg-slate-800 text-slate-300 text-[10px] font-mono font-bold">
                                {{ $pm->count }}x
                            </span>
                        </div>
                    @empty
                        <div class="col-span-2 text-center py-3 text-xs text-slate-500">
                            Belum ada transaksi bulan ini.
                        </div>
                    @endforelse
                </div>
            </div>

        </div>
    </div>

    <!-- 5. Operational Workspace Grid: Bookings, Recent POS & Top Supplements -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5 sm:gap-6">
        
        <!-- Column 1: Today's Bookings with Quick Action -->
        <div class="p-6 rounded-3xl glass-panel flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-5">
                    <div>
                        <h3 class="font-extrabold text-white text-base font-display">Jadwal Booking Hari Ini</h3>
                        <p class="text-xs text-slate-400">{{ $todayBookings->count() }} sesi terjadwal ({{ date('d M Y') }})</p>
                    </div>
                    <a href="{{ route('bookings.calendar') }}" class="px-3 py-1.5 rounded-xl bg-slate-900 hover:bg-slate-800 border border-slate-800 text-xs font-bold text-cyan-400 transition-colors">
                        <i class="fa-regular fa-calendar-days mr-1"></i> Kalender
                    </a>
                </div>

                <div class="space-y-3 max-h-96 overflow-y-auto pr-1">
                    @forelse($todayBookings as $bk)
                        <div class="p-3.5 rounded-2xl bg-slate-900/80 border border-slate-800/90 flex items-center justify-between gap-3">
                            <div class="flex items-center gap-3 min-w-0">
                                <div class="w-12 h-12 rounded-xl bg-cyan-500/10 border border-cyan-500/25 text-cyan-400 flex flex-col items-center justify-center font-bold shrink-0">
                                    <span class="font-mono text-xs leading-none">{{ substr($bk->start_time, 0, 5) }}</span>
                                    <span class="text-[9px] text-slate-400 uppercase mt-0.5">WIB</span>
                                </div>
                                <div class="min-w-0">
                                    <h4 class="text-xs font-bold text-white truncate">{{ $bk->customer_name }}</h4>
                                    <p class="text-[11px] text-slate-400 truncate">
                                        {{ $bk->booking_type === 'personal_trainer' ? 'PT' : 'Kelas' }} &bull;
                                        <span class="text-slate-200 font-semibold">{{ $bk->trainer ? $bk->trainer->name : ($bk->fitnessClass ? $bk->fitnessClass->name : '-') }}</span>
                                    </p>
                                    <div class="flex items-center gap-1.5 mt-1">
                                        <span class="inline-flex px-1.5 py-0.5 rounded text-[9px] font-bold uppercase {{ $bk->payment_status === 'paid' ? 'bg-emerald-500/20 text-emerald-300' : 'bg-rose-500/20 text-rose-300' }}">
                                            {{ $bk->payment_status === 'paid' ? 'LUNAS' : 'BELUM BAYAR' }}
                                        </span>
                                        <span class="inline-flex px-1.5 py-0.5 rounded text-[9px] font-bold uppercase {{ $bk->status === 'confirmed' ? 'bg-emerald-500/20 text-emerald-300' : ($bk->status === 'completed' ? 'bg-cyan-500/20 text-cyan-300' : 'bg-amber-500/20 text-amber-300') }}">
                                            {{ $bk->status }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="shrink-0">
                                @if($bk->status !== 'completed' && $bk->status !== 'cancelled')
                                    <form method="POST" action="{{ route('bookings.updateStatus', $bk->id) }}">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="status" value="completed">
                                        <button type="submit" title="Tandai Hadir / Selesai"
                                                class="px-2.5 py-1.5 rounded-xl bg-emerald-500/20 hover:bg-emerald-500/30 text-emerald-300 border border-emerald-500/30 text-[11px] font-bold transition-colors flex items-center gap-1">
                                            <i class="fa-solid fa-check"></i>
                                            <span>Check-in</span>
                                        </button>
                                    </form>
                                @else
                                    <span class="text-cyan-400 text-xs" title="Sesi Selesai">
                                        <i class="fa-solid fa-circle-check text-base"></i>
                                    </span>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-10 text-slate-500 text-xs space-y-2">
                            <div class="w-12 h-12 rounded-2xl bg-slate-900 border border-slate-800 flex items-center justify-center mx-auto text-slate-600">
                                <i class="fa-regular fa-calendar-check text-lg"></i>
                            </div>
                            <p>Belum ada jadwal booking PT atau Kelas untuk hari ini.</p>
                            <a href="{{ route('bookings.create') }}" class="inline-block text-emerald-400 font-bold hover:underline">+ Buat Booking Baru</a>
                        </div>
                    @endforelse
                </div>
            </div>

            <a href="{{ route('bookings.index') }}" class="mt-4 pt-3 border-t border-slate-800/80 block text-center text-xs font-bold text-emerald-400 hover:text-emerald-300">
                Kelola Seluruh Daftar Booking &rarr;
            </a>
        </div>

        <!-- Column 2: Recent POS Transactions -->
        <div class="p-6 rounded-3xl glass-panel flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-5">
                    <div>
                        <h3 class="font-extrabold text-white text-base font-display">Transaksi POS Terakhir</h3>
                        <p class="text-xs text-slate-400">Aktivitas kasir real-time</p>
                    </div>
                    <a href="{{ route('pos.orders') }}" class="px-3 py-1.5 rounded-xl bg-slate-900 hover:bg-slate-800 border border-slate-800 text-xs font-bold text-emerald-400 transition-colors">
                        Semua Struk
                    </a>
                </div>

                <div class="space-y-3 max-h-96 overflow-y-auto pr-1">
                    @forelse($recentOrders as $ord)
                        <div class="p-3.5 rounded-2xl bg-slate-900/80 border border-slate-800/90 flex items-center justify-between gap-3">
                            <div class="min-w-0">
                                <div class="flex items-center gap-2">
                                    <span class="text-xs font-mono font-bold text-white">{{ $ord->invoice_no }}</span>
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase bg-slate-800 text-slate-300 border border-slate-700">
                                        {{ $ord->payment_method }}
                                    </span>
                                    <span class="text-[10px] font-mono text-slate-400">
                                        ({{ $ord->items->sum('quantity') }} item)
                                    </span>
                                </div>
                                <p class="text-[11px] text-slate-400 mt-1 truncate">
                                    <span class="text-slate-200 font-semibold">{{ $ord->customer_name }}</span>
                                    &bull; {{ $ord->created_at->diffForHumans() }}
                                </p>
                            </div>
                            <div class="text-right shrink-0">
                                <div class="text-xs font-bold font-mono text-emerald-400">
                                    Rp {{ number_format($ord->total_amount, 0, ',', '.') }}
                                </div>
                                <a href="{{ route('pos.receipt', $ord->id) }}" target="_blank"
                                   class="inline-flex items-center gap-1 text-[10px] font-semibold text-slate-400 hover:text-white mt-1">
                                    <i class="fa-solid fa-print"></i> Cetak Struk
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-10 text-slate-500 text-xs space-y-2">
                            <div class="w-12 h-12 rounded-2xl bg-slate-900 border border-slate-800 flex items-center justify-center mx-auto text-slate-600">
                                <i class="fa-solid fa-receipt text-lg"></i>
                            </div>
                            <p>Belum ada riwayat transaksi POS yang tercatat.</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <a href="{{ route('pos.orders') }}" class="mt-4 pt-3 border-t border-slate-800/80 block text-center text-xs font-bold text-emerald-400 hover:text-emerald-300">
                Buka Riwayat Transaksi Lengkap &rarr;
            </a>
        </div>

        <!-- Column 3: Top Selling Supplements Leaderboard -->
        <div class="p-6 rounded-3xl glass-panel flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-5">
                    <div>
                        <h3 class="font-extrabold text-white text-base font-display">Suplemen Terlaris</h3>
                        <p class="text-xs text-slate-400">Produk dengan penjualan tertinggi</p>
                    </div>
                    <div class="w-9 h-9 rounded-xl bg-amber-500/20 text-amber-400 flex items-center justify-center">
                        <i class="fa-solid fa-trophy text-sm"></i>
                    </div>
                </div>
                
                <div class="space-y-3.5">
                    @forelse($topSupplements as $index => $top)
                        @php
                            $barPercent = round(($top->total_sold / $maxSoldSupp) * 100);
                        @endphp
                        <div class="p-3.5 rounded-2xl bg-slate-900/80 border border-slate-800/90 space-y-2">
                            <div class="flex items-center justify-between gap-2">
                                <div class="flex items-center gap-2.5 min-w-0">
                                    <span class="w-6 h-6 rounded-lg font-mono font-extrabold text-xs flex items-center justify-center shrink-0
                                        {{ $index === 0 ? 'bg-amber-500 text-slate-950' : ($index === 1 ? 'bg-slate-300 text-slate-950' : ($index === 2 ? 'bg-amber-700 text-white' : 'bg-slate-800 text-slate-300')) }}">
                                        #{{ $index + 1 }}
                                    </span>
                                    <div class="min-w-0">
                                        <h4 class="text-xs font-bold text-white truncate">{{ $top->name }}</h4>
                                        <p class="text-[10px] text-slate-400">{{ $top->total_sold }} unit terjual</p>
                                    </div>
                                </div>
                                <span class="text-xs font-bold text-emerald-400 shrink-0 font-mono">
                                    Rp {{ number_format($top->total_revenue, 0, ',', '.') }}
                                </span>
                            </div>
                            <div class="w-full h-1.5 rounded-full bg-slate-800 overflow-hidden">
                                <div class="h-full rounded-full bg-gradient-to-r from-emerald-500 to-teal-400"
                                     style="width: {{ max(8, $barPercent) }}%"></div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-10 text-slate-500 text-xs">
                            Belum ada data penjualan suplemen yang tercatat.
                        </div>
                    @endforelse
                </div>
            </div>

            <a href="{{ route('supplements.index') }}" class="mt-4 pt-3 border-t border-slate-800/80 block text-center text-xs font-bold text-emerald-400 hover:text-emerald-300">
                Lihat Katalog & Stok Suplemen &rarr;
            </a>
        </div>

    </div>

</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('salesTrendChart').getContext('2d');
        const initialTrend = {!! json_encode($salesTrend) !!};
        const labels = initialTrend.map(d => d.date);
        const revenueValues = initialTrend.map(d => d.total);
        const profitValues = initialTrend.map(d => d.profit);

        window.ownerSalesChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Omset Penjualan (Rp)',
                        data: revenueValues,
                        borderColor: '#10B981',
                        backgroundColor: 'rgba(16, 185, 129, 0.12)',
                        borderWidth: 3,
                        fill: true,
                        tension: 0.35,
                        pointBackgroundColor: '#10B981',
                        pointBorderColor: '#0B0F19',
                        pointBorderWidth: 2,
                        pointRadius: 4,
                        pointHoverRadius: 6,
                    },
                    {
                        label: 'Estimasi Laba Kotor (Rp)',
                        data: profitValues,
                        borderColor: '#F59E0B',
                        backgroundColor: 'rgba(245, 158, 11, 0.06)',
                        borderWidth: 2.5,
                        borderDash: [5, 5],
                        fill: true,
                        tension: 0.35,
                        pointBackgroundColor: '#F59E0B',
                        pointBorderColor: '#0B0F19',
                        pointBorderWidth: 2,
                        pointRadius: 3.5,
                        pointHoverRadius: 6,
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: {
                    mode: 'index',
                    intersect: false,
                },
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                        align: 'end',
                        labels: {
                            color: '#94A3B8',
                            usePointStyle: true,
                            boxWidth: 8,
                            font: { family: 'Plus Jakarta Sans', size: 11, weight: '600' }
                        }
                    },
                    tooltip: {
                        backgroundColor: '#0F172A',
                        titleColor: '#F8FAFC',
                        bodyColor: '#E2E8F0',
                        borderColor: '#334155',
                        borderWidth: 1,
                        padding: 12,
                        cornerRadius: 10,
                        callbacks: {
                            label: function(context) {
                                return context.dataset.label + ': Rp ' + new Intl.NumberFormat('id-ID').format(context.raw);
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: {
                            color: 'rgba(51, 65, 85, 0.25)',
                            drawBorder: false,
                        },
                        ticks: {
                            color: '#94A3B8',
                            font: { family: 'Plus Jakarta Sans', size: 11 }
                        }
                    },
                    y: {
                        grid: {
                            color: 'rgba(51, 65, 85, 0.25)',
                            drawBorder: false,
                        },
                        ticks: {
                            color: '#94A3B8',
                            font: { family: 'Plus Jakarta Sans', size: 11 },
                            callback: function(value) {
                                if (value >= 1000000) return 'Rp ' + (value / 1000000).toFixed(1) + 'M';
                                if (value >= 1000) return 'Rp ' + (value / 1000).toFixed(0) + 'K';
                                return 'Rp ' + value;
                            }
                        }
                    }
                }
            }
        });
    });
</script>
@endpush
@endsection
