@extends('layouts.app')

@section('title', 'Dashboard Kasir')
@section('page-title', 'Frontdesk & Shift Kasir')
@section('page-subtitle', 'Pusat Kendali Kasir POS, Check-in Jadwal Booking, & Layanan Member Harian')

@section('content')
@php
    $pendingBookingsToday = $todayBookings->whereNotIn('status', ['completed', 'cancelled'])->count();
    $completedBookingsToday = $todayBookings->where('status', 'completed')->count();
@endphp

<div class="space-y-6">

    <!-- 1. Frontdesk Command Hero & Quick Shortcuts -->
    <div class="p-6 sm:p-7 rounded-3xl glass-panel border border-emerald-500/30 shadow-2xl relative overflow-hidden">
        <div class="absolute -right-12 -bottom-12 w-64 h-64 rounded-full blur-3xl pointer-events-none opacity-20" style="background: var(--primary-accent);"></div>

        <div class="flex flex-col xl:flex-row xl:items-center justify-between gap-6 relative z-10">
            <div class="space-y-2">
                <div class="flex flex-wrap items-center gap-2">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-extrabold uppercase bg-emerald-500/20 text-emerald-300 border border-emerald-500/30">
                        <span class="w-2 h-2 rounded-full bg-emerald-400 animate-ping"></span>
                        Shift Kasir Aktif
                    </span>
                    <span class="text-xs text-slate-400 font-mono">
                        Petugas: <strong class="text-white">{{ Auth::user()->name }}</strong> &bull; {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}
                    </span>
                </div>
                <h2 class="text-2xl sm:text-3xl font-black text-white font-display tracking-tight">
                    Frontdesk {{ $appSetting->app_name ?? 'FitZone' }} Siap Melayani
                </h2>
                <p class="text-xs sm:text-sm text-slate-400 max-w-2xl">
                    Proses transaksi suplemen, tiket harian (Daily Pass), aktivasi membership baru, serta konfirmasi check-in sesi Personal Trainer & Kelas Studio dengan cepat.
                </p>
            </div>

            <div class="flex flex-wrap items-center gap-2.5 shrink-0">
                <a href="{{ route('pos.index') }}" 
                   class="btn-theme-primary px-6 py-3.5 rounded-2xl text-slate-950 font-black text-xs sm:text-sm uppercase tracking-wider flex items-center gap-2.5 shadow-xl">
                    <i class="fa-solid fa-cash-register text-lg"></i>
                    <span>Buka Terminal Kasir POS</span>
                </a>
                <a href="{{ route('bookings.create') }}" 
                   class="px-4 py-3.5 rounded-2xl bg-slate-900 hover:bg-slate-800 text-slate-200 border border-slate-800 text-xs font-bold flex items-center gap-2 transition-all">
                    <i class="fa-solid fa-calendar-plus text-cyan-400"></i>
                    <span>+ Booking</span>
                </a>
                <a href="{{ route('members.index') }}" 
                   class="px-4 py-3.5 rounded-2xl bg-slate-900 hover:bg-slate-800 text-slate-200 border border-slate-800 text-xs font-bold flex items-center gap-2 transition-all">
                    <i class="fa-solid fa-id-card-clip text-purple-400"></i>
                    <span>Member</span>
                </a>
            </div>
        </div>
    </div>

    <!-- 2. Shift Financial & Operational Summary Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 sm:gap-5">
        
        <!-- Total Penjualan Shift Saya -->
        <div class="p-5 sm:p-6 rounded-3xl glass-panel glass-panel-hover flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between text-slate-400 mb-2.5">
                    <span class="text-[11px] font-extrabold uppercase tracking-wider font-display">Total Shift Saya</span>
                    <div class="w-10 h-10 rounded-2xl bg-emerald-500/15 border border-emerald-500/30 text-emerald-400 flex items-center justify-center">
                        <i class="fa-solid fa-receipt text-sm"></i>
                    </div>
                </div>
                <div class="text-2xl sm:text-3xl font-black text-white font-display tracking-tight">
                    Rp {{ number_format($cashierTotalToday, 0, ',', '.') }}
                </div>
            </div>
            <div class="mt-4 pt-3 border-t border-slate-800/80 flex items-center justify-between text-xs">
                <span class="text-slate-400">Transaksi saya hari ini</span>
                <span class="px-2 py-0.5 rounded-full bg-emerald-500/20 text-emerald-300 font-mono font-bold text-[10px]">
                    {{ $cashierOrdersCountToday ?? 0 }} Struk
                </span>
            </div>
        </div>

        <!-- Uang Tunai Diterima (Cash di Laci) -->
        <div class="p-5 sm:p-6 rounded-3xl glass-panel glass-panel-hover flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between text-slate-400 mb-2.5">
                    <span class="text-[11px] font-extrabold uppercase tracking-wider font-display">Uang Tunai (Laci Kasir)</span>
                    <div class="w-10 h-10 rounded-2xl bg-cyan-500/15 border border-cyan-500/30 text-cyan-400 flex items-center justify-center">
                        <i class="fa-solid fa-money-bill-wave text-sm"></i>
                    </div>
                </div>
                <div class="text-2xl sm:text-3xl font-black text-cyan-400 font-display tracking-tight">
                    Rp {{ number_format($cashierTodayCash, 0, ',', '.') }}
                </div>
            </div>
            <div class="mt-4 pt-3 border-t border-slate-800/80 flex items-center justify-between text-xs">
                <span class="text-slate-400">Wajib sesuai fisik laci</span>
                <span class="text-[10px] font-mono font-bold text-cyan-400 uppercase">CASH</span>
            </div>
        </div>

        <!-- Non Tunai (QRIS / Transfer / Debit) -->
        <div class="p-5 sm:p-6 rounded-3xl glass-panel glass-panel-hover flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between text-slate-400 mb-2.5">
                    <span class="text-[11px] font-extrabold uppercase tracking-wider font-display">Pembayaran Non-Tunai</span>
                    <div class="w-10 h-10 rounded-2xl bg-amber-500/15 border border-amber-500/30 text-amber-400 flex items-center justify-center">
                        <i class="fa-solid fa-qrcode text-sm"></i>
                    </div>
                </div>
                <div class="text-2xl sm:text-3xl font-black text-amber-400 font-display tracking-tight">
                    Rp {{ number_format($cashierTodayNonCash, 0, ',', '.') }}
                </div>
            </div>
            <div class="mt-4 pt-3 border-t border-slate-800/80 flex items-center justify-between text-xs">
                <span class="text-slate-400">QRIS, Transfer & Debit</span>
                <span class="text-[10px] font-mono font-bold text-amber-400 uppercase">DIGITAL</span>
            </div>
        </div>

        <!-- Status Check-in Booking Hari Ini -->
        <div class="p-5 sm:p-6 rounded-3xl glass-panel glass-panel-hover flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between text-slate-400 mb-2.5">
                    <span class="text-[11px] font-extrabold uppercase tracking-wider font-display">Sesi Booking Hari Ini</span>
                    <div class="w-10 h-10 rounded-2xl bg-purple-500/15 border border-purple-500/30 text-purple-400 flex items-center justify-center">
                        <i class="fa-solid fa-calendar-check text-sm"></i>
                    </div>
                </div>
                <div class="text-2xl sm:text-3xl font-black text-white font-display tracking-tight">
                    {{ $todayBookings->count() }} <span class="text-sm font-semibold text-slate-400">Sesi</span>
                </div>
            </div>
            <div class="mt-4 pt-3 border-t border-slate-800/80 flex items-center justify-between text-xs">
                <span class="text-amber-400 font-semibold">{{ $pendingBookingsToday }} menunggu check-in</span>
                <span class="text-emerald-400 font-mono font-bold">{{ $completedBookingsToday }} selesai</span>
            </div>
        </div>
    </div>

    <!-- 3. Frontdesk Interactive Workspace -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        
        <!-- Left (7 cols): Antrean Jadwal Sesi Booking Hari Ini -->
        <div class="lg:col-span-7 p-6 rounded-3xl glass-panel flex flex-col justify-between"
             x-data="{ bookingSearch: '' }">
            <div>
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-5">
                    <div>
                        <h3 class="font-extrabold text-white text-base font-display">Antrean Check-In Booking Hari Ini</h3>
                        <p class="text-xs text-slate-400">Konfirmasi kehadiran member atau arahkan ke POS jika belum lunas</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <div class="relative">
                            <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-slate-500 text-xs"></i>
                            <input x-model="bookingSearch" type="text" placeholder="Cari nama member..."
                                   class="pl-8 pr-3 py-1.5 rounded-xl bg-slate-900 border border-slate-800 text-xs text-white placeholder-slate-500 focus:outline-none">
                        </div>
                        <a href="{{ route('bookings.calendar') }}" class="px-3 py-1.5 rounded-xl bg-slate-900 hover:bg-slate-800 border border-slate-800 text-xs font-bold text-cyan-400 shrink-0">
                            Kalender
                        </a>
                    </div>
                </div>

                <div class="space-y-3 max-h-[420px] overflow-y-auto pr-1">
                    @forelse($todayBookings as $bk)
                        <div x-show="!bookingSearch || '{{ strtolower(addslashes($bk->customer_name)) }}'.includes(bookingSearch.toLowerCase())"
                             class="p-4 rounded-2xl bg-slate-900/80 border border-slate-800/90 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                            <div class="flex items-center gap-3.5 min-w-0">
                                <div class="w-12 h-12 rounded-xl bg-cyan-500/10 border border-cyan-500/20 text-cyan-400 flex flex-col items-center justify-center font-bold text-xs shrink-0">
                                    <span class="font-mono text-sm leading-none">{{ substr($bk->start_time, 0, 5) }}</span>
                                    <span class="text-[9px] text-slate-400 uppercase mt-0.5">WIB</span>
                                </div>
                                <div class="min-w-0">
                                    <h4 class="text-sm font-bold text-white truncate">{{ $bk->customer_name }}</h4>
                                    <p class="text-xs text-slate-400 truncate">
                                        {{ $bk->booking_type === 'personal_trainer' ? 'Sesi PT' : 'Kelas Studio' }} &bull; 
                                        <span class="text-emerald-400 font-semibold">{{ $bk->trainer ? $bk->trainer->name : ($bk->fitnessClass ? $bk->fitnessClass->name : '-') }}</span>
                                    </p>
                                    <div class="mt-1.5 flex flex-wrap items-center gap-2">
                                        <span class="inline-flex px-2 py-0.5 rounded text-[10px] font-bold uppercase {{ $bk->payment_status === 'paid' ? 'bg-emerald-500/20 text-emerald-400 border border-emerald-500/30' : 'bg-rose-500/20 text-rose-400 border border-rose-500/30' }}">
                                            {{ $bk->payment_status === 'paid' ? 'LUNAS' : 'BELUM LUNAS' }}
                                        </span>
                                        <span class="text-[11px] font-mono text-slate-400">
                                            Rp {{ number_format($bk->price, 0, ',', '.') }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="flex items-center gap-2 self-end sm:self-center shrink-0">
                                @if($bk->status !== 'completed' && $bk->status !== 'cancelled')
                                    <form method="POST" action="{{ route('bookings.updateStatus', $bk->id) }}">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="status" value="completed">
                                        <button type="submit" class="px-3.5 py-2 rounded-xl bg-emerald-500/20 hover:bg-emerald-500/30 text-emerald-300 border border-emerald-500/30 text-xs font-bold transition-colors flex items-center gap-1.5">
                                            <i class="fa-solid fa-check"></i>
                                            <span>Check-in Hadir</span>
                                        </button>
                                    </form>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-xl text-[10px] font-bold uppercase {{ $bk->status === 'completed' ? 'bg-cyan-500/20 text-cyan-300 border border-cyan-500/30' : 'bg-rose-500/20 text-rose-300 border border-rose-500/30' }}">
                                        <i class="fa-solid {{ $bk->status === 'completed' ? 'fa-circle-check' : 'fa-ban' }}"></i>
                                        {{ $bk->status }}
                                    </span>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-12 text-slate-500 text-xs space-y-2">
                            <i class="fa-regular fa-calendar-check text-2xl text-slate-600"></i>
                            <p>Tidak ada jadwal booking untuk hari ini.</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <a href="{{ route('bookings.index') }}" class="mt-4 pt-3 border-t border-slate-800/80 block text-center text-xs font-bold text-emerald-400 hover:text-emerald-300">
                Lihat Semua Daftar Booking &rarr;
            </a>
        </div>

        <!-- Right (5 cols): Tabbed Operational Panel (Struk Terakhir, Member Expired, Stok Menipis) -->
        <div class="lg:col-span-5 p-6 rounded-3xl glass-panel flex flex-col justify-between"
             x-data="{ sideTab: 'orders' }">
            <div>
                <!-- Tab Switcher Header -->
                <div class="flex items-center justify-between gap-2 pb-4 mb-4 border-b border-slate-800/80">
                    <div class="flex items-center gap-1.5">
                        <button @click="sideTab = 'orders'"
                                type="button"
                                :class="sideTab === 'orders' ? 'btn-theme-primary text-slate-950 font-extrabold' : 'bg-slate-900 text-slate-400 hover:text-white border border-slate-800'"
                                class="px-3 py-1.5 rounded-xl text-xs font-semibold transition-all">
                            Struk Terakhir
                        </button>
                        <button @click="sideTab = 'stock'"
                                type="button"
                                :class="sideTab === 'stock' ? 'bg-amber-500 text-slate-950 font-extrabold' : 'bg-slate-900 text-slate-400 hover:text-white border border-slate-800'"
                                class="px-3 py-1.5 rounded-xl text-xs font-semibold transition-all flex items-center gap-1.5">
                            <span>Stok Menipis</span>
                            @if($lowStockSupplements->isNotEmpty())
                                <span class="px-1.5 py-0.2 rounded-full text-[10px] font-mono bg-black/20">{{ $lowStockSupplements->count() }}</span>
                            @endif
                        </button>
                        <button @click="sideTab = 'members'"
                                type="button"
                                :class="sideTab === 'members' ? 'bg-purple-500 text-white font-extrabold' : 'bg-slate-900 text-slate-400 hover:text-white border border-slate-800'"
                                class="px-3 py-1.5 rounded-xl text-xs font-semibold transition-all flex items-center gap-1.5">
                            <span>Member</span>
                            @if(($expiringMembers ?? collect())->isNotEmpty())
                                <span class="px-1.5 py-0.2 rounded-full text-[10px] font-mono bg-black/20">{{ $expiringMembers->count() }}</span>
                            @endif
                        </button>
                    </div>
                </div>

                <!-- Tab 1: Recent Orders (Quick Reprint) -->
                <div x-show="sideTab === 'orders'" class="space-y-3 max-h-[380px] overflow-y-auto pr-1">
                    @forelse($recentOrders as $ord)
                        <div class="p-3.5 rounded-2xl bg-slate-900/80 border border-slate-800 flex items-center justify-between gap-3">
                            <div class="min-w-0">
                                <div class="flex items-center gap-2">
                                    <span class="text-xs font-mono font-bold text-white">{{ $ord->invoice_no }}</span>
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase bg-slate-800 text-slate-300">
                                        {{ $ord->payment_method }}
                                    </span>
                                </div>
                                <p class="text-[11px] text-slate-400 mt-1 truncate">
                                    {{ $ord->customer_name }} &bull; {{ $ord->created_at->diffForHumans() }}
                                </p>
                            </div>
                            <div class="text-right shrink-0">
                                <div class="text-xs font-bold font-mono text-emerald-400">
                                    Rp {{ number_format($ord->total_amount, 0, ',', '.') }}
                                </div>
                                <a href="{{ route('pos.receipt', $ord->id) }}" target="_blank"
                                   class="inline-flex items-center gap-1 text-[10px] font-bold text-slate-400 hover:text-white mt-1">
                                    <i class="fa-solid fa-print"></i> Cetak Struk
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-10 text-slate-500 text-xs">
                            Belum ada transaksi kasir tercatat.
                        </div>
                    @endforelse
                </div>

                <!-- Tab 2: Low Stock Supplements -->
                <div x-show="sideTab === 'stock'" x-cloak class="space-y-3 max-h-[380px] overflow-y-auto pr-1">
                    @forelse($lowStockSupplements as $supp)
                        <div class="p-3.5 rounded-2xl bg-slate-900/80 border border-slate-800 flex items-center justify-between">
                            <div>
                                <h4 class="text-xs font-bold text-white">{{ $supp->name }}</h4>
                                <p class="text-[11px] text-slate-400">{{ $supp->brand }} &bull; {{ $supp->unit }}</p>
                            </div>
                            <span class="inline-flex px-2.5 py-1 rounded-lg text-xs font-bold font-mono {{ $supp->stock <= 0 ? 'bg-rose-500/20 text-rose-300 border border-rose-500/30' : 'bg-amber-500/20 text-amber-300 border border-amber-500/30' }}">
                                Sisa: {{ $supp->stock }}
                            </span>
                        </div>
                    @empty
                        <div class="text-center py-10 text-emerald-400/80 text-xs flex flex-col items-center gap-2">
                            <i class="fa-solid fa-circle-check text-2xl"></i>
                            <span>Seluruh stok suplemen aman dan siap dijual.</span>
                        </div>
                    @endforelse
                </div>

                <!-- Tab 3: Expiring Members -->
                <div x-show="sideTab === 'members'" x-cloak class="space-y-3 max-h-[380px] overflow-y-auto pr-1">
                    @forelse($expiringMembers ?? [] as $mem)
                        @php
                            $waPhone = preg_replace('/[^0-9]/', '', $mem->phone ?? '');
                            if (str_starts_with($waPhone, '0')) {
                                $waPhone = '62' . substr($waPhone, 1);
                            }
                            $waMsg = urlencode("Halo Kak {$mem->name}, masa aktif membership Anda di " . ($appSetting->app_name ?? 'FitZone Gym') . " berakhir pada " . ($mem->membership_expires_at ? $mem->membership_expires_at->format('d M Y') : '-') . ". Yuk perpanjang di kasir kami!");
                        @endphp
                        <div class="p-3.5 rounded-2xl bg-slate-900/80 border border-slate-800 flex items-center justify-between gap-2">
                            <div class="min-w-0">
                                <h4 class="text-xs font-bold text-white truncate">{{ $mem->name }}</h4>
                                <p class="text-[11px] text-rose-400 font-mono">
                                    Berakhir: {{ $mem->membership_expires_at ? $mem->membership_expires_at->format('d M Y') : '-' }}
                                </p>
                            </div>
                            @if($waPhone)
                                <a href="https://wa.me/{{ $waPhone }}?text={{ $waMsg }}" target="_blank"
                                   class="px-2.5 py-1.5 rounded-xl bg-emerald-500/20 hover:bg-emerald-500/30 text-emerald-300 border border-emerald-500/30 text-[11px] font-bold flex items-center gap-1 shrink-0">
                                    <i class="fa-brands fa-whatsapp"></i>
                                    <span>WA</span>
                                </a>
                            @endif
                        </div>
                    @empty
                        <div class="text-center py-10 text-slate-500 text-xs">
                            Tidak ada member yang masa aktifnya berakhir dalam 7 hari ke depan.
                        </div>
                    @endforelse
                </div>
            </div>

            <a href="{{ route('pos.orders') }}" class="mt-4 pt-3 border-t border-slate-800/80 block text-center text-xs font-bold text-emerald-400 hover:text-emerald-300">
                Buka Riwayat Transaksi Kasir &rarr;
            </a>
        </div>

    </div>

</div>
@endsection
