@extends('layouts.app')

@section('title', 'Riwayat Transaksi POS')
@section('page-title', 'Riwayat Transaksi Penjualan Kasir')
@section('page-subtitle', 'Daftar struk transaksi POS, re-print struk, dan status pembayaran')

@section('content')
<div class="space-y-6">

    <!-- Header Stats Banner -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="p-5 rounded-2xl bg-[#0B0F19] border border-slate-800">
            <span class="text-xs font-bold uppercase tracking-wider text-slate-400 block mb-1">Total Omset Kasir Hari Ini</span>
            <div class="text-2xl font-black text-emerald-400 font-mono">
                Rp {{ number_format($todayTotal, 0, ',', '.') }}
            </div>
            <p class="text-xs text-slate-400 mt-1">Transaksi berstatus selesai (completed)</p>
        </div>

        <div class="p-5 rounded-2xl bg-[#0B0F19] border border-slate-800">
            <span class="text-xs font-bold uppercase tracking-wider text-slate-400 block mb-1">Total Transaksi Tercatat</span>
            <div class="text-2xl font-black text-white font-mono">
                {{ $orders->total() }} <span class="text-xs text-slate-400 font-normal">struk</span>
            </div>
            <p class="text-xs text-slate-400 mt-1">Diurutkan dari transaksi terbaru</p>
        </div>

        <div class="p-5 rounded-2xl bg-[#0B0F19] border border-slate-800 flex items-center justify-between">
            <div>
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400 block mb-1">Aksi Cepat</span>
                <span class="text-sm font-bold text-white">Buka Kasir Baru</span>
            </div>
            <a href="{{ route('pos.index') }}" 
               class="px-4 py-2.5 rounded-xl bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-bold text-xs flex items-center gap-2 transition-all">
                <i class="fa-solid fa-cash-register"></i>
                <span>Kasir POS</span>
            </a>
        </div>
    </div>

    <!-- Filter Bar -->
    <div class="p-4 rounded-2xl bg-[#0B0F19] border border-slate-800">
        <form method="GET" action="{{ route('pos.orders') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3">
            <div>
                <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-400 mb-1">Cari Invoice / Nama</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="INV-2026... atau nama tamu"
                       class="w-full px-3 py-2 rounded-xl bg-slate-900 border border-slate-700/80 text-white text-xs placeholder-slate-500 focus:outline-none focus:border-emerald-500">
            </div>

            <div>
                <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-400 mb-1">Tanggal Transaksi</label>
                <input type="date" name="date" value="{{ request('date') }}"
                       class="w-full px-3 py-2 rounded-xl bg-slate-900 border border-slate-700/80 text-white text-xs focus:outline-none focus:border-emerald-500">
            </div>

            <div>
                <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-400 mb-1">Metode Bayar</label>
                <select name="payment_method" class="select2 w-full px-3 py-2 rounded-xl bg-slate-900 border border-slate-700/80 text-white text-xs focus:outline-none focus:border-emerald-500">
                    <option value="">-- Semua Metode --</option>
                    <option value="cash" {{ request('payment_method') === 'cash' ? 'selected' : '' }}>Tunai (Cash)</option>
                    <option value="qris" {{ request('payment_method') === 'qris' ? 'selected' : '' }}>QRIS</option>
                    <option value="transfer" {{ request('payment_method') === 'transfer' ? 'selected' : '' }}>Transfer Bank</option>
                    <option value="debit" {{ request('payment_method') === 'debit' ? 'selected' : '' }}>Kartu Debit/Kredit</option>
                </select>
            </div>

            <div>
                <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-400 mb-1">Status</label>
                <select name="status" class="select2 w-full px-3 py-2 rounded-xl bg-slate-900 border border-slate-700/80 text-white text-xs focus:outline-none focus:border-emerald-500">
                    <option value="">-- Semua Status --</option>
                    <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Selesai (Completed)</option>
                    <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Dibatalkan (Cancelled)</option>
                </select>
            </div>

            <div class="flex items-end gap-2">
                <button type="submit" class="flex-1 py-2 px-3 rounded-xl bg-slate-800 hover:bg-slate-700 text-white font-bold text-xs flex items-center justify-center gap-1.5 transition-colors">
                    <i class="fa-solid fa-filter"></i>
                    <span>Filter</span>
                </button>
                <a href="{{ route('pos.orders') }}" class="py-2 px-3 rounded-xl bg-slate-900 text-slate-400 hover:text-white border border-slate-800 text-xs">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Orders Table -->
    <div class="bg-[#0B0F19] rounded-2xl border border-slate-800 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-900/80 border-b border-slate-800 text-slate-400 uppercase tracking-wider font-mono text-[11px]">
                    <tr>
                        <th class="py-3.5 px-4">Invoice & Waktu</th>
                        <th class="py-3.5 px-4">Pelanggan</th>
                        <th class="py-3.5 px-4">Kasir</th>
                        <th class="py-3.5 px-4">Item Transaksi</th>
                        <th class="py-3.5 px-4">Metode</th>
                        <th class="py-3.5 px-4 text-right">Total Tagihan</th>
                        <th class="py-3.5 px-4 text-center">Status</th>
                        <th class="py-3.5 px-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/80">
                    @forelse($orders as $ord)
                        <tr class="hover:bg-slate-900/50 transition-colors {{ $ord->status === 'cancelled' ? 'opacity-50 line-through' : '' }}">
                            <td class="py-3.5 px-4 whitespace-nowrap">
                                <span class="font-mono font-bold text-white text-xs block">{{ $ord->invoice_no }}</span>
                                <span class="text-[11px] text-slate-400">{{ $ord->created_at->format('d M Y, H:i') }}</span>
                            </td>

                            <td class="py-3.5 px-4">
                                <span class="font-semibold text-white block">{{ $ord->customer_name }}</span>
                                <span class="text-[10px] text-slate-400 uppercase">{{ $ord->customer_type }}</span>
                            </td>

                            <td class="py-3.5 px-4 text-slate-300">
                                {{ $ord->user ? $ord->user->name : '-' }}
                            </td>

                            <td class="py-3.5 px-4">
                                <div class="max-w-xs space-y-0.5">
                                    @foreach($ord->items as $it)
                                        <div class="text-[11px] text-slate-300 truncate">
                                            <span class="text-emerald-400 font-bold font-mono">{{ $it->quantity }}x</span> {{ $it->name }}
                                        </div>
                                    @endforeach
                                </div>
                            </td>

                            <td class="py-3.5 px-4 whitespace-nowrap">
                                <span class="inline-flex px-2 py-0.5 rounded text-[10px] font-bold uppercase bg-slate-800 text-slate-300 border border-slate-700">
                                    {{ $ord->payment_method }}
                                </span>
                            </td>

                            <td class="py-3.5 px-4 text-right font-mono font-bold text-white text-sm whitespace-nowrap">
                                Rp {{ number_format($ord->total_amount, 0, ',', '.') }}
                            </td>

                            <td class="py-3.5 px-4 text-center whitespace-nowrap">
                                @if($ord->status === 'completed')
                                    <span class="inline-flex px-2 py-0.5 rounded-full text-[10px] font-bold uppercase bg-emerald-500/20 text-emerald-400 border border-emerald-500/30">
                                        Selesai
                                    </span>
                                @else
                                    <span class="inline-flex px-2 py-0.5 rounded-full text-[10px] font-bold uppercase bg-rose-500/20 text-rose-400 border border-rose-500/30">
                                        Dibatalkan
                                    </span>
                                @endif
                            </td>

                            <td class="py-3.5 px-4 text-right whitespace-nowrap">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('pos.receipt', $ord->id) }}" target="_blank"
                                       title="Cetak Struk"
                                       class="p-2 rounded-lg bg-slate-800 hover:bg-slate-700 text-slate-300 hover:text-white transition-colors">
                                        <i class="fa-solid fa-print"></i>
                                    </a>

                                    @if(Auth::user()->isOwner() && $ord->status === 'completed')
                                        <form method="POST" action="{{ route('pos.orders.cancel', $ord->id) }}" 
                                              onsubmit="return confirm('Apakah Anda yakin ingin membatalkan transaksi #{{ $ord->invoice_no }}? Stok suplemen yang terjual akan otomatis dikembalikan ke inventori.')">
                                            @csrf
                                            <button type="submit" title="Batalkan Transaksi (Owner)"
                                                    class="p-2 rounded-lg bg-rose-500/10 hover:bg-rose-500/20 text-rose-400 border border-rose-500/30 transition-colors">
                                                <i class="fa-solid fa-ban"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-12 text-slate-500 text-xs">
                                Tidak ada transaksi yang sesuai dengan kriteria filter.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($orders->hasPages())
            <div class="p-4 border-t border-slate-800">
                {{ $orders->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
