@extends('layouts.app')

@section('title', 'Perjalanan Kebugaran - ' . $member->name)
@section('page-title', 'Transformasi & Progres Kebugaran Member')
@section('page-subtitle', 'Riwayat lengkap penimbangan berat badan, lingkar tubuh, dan analisis body fat klien')

@section('content')
<div class="space-y-6">

    <!-- Member Profile & Milestone Card -->
    <div class="p-6 rounded-3xl bg-[#0B0F19] border border-slate-800 relative overflow-hidden">
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6 relative z-10">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 rounded-2xl bg-gradient-to-tr from-rose-500 to-amber-500 text-white font-black text-xl flex items-center justify-center shadow-lg shadow-rose-500/20">
                    {{ strtoupper(substr($member->name, 0, 1)) }}
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <h3 class="text-lg font-black text-white font-display">{{ $member->name }}</h3>
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-mono font-bold bg-slate-800 text-slate-300 border border-slate-700">
                            {{ $member->member_code }}
                        </span>
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold uppercase {{ $member->status === 'active' ? 'bg-emerald-500/20 text-emerald-300 border border-emerald-500/30' : 'bg-rose-500/20 text-rose-300 border border-rose-500/30' }}">
                            {{ $member->status }}
                        </span>
                    </div>
                    <p class="text-xs text-slate-400 mt-1">
                        Jenis Kelamin: <span class="text-white">{{ ucfirst($member->gender) }}</span> • 
                        No. HP: <span class="font-mono text-white">{{ $member->phone ?: '-' }}</span> • 
                        Paket: <span class="text-emerald-400 font-semibold">{{ ucfirst(str_replace('_', ' ', $member->membership_type)) }}</span>
                    </p>
                </div>
            </div>

            <div class="flex items-center gap-2.5">
                <a href="{{ route('assessments.create', ['member_id' => $member->id]) }}"
                   class="px-4 py-2.5 rounded-xl bg-gradient-to-r from-emerald-500 to-teal-400 hover:from-emerald-400 hover:to-teal-300 text-slate-950 font-black text-xs flex items-center gap-2 shadow-lg shadow-emerald-500/20 transition-all">
                    <i class="fa-solid fa-weight-scale"></i>
                    <span>Timbang & Asesmen Baru</span>
                </a>

                <a href="{{ route('members.index') }}"
                   class="px-4 py-2.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-300 font-bold text-xs transition-colors">
                    Kembali
                </a>
            </div>
        </div>

        <!-- Milestone Transformation Cards -->
        @if($firstAssessment && $latestAssessment && $firstAssessment->id !== $latestAssessment->id)
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mt-6 pt-6 border-t border-slate-800">
                <!-- Berat Total Delta -->
                <div class="p-3.5 rounded-xl bg-slate-900/90 border border-slate-800">
                    <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block mb-1">Perubahan Berat Total</span>
                    <div class="flex items-baseline gap-2">
                        <span class="text-2xl font-black font-mono {{ $weightChange < 0 ? 'text-emerald-400' : ($weightChange > 0 ? 'text-amber-400' : 'text-slate-300') }}">
                            {{ $weightChange > 0 ? '+' : '' }}{{ $weightChange }} kg
                        </span>
                    </div>
                    <span class="text-[10px] text-slate-500">
                        {{ $firstAssessment->weight }} kg ➔ {{ $latestAssessment->weight }} kg
                    </span>
                </div>

                <!-- Fat Total Delta -->
                <div class="p-3.5 rounded-xl bg-slate-900/90 border border-slate-800">
                    <span class="text-[10px] font-bold uppercase tracking-wider text-amber-400 block mb-1">Perubahan Body Fat</span>
                    @if($fatChange !== null)
                        <div class="flex items-baseline gap-2">
                            <span class="text-2xl font-black font-mono {{ $fatChange < 0 ? 'text-emerald-400' : ($fatChange > 0 ? 'text-rose-400' : 'text-slate-300') }}">
                                {{ $fatChange > 0 ? '+' : '' }}{{ $fatChange }}%
                            </span>
                        </div>
                        <span class="text-[10px] text-slate-500">
                            {{ $firstAssessment->body_fat_percentage }}% ➔ {{ $latestAssessment->body_fat_percentage }}%
                        </span>
                    @else
                        <span class="text-sm font-bold text-slate-500">-</span>
                    @endif
                </div>

                <!-- Muscle Total Delta -->
                <div class="p-3.5 rounded-xl bg-slate-900/90 border border-slate-800">
                    <span class="text-[10px] font-bold uppercase tracking-wider text-cyan-400 block mb-1">Massa Otot</span>
                    @if($muscleChange !== null)
                        <div class="flex items-baseline gap-2">
                            <span class="text-2xl font-black font-mono {{ $muscleChange > 0 ? 'text-emerald-400' : ($muscleChange < 0 ? 'text-rose-400' : 'text-slate-300') }}">
                                {{ $muscleChange > 0 ? '+' : '' }}{{ $muscleChange }} kg
                            </span>
                        </div>
                        <span class="text-[10px] text-slate-500">
                            {{ $firstAssessment->muscle_mass }} kg ➔ {{ $latestAssessment->muscle_mass }} kg
                        </span>
                    @else
                        <span class="text-sm font-bold text-slate-500">-</span>
                    @endif
                </div>

                <!-- Durasi Pemantauan -->
                <div class="p-3.5 rounded-xl bg-slate-900/90 border border-slate-800">
                    <span class="text-[10px] font-bold uppercase tracking-wider text-purple-400 block mb-1">Durasi Pemantauan</span>
                    <div class="text-2xl font-black font-mono text-purple-300">
                        {{ $latestAssessment->assessment_date->diffInDays($firstAssessment->assessment_date) }} Hari
                    </div>
                    <span class="text-[10px] text-slate-500">{{ $assessments->count() }} Sesi Penimbangan</span>
                </div>
            </div>
        @endif
    </div>

    <!-- Timeline of Assessments -->
    <div class="bg-[#0B0F19] rounded-2xl border border-slate-800 p-6 space-y-4">
        <h4 class="text-sm font-bold text-white flex items-center gap-2">
            <i class="fa-solid fa-timeline text-emerald-400"></i>
            <span>Kronologi Riwayat Penimbangan & Asesmen ({{ $assessments->count() }} Sesi)</span>
        </h4>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-900/80 border-b border-slate-800 text-slate-400 uppercase font-mono text-[11px]">
                    <tr>
                        <th class="py-3 px-4">Tanggal</th>
                        <th class="py-3 px-4">Trainer</th>
                        <th class="py-3 px-4 text-center">Berat (kg)</th>
                        <th class="py-3 px-4 text-center">BMI</th>
                        <th class="py-3 px-4 text-center">Body Fat %</th>
                        <th class="py-3 px-4 text-center">Massa Otot (kg)</th>
                        <th class="py-3 px-4 text-center">Pinggang (cm)</th>
                        <th class="py-3 px-4">Target / Catatan</th>
                        <th class="py-3 px-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/80">
                    @forelse($assessments as $item)
                        @php $badge = $item->bmi_badge; @endphp
                        <tr class="hover:bg-slate-900/50 transition-colors">
                            <td class="py-3.5 px-4 font-mono font-bold text-white whitespace-nowrap">
                                {{ $item->assessment_date->format('d/m/Y') }}
                                <div class="text-[10px] text-slate-500 font-sans font-normal">{{ $item->assessment_date->diffForHumans() }}</div>
                            </td>

                            <td class="py-3.5 px-4 whitespace-nowrap text-slate-300">
                                {{ $item->trainer?->name ?: '-' }}
                            </td>

                            <td class="py-3.5 px-4 text-center font-mono font-black text-white text-sm whitespace-nowrap">
                                {{ $item->weight }}
                            </td>

                            <td class="py-3.5 px-4 text-center whitespace-nowrap">
                                @if($item->bmi)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-extrabold uppercase border {{ $badge['bg'] }}">
                                        {{ $item->bmi }}
                                    </span>
                                @else
                                    <span class="text-slate-600">-</span>
                                @endif
                            </td>

                            <td class="py-3.5 px-4 text-center font-mono font-bold text-amber-400 whitespace-nowrap">
                                {{ $item->body_fat_percentage ? $item->body_fat_percentage . '%' : '-' }}
                            </td>

                            <td class="py-3.5 px-4 text-center font-mono font-bold text-cyan-400 whitespace-nowrap">
                                {{ $item->muscle_mass ? $item->muscle_mass . ' kg' : '-' }}
                            </td>

                            <td class="py-3.5 px-4 text-center font-mono text-rose-300 whitespace-nowrap">
                                {{ $item->waist_circumference ? $item->waist_circumference . ' cm' : '-' }}
                            </td>

                            <td class="py-3.5 px-4 max-w-xs truncate text-slate-300">
                                {{ $item->trainer_notes ?: ($item->fitness_goal ?: '-') }}
                            </td>

                            <td class="py-3.5 px-4 text-right whitespace-nowrap">
                                <div class="flex items-center justify-end gap-1.5">
                                    <a href="{{ route('assessments.show', $item) }}" 
                                       class="px-2.5 py-1.5 rounded-lg bg-slate-800 hover:bg-slate-700 text-slate-300 hover:text-white text-xs font-bold transition-all">
                                        Rincian
                                    </a>
                                    <a href="{{ route('assessments.print', $item) }}" target="_blank"
                                       class="p-1.5 rounded-lg bg-emerald-500/10 hover:bg-emerald-500/20 text-emerald-400 text-xs transition-colors"
                                       title="Cetak">
                                        <i class="fa-solid fa-print"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="py-12 text-center text-slate-400 text-xs">
                                Member ini belum memiliki data asesmen timbangan kebugaran.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection

