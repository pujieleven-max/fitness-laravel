@extends('layouts.app')

@section('title', 'Dashboard Owner')
@section('page-title', 'Executive Dashboard (Owner)')
@section('page-subtitle', 'Ikhtisar Omset, Keuntungan, Stok Suplemen, dan Operasional Gym')

@section('content')
<div class="space-y-6">

    <!-- Top Key Metrics Cards with Modern Glassmorphism & Cyber Accents -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-5">
        
        <!-- Card 1: Omset Hari Ini -->
        <div class="p-6 rounded-3xl glass-panel glass-panel-hover relative overflow-hidden group">
            <div class="absolute -right-6 -bottom-6 w-28 h-28 bg-emerald-500/10 rounded-full blur-2xl group-hover:scale-150 transition-transform duration-500"></div>
            <div class="flex items-center justify-between text-slate-400 mb-3">
                <span class="text-xs font-bold uppercase tracking-wider font-display">Omset Hari Ini</span>
                <div class="w-10 h-10 rounded-2xl bg-emerald-500/15 border border-emerald-500/30 text-emerald-400 flex items-center justify-center shadow-md shadow-emerald-500/10">
                    <i class="fa-solid fa-coins text-sm"></i>
                </div>
            </div>
            <div class="text-2xl sm:text-3xl font-black text-white font-display tracking-tight">
                Rp {{ number_format($todayOrdersRevenue, 0, ',', '.') }}
            </div>
            <div class="mt-2.5 flex items-center gap-2 text-xs text-slate-400">
                <span class="px-2 py-0.5 rounded-full bg-emerald-500/20 text-emerald-300 font-bold border border-emerald-500/30 text-[10px]">
                    +{{ $todayOrdersCount }} Transaksi
                </span>
                <span>hari ini</span>
            </div>
        </div>

        <!-- Card 2: Omset Bulan Ini -->
        <div class="p-6 rounded-3xl glass-panel glass-panel-hover relative overflow-hidden group">
            <div class="absolute -right-6 -bottom-6 w-28 h-28 bg-cyan-500/10 rounded-full blur-2xl group-hover:scale-150 transition-transform duration-500"></div>
            <div class="flex items-center justify-between text-slate-400 mb-3">
                <span class="text-xs font-bold uppercase tracking-wider font-display">Omset Bulan Ini</span>
                <div class="w-10 h-10 rounded-2xl bg-cyan-500/15 border border-cyan-500/30 text-cyan-400 flex items-center justify-center shadow-md shadow-cyan-500/10">
                    <i class="fa-solid fa-wallet text-sm"></i>
                </div>
            </div>
            <div class="text-2xl sm:text-3xl font-black text-white font-display tracking-tight">
                Rp {{ number_format($monthOrdersRevenue, 0, ',', '.') }}
            </div>
            <div class="mt-2.5 flex items-center gap-1.5 text-xs text-slate-400">
                <span class="text-cyan-400 font-semibold font-mono">{{ date('F Y') }}</span>
            </div>
        </div>

        <!-- Card 3: Estimasi Keuntungan / Margin Laba -->
        <div class="p-6 rounded-3xl glass-panel glass-panel-hover relative overflow-hidden group">
            <div class="absolute -right-6 -bottom-6 w-28 h-28 bg-amber-500/10 rounded-full blur-2xl group-hover:scale-150 transition-transform duration-500"></div>
            <div class="flex items-center justify-between text-slate-400 mb-3">
                <span class="text-xs font-bold uppercase tracking-wider font-display">Est. Laba Kotor Bulan Ini</span>
                <div class="w-10 h-10 rounded-2xl bg-amber-500/15 border border-amber-500/30 text-amber-400 flex items-center justify-center shadow-md shadow-amber-500/10">
                    <i class="fa-solid fa-chart-pie text-sm"></i>
                </div>
            </div>
            <div class="text-2xl sm:text-3xl font-black text-amber-400 font-display tracking-tight">
                Rp {{ number_format($estimatedMonthProfit, 0, ',', '.') }}
            </div>
            <div class="mt-2.5 text-[11px] text-slate-400 flex items-center gap-1">
                <span>(Margin Harga Jual - Modal Beli)</span>
            </div>
        </div>

        <!-- Card 4: Nilai Aset Stok Suplemen -->
        <div class="p-6 rounded-3xl glass-panel glass-panel-hover relative overflow-hidden group">
            <div class="absolute -right-6 -bottom-6 w-28 h-28 bg-purple-500/10 rounded-full blur-2xl group-hover:scale-150 transition-transform duration-500"></div>
            <div class="flex items-center justify-between text-slate-400 mb-3">
                <span class="text-xs font-bold uppercase tracking-wider font-display">Nilai Aset Stok Suplemen</span>
                <div class="w-10 h-10 rounded-2xl bg-purple-500/15 border border-purple-500/30 text-purple-400 flex items-center justify-center shadow-md shadow-purple-500/10">
                    <i class="fa-solid fa-boxes-stacked text-sm"></i>
                </div>
            </div>
            <div class="text-2xl sm:text-3xl font-black text-white font-display tracking-tight">
                Rp {{ number_format($totalStockValue, 0, ',', '.') }}
            </div>
            <div class="mt-2.5 text-xs text-slate-400 flex items-center gap-2">
                <span class="text-emerald-400 font-bold font-mono">{{ $activeMembers }}</span> Member Aktif dari {{ $totalMembers }}
            </div>
        </div>
    </div>

    <!-- Alert Banners for Low Stock & Expiring Soon -->
    @if($lowStockSupplements->isNotEmpty() || $expiringSupplements->isNotEmpty())
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
            <!-- Low stock alert -->
            @if($lowStockSupplements->isNotEmpty())
                <div class="p-4 rounded-2xl bg-amber-500/10 border border-amber-500/30 flex items-start gap-4">
                    <div class="w-10 h-10 rounded-xl bg-amber-500/20 text-amber-400 flex items-center justify-center shrink-0 text-lg">
                        <i class="fa-solid fa-triangle-exclamation"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <h4 class="text-sm font-bold text-amber-300">Peringatan: Stok Suplemen Menipis!</h4>
                        <p class="text-xs text-slate-300 mt-0.5">Ada produk yang telah mencapai batas minimum stok:</p>
                        <div class="mt-2 flex flex-wrap gap-2">
                            @foreach($lowStockSupplements as $item)
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-semibold {{ $item->stock <= 0 ? 'bg-rose-500/20 text-rose-300 border border-rose-500/30' : 'bg-amber-500/20 text-amber-300 border border-amber-500/30' }}">
                                    <span>{{ $item->name }}</span>
                                    <span class="font-bold font-mono">({{ $item->stock }} {{ $item->unit }})</span>
                                </span>
                            @endforeach
                        </div>
                    </div>
                    <a href="{{ route('supplements.index', ['status' => 'low_stock']) }}" 
                       class="px-3 py-1.5 rounded-lg bg-amber-500 hover:bg-amber-400 text-slate-950 font-bold text-xs shrink-0 transition-colors">
                        Restock
                    </a>
                </div>
            @endif

            <!-- Expiring soon alert -->
            @if($expiringSupplements->isNotEmpty())
                <div class="p-4 rounded-2xl bg-rose-500/10 border border-rose-500/30 flex items-start gap-4">
                    <div class="w-10 h-10 rounded-xl bg-rose-500/20 text-rose-400 flex items-center justify-center shrink-0 text-lg">
                        <i class="fa-solid fa-clock"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <h4 class="text-sm font-bold text-rose-300">Peringatan: Suplemen Mendekati Kadaluarsa</h4>
                        <p class="text-xs text-slate-300 mt-0.5">Produk berikut kadaluarsa dalam < 30 hari:</p>
                        <div class="mt-2 flex flex-wrap gap-2">
                            @foreach($expiringSupplements as $exp)
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-semibold bg-rose-500/20 text-rose-300 border border-rose-500/30">
                                    <span>{{ $exp->name }}</span>
                                    <span class="font-mono text-[11px]">({{ $exp->expiry_date->format('d M Y') }})</span>
                                </span>
                            @endforeach
                        </div>
                    </div>
                    <a href="{{ route('supplements.index', ['status' => 'expiring_soon']) }}" 
                       class="px-3 py-1.5 rounded-lg bg-rose-500 hover:bg-rose-400 text-slate-950 font-bold text-xs shrink-0 transition-colors">
                        Cek Detail
                    </a>
                </div>
            @endif
        </div>
    @endif

    <!-- Sales Trend Chart & Top Supplements with Glass Panels -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5 sm:gap-6">
        <!-- 7 Days Sales Trend Chart -->
        <div class="lg:col-span-2 p-6 sm:p-7 rounded-3xl glass-panel shadow-2xl">
            <div class="flex items-center justify-between mb-5">
                <div>
                    <h3 class="font-bold text-white text-base font-display">Tren Penjualan 7 Hari Terakhir</h3>
                    <p class="text-xs text-slate-400">Total omset harian dari POS Kasir</p>
                </div>
                <div class="flex items-center gap-2 text-xs font-semibold text-emerald-400 bg-emerald-500/10 px-3 py-1 rounded-full border border-emerald-500/20 shadow-sm">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                    <span class="font-display">Live Real-time</span>
                </div>
            </div>
            <div class="h-64 sm:h-72">
                <canvas id="salesTrendChart"></canvas>
            </div>
        </div>

        <!-- Top Selling Supplements -->
        <div class="p-6 sm:p-7 rounded-3xl glass-panel shadow-2xl flex flex-col">
            <div class="flex items-center justify-between mb-5">
                <div>
                    <h3 class="font-bold text-white text-base font-display">Suplemen Terlaris</h3>
                    <p class="text-xs text-slate-400">Paling banyak terjual di kasir</p>
                </div>
                <div class="w-8 h-8 rounded-xl bg-amber-500/20 text-amber-400 flex items-center justify-center">
                    <i class="fa-solid fa-fire text-sm"></i>
                </div>
            </div>
            
            <div class="space-y-3 flex-1 overflow-y-auto">
                @forelse($topSupplements as $index => $top)
                    <div class="p-3 rounded-xl bg-slate-900/80 border border-slate-800/80 flex items-center justify-between">
                        <div class="flex items-center gap-3 min-w-0">
                            <span class="w-6 h-6 rounded-lg bg-slate-800 text-slate-300 font-bold text-xs flex items-center justify-center shrink-0">
                                #{{ $index + 1 }}
                            </span>
                            <div class="min-w-0">
                                <h4 class="text-xs font-bold text-white truncate">{{ $top->name }}</h4>
                                <p class="text-[11px] text-slate-400">{{ $top->total_sold }} terjual</p>
                            </div>
                        </div>
                        <span class="text-xs font-bold text-emerald-400 shrink-0 font-mono">
                            Rp {{ number_format($top->total_revenue, 0, ',', '.') }}
                        </span>
                    </div>
                @empty
                    <div class="text-center py-8 text-slate-500 text-xs">
                        Belum ada data penjualan suplemen yang tercatat.
                    </div>
                @endforelse
            </div>

            <a href="{{ route('supplements.index') }}" class="mt-4 block text-center text-xs font-bold text-emerald-400 hover:text-emerald-300">
                Lihat Seluruh Stok Suplemen &rarr;
            </a>
        </div>
    </div>

    <!-- Today's Bookings & Recent Transactions -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        
        <!-- Today's Bookings -->
        <div class="p-6 rounded-2xl bg-[#0B0F19] border border-slate-800">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="font-bold text-white text-base">Jadwal Sesi Booking Hari Ini</h3>
                    <p class="text-xs text-slate-400">{{ date('d F Y') }}</p>
                </div>
                <a href="{{ route('bookings.index') }}" class="text-xs font-bold text-emerald-400 hover:text-emerald-300">
                    Semua Booking &rarr;
                </a>
            </div>

            <div class="space-y-3">
                @forelse($todayBookings as $bk)
                    <div class="p-3.5 rounded-xl bg-slate-900/80 border border-slate-800 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-cyan-500/10 border border-cyan-500/20 text-cyan-400 flex items-center justify-center font-bold text-xs shrink-0">
                                {{ substr($bk->start_time, 0, 5) }}
                            </div>
                            <div>
                                <h4 class="text-xs font-bold text-white">{{ $bk->customer_name }}</h4>
                                <p class="text-[11px] text-slate-400">
                                    {{ $bk->booking_type === 'personal_trainer' ? 'Personal Trainer' : 'Kelas Fitness' }} 
                                    &bull; <span class="text-slate-300">{{ $bk->trainer ? $bk->trainer->name : ($bk->fitnessClass ? $bk->fitnessClass->name : '-') }}</span>
                                </p>
                            </div>
                        </div>
                        <div class="text-right">
                            <span class="inline-flex px-2 py-0.5 rounded text-[10px] font-bold uppercase {{ $bk->status === 'confirmed' ? 'bg-emerald-500/20 text-emerald-300 border border-emerald-500/30' : ($bk->status === 'completed' ? 'bg-cyan-500/20 text-cyan-300 border border-cyan-500/30' : 'bg-slate-700 text-slate-300') }}">
                                {{ $bk->status }}
                            </span>
                            <div class="text-[10px] text-slate-400 mt-1">
                                {{ $bk->payment_status === 'paid' ? 'LUNAS' : 'BELUM BAYAR' }}
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-8 text-slate-500 text-xs">
                        Tidak ada jadwal booking Personal Trainer atau Kelas untuk hari ini.
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Recent POS Transactions -->
        <div class="p-6 rounded-2xl bg-[#0B0F19] border border-slate-800">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="font-bold text-white text-base">Transaksi Kasir Terakhir</h3>
                    <p class="text-xs text-slate-400">Struk penjualan realtime</p>
                </div>
                <a href="{{ route('pos.orders') }}" class="text-xs font-bold text-emerald-400 hover:text-emerald-300">
                    Riwayat Kasir &rarr;
                </a>
            </div>

            <div class="space-y-3">
                @forelse($recentOrders as $ord)
                    <div class="p-3.5 rounded-xl bg-slate-900/80 border border-slate-800 flex items-center justify-between">
                        <div>
                            <div class="flex items-center gap-2">
                                <span class="text-xs font-mono font-bold text-white">{{ $ord->invoice_no }}</span>
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase bg-slate-800 text-slate-300">
                                    {{ $ord->payment_method }}
                                </span>
                            </div>
                            <p class="text-[11px] text-slate-400 mt-1">
                                {{ $ord->customer_name }} &bull; <span class="text-slate-400">{{ $ord->created_at->diffForHumans() }}</span>
                            </p>
                        </div>
                        <div class="text-right">
                            <div class="text-xs font-bold font-mono text-emerald-400">
                                Rp {{ number_format($ord->total_amount, 0, ',', '.') }}
                            </div>
                            <a href="{{ route('pos.receipt', $ord->id) }}" target="_blank" class="text-[10px] text-slate-400 hover:text-white flex items-center gap-1 justify-end mt-1">
                                <i class="fa-solid fa-print"></i> Cetak
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-8 text-slate-500 text-xs">
                        Belum ada riwayat transaksi POS.
                    </div>
                @endforelse
            </div>
        </div>
    </div>

</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('salesTrendChart').getContext('2d');
        const labels = {!! json_encode(array_column($salesTrend, 'date')) !!};
        const dataValues = {!! json_encode(array_column($salesTrend, 'total')) !!};

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Omset Penjualan (Rp)',
                    data: dataValues,
                    borderColor: '#10B981',
                    backgroundColor: 'rgba(16, 185, 129, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.35,
                    pointBackgroundColor: '#10B981',
                    pointBorderColor: '#0B0F19',
                    pointBorderWidth: 2,
                    pointRadius: 5,
                    pointHoverRadius: 7,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: '#1E293B',
                        titleColor: '#F8FAFC',
                        bodyColor: '#34D399',
                        padding: 12,
                        cornerRadius: 8,
                        callbacks: {
                            label: function(context) {
                                return 'Omset: Rp ' + new Intl.NumberFormat('id-ID').format(context.raw);
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: {
                            color: 'rgba(51, 65, 85, 0.3)',
                            drawBorder: false,
                        },
                        ticks: {
                            color: '#94A3B8',
                            font: { family: 'Plus Jakarta Sans', size: 11 }
                        }
                    },
                    y: {
                        grid: {
                            color: 'rgba(51, 65, 85, 0.3)',
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
