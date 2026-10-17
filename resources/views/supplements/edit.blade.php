@extends('layouts.app')

@section('title', 'Edit Suplemen')
@section('page-title', 'Edit Data Suplemen')
@section('page-subtitle', 'Perbarui detail produk suplemen #' . $supplement->sku)

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="bg-[#0B0F19] rounded-2xl border border-slate-800 p-6 sm:p-8">
        
        <form method="POST" action="{{ route('supplements.update', $supplement->id) }}" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <!-- SKU / Barcode -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5">SKU / Kode Produk</label>
                    <input type="text" name="sku" value="{{ old('sku', $supplement->sku) }}" required
                           class="w-full px-3.5 py-2.5 rounded-xl bg-slate-900 border border-slate-700 text-white font-mono text-sm focus:outline-none focus:border-emerald-500">
                </div>

                <!-- Category -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5">Kategori Suplemen</label>
                    <select name="category_id" required class="select2 w-full px-3.5 py-2.5 rounded-xl bg-slate-900 border border-slate-700 text-white text-sm focus:outline-none focus:border-emerald-500">
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('category_id', $supplement->category_id) == $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Product Name -->
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5">Nama Produk Lengkap</label>
                <input type="text" name="name" value="{{ old('name', $supplement->name) }}" required
                       class="w-full px-3.5 py-2.5 rounded-xl bg-slate-900 border border-slate-700 text-white text-sm focus:outline-none focus:border-emerald-500">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <!-- Brand -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5">Merk / Brand</label>
                    <input type="text" name="brand" value="{{ old('brand', $supplement->brand) }}"
                           class="w-full px-3.5 py-2.5 rounded-xl bg-slate-900 border border-slate-700 text-white text-sm focus:outline-none focus:border-emerald-500">
                </div>

                <!-- Unit -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5">Satuan Kemasan</label>
                    <input type="text" name="unit" value="{{ old('unit', $supplement->unit) }}" required
                           class="w-full px-3.5 py-2.5 rounded-xl bg-slate-900 border border-slate-700 text-white text-sm focus:outline-none focus:border-emerald-500">
                </div>

                <!-- Flavor / Variant -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5">Rasa / Varian</label>
                    <input type="text" name="flavor" value="{{ old('flavor', $supplement->flavor) }}"
                           class="w-full px-3.5 py-2.5 rounded-xl bg-slate-900 border border-slate-700 text-white text-sm focus:outline-none focus:border-emerald-500">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <!-- Purchase Price -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5">Harga Beli / Modal (Rp)</label>
                    <input type="number" name="purchase_price" value="{{ old('purchase_price', (int)$supplement->purchase_price) }}" min="0" required
                           class="w-full px-3.5 py-2.5 rounded-xl bg-slate-900 border border-slate-700 text-white font-mono text-sm focus:outline-none focus:border-emerald-500">
                </div>

                <!-- Selling Price -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5">Harga Jual Kasir (Rp)</label>
                    <input type="number" name="selling_price" value="{{ old('selling_price', (int)$supplement->selling_price) }}" min="0" required
                           class="w-full px-3.5 py-2.5 rounded-xl bg-slate-900 border border-slate-700 text-white font-mono text-sm focus:outline-none focus:border-emerald-500">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <!-- Min Stock Alert -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5">Peringatan Stok Minimum</label>
                    <input type="number" name="min_stock_alert" value="{{ old('min_stock_alert', $supplement->min_stock_alert) }}" min="0" required
                           class="w-full px-3.5 py-2.5 rounded-xl bg-slate-900 border border-slate-700 text-white font-mono text-sm focus:outline-none focus:border-emerald-500">
                </div>

                <!-- Expiry Date -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5">Tanggal Kadaluarsa (Exp)</label>
                    <input type="date" name="expiry_date" value="{{ old('expiry_date', $supplement->expiry_date ? $supplement->expiry_date->format('Y-m-d') : '') }}"
                           class="w-full px-3.5 py-2.5 rounded-xl bg-slate-900 border border-slate-700 text-white text-sm focus:outline-none focus:border-emerald-500">
                </div>
            </div>

            <!-- Description -->
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5">Deskripsi / Aturan Pakai</label>
                <textarea name="description" rows="3"
                          class="w-full px-3.5 py-2.5 rounded-xl bg-slate-900 border border-slate-700 text-white text-sm focus:outline-none focus:border-emerald-500">{{ old('description', $supplement->description) }}</textarea>
            </div>

            <!-- Active Status -->
            <div class="flex items-center gap-3">
                <input type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', $supplement->is_active) ? 'checked' : '' }}
                       class="w-4 h-4 rounded border-slate-700 bg-slate-900 text-emerald-500 focus:ring-emerald-500">
                <label for="is_active" class="text-xs text-slate-300 font-bold cursor-pointer">Produk Aktif & Ditampilkan di Kasir POS</label>
            </div>

            <!-- Actions -->
            <div class="pt-4 border-t border-slate-800 flex items-center justify-end gap-3">
                <a href="{{ route('supplements.index') }}" class="px-5 py-2.5 rounded-xl bg-slate-900 hover:bg-slate-800 border border-slate-800 text-slate-400 hover:text-white text-xs font-bold transition-colors">
                    Batal
                </a>
                <button type="submit" 
                        class="px-6 py-2.5 rounded-xl bg-gradient-to-r from-emerald-500 to-teal-400 hover:from-emerald-400 hover:to-teal-300 text-slate-950 font-black text-xs uppercase tracking-wider shadow-lg shadow-emerald-500/25 transition-all">
                    Simpan Perubahan
                </button>
            </div>
        </form>

    </div>
</div>
@endsection
