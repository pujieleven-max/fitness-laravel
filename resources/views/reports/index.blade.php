@extends('layouts.app')

@section('title', 'Laporan Keuangan & Laba')
@section('page-title', 'Laporan Keuangan, Omset & Keuntungan')
@section('page-subtitle', 'Analisis laba rugi kotor, perputaran suplemen, dan rekap pembayaran')

@section('content')
<div class="space-y-6">

    <!-- Date Range Filter & Print -->
    <div class="flex flex-col sm:flex-row items-center justify-between gap-4 p-4 rounded-2xl bg-[#0B0F19] border border-slate-800">
        <form method="GET" action="{{ route('reports.index') }}" class="flex flex-wrap items-center gap-3">
            <div>
                <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-1">Dari Tanggal</label>
                <input type="date" name="start_date" value="{{ $startDate->format('Y-m-d') }}"
                       class="px-3 py-1.5 rounded-xl bg-slate-900 border border-slate-700 text-white text-xs focus:outline-none focus:border-amber-400">
            </div>

            <div>
                <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-1">Sampai Tanggal</label>
                <input type="date" name="end_date" value="{{ $endDate->format('Y-m-d') }}"
                       class="px-3 py-1.5 rounded-xl bg-slate-900 border border-slate-700 text-white text-xs focus:outline-none focus:border-amber-400">
            </div>

            <div class="self-end">
                <button type="submit" class="px-4 py-2 rounded-xl bg-amber-500 hover:bg-amber-400 text-slate-950 font-bold text-xs flex items-center gap-1.5 transition-colors">
                    <i class="fa-solid fa-filter"></i>
                    <span>Tampilkan Laporan</span>
                </button>
            </div>
        </form>

        <button onclick="window.print()" class="px-4 py-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-white font-bold text-xs flex items-center gap-2 transition-colors shrink-0">
            <i class="fa-solid fa-print"></i>
            <span>Cetak Rekap Laporan</span>
        </button>
    </div>

    <!-- Executive Summary Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        
        <!-- Total Revenue -->
        <div class="p-5 rounded-2xl bg-[#0B0F19] border border-slate-800">
            <span class="text-xs font-bold uppercase tracking-wider text-slate-400 block mb-1">Total Omset Penjualan</span>
            <div class="text-2xl font-black text-white font-mono">
                Rp {{ number_format($totalRevenue, 0, ',', '.') }}
            </div>
            <p class="text-xs text-slate-400 mt-1">{{ $totalOrdersCount }} transaksi selesai</p>
        </div>

        <!-- Gross Profit -->
        <div class="p-5 rounded-2xl bg-[#0B0F19] border border-amber-500/30">
            <div class="flex items-center justify-between text-amber-400 mb-1">
                <span class="text-xs font-bold uppercase tracking-wider">Estimasi Laba Kotor</span>
                <i class="fa-solid fa-chart-line"></i>
            </div>
            <div class="text-2xl font-black text-amber-400 font-mono">
                Rp {{ number_format($totalProfit, 0, ',', '.') }}
            </div>
            <p class="text-xs text-amber-400/80 mt-1">Margin = Omset &minus; Modal Pokok</p>
        </div>

        <!-- Inventory Cost Valuation -->
        <div class="p-5 rounded-2xl bg-[#0B0F19] border border-slate-800">
            <span class="text-xs font-bold uppercase tracking-wider text-slate-400 block mb-1">Nilai Modal Persediaan</span>
            <div class="text-2xl font-black text-white font-mono">
                Rp {{ number_format($totalStockValue->stock_cost ?? 0, 0, ',', '.') }}
            </div>
            <p class="text-xs text-slate-400 mt-1">Aset suplemen tersimpan di gudang</p>
        </div>

        <!-- Inventory Revenue Potential -->
        <div class="p-5 rounded-2xl bg-[#0B0F19] border border-emerald-500/30">
            <span class="text-xs font-bold uppercase tracking-wider text-emerald-400 block mb-1">Potensi Omset Stok</span>
            <div class="text-2xl font-black text-emerald-400 font-mono">
                Rp {{ number_format($totalStockValue->stock_revenue_potential ?? 0, 0, ',', '.') }}
            </div>
            <p class="text-xs text-emerald-400/80 mt-1">Bila seluruh stok terjual habis</p>
        </div>

    </div>

    <!-- Breakdown by Category & Payment Method -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        
        <!-- Category Breakdown -->
        <div class="p-6 rounded-2xl bg-[#0B0F19] border border-slate-800">
            <h3 class="font-bold text-white text-base mb-1">Breakdown Pendapatan Berdasarkan Kategori</h3>
            <p class="text-xs text-slate-400 mb-4">Kontribusi suplemen, tiket gym harian, dan booking</p>

            <div class="space-y-3">
                @forelse($itemTypesBreakdown as $b)
                    <div class="p-4 rounded-xl bg-slate-900 border border-slate-800 flex items-center justify-between">
                        <div>
                            <span class="text-xs font-bold text-white uppercase tracking-wider block">
                                @if($b->item_type === 'supplement')
                                    Suplemen & Minuman
                                @elseif($b->item_type === 'gym_package')
                                    Tiket Gym & Membership
                                @else
                                    Sesi Personal Trainer / Kelas
                                @endif
                            </span>
                            <span class="text-[11px] text-slate-400">{{ $b->qty }} unit/sesi terjual</span>
                        </div>
                        <div class="text-right">
                            <span class="text-sm font-bold font-mono text-white block">
                                Rp {{ number_format($b->revenue, 0, ',', '.') }}
                            </span>
                            <span class="text-[11px] font-bold text-amber-400">
                                Profit: Rp {{ number_format($b->profit, 0, ',', '.') }}
                            </span>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-8 text-slate-500 text-xs">
                        Tidak ada penjualan pada rentang tanggal ini.
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Payment Method Breakdown -->
        <div class="p-6 rounded-2xl bg-[#0B0F19] border border-slate-800">
            <h3 class="font-bold text-white text-base mb-1">Rekap Berdasarkan Metode Pembayaran</h3>
            <p class="text-xs text-slate-400 mb-4">Uang tunai kasir vs pembayaran digital QRIS/Transfer</p>

            <div class="space-y-3">
                @forelse($paymentMethods as $pm)
                    <div class="p-4 rounded-xl bg-slate-900 border border-slate-800 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-slate-800 flex items-center justify-center text-slate-300">
                                @if($pm->payment_method === 'cash')
                                    <i class="fa-solid fa-money-bill-wave text-emerald-400"></i>
                                @elseif($pm->payment_method === 'qris')
                                    <i class="fa-solid fa-qrcode text-cyan-400"></i>
                                @elseif($pm->payment_method === 'transfer')
                                    <i class="fa-solid fa-building-columns text-amber-400"></i>
                                @else
                                    <i class="fa-solid fa-credit-card text-purple-400"></i>
                                @endif
                            </div>
                            <div>
                                <span class="text-xs font-bold text-white uppercase block">{{ $pm->payment_method }}</span>
                                <span class="text-[11px] text-slate-400">{{ $pm->total_count }} transaksi</span>
                            </div>
                        </div>

                        <div class="text-right">
                            <span class="text-sm font-black font-mono text-white">
                                Rp {{ number_format($pm->total_sum, 0, ',', '.') }}
                            </span>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-8 text-slate-500 text-xs">
                        Belum ada pembayaran pada periode ini.
                    </div>
                @endforelse
            </div>
        </div>

    </div>

    <!-- Top Selling Products Table -->
    <div class="p-6 rounded-2xl bg-[#0B0F19] border border-slate-800">
        <h3 class="font-bold text-white text-base mb-1">Top 10 Produk & Layanan Terlaris</h3>
        <p class="text-xs text-slate-400 mb-4">Urutan omset dan kontribusi laba kotor tertinggi</p>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-900/80 border-b border-slate-800 text-slate-400 uppercase tracking-wider font-mono text-[11px]">
                    <tr>
                        <th class="py-3 px-4">Peringkat & Item</th>
                        <th class="py-3 px-4">Tipe Layanan</th>
                        <th class="py-3 px-4 text-center">Terjual (Qty)</th>
                        <th class="py-3 px-4 text-right">Total Omset</th>
                        <th class="py-3 px-4 text-right">Laba Kotor</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/80">
                    @forelse($topProducts as $index => $prod)
                        <tr class="hover:bg-slate-900/50">
                            <td class="py-3 px-4">
                                <div class="flex items-center gap-2.5">
                                    <span class="w-6 h-6 rounded bg-slate-800 text-slate-300 font-bold flex items-center justify-center text-xs">
                                        #{{ $index + 1 }}
                                    </span>
                                    <span class="font-bold text-white text-xs">{{ $prod->name }}</span>
                                </div>
                            </td>

                            <td class="py-3 px-4 uppercase text-[10px] text-slate-400">
                                {{ $prod->item_type }}
                            </td>

                            <td class="py-3 px-4 text-center font-mono font-bold text-white">
                                {{ $prod->total_qty }}
                            </td>

                            <td class="py-3 px-4 text-right font-mono font-bold text-emerald-400">
                                Rp {{ number_format($prod->total_sales, 0, ',', '.') }}
                            </td>

                            <td class="py-3 px-4 text-right font-mono font-bold text-amber-400">
                                Rp {{ number_format($prod->product_profit, 0, ',', '.') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-8 text-slate-500 text-xs">
                                Tidak ada data produk pada periode ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
