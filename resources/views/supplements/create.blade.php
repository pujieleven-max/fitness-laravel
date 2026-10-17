@extends('layouts.app')

@section('title', 'Tambah Suplemen Baru')
@section('page-title', 'Tambah Suplemen Baru')
@section('page-subtitle', 'Masukkan produk suplemen ke inventori katalog gym')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="bg-[#0B0F19] rounded-2xl border border-slate-800 p-6 sm:p-8">
        
        <form method="POST" action="{{ route('supplements.store') }}" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <!-- SKU / Barcode -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5">SKU / Kode Produk</label>
                    <input type="text" name="sku" value="{{ old('sku', $suggestedSku) }}" required
                           class="w-full px-3.5 py-2.5 rounded-xl bg-slate-900 border border-slate-700 text-white font-mono text-sm focus:outline-none focus:border-emerald-500">
                </div>

                <!-- Category -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5">Kategori Suplemen</label>
                    <select name="category_id" required class="select2 w-full px-3.5 py-2.5 rounded-xl bg-slate-900 border border-slate-700 text-white text-sm focus:outline-none focus:border-emerald-500">
                        <option value="">-- Pilih Kategori --</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Product Name -->
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5">Nama Produk Lengkap</label>
                <input type="text" name="name" value="{{ old('name') }}" required placeholder="Contoh: ON Gold Standard 100% Whey 5 lbs"
                       class="w-full px-3.5 py-2.5 rounded-xl bg-slate-900 border border-slate-700 text-white text-sm focus:outline-none focus:border-emerald-500">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <!-- Brand -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5">Merk / Brand</label>
                    <input type="text" name="brand" value="{{ old('brand') }}" placeholder="Contoh: Optimum Nutrition"
                           class="w-full px-3.5 py-2.5 rounded-xl bg-slate-900 border border-slate-700 text-white text-sm focus:outline-none focus:border-emerald-500">
                </div>

                <!-- Unit -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5">Satuan Kemasan</label>
                    <input type="text" name="unit" value="{{ old('unit', 'Tub 5 lbs') }}" required placeholder="Tub / Botol / Sachet / Kaleng"
                           class="w-full px-3.5 py-2.5 rounded-xl bg-slate-900 border border-slate-700 text-white text-sm focus:outline-none focus:border-emerald-500">
                </div>

                <!-- Flavor / Variant -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5">Rasa / Varian</label>
                    <input type="text" name="flavor" value="{{ old('flavor') }}" placeholder="Contoh: Double Rich Chocolate"
                           class="w-full px-3.5 py-2.5 rounded-xl bg-slate-900 border border-slate-700 text-white text-sm focus:outline-none focus:border-emerald-500">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <!-- Purchase Price -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5">Harga Beli / Modal (Rp)</label>
                    <input type="number" name="purchase_price" value="{{ old('purchase_price', 0) }}" min="0" required
                           class="w-full px-3.5 py-2.5 rounded-xl bg-slate-900 border border-slate-700 text-white font-mono text-sm focus:outline-none focus:border-emerald-500">
                </div>

                <!-- Selling Price -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5">Harga Jual Kasir (Rp)</label>
                    <input type="number" name="selling_price" value="{{ old('selling_price') }}" min="0" required
                           class="w-full px-3.5 py-2.5 rounded-xl bg-slate-900 border border-slate-700 text-white font-mono text-sm focus:outline-none focus:border-emerald-500">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <!-- Initial Stock -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5">Stok Awal Fisik</label>
                    <input type="number" name="stock" value="{{ old('stock', 10) }}" min="0" required
                           class="w-full px-3.5 py-2.5 rounded-xl bg-slate-900 border border-slate-700 text-white font-mono text-sm focus:outline-none focus:border-emerald-500">
                </div>

                <!-- Min Stock Alert -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5">Peringatan Stok Minimum</label>
                    <input type="number" name="min_stock_alert" value="{{ old('min_stock_alert', 5) }}" min="0" required
                           class="w-full px-3.5 py-2.5 rounded-xl bg-slate-900 border border-slate-700 text-white font-mono text-sm focus:outline-none focus:border-emerald-500">
                </div>

                <!-- Expiry Date -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5">Tanggal Kadaluarsa (Exp)</label>
                    <input type="date" name="expiry_date" value="{{ old('expiry_date') }}"
                           class="w-full px-3.5 py-2.5 rounded-xl bg-slate-900 border border-slate-700 text-white text-sm focus:outline-none focus:border-emerald-500">
                </div>
            </div>

            <!-- Description -->
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5">Deskripsi / Kandungan Nutrisi</label>
                <textarea name="description" rows="3" placeholder="Informasi servings, gramasi protein, dan aturan konsumsi..."
                          class="w-full px-3.5 py-2.5 rounded-xl bg-slate-900 border border-slate-700 text-white text-sm focus:outline-none focus:border-emerald-500">{{ old('description') }}</textarea>
            </div>

            <!-- Actions -->
            <div class="pt-4 border-t border-slate-800 flex items-center justify-end gap-3">
                <a href="{{ route('supplements.index') }}" class="px-5 py-2.5 rounded-xl bg-slate-900 hover:bg-slate-800 border border-slate-800 text-slate-400 hover:text-white text-xs font-bold transition-colors">
                    Batal
                </a>
                <button type="submit" 
                        class="px-6 py-2.5 rounded-xl bg-gradient-to-r from-emerald-500 to-teal-400 hover:from-emerald-400 hover:to-teal-300 text-slate-950 font-black text-xs uppercase tracking-wider shadow-lg shadow-emerald-500/25 transition-all">
                    Simpan Suplemen
                </button>
            </div>
        </form>

    </div>
</div>
@endsection
