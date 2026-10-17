@extends('layouts.app')

@section('title', 'Katalog Suplemen & Stok')
@section('page-title', 'Katalog Suplemen Fitness & Manajemen Stok')
@section('page-subtitle', 'Monitoring stok realtime, peringatan menipis/kadaluarsa, dan restock barang')

@section('content')
<div class="space-y-6" x-data="{ 
    showRestockModal: false, 
    showAdjustModal: false,
    selectedProductId: null,
    selectedProductName: '',
    selectedProductStock: 0,
    selectedProductUnit: '',
    restockQty: 10,
    restockCost: 0,
    restockNotes: '',
    actualStock: 0,
    adjustReason: 'manual_audit',
    adjustNotes: ''
}">

    <!-- Inventory Stat Cards -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
        <!-- Card 1 -->
        <a href="{{ route('supplements.index') }}" 
           class="p-4 rounded-2xl bg-[#0B0F19] border border-slate-800 hover:border-slate-700 transition-all block">
            <span class="text-xs font-bold uppercase tracking-wider text-slate-400 block mb-1">Total Produk Suplemen</span>
            <div class="text-2xl font-black text-white font-mono">{{ $totalProducts }}</div>
            <p class="text-[11px] text-slate-400 mt-1">Item aktif di katalog</p>
        </a>

        <!-- Card 2: Low Stock -->
        <a href="{{ route('supplements.index', ['status' => 'low_stock']) }}" 
           class="p-4 rounded-2xl bg-[#0B0F19] border border-amber-500/30 hover:border-amber-500/60 transition-all block">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold uppercase tracking-wider text-amber-400 block mb-1">Stok Menipis</span>
                <i class="fa-solid fa-triangle-exclamation text-amber-400"></i>
            </div>
            <div class="text-2xl font-black text-amber-300 font-mono">{{ $lowStockCount }}</div>
            <p class="text-[11px] text-amber-400/80 mt-1">&le; Batas Minimum Stok</p>
        </a>

        <!-- Card 3: Out of Stock -->
        <a href="{{ route('supplements.index', ['status' => 'out_of_stock']) }}" 
           class="p-4 rounded-2xl bg-[#0B0F19] border border-rose-500/30 hover:border-rose-500/60 transition-all block">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold uppercase tracking-wider text-rose-400 block mb-1">Stok Habis (Kosong)</span>
                <i class="fa-solid fa-circle-xmark text-rose-400"></i>
            </div>
            <div class="text-2xl font-black text-rose-400 font-mono">{{ $outOfStockCount }}</div>
            <p class="text-[11px] text-rose-400/80 mt-1">Perlu segera restock</p>
        </a>

        <!-- Card 4: Expiring Soon -->
        <a href="{{ route('supplements.index', ['status' => 'expiring_soon']) }}" 
           class="p-4 rounded-2xl bg-[#0B0F19] border border-purple-500/30 hover:border-purple-500/60 transition-all block">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold uppercase tracking-wider text-purple-400 block mb-1">Kadaluarsa &lt; 30 Hari</span>
                <i class="fa-solid fa-clock text-purple-400"></i>
            </div>
            <div class="text-2xl font-black text-purple-400 font-mono">{{ $expiringCount }}</div>
            <p class="text-[11px] text-purple-400/80 mt-1">Pantau perputaran barang</p>
        </a>
    </div>

    <!-- Actions and Filters Bar -->
    <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
        <!-- Filter Form -->
        <form method="GET" action="{{ route('supplements.index') }}" class="flex-1 w-full flex flex-wrap items-center gap-2.5">
            <!-- Search -->
            <div class="relative flex-1 min-w-[200px]">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-500">
                    <i class="fa-solid fa-magnifying-glass text-xs"></i>
                </div>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama, SKU, atau merk suplemen..."
                       class="w-full pl-9 pr-3 py-2 rounded-xl bg-slate-900 border border-slate-700/80 text-white text-xs placeholder-slate-500 focus:outline-none focus:border-emerald-500">
            </div>

            <!-- Category -->
            <select name="category_id" class="select2 min-w-[160px] px-3 py-2 rounded-xl bg-slate-900 border border-slate-700/80 text-white text-xs focus:outline-none focus:border-emerald-500">
                <option value="">Semua Kategori</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                        {{ $cat->name }}
                    </option>
                @endforeach
            </select>

            <!-- Status Filter -->
            <select name="status" class="select2 min-w-[160px] px-3 py-2 rounded-xl bg-slate-900 border border-slate-700/80 text-white text-xs focus:outline-none focus:border-emerald-500">
                <option value="">Semua Status Stok</option>
                <option value="in_stock" {{ request('status') === 'in_stock' ? 'selected' : '' }}>Stok Aman</option>
                <option value="low_stock" {{ request('status') === 'low_stock' ? 'selected' : '' }}>Stok Menipis</option>
                <option value="out_of_stock" {{ request('status') === 'out_of_stock' ? 'selected' : '' }}>Stok Habis</option>
                <option value="expiring_soon" {{ request('status') === 'expiring_soon' ? 'selected' : '' }}>Mendekati Kadaluarsa</option>
            </select>

            <button type="submit" class="px-3.5 py-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-white text-xs font-bold transition-colors">
                Terapkan
            </button>
            
            @if(request()->anyFilled(['search', 'category_id', 'status']))
                <a href="{{ route('supplements.index') }}" class="px-3 py-2 rounded-xl bg-slate-900 text-slate-400 hover:text-white border border-slate-800 text-xs">
                    Reset
                </a>
            @endif
        </form>

        <!-- Right Buttons -->
        <div class="flex items-center gap-2.5 w-full sm:w-auto shrink-0">
            <a href="{{ route('supplements.history') }}" 
               class="px-3.5 py-2 rounded-xl bg-slate-900 hover:bg-slate-800 border border-slate-800 text-slate-300 hover:text-white text-xs font-bold flex items-center gap-2 transition-colors">
                <i class="fa-solid fa-clock-rotate-left"></i>
                <span>Riwayat Mutasi</span>
            </a>

            <a href="{{ route('supplements.create') }}" 
               class="px-4 py-2 rounded-xl bg-gradient-to-r from-emerald-500 to-teal-400 hover:from-emerald-400 hover:to-teal-300 text-slate-950 font-black text-xs flex items-center gap-2 shadow-lg shadow-emerald-500/20 transition-all hover:scale-105 active:scale-95">
                <i class="fa-solid fa-plus"></i>
                <span>Tambah Suplemen</span>
            </a>
        </div>
    </div>

    <!-- Products Table -->
    <div class="bg-[#0B0F19] rounded-2xl border border-slate-800 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-900/80 border-b border-slate-800 text-slate-400 uppercase tracking-wider font-mono text-[11px]">
                    <tr>
                        <th class="py-3.5 px-4">SKU & Produk</th>
                        <th class="py-3.5 px-4">Kategori & Brand</th>
                        <th class="py-3.5 px-4">Varian / Satuan</th>
                        @if(Auth::user()->isOwner())
                            <th class="py-3.5 px-4 text-right">Harga Modal</th>
                        @endif
                        <th class="py-3.5 px-4 text-right">Harga Jual</th>
                        <th class="py-3.5 px-4 text-center">Stok Fisik</th>
                        <th class="py-3.5 px-4">Kadaluarsa</th>
                        <th class="py-3.5 px-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/80">
                    @forelse($supplements as $item)
                        <tr class="hover:bg-slate-900/50 transition-colors">
                            <td class="py-3.5 px-4">
                                <span class="font-mono text-[11px] font-bold text-slate-400 block">{{ $item->sku }}</span>
                                <span class="font-bold text-white text-sm block mt-0.5">{{ $item->name }}</span>
                            </td>

                            <td class="py-3.5 px-4">
                                <span class="inline-flex px-2 py-0.5 rounded text-[10px] font-bold bg-slate-800 text-emerald-400 border border-slate-700">
                                    {{ $item->category ? $item->category->name : '-' }}
                                </span>
                                <span class="block text-slate-400 text-xs mt-1 font-medium">{{ $item->brand ?: 'FitZone Brand' }}</span>
                            </td>

                            <td class="py-3.5 px-4 text-slate-300">
                                <span class="font-semibold">{{ $item->unit }}</span>
                                @if($item->flavor)
                                    <span class="block text-slate-400 text-[11px]">{{ $item->flavor }}</span>
                                @endif
                            </td>

                            @if(Auth::user()->isOwner())
                                <td class="py-3.5 px-4 text-right font-mono text-slate-400">
                                    Rp {{ number_format($item->purchase_price, 0, ',', '.') }}
                                </td>
                            @endif

                            <td class="py-3.5 px-4 text-right font-mono font-bold text-emerald-400 text-sm">
                                Rp {{ number_format($item->selling_price, 0, ',', '.') }}
                            </td>

                            <td class="py-3.5 px-4 text-center whitespace-nowrap">
                                @if($item->isOutOfStock())
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-extrabold uppercase bg-rose-500/20 text-rose-400 border border-rose-500/30">
                                        <span class="w-2 h-2 rounded-full bg-rose-400"></span>
                                        Habis (0)
                                    </span>
                                @elseif($item->isLowStock())
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-extrabold uppercase bg-amber-500/20 text-amber-300 border border-amber-500/30">
                                        <span class="w-2 h-2 rounded-full bg-amber-400 animate-pulse"></span>
                                        Sisa: {{ $item->stock }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-500/20 text-emerald-400 border border-emerald-500/30">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
                                        {{ $item->stock }} {{ $item->unit }}
                                    </span>
                                @endif
                            </td>

                            <td class="py-3.5 px-4 whitespace-nowrap">
                                @if($item->expiry_date)
                                    <span class="font-mono text-xs {{ $item->isExpiringSoon() ? 'text-purple-400 font-bold' : ($item->isExpired() ? 'text-rose-400 font-bold line-through' : 'text-slate-300') }}">
                                        {{ $item->expiry_date->format('d/m/Y') }}
                                    </span>
                                    @if($item->isExpiringSoon())
                                        <span class="block text-[10px] text-purple-400 font-semibold">&lt; 30 Hari</span>
                                    @endif
                                @else
                                    <span class="text-slate-500">-</span>
                                @endif
                            </td>

                            <td class="py-3.5 px-4 text-right whitespace-nowrap">
                                <div class="flex items-center justify-end gap-1.5">
                                    <!-- Restock Button -->
                                    <button type="button" 
                                            @click="selectedProductId = {{ $item->id }}; selectedProductName = '{{ addslashes($item->name) }}'; selectedProductStock = {{ $item->stock }}; selectedProductUnit = '{{ $item->unit }}'; restockCost = {{ $item->purchase_price }}; showRestockModal = true"
                                            class="px-2.5 py-1.5 rounded-lg bg-emerald-500/10 hover:bg-emerald-500/20 text-emerald-300 border border-emerald-500/30 font-bold text-xs flex items-center gap-1 transition-colors"
                                            title="Tambah Stok Masuk (Restock)">
                                        <i class="fa-solid fa-arrow-down-to-bracket text-[10px]"></i>
                                        <span>Restock</span>
                                    </button>

                                    <!-- Adjust Stock / Opname -->
                                    <button type="button" 
                                            @click="selectedProductId = {{ $item->id }}; selectedProductName = '{{ addslashes($item->name) }}'; selectedProductStock = {{ $item->stock }}; selectedProductUnit = '{{ $item->unit }}'; actualStock = {{ $item->stock }}; showAdjustModal = true"
                                            class="px-2.5 py-1.5 rounded-lg bg-cyan-500/10 hover:bg-cyan-500/20 text-cyan-300 border border-cyan-500/30 font-bold text-xs flex items-center gap-1 transition-colors"
                                            title="Penyesuaian Fisik / Stock Opname">
                                        <i class="fa-solid fa-sliders text-[10px]"></i>
                                        <span>Opname</span>
                                    </button>

                                    <!-- Edit -->
                                    <a href="{{ route('supplements.edit', $item->id) }}" 
                                       class="p-1.5 rounded-lg bg-slate-800 hover:bg-slate-700 text-slate-300 hover:text-white transition-colors"
                                       title="Edit Data">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </a>

                                    @if(Auth::user()->isOwner())
                                        <!-- Delete (Owner only) -->
                                        <form method="POST" action="{{ route('supplements.destroy', $item->id) }}" 
                                              onsubmit="return confirm('Apakah Anda yakin ingin menghapus suplemen \'{{ $item->name }}\'?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="p-1.5 rounded-lg bg-rose-500/10 hover:bg-rose-500/20 text-rose-400 border border-rose-500/30 transition-colors"
                                                    title="Hapus Suplemen">
                                                <i class="fa-solid fa-trash-can"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-12 text-slate-500 text-xs">
                                Belum ada data suplemen yang cocok dengan pencarian / filter.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($supplements->hasPages())
            <div class="p-4 border-t border-slate-800">
                {{ $supplements->links() }}
            </div>
        @endif
    </div>

    <!-- MODAL 1: RESTOCK MODAL -->
    <div x-show="showRestockModal" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/80 backdrop-blur-md" style="display: none;">
        
        <div class="bg-slate-950 border border-slate-800 rounded-3xl max-w-md w-full p-6 shadow-2xl relative" @click.away="showRestockModal = false">
            <div class="flex items-center justify-between pb-4 border-b border-slate-800">
                <div>
                    <h3 class="text-base font-bold text-white flex items-center gap-2">
                        <i class="fa-solid fa-arrow-down-to-bracket text-emerald-400"></i>
                        <span>Restock / Barang Masuk</span>
                    </h3>
                    <p class="text-xs text-slate-400 mt-0.5" x-text="selectedProductName"></p>
                </div>
                <button @click="showRestockModal = false" class="text-slate-400 hover:text-white">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <form :action="'{{ url('supplements') }}/' + (selectedProductId || '') + '/restock'" method="POST" class="space-y-4 mt-4">
                @csrf
                
                <div class="p-3 rounded-xl bg-slate-900 border border-slate-800 text-xs flex justify-between items-center">
                    <span class="text-slate-400">Stok Saat Ini:</span>
                    <span class="font-mono font-bold text-emerald-400 text-sm" x-text="selectedProductStock + ' ' + selectedProductUnit"></span>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5">Jumlah Tambah Stok (Qty Masuk)</label>
                    <input type="number" name="quantity" x-model.number="restockQty" min="1" required
                           class="w-full px-3 py-2 rounded-xl bg-slate-900 border border-slate-700 text-white font-mono text-sm focus:outline-none focus:border-emerald-500">
                </div>

                @if(Auth::user()->isOwner())
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5">Harga Beli / Modal Baru per Unit (Rp)</label>
                        <input type="number" name="cost_per_unit" x-model.number="restockCost" min="0"
                               class="w-full px-3 py-2 rounded-xl bg-slate-900 border border-slate-700 text-white font-mono text-sm focus:outline-none focus:border-emerald-500">
                        <span class="text-[10px] text-slate-500 mt-1 block">Biarkan jika harga modal tidak berubah.</span>
                    </div>
                @endif

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5">Keterangan / Supplier</label>
                    <input type="text" name="supplier_notes" x-model="restockNotes" placeholder="misal: Supplier PT Suplemen Sehat, PO #892"
                           class="w-full px-3 py-2 rounded-xl bg-slate-900 border border-slate-700 text-white text-xs focus:outline-none focus:border-emerald-500">
                </div>

                <div class="pt-3 border-t border-slate-800 flex items-center justify-end gap-2.5">
                    <button type="button" @click="showRestockModal = false" class="px-4 py-2 rounded-xl bg-slate-900 hover:bg-slate-800 text-slate-400 text-xs font-bold">
                        Batal
                    </button>
                    <button type="submit" class="px-5 py-2.5 rounded-xl bg-emerald-500 hover:bg-emerald-400 text-slate-950 text-xs font-black uppercase tracking-wider shadow-lg shadow-emerald-500/20">
                        Simpan Restock
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL 2: ADJUST STOCK / OPNAME -->
    <div x-show="showAdjustModal" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/80 backdrop-blur-md" style="display: none;">
        
        <div class="bg-slate-950 border border-slate-800 rounded-3xl max-w-md w-full p-6 shadow-2xl relative" @click.away="showAdjustModal = false">
            <div class="flex items-center justify-between pb-4 border-b border-slate-800">
                <div>
                    <h3 class="text-base font-bold text-white flex items-center gap-2">
                        <i class="fa-solid fa-sliders text-cyan-400"></i>
                        <span>Penyesuaian Fisik (Stock Opname)</span>
                    </h3>
                    <p class="text-xs text-slate-400 mt-0.5" x-text="selectedProductName"></p>
                </div>
                <button @click="showAdjustModal = false" class="text-slate-400 hover:text-white">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <form :action="'{{ url('supplements') }}/' + (selectedProductId || '') + '/adjust-stock'" method="POST" class="space-y-4 mt-4">
                @csrf
                
                <div class="p-3 rounded-xl bg-slate-900 border border-slate-800 text-xs flex justify-between items-center">
                    <span class="text-slate-400">Stok Sistem Saat Ini:</span>
                    <span class="font-mono font-bold text-white" x-text="selectedProductStock + ' ' + selectedProductUnit"></span>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5">Jumlah Stok Fisik Sebenarnya</label>
                    <input type="number" name="actual_stock" x-model.number="actualStock" min="0" required
                           class="w-full px-3 py-2 rounded-xl bg-slate-900 border border-slate-700 text-white font-mono text-sm focus:outline-none focus:border-cyan-500">
                    <div class="mt-1.5 text-xs font-mono">
                        <span>Selisih: </span>
                        <span :class="(actualStock - selectedProductStock) >= 0 ? 'text-emerald-400 font-bold' : 'text-rose-400 font-bold'"
                              x-text="(actualStock - selectedProductStock) > 0 ? '+' + (actualStock - selectedProductStock) : (actualStock - selectedProductStock)"></span>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5">Alasan Penyesuaian</label>
                    <select name="reason" x-model="adjustReason" class="select2 w-full px-3 py-2 rounded-xl bg-slate-900 border border-slate-700 text-white text-xs focus:outline-none focus:border-cyan-500">
                        <option value="manual_audit">Selisih Hitung / Audit Fisik Berkala</option>
                        <option value="damage">Barang Rusak / Bocor / Cacat Pabrik</option>
                        <option value="expired">Barang Kadaluarsa / Expired</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5">Catatan Penjelasan</label>
                    <input type="text" name="notes" x-model="adjustNotes" required placeholder="Contoh: Ditemukan 1 botol segel robek saat stock opname mingguan"
                           class="w-full px-3 py-2 rounded-xl bg-slate-900 border border-slate-700 text-white text-xs focus:outline-none focus:border-cyan-500">
                </div>

                <div class="pt-3 border-t border-slate-800 flex items-center justify-end gap-2.5">
                    <button type="button" @click="showAdjustModal = false" class="px-4 py-2 rounded-xl bg-slate-900 hover:bg-slate-800 text-slate-400 text-xs font-bold">
                        Batal
                    </button>
                    <button type="submit" class="px-5 py-2.5 rounded-xl bg-cyan-500 hover:bg-cyan-400 text-slate-950 text-xs font-black uppercase tracking-wider shadow-lg shadow-cyan-500/20">
                        Perbarui Stok Fisik
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
