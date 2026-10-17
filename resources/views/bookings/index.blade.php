@extends('layouts.app')

@section('title', 'Daftar Booking PT & Kelas')
@section('page-title', 'Daftar Booking Personal Trainer & Kelas')
@section('page-subtitle', 'Manajemen reservasi sesi latihan privat, kelas studio, dan presensi kehadiran')

@section('content')
<div class="space-y-6">

    <!-- Stat Header -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
        <div class="p-4 rounded-2xl bg-[#0B0F19] border border-slate-800">
            <span class="text-xs font-bold uppercase tracking-wider text-slate-400 block mb-1">Booking Hari Ini</span>
            <div class="text-2xl font-black text-white font-mono">{{ $todayBookingsCount }}</div>
            <p class="text-[11px] text-slate-400 mt-1">{{ date('d F Y') }}</p>
        </div>

        <div class="p-4 rounded-2xl bg-[#0B0F19] border border-amber-500/30">
            <span class="text-xs font-bold uppercase tracking-wider text-amber-400 block mb-1">Menunggu Konfirmasi</span>
            <div class="text-2xl font-black text-amber-300 font-mono">{{ $pendingCount }}</div>
            <p class="text-[11px] text-amber-400/80 mt-1">Status pending</p>
        </div>

        <div class="p-4 rounded-2xl bg-[#0B0F19] border border-rose-500/30">
            <span class="text-xs font-bold uppercase tracking-wider text-rose-400 block mb-1">Belum Lunas</span>
            <div class="text-2xl font-black text-rose-400 font-mono">{{ $unpaidCount }}</div>
            <p class="text-[11px] text-rose-400/80 mt-1">Dapat dibayar di kasir POS</p>
        </div>

        <div class="p-4 rounded-2xl bg-[#0B0F19] border border-emerald-500/30">
            <span class="text-xs font-bold uppercase tracking-wider text-emerald-400 block mb-1">Sesi Selesai</span>
            <div class="text-2xl font-black text-emerald-400 font-mono">{{ $completedCount }}</div>
            <p class="text-[11px] text-emerald-400/80 mt-1">Presensi terlaksana</p>
        </div>
    </div>

    <!-- Actions and Filters -->
    <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
        
        <form method="GET" action="{{ route('bookings.index') }}" class="flex-1 w-full flex flex-wrap items-center gap-2.5">
            <!-- Search -->
            <div class="relative flex-1 min-w-[180px]">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-500">
                    <i class="fa-solid fa-magnifying-glass text-xs"></i>
                </div>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Kode booking atau nama pelanggan..."
                       class="w-full pl-9 pr-3 py-2 rounded-xl bg-slate-900 border border-slate-700/80 text-white text-xs placeholder-slate-500 focus:outline-none focus:border-emerald-500">
            </div>

            <!-- Date -->
            <input type="date" name="date" value="{{ request('date') }}"
                   class="px-3 py-2 rounded-xl bg-slate-900 border border-slate-700/80 text-white text-xs focus:outline-none focus:border-emerald-500">

            <!-- Status -->
            <select name="status" class="select2 min-w-[150px] px-3 py-2 rounded-xl bg-slate-900 border border-slate-700/80 text-white text-xs focus:outline-none focus:border-emerald-500">
                <option value="">Semua Status</option>
                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="confirmed" {{ request('status') === 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed (Selesai)</option>
                <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
            </select>

            <!-- Payment Status -->
            <select name="payment_status" class="select2 min-w-[150px] px-3 py-2 rounded-xl bg-slate-900 border border-slate-700/80 text-white text-xs focus:outline-none focus:border-emerald-500">
                <option value="">Status Bayar</option>
                <option value="paid" {{ request('payment_status') === 'paid' ? 'selected' : '' }}>Lunas (Paid)</option>
                <option value="unpaid" {{ request('payment_status') === 'unpaid' ? 'selected' : '' }}>Belum Lunas (Unpaid)</option>
            </select>

            <button type="submit" class="px-3.5 py-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-white text-xs font-bold transition-colors">
                Filter
            </button>

            @if(request()->anyFilled(['search', 'date', 'status', 'payment_status', 'booking_type']))
                <a href="{{ route('bookings.index') }}" class="px-3 py-2 rounded-xl bg-slate-900 text-slate-400 hover:text-white border border-slate-800 text-xs">
                    Reset
                </a>
            @endif
        </form>

        <div class="flex items-center gap-2.5 w-full sm:w-auto shrink-0">
            <a href="{{ route('bookings.calendar') }}" 
               class="px-3.5 py-2 rounded-xl bg-slate-900 hover:bg-slate-800 border border-slate-800 text-slate-300 hover:text-white text-xs font-bold flex items-center gap-2 transition-colors">
                <i class="fa-solid fa-calendar-days text-cyan-400"></i>
                <span>Jadwal Kalender</span>
            </a>

            <a href="{{ route('bookings.create') }}" 
               class="px-4 py-2 rounded-xl bg-gradient-to-r from-emerald-500 to-teal-400 hover:from-emerald-400 hover:to-teal-300 text-slate-950 font-black text-xs flex items-center gap-2 shadow-lg shadow-emerald-500/20 transition-all hover:scale-105 active:scale-95">
                <i class="fa-solid fa-plus"></i>
                <span>Buat Booking Baru</span>
            </a>
        </div>
    </div>

    <!-- Bookings Table -->
    <div class="bg-[#0B0F19] rounded-2xl border border-slate-800 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-900/80 border-b border-slate-800 text-slate-400 uppercase tracking-wider font-mono text-[11px]">
                    <tr>
                        <th class="py-3.5 px-4">Kode & Tanggal</th>
                        <th class="py-3.5 px-4">Jam Sesi</th>
                        <th class="py-3.5 px-4">Nama Pelanggan</th>
                        <th class="py-3.5 px-4">Tipe & Instruktur</th>
                        <th class="py-3.5 px-4 text-right">Tarif</th>
                        <th class="py-3.5 px-4 text-center">Status Pembayaran</th>
                        <th class="py-3.5 px-4 text-center">Status Sesi</th>
                        <th class="py-3.5 px-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/80">
                    @forelse($bookings as $bk)
                        <tr class="hover:bg-slate-900/50 transition-colors {{ $bk->status === 'cancelled' ? 'opacity-50' : '' }}">
                            <td class="py-3.5 px-4 whitespace-nowrap">
                                <span class="font-mono font-bold text-white block">{{ $bk->booking_code }}</span>
                                <span class="text-[11px] text-slate-400">{{ $bk->booking_date->format('d M Y') }}</span>
                            </td>

                            <td class="py-3.5 px-4 whitespace-nowrap">
                                <span class="font-mono font-bold text-cyan-400">
                                    {{ substr($bk->start_time, 0, 5) }} - {{ substr($bk->end_time, 0, 5) }}
                                </span>
                            </td>

                            <td class="py-3.5 px-4">
                                <span class="font-bold text-white block">{{ $bk->customer_name }}</span>
                                <span class="text-slate-400 text-[11px]">{{ $bk->customer_phone ?: '-' }}</span>
                            </td>

                            <td class="py-3.5 px-4">
                                <span class="inline-flex px-2 py-0.5 rounded text-[10px] font-bold uppercase {{ $bk->booking_type === 'personal_trainer' ? 'bg-amber-500/20 text-amber-400' : 'bg-cyan-500/20 text-cyan-400' }}">
                                    {{ $bk->booking_type === 'personal_trainer' ? 'Personal Trainer' : 'Kelas Studio' }}
                                </span>
                                <span class="block text-slate-300 font-medium text-xs mt-1">
                                    {{ $bk->trainer ? $bk->trainer->name : ($bk->fitnessClass ? $bk->fitnessClass->name : '-') }}
                                </span>
                            </td>

                            <td class="py-3.5 px-4 text-right font-mono font-bold text-emerald-400 text-xs whitespace-nowrap">
                                Rp {{ number_format($bk->price, 0, ',', '.') }}
                            </td>

                            <td class="py-3.5 px-4 text-center whitespace-nowrap">
                                @if($bk->payment_status === 'paid')
                                    <span class="inline-flex px-2.5 py-1 rounded-full text-[10px] font-bold uppercase bg-emerald-500/20 text-emerald-400 border border-emerald-500/30">
                                        <i class="fa-solid fa-check mr-1 text-[9px]"></i> Lunas
                                    </span>
                                @else
                                    <span class="inline-flex px-2.5 py-1 rounded-full text-[10px] font-bold uppercase bg-rose-500/20 text-rose-400 border border-rose-500/30">
                                        Belum Lunas
                                    </span>
                                @endif
                            </td>

                            <td class="py-3.5 px-4 text-center whitespace-nowrap">
                                @if($bk->status === 'confirmed')
                                    <span class="inline-flex px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase bg-cyan-500/20 text-cyan-400 border border-cyan-500/30">
                                        Confirmed
                                    </span>
                                @elseif($bk->status === 'completed')
                                    <span class="inline-flex px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase bg-emerald-500/20 text-emerald-400 border border-emerald-500/30">
                                        Selesai Hadir
                                    </span>
                                @elseif($bk->status === 'pending')
                                    <span class="inline-flex px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase bg-amber-500/20 text-amber-300 border border-amber-500/30">
                                        Pending
                                    </span>
                                @else
                                    <span class="inline-flex px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase bg-slate-800 text-slate-400 border border-slate-700">
                                        Batal
                                    </span>
                                @endif
                            </td>

                            <td class="py-3.5 px-4 text-right whitespace-nowrap">
                                <div class="flex items-center justify-end gap-1.5">
                                    <!-- Quick Status Toggle Buttons -->
                                    @if($bk->status !== 'completed' && $bk->status !== 'cancelled')
                                        <form method="POST" action="{{ route('bookings.updateStatus', $bk->id) }}">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="completed">
                                            <button type="submit" class="px-2.5 py-1 rounded-lg bg-emerald-500/20 hover:bg-emerald-500/30 text-emerald-300 border border-emerald-500/30 text-[11px] font-bold transition-colors" title="Tandai Selesai / Hadir">
                                                <i class="fa-solid fa-check"></i> Selesai
                                            </button>
                                        </form>
                                    @endif

                                    @if($bk->payment_status === 'unpaid')
                                        <form method="POST" action="{{ route('bookings.updateStatus', $bk->id) }}">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="payment_status" value="paid">
                                            <button type="submit" class="px-2 py-1 rounded-lg bg-cyan-500/20 hover:bg-cyan-500/30 text-cyan-300 border border-cyan-500/30 text-[11px] font-bold transition-colors" title="Tandai Lunas">
                                                Lunas
                                            </button>
                                        </form>
                                    @endif

                                    @if($bk->status !== 'cancelled')
                                        <form method="POST" action="{{ route('bookings.updateStatus', $bk->id) }}" onsubmit="return confirm('Batalkan booking ini?')">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="cancelled">
                                            <button type="submit" class="p-1.5 rounded-lg bg-rose-500/10 hover:bg-rose-500/20 text-rose-400 border border-rose-500/30 transition-colors" title="Batalkan">
                                                <i class="fa-solid fa-ban"></i>
                                            </button>
                                        </form>
                                    @endif

                                    <form method="POST" action="{{ route('bookings.destroy', $bk->id) }}" onsubmit="return confirm('Hapus permanen data booking ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1.5 rounded-lg bg-slate-800 hover:bg-slate-700 text-slate-400 hover:text-white transition-colors" title="Hapus">
                                            <i class="fa-solid fa-trash-can"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-12 text-slate-500 text-xs">
                                Belum ada data booking jadwal sesuai kriteria.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($bookings->hasPages())
            <div class="p-4 border-t border-slate-800">
                {{ $bookings->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
