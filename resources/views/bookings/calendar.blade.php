@extends('layouts.app')

@section('title', 'Jadwal Mingguan (Kalender)')
@section('page-title', 'Kalender & Matriks Jadwal Fitness')
@section('page-subtitle', 'Visualisasi jadwal sesi mingguan Personal Trainer dan Kelas Studio')

@section('content')
<div class="space-y-6">

    <!-- Header Navigation Bar -->
    <div class="flex flex-col sm:flex-row items-center justify-between gap-4 p-4 rounded-2xl bg-[#0B0F19] border border-slate-800">
        <div class="flex items-center gap-3">
            <a href="{{ route('bookings.calendar', ['date' => $startOfWeek->copy()->subWeek()->format('Y-m-d')]) }}" 
               class="p-2.5 rounded-xl bg-slate-900 hover:bg-slate-800 border border-slate-700 text-slate-300 hover:text-white transition-colors"
               title="Minggu Sebelumnya">
                <i class="fa-solid fa-chevron-left"></i>
            </a>

            <div>
                <h3 class="text-base font-bold text-white flex items-center gap-2">
                    <i class="fa-regular fa-calendar text-emerald-400"></i>
                    <span>{{ $startOfWeek->format('d M') }} &ndash; {{ $endOfWeek->format('d M Y') }}</span>
                </h3>
                <span class="text-xs text-slate-400">Minggu Ke-{{ $startOfWeek->weekOfYear }}</span>
            </div>

            <a href="{{ route('bookings.calendar', ['date' => $startOfWeek->copy()->addWeek()->format('Y-m-d')]) }}" 
               class="p-2.5 rounded-xl bg-slate-900 hover:bg-slate-800 border border-slate-700 text-slate-300 hover:text-white transition-colors"
               title="Minggu Berikutnya">
                <i class="fa-solid fa-chevron-right"></i>
            </a>
        </div>

        <div class="flex items-center gap-2">
            <a href="{{ route('bookings.calendar') }}" 
               class="px-3.5 py-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-200 text-xs font-bold transition-colors">
                Hari Ini
            </a>
            <a href="{{ route('bookings.create') }}" 
               class="px-4 py-2 rounded-xl bg-emerald-500 hover:bg-emerald-400 text-slate-950 text-xs font-black flex items-center gap-2 transition-all">
                <i class="fa-solid fa-plus"></i>
                <span>Tambah Booking</span>
            </a>
        </div>
    </div>

    <!-- Weekly Matrix Calendar Grid (7 Days) -->
    <div class="grid grid-cols-1 md:grid-cols-7 gap-3">
        @for($i = 0; $i < 7; $i++)
            @php
                $dayDate = $startOfWeek->copy()->addDays($i);
                $isToday = $dayDate->isToday();
                $dayBookings = $weekBookings->filter(function($b) use ($dayDate) {
                    return $b->booking_date->format('Y-m-d') === $dayDate->format('Y-m-d');
                });
                $indonesianDays = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];
                $indonesianDayName = $indonesianDays[$i];
            @endphp

            <div class="rounded-2xl border flex flex-col min-h-[380px] overflow-hidden {{ $isToday ? 'bg-slate-900/90 border-emerald-500/50 shadow-lg shadow-emerald-500/10' : 'bg-[#0B0F19] border-slate-800' }}">
                
                <!-- Day Header -->
                <div class="p-3 border-b border-slate-800/80 flex items-center justify-between {{ $isToday ? 'bg-emerald-500/10' : 'bg-slate-900/50' }}">
                    <div>
                        <span class="text-xs font-bold uppercase tracking-wider block {{ $isToday ? 'text-emerald-400 font-extrabold' : 'text-slate-400' }}">
                            {{ $indonesianDayName }}
                        </span>
                        <span class="text-sm font-extrabold text-white font-mono">{{ $dayDate->format('d M') }}</span>
                    </div>
                    @if($isToday)
                        <span class="w-2 h-2 rounded-full bg-emerald-400 animate-ping"></span>
                    @endif
                </div>

                <!-- Bookings on this Day -->
                <div class="flex-1 p-2 space-y-2 overflow-y-auto">
                    @forelse($dayBookings as $bk)
                        <div class="p-2.5 rounded-xl border text-xs space-y-1 transition-all hover:scale-[1.02] {{ $bk->booking_type === 'personal_trainer' ? 'bg-amber-500/10 border-amber-500/30 text-amber-200' : 'bg-cyan-500/10 border-cyan-500/30 text-cyan-200' }}">
                            <div class="flex items-center justify-between font-mono font-bold text-[10px]">
                                <span>{{ substr($bk->start_time, 0, 5) }} - {{ substr($bk->end_time, 0, 5) }}</span>
                                <span class="uppercase text-[9px] px-1 rounded bg-black/40">{{ $bk->status }}</span>
                            </div>
                            
                            <h5 class="font-bold text-white truncate text-[11px]">{{ $bk->customer_name }}</h5>
                            
                            <p class="text-[10px] opacity-80 truncate">
                                {{ $bk->trainer ? $bk->trainer->name : ($bk->fitnessClass ? $bk->fitnessClass->name : '-') }}
                            </p>
                        </div>
                    @empty
                        <div class="h-full flex items-center justify-center text-slate-400 text-[11px] text-center p-2">
                            Kosong
                        </div>
                    @endforelse
                </div>

                <!-- Footer Quick Add for This Day -->
                <div class="p-2 border-t border-slate-800/60 bg-slate-900/30">
                    <a href="{{ route('bookings.create') }}" class="block text-center text-[10px] font-bold text-slate-400 hover:text-white py-1 rounded bg-slate-800/50 hover:bg-slate-800 transition-colors">
                        + Slot
                    </a>
                </div>
            </div>
        @endfor
    </div>

    <!-- Regular Fitness Classes Schedule Reference -->
    <div class="p-6 rounded-2xl bg-[#0B0F19] border border-slate-800">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h3 class="font-bold text-white text-base">Jadwal Tetap Kelas Studio Gym</h3>
                <p class="text-xs text-slate-400">Kelas rutin mingguan dengan instruktur bersertifikasi</p>
            </div>
            <a href="{{ route('trainers.index') }}" class="text-xs font-bold text-emerald-400 hover:text-emerald-300">
                Kelola Kelas &rarr;
            </a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            @foreach($classes as $c)
                <div class="p-4 rounded-xl bg-slate-900/80 border border-slate-800 space-y-2">
                    <div class="flex items-center justify-between">
                        <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase bg-cyan-500/20 text-cyan-400 font-mono">
                            Hari {{ $c->schedule_day }}
                        </span>
                        <span class="text-xs font-mono font-bold text-white">{{ substr($c->start_time, 0, 5) }} WIB</span>
                    </div>

                    <h4 class="text-sm font-bold text-white">{{ $c->name }}</h4>
                    <p class="text-xs text-slate-400">{{ $c->room }} &bull; Max {{ $c->capacity }} orang</p>

                    <div class="pt-2 border-t border-slate-800 flex items-center justify-between text-xs">
                        <span class="text-slate-300 font-medium">{{ $c->trainer ? $c->trainer->name : '-' }}</span>
                        <span class="font-bold text-emerald-400 font-mono">Rp {{ number_format($c->price_per_session, 0, ',', '.') }}</span>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

</div>
@endsection
