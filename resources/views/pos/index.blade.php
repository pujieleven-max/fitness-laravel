@extends('layouts.app')

@section('title', 'Terminal Kasir POS')
@section('page-title', 'Terminal Kasir Point of Sale (POS)')
@section('page-subtitle', 'Penjualan Suplemen, Tiket Gym, dan Pembayaran Sesi')

@section('content')
<div x-data="posApp()" class="h-[calc(100vh-8.5rem)] flex flex-col lg:flex-row gap-5 -m-4 sm:-m-6 lg:-m-8 p-4 sm:p-6 lg:p-6 overflow-hidden">
    
    <!-- LEFT SIDE: Product & Services Catalog -->
    <div class="flex-1 flex flex-col min-w-0 glass-panel rounded-3xl overflow-hidden shadow-2xl">
        
        <!-- Search and Filter Bar -->
        <div class="p-4 border-b border-slate-800/80 bg-[#0B0F19] space-y-3">
            <div class="flex items-center gap-3">
                <div class="relative flex-1">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-500">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </div>
                    <input type="text" x-model="searchQuery" 
                           placeholder="Cari suplemen, barcode/SKU, tiket harian, atau merk..."
                           class="w-full pl-10 pr-4 py-2.5 rounded-xl bg-slate-900 border border-slate-700/80 text-white placeholder-slate-500 text-sm focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 transition-colors">
                </div>
                
                <button @click="searchQuery = ''" x-show="searchQuery" class="px-3 py-2 rounded-xl bg-slate-800 text-xs text-slate-400 hover:text-white">
                    Reset
                </button>
            </div>

            <!-- Category Pills -->
            <div class="flex items-center gap-2 overflow-x-auto pb-1 text-xs font-semibold no-scrollbar">
                <button @click="selectedCategory = 'all'"
                        :class="selectedCategory === 'all' ? 'bg-emerald-500 text-slate-950 font-bold shadow-md shadow-emerald-500/20' : 'bg-slate-900 text-slate-400 hover:text-white border border-slate-800'"
                        class="px-3.5 py-1.5 rounded-xl whitespace-nowrap transition-all">
                    Semua Item
                </button>

                @foreach($categories as $cat)
                    <button @click="selectedCategory = 'cat-{{ $cat->id }}'"
                            :class="selectedCategory === 'cat-{{ $cat->id }}' ? 'bg-emerald-500 text-slate-950 font-bold shadow-md shadow-emerald-500/20' : 'bg-slate-900 text-slate-400 hover:text-white border border-slate-800'"
                            class="px-3.5 py-1.5 rounded-xl whitespace-nowrap transition-all flex items-center gap-1.5">
                        <span>{{ $cat->name }}</span>
                    </button>
                @endforeach

                <button @click="selectedCategory = 'packages'"
                        :class="selectedCategory === 'packages' ? 'bg-emerald-500 text-slate-950 font-bold shadow-md shadow-emerald-500/20' : 'bg-slate-900 text-slate-400 hover:text-white border border-slate-800'"
                        class="px-3.5 py-1.5 rounded-xl whitespace-nowrap transition-all flex items-center gap-1.5 text-cyan-400">
                    <i class="fa-solid fa-ticket text-xs"></i>
                    <span>Tiket Gym & Pass</span>
                </button>

                @if($unpaidBookings->isNotEmpty())
                    <button @click="selectedCategory = 'unpaid-bookings'"
                            :class="selectedCategory === 'unpaid-bookings' ? 'bg-amber-500 text-slate-950 font-bold shadow-md shadow-amber-500/20' : 'bg-amber-500/10 text-amber-400 hover:bg-amber-500/20 border border-amber-500/30'"
                            class="px-3.5 py-1.5 rounded-xl whitespace-nowrap transition-all flex items-center gap-1.5 font-bold">
                        <i class="fa-solid fa-clock-rotate-left text-xs"></i>
                        <span>Tagihan Booking ({{ $unpaidBookings->count() }})</span>
                    </button>
                @endif
            </div>
        </div>

        <!-- Product Grid List -->
        <div class="flex-1 overflow-y-auto p-4">
            
            <!-- Category: Supplements -->
            <div x-show="selectedCategory === 'all' || selectedCategory.startsWith('cat-')" class="mb-6">
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-3 flex items-center justify-between">
                    <span>Produk Suplemen & Minuman</span>
                    <span class="text-[11px] font-mono text-emerald-400" x-text="filteredSupplements.length + ' item'"></span>
                </h3>

                <div class="grid grid-cols-2 sm:grid-cols-3 xl:grid-cols-4 gap-3.5">
                    <template x-for="item in filteredSupplements" :key="'supp-' + item.id">
                        <div class="p-3.5 rounded-xl bg-slate-900/90 border border-slate-800/80 hover:border-slate-700 flex flex-col justify-between transition-all group relative overflow-hidden"
                             :class="item.stock <= 0 ? 'opacity-60 bg-slate-900/40' : ''">
                            
                            <div>
                                <!-- Top status badge -->
                                <div class="flex items-center justify-between gap-1 mb-2">
                                    <span class="text-[10px] font-mono text-slate-400 font-bold uppercase tracking-wide truncate" x-text="item.brand || 'FitZone'"></span>
                                    
                                    <template x-if="item.stock <= 0">
                                        <span class="px-2 py-0.5 rounded text-[10px] font-extrabold uppercase bg-rose-500/20 text-rose-400 border border-rose-500/30">
                                            Habis
                                        </span>
                                    </template>
                                    <template x-if="item.stock > 0 && item.stock <= item.min_stock_alert">
                                        <span class="px-2 py-0.5 rounded text-[10px] font-extrabold uppercase bg-amber-500/20 text-amber-300 border border-amber-500/30">
                                            Sisa <span x-text="item.stock"></span>
                                        </span>
                                    </template>
                                    <template x-if="item.stock > item.min_stock_alert">
                                        <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-500/20 text-emerald-400 border border-emerald-500/30">
                                            Stok: <span x-text="item.stock"></span>
                                        </span>
                                    </template>
                                </div>

                                <!-- Product Title -->
                                <h4 class="text-xs font-bold text-white line-clamp-2 leading-snug group-hover:text-emerald-400 transition-colors" x-text="item.name"></h4>
                                
                                <p class="text-[11px] text-slate-400 mt-1 truncate" x-show="item.flavor">
                                    <i class="fa-solid fa-tag text-[9px] text-slate-500 mr-1"></i><span x-text="item.flavor"></span>
                                </p>
                            </div>

                            <div class="mt-3 pt-2.5 border-t border-slate-800/80 flex items-center justify-between">
                                <span class="text-xs font-black text-emerald-400 font-mono" x-text="formatRupiah(item.selling_price)"></span>

                                <button @click="addToCart('supplement', item)" 
                                        :disabled="item.stock <= 0"
                                        :class="item.stock <= 0 ? 'cursor-not-allowed bg-slate-800 text-slate-500' : 'bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-bold hover:scale-105 active:scale-95 shadow-sm'"
                                        class="px-2.5 py-1.5 rounded-lg text-xs transition-all flex items-center gap-1">
                                    <i class="fa-solid fa-plus text-[10px]"></i>
                                    <span>Pilih</span>
                                </button>
                            </div>
                        </div>
                    </template>
                </div>

                <div x-show="filteredSupplements.length === 0" class="text-center py-10 text-slate-500 text-xs">
                    Tidak ada suplemen yang cocok dengan pencarian / kategori ini.
                </div>
            </div>

            <!-- Category: Gym Packages & Day Passes -->
            <div x-show="selectedCategory === 'all' || selectedCategory === 'packages'" class="mb-6">
                <h3 class="text-xs font-bold uppercase tracking-wider text-cyan-400 mb-3 flex items-center gap-1.5">
                    <i class="fa-solid fa-ticket"></i>
                    <span>Tiket Masuk Harian & Langganan Gym</span>
                </h3>

                <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-3.5">
                    @foreach($gymPackages as $pkg)
                        <div class="p-4 rounded-xl bg-slate-900/90 border border-cyan-500/20 hover:border-cyan-500/50 flex flex-col justify-between transition-all group">
                            <div>
                                <div class="flex items-center justify-between gap-2 mb-1.5">
                                    <span class="text-[10px] font-mono px-2 py-0.5 rounded bg-cyan-500/20 text-cyan-300 font-bold uppercase">
                                        {{ $pkg->type }} ({{ $pkg->duration_days }} hari)
                                    </span>
                                    <i class="fa-solid fa-dumbbell text-cyan-400/50"></i>
                                </div>
                                <h4 class="text-sm font-bold text-white group-hover:text-cyan-300 transition-colors">{{ $pkg->name }}</h4>
                                <p class="text-xs text-slate-400 mt-1 line-clamp-2">{{ $pkg->description }}</p>
                            </div>

                            <div class="mt-3 pt-3 border-t border-slate-800 flex items-center justify-between">
                                <span class="text-sm font-black text-white font-mono">Rp {{ number_format($pkg->price, 0, ',', '.') }}</span>
                                <button @click="addToCart('gym_package', { id: {{ $pkg->id }}, name: '{{ addslashes($pkg->name) }}', price: {{ $pkg->price }}, type: 'gym_package' })"
                                        class="px-3 py-1.5 rounded-lg bg-cyan-500 hover:bg-cyan-400 text-slate-950 font-bold text-xs flex items-center gap-1 shadow-sm hover:scale-105 active:scale-95 transition-all">
                                    <i class="fa-solid fa-plus text-[10px]"></i>
                                    <span>Tambah</span>
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Category: Unpaid Bookings -->
            @if($unpaidBookings->isNotEmpty())
                <div x-show="selectedCategory === 'all' || selectedCategory === 'unpaid-bookings'" class="mb-6">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-amber-400 mb-3 flex items-center gap-1.5">
                        <i class="fa-solid fa-calendar-check"></i>
                        <span>Tagihan Booking Personal Trainer & Kelas (Belum Bayar)</span>
                    </h3>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                        @foreach($unpaidBookings as $ub)
                            <div class="p-4 rounded-xl bg-amber-500/10 border border-amber-500/30 flex items-center justify-between gap-4">
                                <div>
                                    <div class="flex items-center gap-2">
                                        <span class="text-xs font-mono font-bold text-amber-300">{{ $ub->booking_code }}</span>
                                        <span class="text-[10px] px-1.5 py-0.5 rounded bg-amber-500/20 text-amber-400 uppercase font-bold">{{ $ub->booking_type }}</span>
                                    </div>
                                    <h4 class="text-sm font-bold text-white mt-1">{{ $ub->customer_name }}</h4>
                                    <p class="text-xs text-slate-300">
                                        {{ $ub->trainer ? $ub->trainer->name : ($ub->fitnessClass ? $ub->fitnessClass->name : '') }} &bull; 
                                        {{ $ub->booking_date->format('d M Y') }} ({{ substr($ub->start_time, 0, 5) }})
                                    </p>
                                </div>
                                <div class="text-right shrink-0">
                                    <div class="text-xs font-black text-amber-400 font-mono mb-1.5">Rp {{ number_format($ub->price, 0, ',', '.') }}</div>
                                    <button @click="addToCart('booking', { id: {{ $ub->id }}, name: 'Sesi: {{ addslashes($ub->customer_name) }} ({{ $ub->booking_code }})', price: {{ $ub->price }}, type: 'booking' })"
                                            class="px-3 py-1.5 rounded-lg bg-amber-500 hover:bg-amber-400 text-slate-950 font-bold text-xs flex items-center gap-1 shadow-sm transition-all">
                                        <i class="fa-solid fa-cash-register text-[10px]"></i>
                                        <span>Bayar di Kasir</span>
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

        </div>
    </div>

    <!-- RIGHT SIDE: Cart Drawer & Checkout Panel -->
    <div class="w-full lg:w-96 xl:w-[420px] glass-panel rounded-3xl flex flex-col overflow-hidden shrink-0 shadow-2xl">
        
        <!-- Cart Header & Customer Selector -->
        <div class="p-4 border-b border-slate-800/80 bg-[#0B0F19] space-y-3">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <div class="w-7 h-7 rounded-lg bg-emerald-500/20 text-emerald-400 flex items-center justify-center text-xs">
                        <i class="fa-solid fa-cart-shopping"></i>
                    </div>
                    <h3 class="font-bold text-white text-sm">Keranjang Transaksi</h3>
                </div>
                <button @click="clearCart()" x-show="cart.length > 0" 
                        class="text-xs text-rose-400 hover:text-rose-300 font-bold flex items-center gap-1">
                    <i class="fa-solid fa-trash-can text-[11px]"></i>
                    <span>Kosongkan</span>
                </button>
            </div>

            <!-- Customer Type Toggle -->
            <div class="grid grid-cols-2 p-1 rounded-xl bg-slate-900 border border-slate-800 text-xs font-bold">
                <button @click="customerType = 'walk_in'" 
                        :class="customerType === 'walk_in' ? 'bg-emerald-500 text-slate-950 shadow-sm' : 'text-slate-400 hover:text-white'"
                        class="py-1.5 rounded-lg transition-all text-center">
                    Tamu Walk-in
                </button>
                <button @click="customerType = 'member'" 
                        :class="customerType === 'member' ? 'bg-emerald-500 text-slate-950 shadow-sm' : 'text-slate-400 hover:text-white'"
                        class="py-1.5 rounded-lg transition-all text-center">
                    Member Terdaftar
                </button>
            </div>

            <!-- Member Select or Walk-in Name Input -->
            <div x-show="customerType === 'walk_in'">
                <input type="text" x-model="customerName" placeholder="Nama Tamu (misal: Bpk. Andi)"
                       class="w-full px-3 py-2 rounded-xl bg-slate-900 border border-slate-700/80 text-white placeholder-slate-500 text-xs focus:outline-none focus:border-emerald-500">
            </div>

            <div x-show="customerType === 'member'">
                <select x-model="selectedMemberId" @change="onMemberSelected()"
                        class="w-full px-3 py-2 rounded-xl bg-slate-900 border border-slate-700/80 text-white text-xs focus:outline-none focus:border-emerald-500">
                    <option value="">-- Pilih Member Gym --</option>
                    @foreach($members as $m)
                        <option value="{{ $m->id }}" data-name="{{ $m->name }} ({{ $m->member_code }})">
                            {{ $m->member_code }} - {{ $m->name }} ({{ strtoupper($m->membership_type) }})
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <!-- Cart Items List (Scrollable) -->
        <div class="flex-1 overflow-y-auto p-4 space-y-3">
            <template x-for="(item, index) in cart" :key="item.type + '-' + item.id">
                <div class="p-3 rounded-xl bg-slate-900/80 border border-slate-800/80 flex items-center justify-between gap-3">
                    <div class="min-w-0 flex-1">
                        <h4 class="text-xs font-bold text-white truncate" x-text="item.name"></h4>
                        <div class="text-[11px] font-mono text-emerald-400 mt-0.5" x-text="formatRupiah(item.price)"></div>
                    </div>

                    <!-- Quantity Controls -->
                    <div class="flex items-center gap-1.5 bg-slate-800/80 rounded-lg p-1 border border-slate-700/60 shrink-0">
                        <button @click="decreaseQty(index)" class="w-6 h-6 rounded flex items-center justify-center text-slate-300 hover:text-white hover:bg-slate-700 text-xs">
                            <i class="fa-solid fa-minus text-[10px]"></i>
                        </button>
                        <span class="w-6 text-center text-xs font-bold font-mono text-white" x-text="item.quantity"></span>
                        <button @click="increaseQty(index)" class="w-6 h-6 rounded flex items-center justify-center text-slate-300 hover:text-white hover:bg-slate-700 text-xs">
                            <i class="fa-solid fa-plus text-[10px]"></i>
                        </button>
                    </div>

                    <!-- Subtotal & Remove -->
                    <div class="text-right shrink-0">
                        <div class="text-xs font-bold font-mono text-white" x-text="formatRupiah(item.price * item.quantity)"></div>
                        <button @click="removeItem(index)" class="text-[10px] text-rose-400 hover:text-rose-300 mt-0.5">
                            <i class="fa-solid fa-xmark"></i> Hapus
                        </button>
                    </div>
                </div>
            </template>

            <div x-show="cart.length === 0" class="h-48 flex flex-col items-center justify-center text-slate-500 text-center">
                <i class="fa-solid fa-cart-shopping text-3xl mb-2 opacity-30"></i>
                <p class="text-xs font-medium">Keranjang masih kosong.</p>
                <p class="text-[11px] text-slate-400">Pilih suplemen atau tiket dari katalog di samping.</p>
            </div>
        </div>

        <!-- Checkout Calculation & Payment Controls -->
        <div class="p-4 border-t border-slate-800 bg-[#0B0F19] space-y-3">
            
            <!-- Subtotal & Discount -->
            <div class="space-y-1.5 text-xs text-slate-300">
                <div class="flex justify-between">
                    <span>Subtotal</span>
                    <span class="font-mono font-bold" x-text="formatRupiah(subtotal)"></span>
                </div>
                
                <div class="flex items-center justify-between gap-3">
                    <span class="text-amber-400">Potongan Diskon (Rp)</span>
                    <input type="number" x-model.number="discount" min="0" 
                           class="w-28 px-2 py-1 text-right font-mono rounded bg-slate-900 border border-slate-700 text-xs text-white focus:outline-none focus:border-amber-400">
                </div>
            </div>

            <!-- Grand Total -->
            <div class="pt-2 border-t border-slate-800 flex items-center justify-between">
                <div>
                    <span class="text-xs text-slate-400 font-bold uppercase tracking-wider block">Total Tagihan</span>
                    <span class="text-xl font-black text-emerald-400 font-mono tracking-tight" x-text="formatRupiah(grandTotal)"></span>
                </div>
            </div>

            <!-- Payment Method Selector -->
            <div>
                <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-400 mb-1.5">Metode Pembayaran</label>
                <div class="grid grid-cols-4 gap-1.5">
                    <button @click="paymentMethod = 'cash'" 
                            :class="paymentMethod === 'cash' ? 'bg-emerald-500 text-slate-950 font-extrabold border-emerald-400' : 'bg-slate-900 text-slate-300 border-slate-800'"
                            class="p-2 rounded-xl text-xs flex flex-col items-center gap-1 border transition-all">
                        <i class="fa-solid fa-money-bill-wave"></i>
                        <span>Tunai</span>
                    </button>

                    <button @click="paymentMethod = 'qris'" 
                            :class="paymentMethod === 'qris' ? 'bg-emerald-500 text-slate-950 font-extrabold border-emerald-400' : 'bg-slate-900 text-slate-300 border-slate-800'"
                            class="p-2 rounded-xl text-xs flex flex-col items-center gap-1 border transition-all">
                        <i class="fa-solid fa-qrcode"></i>
                        <span>QRIS</span>
                    </button>

                    <button @click="paymentMethod = 'transfer'" 
                            :class="paymentMethod === 'transfer' ? 'bg-emerald-500 text-slate-950 font-extrabold border-emerald-400' : 'bg-slate-900 text-slate-300 border-slate-800'"
                            class="p-2 rounded-xl text-xs flex flex-col items-center gap-1 border transition-all">
                        <i class="fa-solid fa-building-columns"></i>
                        <span>Transfer</span>
                    </button>

                    <button @click="paymentMethod = 'debit'" 
                            :class="paymentMethod === 'debit' ? 'bg-emerald-500 text-slate-950 font-extrabold border-emerald-400' : 'bg-slate-900 text-slate-300 border-slate-800'"
                            class="p-2 rounded-xl text-xs flex flex-col items-center gap-1 border transition-all">
                        <i class="fa-solid fa-credit-card"></i>
                        <span>Debit/EDC</span>
                    </button>
                </div>
            </div>

            <!-- Cash Input Section -->
            <div x-show="paymentMethod === 'cash'" class="space-y-2 p-2.5 rounded-xl bg-slate-900/90 border border-slate-800">
                <div class="flex items-center justify-between text-xs">
                    <span class="text-slate-300 font-bold">Uang Diterima:</span>
                    <input type="number" x-model.number="cashReceived" min="0" step="5000"
                           class="w-36 px-2.5 py-1 text-right font-mono font-bold rounded bg-slate-950 border border-slate-700 text-emerald-400 text-sm focus:outline-none focus:border-emerald-500">
                </div>

                <!-- Quick Cash Presets -->
                <div class="flex items-center gap-1.5 overflow-x-auto text-[11px] font-mono font-bold">
                    <button @click="cashReceived = grandTotal" class="px-2 py-1 rounded bg-slate-800 text-slate-300 hover:text-white hover:bg-slate-700">
                        Pas
                    </button>
                    <button @click="cashReceived = 50000" class="px-2 py-1 rounded bg-slate-800 text-slate-300 hover:text-white hover:bg-slate-700">
                        50k
                    </button>
                    <button @click="cashReceived = 100000" class="px-2 py-1 rounded bg-slate-800 text-slate-300 hover:text-white hover:bg-slate-700">
                        100k
                    </button>
                    <button @click="cashReceived = 200000" class="px-2 py-1 rounded bg-slate-800 text-slate-300 hover:text-white hover:bg-slate-700">
                        200k
                    </button>
                    <button @click="cashReceived = 500000" class="px-2 py-1 rounded bg-slate-800 text-slate-300 hover:text-white hover:bg-slate-700">
                        500k
                    </button>
                </div>

                <!-- Kembalian Display -->
                <div class="flex justify-between items-center pt-1.5 border-t border-slate-800 text-xs">
                    <span class="text-slate-400 font-bold">Kembalian:</span>
                    <span class="font-mono font-bold text-sm" 
                          :class="cashChange >= 0 ? 'text-cyan-400' : 'text-rose-400'"
                          x-text="formatRupiah(cashChange)"></span>
                </div>
            </div>

            <!-- QRIS QR Preview Notice -->
            <div x-show="paymentMethod === 'qris'" class="p-3 rounded-xl bg-cyan-500/10 border border-cyan-500/30 text-cyan-300 text-xs flex items-center gap-3">
                <i class="fa-solid fa-qrcode text-2xl text-cyan-400 shrink-0"></i>
                <div>
                    <span class="font-bold">QRIS Statis {{ $appSetting->app_name ?? 'FitZone Gym' }}</span>
                    <p class="text-[11px] text-cyan-400/80">Arahkan member scan barcode QRIS kasir. Konfirmasi notifikasi bank sebelum cetak.</p>
                </div>
            </div>

            <!-- Submit Checkout Button -->
            <button @click="processCheckout()" 
                    :disabled="cart.length === 0 || isProcessing || (paymentMethod === 'cash' && cashReceived < grandTotal)"
                    :class="(cart.length === 0 || isProcessing || (paymentMethod === 'cash' && cashReceived < grandTotal)) ? 'opacity-50 cursor-not-allowed bg-slate-800 text-slate-500' : 'bg-gradient-to-r from-emerald-500 to-teal-400 hover:from-emerald-400 hover:to-teal-300 text-slate-950 font-black shadow-lg shadow-emerald-500/25 hover:scale-[1.01] active:scale-[0.99]'"
                    class="w-full py-3.5 px-4 rounded-xl text-xs uppercase tracking-wider font-extrabold flex items-center justify-center gap-2 transition-all">
                <template x-if="!isProcessing">
                    <div class="flex items-center gap-2">
                        <i class="fa-solid fa-check-double text-sm"></i>
                        <span>Bayar & Cetak Struk (F10)</span>
                    </div>
                </template>
                <template x-if="isProcessing">
                    <div class="flex items-center gap-2">
                        <i class="fa-solid fa-circle-notch fa-spin text-sm"></i>
                        <span>Memproses Transaksi...</span>
                    </div>
                </template>
            </button>
        </div>

    </div>

    <!-- MODAL RECEIPT SUCCESS -->
    <div x-show="showReceiptModal" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/80 backdrop-blur-md" style="display: none;">
        
        <div class="bg-slate-950 border border-slate-800 rounded-3xl max-w-md w-full p-6 shadow-2xl relative">
            <div class="text-center mb-4">
                <div class="w-14 h-14 rounded-full bg-emerald-500/20 text-emerald-400 flex items-center justify-center mx-auto mb-3 text-2xl">
                    <i class="fa-solid fa-circle-check"></i>
                </div>
                <h3 class="text-lg font-bold text-white">Transaksi Berhasil!</h3>
                <p class="text-xs text-slate-400 mt-1" x-text="'Invoice #' + (completedOrder ? completedOrder.invoice_no : '')"></p>
            </div>

            <!-- Receipt Mini Summary -->
            <div class="p-4 rounded-2xl bg-slate-900 border border-slate-800 text-xs space-y-2 mb-4 font-mono">
                <div class="flex justify-between text-slate-400">
                    <span>Total Belanja:</span>
                    <span class="text-white font-bold" x-text="completedOrder ? formatRupiah(completedOrder.total_amount) : ''"></span>
                </div>
                <div class="flex justify-between text-slate-400">
                    <span>Metode Bayar:</span>
                    <span class="text-cyan-400 uppercase font-bold" x-text="completedOrder ? completedOrder.payment_method : ''"></span>
                </div>
                <div class="flex justify-between text-slate-400">
                    <span>Uang Diterima:</span>
                    <span class="text-white" x-text="completedOrder ? formatRupiah(completedOrder.cash_received) : ''"></span>
                </div>
                <div class="flex justify-between text-slate-400 pt-1.5 border-t border-slate-800">
                    <span class="text-emerald-400 font-bold">Kembalian:</span>
                    <span class="text-emerald-400 font-bold" x-text="completedOrder ? formatRupiah(completedOrder.cash_change) : ''"></span>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="grid grid-cols-2 gap-3">
                <a :href="receiptUrl" target="_blank"
                   class="py-3 px-4 rounded-xl bg-slate-800 hover:bg-slate-700 text-white font-bold text-xs flex items-center justify-center gap-2 transition-colors">
                    <i class="fa-solid fa-print"></i>
                    <span>Cetak Struk Thermal</span>
                </a>

                <button @click="resetPosState()" 
                        class="py-3 px-4 rounded-xl bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-black text-xs flex items-center justify-center gap-2 transition-colors shadow-lg shadow-emerald-500/20">
                    <i class="fa-solid fa-plus"></i>
                    <span>Transaksi Baru</span>
                </button>
            </div>
        </div>
    </div>

</div>

@push('scripts')
<script>
    function posApp() {
        return {
            supplements: {!! json_encode($supplements) !!},
            searchQuery: '',
            selectedCategory: 'all',
            cart: [],
            customerType: 'walk_in',
            customerName: '',
            selectedMemberId: '',
            discount: 0,
            paymentMethod: 'cash',
            cashReceived: 0,
            isProcessing: false,
            showReceiptModal: false,
            completedOrder: null,
            receiptUrl: '',

            get filteredSupplements() {
                return this.supplements.filter(item => {
                    const matchesCategory = (this.selectedCategory === 'all') || 
                                            (this.selectedCategory === 'cat-' + item.category_id);

                    if (!matchesCategory) return false;

                    if (!this.searchQuery) return true;

                    const query = this.searchQuery.toLowerCase();
                    return item.name.toLowerCase().includes(query) ||
                           item.sku.toLowerCase().includes(query) ||
                           (item.brand && item.brand.toLowerCase().includes(query)) ||
                           (item.flavor && item.flavor.toLowerCase().includes(query));
                });
            },

            get subtotal() {
                return this.cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
            },

            get grandTotal() {
                return Math.max(0, this.subtotal - (this.discount || 0));
            },

            get cashChange() {
                if (this.paymentMethod !== 'cash') return 0;
                return Math.max(0, (this.cashReceived || 0) - this.grandTotal);
            },

            formatRupiah(val) {
                return 'Rp ' + new Intl.NumberFormat('id-ID').format(val || 0);
            },

            addToCart(type, item) {
                const existingIndex = this.cart.findIndex(c => c.type === type && c.id === item.id);

                if (existingIndex > -1) {
                    if (type === 'supplement') {
                        const targetSupp = this.supplements.find(s => s.id === item.id);
                        if (targetSupp && (this.cart[existingIndex].quantity + 1) > targetSupp.stock) {
                            alert('Stok tidak mencukupi! Sisa stok ' + targetSupp.stock);
                            return;
                        }
                    }
                    this.cart[existingIndex].quantity++;
                } else {
                    this.cart.push({
                        type: type,
                        id: item.id,
                        name: item.name,
                        price: parseFloat(item.selling_price || item.price),
                        quantity: 1
                    });
                }

                if (this.paymentMethod === 'cash') {
                    this.cashReceived = this.grandTotal;
                }
            },

            increaseQty(index) {
                const item = this.cart[index];
                if (item.type === 'supplement') {
                    const targetSupp = this.supplements.find(s => s.id === item.id);
                    if (targetSupp && (item.quantity + 1) > targetSupp.stock) {
                        alert('Stok maksimal tercapai: ' + targetSupp.stock);
                        return;
                    }
                }
                item.quantity++;
                if (this.paymentMethod === 'cash') this.cashReceived = this.grandTotal;
            },

            decreaseQty(index) {
                if (this.cart[index].quantity > 1) {
                    this.cart[index].quantity--;
                } else {
                    this.cart.splice(index, 1);
                }
                if (this.paymentMethod === 'cash') this.cashReceived = this.grandTotal;
            },

            removeItem(index) {
                this.cart.splice(index, 1);
                if (this.paymentMethod === 'cash') this.cashReceived = this.grandTotal;
            },

            clearCart() {
                this.cart = [];
                this.discount = 0;
                this.cashReceived = 0;
            },

            onMemberSelected() {
                const select = document.querySelector('select[x-model="selectedMemberId"]');
                const selectedOption = select.options[select.selectedIndex];
                if (selectedOption && selectedOption.dataset.name) {
                    this.customerName = selectedOption.dataset.name;
                }
            },

            async processCheckout() {
                if (this.cart.length === 0) return;

                if (this.paymentMethod === 'cash' && this.cashReceived < this.grandTotal) {
                    alert('Uang tunai yang diterima kurang dari total tagihan.');
                    return;
                }

                this.isProcessing = true;

                const payload = {
                    items: this.cart,
                    discount: this.discount || 0,
                    payment_method: this.paymentMethod,
                    cash_received: this.paymentMethod === 'cash' ? this.cashReceived : this.grandTotal,
                    customer_type: this.customerType,
                    customer_name: this.customerName,
                    member_id: this.customerType === 'member' ? this.selectedMemberId : null,
                };

                try {
                    const response = await fetch('{{ route("pos.checkout") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify(payload)
                    });

                    const result = await response.json();

                    if (!response.ok || !result.success) {
                        alert(result.message || 'Gagal memproses transaksi.');
                        this.isProcessing = false;
                        return;
                    }

                    // Deduct local supplements stock for realtime view
                    this.cart.forEach(cartItem => {
                        if (cartItem.type === 'supplement') {
                            const found = this.supplements.find(s => s.id === cartItem.id);
                            if (found) {
                                found.stock -= cartItem.quantity;
                            }
                        }
                    });

                    this.completedOrder = result.order;
                    this.receiptUrl = result.receipt_url;
                    this.showReceiptModal = true;
                } catch (error) {
                    alert('Terjadi kesalahan jaringan: ' + error.message);
                } finally {
                    this.isProcessing = false;
                }
            },

            resetPosState() {
                this.showReceiptModal = false;
                this.clearCart();
                this.customerName = '';
                this.selectedMemberId = '';
                this.completedOrder = null;
                this.receiptUrl = '';
            }
        }
    }
</script>
@endpush
@endsection
