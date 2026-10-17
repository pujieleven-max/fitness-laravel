@extends('layouts.app')

@section('title', 'Dashboard Kasir')
@section('page-title', 'Dashboard Kasir Frontdesk')
@section('page-subtitle', 'Shift Kasir Hari Ini & Operasional POS Gym')

@section('content')
<div class="space-y-6">

    <!-- Big Action Banner: Launch POS -->
    <div class="p-6 sm:p-8 rounded-3xl glass-panel border border-emerald-500/30 shadow-2xl relative overflow-hidden flex flex-col sm:flex-row items-center justify-between gap-6">
        <div class="space-y-2 text-center sm:text-left z-10">
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-extrabold uppercase bg-emerald-500/20 text-emerald-300 border border-emerald-500/30">
                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-ping"></span>
                Terminal Aktif
            </span>
            <h2 class="text-2xl sm:text-3xl font-extrabold text-white font-display">Kasir {{ $appSetting->app_name ?? 'FitZone' }} Siap Melayani</h2>
            <p class="text-sm text-slate-400 max-w-xl">
                Catat penjualan suplemen, tiket gym harian (Daily Pass), aktivasi paket membership, dan pembayaran booking Personal Trainer / Kelas Studio dalam hitungan detik.
            </p>
        </div>
        <div class="z-10 shrink-0">
            <a href="{{ route('pos.index') }}" 
               class="btn-theme-primary px-7 py-4 rounded-2xl text-slate-950 font-black text-sm uppercase tracking-wider flex items-center gap-3 transition-all">
                <i class="fa-solid fa-cash-register text-xl"></i>
                <span>Buka Terminal Kasir POS</span>
            </a>
        </div>
        <div class="absolute -right-10 -bottom-10 w-64 h-64 bg-emerald-500/10 rounded-full blur-3xl pointer-events-none"></div>
    </div>

    <!-- Shift Financial Summary Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 sm:gap-5">
        
        <!-- Total Kasir Hari Ini -->
        <div class="p-6 rounded-3xl glass-panel glass-panel-hover">
            <div class="flex items-center justify-between text-slate-400 mb-2">
                <span class="text-xs font-bold uppercase tracking-wider font-display">Total Penjualan Shift Saya</span>
                <div class="w-9 h-9 rounded-xl bg-emerald-500/15 text-emerald-400 flex items-center justify-center">
                    <i class="fa-solid fa-receipt text-sm"></i>
                </div>
            </div>
            <div class="text-2xl sm:text-3xl font-black text-white font-display tracking-tight">
                Rp {{ number_format($cashierTotalToday, 0, ',', '.') }}
            </div>
            <p class="text-xs text-slate-400 mt-2">Akumulasi transaksi kasir hari ini</p>
        </div>

        <!-- Uang Tunai Diterima -->
        <div class="p-6 rounded-3xl glass-panel glass-panel-hover">
            <div class="flex items-center justify-between text-slate-400 mb-2">
                <span class="text-xs font-bold uppercase tracking-wider font-display">Uang Tunai (Cash di Laci)</span>
                <div class="w-9 h-9 rounded-xl bg-cyan-500/15 text-cyan-400 flex items-center justify-center">
                    <i class="fa-solid fa-money-bill-wave text-sm"></i>
                </div>
            </div>
            <div class="text-2xl sm:text-3xl font-black text-cyan-400 font-display tracking-tight">
                Rp {{ number_format($cashierTodayCash, 0, ',', '.') }}
            </div>
            <p class="text-xs text-slate-400 mt-2">Harus sesuai dengan uang fisik di kasir</p>
        </div>

        <!-- Non Tunai (QRIS / Transfer) -->
        <div class="p-6 rounded-3xl glass-panel glass-panel-hover">
            <div class="flex items-center justify-between text-slate-400 mb-2">
                <span class="text-xs font-bold uppercase tracking-wider font-display">Non-Tunai (QRIS / Transfer)</span>
                <div class="w-9 h-9 rounded-xl bg-amber-500/15 text-amber-400 flex items-center justify-center">
                    <i class="fa-solid fa-qrcode text-sm"></i>
                </div>
            </div>
            <div class="text-2xl sm:text-3xl font-black text-amber-400 font-display tracking-tight">
                Rp {{ number_format($cashierTodayNonCash, 0, ',', '.') }}
            </div>
            <p class="text-xs text-slate-400 mt-2">Otomatis masuk rekening gym</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        
        <!-- Today's Bookings for Check-in -->
        <div class="p-6 rounded-2xl bg-[#0B0F19] border border-slate-800">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="font-bold text-white text-base">Jadwal Sesi Booking Hari Ini</h3>
                    <p class="text-xs text-slate-400">Konfirmasi kehadiran member atau terima pembayaran</p>
                </div>
                <a href="{{ route('bookings.index') }}" class="text-xs font-bold text-emerald-400 hover:text-emerald-300">
                    Buka Booking &rarr;
                </a>
            </div>

            <div class="space-y-3">
                @forelse($todayBookings as $bk)
                    <div class="p-4 rounded-xl bg-slate-900/80 border border-slate-800 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 rounded-xl bg-cyan-500/10 border border-cyan-500/20 text-cyan-400 flex flex-col items-center justify-center font-bold text-xs shrink-0">
                                <span class="font-mono text-sm leading-none">{{ substr($bk->start_time, 0, 5) }}</span>
                                <span class="text-[9px] text-slate-500 uppercase mt-0.5">WIB</span>
                            </div>
                            <div>
                                <h4 class="text-sm font-bold text-white">{{ $bk->customer_name }}</h4>
                                <p class="text-xs text-slate-400">
                                    {{ $bk->booking_type === 'personal_trainer' ? 'Sesi PT' : 'Kelas' }} &bull; 
                                    <span class="text-emerald-400 font-semibold">{{ $bk->trainer ? $bk->trainer->name : ($bk->fitnessClass ? $bk->fitnessClass->name : '-') }}</span>
                                </p>
                                <div class="mt-1 flex items-center gap-2">
                                    <span class="inline-flex px-2 py-0.5 rounded text-[10px] font-bold uppercase {{ $bk->payment_status === 'paid' ? 'bg-emerald-500/20 text-emerald-400' : 'bg-rose-500/20 text-rose-400' }}">
                                        {{ $bk->payment_status === 'paid' ? 'LUNAS' : 'BELUM LUNAS' }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="flex flex-col items-end gap-2">
                            @if($bk->status !== 'completed' && $bk->status !== 'cancelled')
                                <form method="POST" action="{{ route('bookings.updateStatus', $bk->id) }}">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="completed">
                                    <button type="submit" class="px-3 py-1.5 rounded-lg bg-emerald-500/20 hover:bg-emerald-500/30 text-emerald-300 border border-emerald-500/30 text-xs font-bold transition-colors">
                                        <i class="fa-solid fa-check"></i> Check-in Hadir
                                    </button>
                                </form>
                            @else
                                <span class="inline-flex px-2 py-0.5 rounded text-[10px] font-bold uppercase {{ $bk->status === 'confirmed' ? 'bg-emerald-500/20 text-emerald-300 border border-emerald-500/30' : ($bk->status === 'completed' ? 'bg-cyan-500/20 text-cyan-300 border border-cyan-500/30' : ($bk->status === 'pending' ? 'bg-amber-500/20 text-amber-300 border border-amber-500/30' : 'bg-rose-500/20 text-rose-300 border border-rose-500/30')) }}">{{ $bk->status }}</span>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="text-center py-10 text-slate-500 text-xs">
                        Tidak ada booking jadwal untuk hari ini.
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Low Stock Warning for Cashiers -->
        <div class="p-6 rounded-2xl bg-[#0B0F19] border border-slate-800">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="font-bold text-white text-base">Info Stok Suplemen Menipis</h3>
                    <p class="text-xs text-slate-400">Informasi stok untuk kasir saat melayani pelanggan</p>
                </div>
                <i class="fa-solid fa-bell text-amber-400"></i>
            </div>

            <div class="space-y-3">
                @forelse($lowStockSupplements as $supp)
                    <div class="p-3.5 rounded-xl bg-slate-900/80 border border-slate-800 flex items-center justify-between">
                        <div>
                            <h4 class="text-xs font-bold text-white">{{ $supp->name }}</h4>
                            <p class="text-[11px] text-slate-400">{{ $supp->brand }} &bull; {{ $supp->unit }}</p>
                        </div>
                        <div class="text-right">
                            <span class="inline-flex px-2.5 py-1 rounded-lg text-xs font-bold font-mono {{ $supp->stock <= 0 ? 'bg-rose-500/20 text-rose-300 border border-rose-500/30' : 'bg-amber-500/20 text-amber-300 border border-amber-500/30' }}">
                                Sisa: {{ $supp->stock }}
                            </span>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-10 text-emerald-400/70 text-xs flex flex-col items-center gap-2">
                        <i class="fa-solid fa-circle-check text-2xl"></i>
                        <span>Seluruh stok suplemen dalam kondisi aman dan siap jual.</span>
                    </div>
                @endforelse
            </div>
        </div>

    </div>

</div>
@endsection
