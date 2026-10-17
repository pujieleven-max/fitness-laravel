@extends('layouts.app')

@section('title', 'Riwayat Mutasi Stok')
@section('page-title', 'Kartu Riwayat Mutasi Stok Suplemen')
@section('page-subtitle', 'Log audit persediaan: barang masuk, penjualan POS, dan penyesuaian fisik')

@section('content')
<div class="space-y-6">

    <!-- Filter Bar -->
    <div class="p-4 rounded-2xl bg-[#0B0F19] border border-slate-800">
        <form method="GET" action="{{ route('supplements.history') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3">
            
            <!-- Supplement Picker -->
            <div>
                <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-400 mb-1">Pilih Produk</label>
                <select name="supplement_id" class="select2 w-full px-3 py-2 rounded-xl bg-slate-900 border border-slate-700 text-white text-xs focus:outline-none focus:border-emerald-500">
                    <option value="">Semua Suplemen</option>
                    @foreach($supplements as $s)
                        <option value="{{ $s->id }}" {{ request('supplement_id') == $s->id ? 'selected' : '' }}>
                            {{ $s->name }} ({{ $s->sku }})
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Type -->
            <div>
                <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-400 mb-1">Arah Mutasi</label>
                <select name="type" class="select2 w-full px-3 py-2 rounded-xl bg-slate-900 border border-slate-700 text-white text-xs focus:outline-none focus:border-emerald-500">
                    <option value="">Semua Arah</option>
                    <option value="in" {{ request('type') === 'in' ? 'selected' : '' }}>Masuk (Inflow / Restock)</option>
                    <option value="out" {{ request('type') === 'out' ? 'selected' : '' }}>Keluar (Outflow / Penjualan)</option>
                    <option value="adjustment" {{ request('type') === 'adjustment' ? 'selected' : '' }}>Penyesuaian (Opname)</option>
                </select>
            </div>

            <!-- Reference Type -->
            <div>
                <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-400 mb-1">Jenis Aktivitas</label>
                <select name="reference_type" class="select2 w-full px-3 py-2 rounded-xl bg-slate-900 border border-slate-700 text-white text-xs focus:outline-none focus:border-emerald-500">
                    <option value="">Semua Aktivitas</option>
                    <option value="pos_sale" {{ request('reference_type') === 'pos_sale' ? 'selected' : '' }}>Penjualan Kasir POS</option>
                    <option value="restock" {{ request('reference_type') === 'restock' ? 'selected' : '' }}>Restock Supplier</option>
                    <option value="manual_audit" {{ request('reference_type') === 'manual_audit' ? 'selected' : '' }}>Audit Stock Opname</option>
                    <option value="damage" {{ request('reference_type') === 'damage' ? 'selected' : '' }}>Barang Rusak</option>
                    <option value="expired" {{ request('reference_type') === 'expired' ? 'selected' : '' }}>Kadaluarsa</option>
                </select>
            </div>

            <!-- Date -->
            <div>
                <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-400 mb-1">Tanggal</label>
                <input type="date" name="date" value="{{ request('date') }}"
                       class="w-full px-3 py-2 rounded-xl bg-slate-900 border border-slate-700 text-white text-xs focus:outline-none focus:border-emerald-500">
            </div>

            <!-- Submit -->
            <div class="flex items-end gap-2">
                <button type="submit" class="flex-1 py-2 px-3 rounded-xl bg-slate-800 hover:bg-slate-700 text-white font-bold text-xs flex items-center justify-center gap-1.5 transition-colors">
                    <i class="fa-solid fa-filter"></i>
                    <span>Filter</span>
                </button>
                <a href="{{ route('supplements.history') }}" class="py-2 px-3 rounded-xl bg-slate-900 text-slate-400 hover:text-white border border-slate-800 text-xs">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Mutation History Table -->
    <div class="bg-[#0B0F19] rounded-2xl border border-slate-800 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-900/80 border-b border-slate-800 text-slate-400 uppercase tracking-wider font-mono text-[11px]">
                    <tr>
                        <th class="py-3.5 px-4">Waktu</th>
                        <th class="py-3.5 px-4">Produk Suplemen</th>
                        <th class="py-3.5 px-4 text-center">Tipe</th>
                        <th class="py-3.5 px-4 text-center">Jumlah</th>
                        <th class="py-3.5 px-4 text-center">Sebelum &rarr; Sesudah</th>
                        <th class="py-3.5 px-4">Referensi & Keterangan</th>
                        <th class="py-3.5 px-4">Operator</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/80">
                    @forelse($mutations as $m)
                        <tr class="hover:bg-slate-900/50 transition-colors">
                            <td class="py-3.5 px-4 whitespace-nowrap text-slate-300 font-mono">
                                {{ $m->created_at->format('d/m/Y H:i') }}
                            </td>

                            <td class="py-3.5 px-4">
                                <span class="font-bold text-white block">{{ $m->supplement ? $m->supplement->name : '-' }}</span>
                                <span class="font-mono text-[10px] text-slate-400">{{ $m->supplement ? $m->supplement->sku : '' }} &bull; {{ $m->supplement ? $m->supplement->unit : '' }}</span>
                            </td>

                            <td class="py-3.5 px-4 text-center whitespace-nowrap">
                                @if($m->type === 'in')
                                    <span class="inline-flex px-2 py-0.5 rounded text-[10px] font-bold uppercase bg-emerald-500/20 text-emerald-400 border border-emerald-500/30">
                                        Masuk
                                    </span>
                                @elseif($m->type === 'out')
                                    <span class="inline-flex px-2 py-0.5 rounded text-[10px] font-bold uppercase bg-rose-500/20 text-rose-400 border border-rose-500/30">
                                        Keluar
                                    </span>
                                @else
                                    <span class="inline-flex px-2 py-0.5 rounded text-[10px] font-bold uppercase bg-cyan-500/20 text-cyan-400 border border-cyan-500/30">
                                        Opname
                                    </span>
                                @endif
                            </td>

                            <td class="py-3.5 px-4 text-center font-mono font-bold whitespace-nowrap">
                                @if($m->type === 'in')
                                    <span class="text-emerald-400">+{{ $m->quantity }}</span>
                                @elseif($m->type === 'out')
                                    <span class="text-rose-400">-{{ $m->quantity }}</span>
                                @else
                                    <span class="text-cyan-400">{{ $m->quantity }}</span>
                                @endif
                            </td>

                            <td class="py-3.5 px-4 text-center font-mono text-slate-300 whitespace-nowrap">
                                <span>{{ $m->stock_before }}</span>
                                <span class="text-slate-500 mx-1">&rarr;</span>
                                <span class="font-bold text-white">{{ $m->stock_after }}</span>
                            </td>

                            <td class="py-3.5 px-4">
                                <span class="font-semibold text-slate-200 block text-[11px]">{{ $m->notes ?: '-' }}</span>
                                @if($m->reference_id)
                                    <span class="font-mono text-[10px] text-slate-400">{{ $m->reference_id }}</span>
                                @endif
                            </td>

                            <td class="py-3.5 px-4 text-slate-400 whitespace-nowrap">
                                {{ $m->user ? $m->user->name : 'System' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-12 text-slate-500 text-xs">
                                Belum ada riwayat mutasi stok yang tercatat sesuai filter.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($mutations->hasPages())
            <div class="p-4 border-t border-slate-800">
                {{ $mutations->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
