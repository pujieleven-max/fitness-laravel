@extends('layouts.app')

@section('title', 'Progres Kebugaran & Asesmen Member')
@section('page-title', 'Fitness & Body Measurement Assessment')
@section('page-subtitle', 'Pencatatan riwayat penimbangan berat badan, lingkar tubuh, massa otot, dan persentase lemak klien secara berkala')

@section('content')
<div class="space-y-6">

    <!-- Overview Stats Cards -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Total Asesmen -->
        <div class="p-4 rounded-2xl bg-[#0B0F19] border border-slate-800">
            <div class="flex items-center justify-between mb-1">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Total Sesi Asesmen</span>
                <i class="fa-solid fa-clipboard-check text-slate-400"></i>
            </div>
            <div class="text-2xl font-black text-white font-mono">{{ number_format($totalAssessments) }}</div>
            <p class="text-[11px] text-slate-500 mt-1">Data penimbangan tersimpan</p>
        </div>

        <!-- Member Aktif Asesmen -->
        <div class="p-4 rounded-2xl bg-[#0B0F19] border border-cyan-500/30">
            <div class="flex items-center justify-between mb-1">
                <span class="text-xs font-bold uppercase tracking-wider text-cyan-400">Member Terdata</span>
                <i class="fa-solid fa-users-viewfinder text-cyan-400"></i>
            </div>
            <div class="text-2xl font-black text-cyan-400 font-mono">{{ number_format($uniqueMembersCount) }}</div>
            <p class="text-[11px] text-cyan-400/70 mt-1">Klien dalam pemantauan</p>
        </div>

        <!-- Sesi Bulan Ini -->
        <div class="p-4 rounded-2xl bg-[#0B0F19] border border-emerald-500/30">
            <div class="flex items-center justify-between mb-1">
                <span class="text-xs font-bold uppercase tracking-wider text-emerald-400">Asesmen Bulan Ini</span>
                <i class="fa-solid fa-calendar-check text-emerald-400"></i>
            </div>
            <div class="text-2xl font-black text-emerald-400 font-mono">{{ number_format($thisMonthCount) }}</div>
            <p class="text-[11px] text-emerald-400/70 mt-1">{{ Carbon\Carbon::now()->translatedFormat('F Y') }}</p>
        </div>

        <!-- Trainer Aktif -->
        <div class="p-4 rounded-2xl bg-[#0B0F19] border border-rose-500/30">
            <div class="flex items-center justify-between mb-1">
                <span class="text-xs font-bold uppercase tracking-wider text-rose-400">Instruktur PT</span>
                <i class="fa-solid fa-person-running text-rose-400"></i>
            </div>
            <div class="text-2xl font-black text-rose-400 font-mono">{{ number_format($trainersCount) }}</div>
            <p class="text-[11px] text-rose-400/70 mt-1">Trainer pendamping aktif</p>
        </div>
    </div>

    <!-- Actions & Filter Bar -->
    <div class="p-4 rounded-2xl bg-[#0B0F19] border border-slate-800 space-y-3">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-3 border-b border-slate-800">
            <div>
                <h3 class="text-sm font-bold text-white flex items-center gap-2">
                    <i class="fa-solid fa-heart-pulse text-rose-400"></i>
                    <span>Riwayat Pengukuran & Timbangan Klien PT</span>
                </h3>
                <p class="text-xs text-slate-400">Gunakan menu ini untuk memantau progres fisik dan efektivitas program latihan klien.</p>
            </div>

            <div class="flex items-center gap-2">
                <a href="{{ route('assessments.create') }}"
                   class="px-4 py-2.5 rounded-xl bg-gradient-to-r from-emerald-500 to-teal-400 hover:from-emerald-400 hover:to-teal-300 text-slate-950 font-black text-xs flex items-center gap-2 shadow-lg shadow-emerald-500/20 transition-all">
                    <i class="fa-solid fa-weight-scale"></i>
                    <span>Catat Asesmen Baru</span>
                </a>
            </div>
        </div>

        <form method="GET" action="{{ route('assessments.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3">
            <!-- Search Member -->
            <div>
                <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-400 mb-1">Cari Member</label>
                <div class="relative">
                    <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-slate-500 text-xs"></i>
                    <input type="text" name="search" value="{{ request('search') }}"
                           placeholder="Nama, kode member, no HP..."
                           class="w-full pl-9 pr-3 py-2 bg-slate-900 border border-slate-800 rounded-xl text-xs text-white placeholder-slate-500 focus:border-emerald-500 focus:outline-none focus:ring-1 focus:ring-emerald-500 transition-colors">
                </div>
            </div>

            <!-- Filter Member Dropdown -->
            <div>
                <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-400 mb-1">Pilih Member</label>
                <select name="member_id" class="w-full py-2 px-3 bg-slate-900 border border-slate-800 rounded-xl text-xs text-white focus:border-emerald-500 focus:outline-none">
                    <option value="">Semua Member</option>
                    @foreach($members as $m)
                        <option value="{{ $m->id }}" {{ request('member_id') == $m->id ? 'selected' : '' }}>
                            {{ $m->name }} ({{ $m->member_code }})
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Filter Trainer Dropdown -->
            <div>
                <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-400 mb-1">Instruktur / Trainer</label>
                <select name="trainer_id" class="w-full py-2 px-3 bg-slate-900 border border-slate-800 rounded-xl text-xs text-white focus:border-emerald-500 focus:outline-none">
                    <option value="">Semua Trainer</option>
                    @foreach($trainers as $t)
                        <option value="{{ $t->id }}" {{ request('trainer_id') == $t->id ? 'selected' : '' }}>
                            {{ $t->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Filter Date Range -->
            <div class="flex items-center gap-2">
                <div class="flex-1">
                    <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-400 mb-1">Dari Tanggal</label>
                    <input type="date" name="date_from" value="{{ request('date_from') }}"
                           class="w-full py-1.5 px-2.5 bg-slate-900 border border-slate-800 rounded-xl text-xs text-white focus:border-emerald-500 focus:outline-none">
                </div>
                <div class="flex-1">
                    <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-400 mb-1">Sampai</label>
                    <input type="date" name="date_to" value="{{ request('date_to') }}"
                           class="w-full py-1.5 px-2.5 bg-slate-900 border border-slate-800 rounded-xl text-xs text-white focus:border-emerald-500 focus:outline-none">
                </div>
            </div>

            <!-- Filter Buttons -->
            <div class="flex items-end gap-2">
                <button type="submit"
                        class="flex-1 py-2 px-3 rounded-xl bg-slate-800 hover:bg-slate-700 text-white font-bold text-xs flex items-center justify-center gap-1.5 transition-all">
                    <i class="fa-solid fa-filter text-xs text-emerald-400"></i>
                    <span>Terapkan</span>
                </button>
                @if(request()->hasAny(['search', 'member_id', 'trainer_id', 'date_from', 'date_to']))
                    <a href="{{ route('assessments.index') }}"
                       class="py-2 px-3 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-300 text-xs font-bold transition-all"
                       title="Reset Filter">
                        <i class="fa-solid fa-arrow-rotate-left"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Assessments Table -->
    <div class="bg-[#0B0F19] rounded-2xl border border-slate-800 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-900/80 border-b border-slate-800 text-slate-400 uppercase tracking-wider font-mono text-[11px]">
                    <tr>
                        <th class="py-3.5 px-4">Tanggal Asesmen</th>
                        <th class="py-3.5 px-4">Member Klien</th>
                        <th class="py-3.5 px-4">Trainer Pendamping</th>
                        <th class="py-3.5 px-4 text-center">Berat & BMI</th>
                        <th class="py-3.5 px-4 text-center">Body Fat %</th>
                        <th class="py-3.5 px-4 text-center">Massa Otot</th>
                        <th class="py-3.5 px-4">Target Kebugaran</th>
                        <th class="py-3.5 px-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/80">
                    @forelse($assessments as $item)
                        @php
                            $badge = $item->bmi_badge;
                        @endphp
                        <tr class="hover:bg-slate-900/50 transition-colors">
                            <!-- Tanggal -->
                            <td class="py-3.5 px-4 whitespace-nowrap">
                                <div class="font-mono text-slate-200 font-bold">{{ $item->assessment_date->format('d/m/Y') }}</div>
                                <div class="text-[10px] text-slate-500">{{ $item->assessment_date->diffForHumans() }}</div>
                            </td>

                            <!-- Member -->
                            <td class="py-3.5 px-4 whitespace-nowrap">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-8 h-8 rounded-xl flex items-center justify-center font-bold text-xs text-white bg-slate-800 border border-slate-700">
                                        {{ strtoupper(substr($item->member->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <a href="{{ route('assessments.member', $item->member_id) }}" 
                                           class="font-bold text-white hover:text-emerald-400 transition-colors block">
                                            {{ $item->member->name }}
                                        </a>
                                        <div class="text-[10px] font-mono text-slate-400">
                                            {{ $item->member->member_code }} • {{ ucfirst($item->member->gender) }}
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <!-- Trainer -->
                            <td class="py-3.5 px-4 whitespace-nowrap">
                                @if($item->trainer)
                                    <div class="flex items-center gap-1.5 text-slate-300">
                                        <i class="fa-solid fa-user-ninja text-rose-400 text-xs"></i>
                                        <span class="font-semibold">{{ $item->trainer->name }}</span>
                                    </div>
                                @else
                                    <span class="text-slate-500 italic">Tanpa Trainer</span>
                                @endif
                            </td>

                            <!-- Berat & BMI -->
                            <td class="py-3.5 px-4 text-center whitespace-nowrap">
                                <div class="font-black text-white font-mono text-sm">
                                    {{ number_format($item->weight, 1) }} <span class="text-xs text-slate-400 font-sans font-normal">kg</span>
                                </div>
                                @if($item->bmi)
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-extrabold uppercase border mt-0.5 {{ $badge['bg'] }}">
                                        <span>BMI {{ $item->bmi }}</span>
                                    </span>
                                @endif
                            </td>

                            <!-- Body Fat % -->
                            <td class="py-3.5 px-4 text-center whitespace-nowrap">
                                @if($item->body_fat_percentage)
                                    <span class="font-mono font-bold text-amber-400">
                                        {{ number_format($item->body_fat_percentage, 1) }}%
                                    </span>
                                @else
                                    <span class="text-slate-600">-</span>
                                @endif
                            </td>

                            <!-- Massa Otot -->
                            <td class="py-3.5 px-4 text-center whitespace-nowrap">
                                @if($item->muscle_mass)
                                    <span class="font-mono font-bold text-cyan-400">
                                        {{ number_format($item->muscle_mass, 1) }} kg
                                    </span>
                                @else
                                    <span class="text-slate-600">-</span>
                                @endif
                            </td>

                            <!-- Target Kebugaran -->
                            <td class="py-3.5 px-4">
                                @if($item->fitness_goal)
                                    <span class="px-2 py-0.5 rounded-md bg-slate-800 border border-slate-700 text-[10px] font-bold text-slate-300">
                                        {{ $item->fitness_goal }}
                                    </span>
                                @else
                                    <span class="text-slate-600 text-[11px]">-</span>
                                @endif
                            </td>

                            <!-- Aksi -->
                            <td class="py-3.5 px-4 text-right whitespace-nowrap">
                                <div class="flex items-center justify-end gap-1.5">
                                    <a href="{{ route('assessments.show', $item) }}"
                                       title="Lihat Rincian Pengukuran"
                                       class="w-7 h-7 rounded-lg bg-slate-800 hover:bg-slate-700 text-slate-300 hover:text-white flex items-center justify-center transition-colors">
                                        <i class="fa-solid fa-eye text-xs"></i>
                                    </a>

                                    <a href="{{ route('assessments.member', $item->member_id) }}"
                                       title="Grafik & Riwayat Member"
                                       class="w-7 h-7 rounded-lg bg-cyan-500/10 hover:bg-cyan-500/20 text-cyan-400 flex items-center justify-center transition-colors">
                                        <i class="fa-solid fa-chart-line text-xs"></i>
                                    </a>

                                    <a href="{{ route('assessments.print', $item) }}" target="_blank"
                                       title="Cetak Kartu Progres"
                                       class="w-7 h-7 rounded-lg bg-emerald-500/10 hover:bg-emerald-500/20 text-emerald-400 flex items-center justify-center transition-colors">
                                        <i class="fa-solid fa-print text-xs"></i>
                                    </a>

                                    <a href="{{ route('assessments.edit', $item) }}"
                                       title="Ubah Data"
                                       class="w-7 h-7 rounded-lg bg-slate-800 hover:bg-slate-700 text-slate-300 hover:text-amber-400 flex items-center justify-center transition-colors">
                                        <i class="fa-solid fa-pen-to-square text-xs"></i>
                                    </a>

                                    <form method="POST" action="{{ route('assessments.destroy', $item) }}" 
                                          onsubmit="return confirm('Apakah Anda yakin ingin menghapus data asesmen tanggal {{ $item->assessment_date->format('d/m/Y') }} untuk {{ $item->member->name }}? Tindakan ini akan tercatat dalam log audit.')"
                                          class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" title="Hapus Data"
                                                class="w-7 h-7 rounded-lg bg-slate-800 hover:bg-rose-500/20 text-slate-400 hover:text-rose-400 flex items-center justify-center transition-colors">
                                            <i class="fa-solid fa-trash-can text-xs"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-12 text-center">
                                <div class="w-12 h-12 rounded-2xl bg-slate-900 border border-slate-800 flex items-center justify-center mx-auto mb-3 text-slate-600">
                                    <i class="fa-solid fa-weight-scale text-xl text-rose-400/60"></i>
                                </div>
                                <h4 class="text-sm font-bold text-white mb-1">Belum Ada Catatan Asesmen Kebugaran</h4>
                                <p class="text-xs text-slate-400 max-w-sm mx-auto mb-4">
                                    Mulai catat penimbangan berat badan, lingkar tubuh, dan body fat percentage klien untuk melihat grafik perkembangannya.
                                </p>
                                <a href="{{ route('assessments.create') }}"
                                   class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-bold text-xs transition-all">
                                    <i class="fa-solid fa-plus"></i>
                                    <span>Catat Asesmen Pertama</span>
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($assessments->hasPages())
            <div class="p-4 border-t border-slate-800 bg-slate-900/30">
                {{ $assessments->links() }}
            </div>
        @endif
    </div>

</div>
@endsection

