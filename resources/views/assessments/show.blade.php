@extends('layouts.app')

@section('title', 'Detail Asesmen Kebugaran - ' . $assessment->member->name)
@section('page-title', 'Rincian Asesmen Kebugaran Member')
@section('page-subtitle', 'Analisis komposisi tubuh, lingkar badan, dan evaluasi progres latihan')

@section('content')
<div class="space-y-6">

    <!-- Top Action & Navigation Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 p-4 rounded-2xl bg-[#0B0F19] border border-slate-800">
        <div class="flex items-center gap-3">
            <a href="{{ route('assessments.index') }}" 
               class="w-9 h-9 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-300 flex items-center justify-center transition-colors">
                <i class="fa-solid fa-arrow-left text-xs"></i>
            </a>
            <div>
                <div class="flex items-center gap-2">
                    <h3 class="text-sm font-bold text-white">{{ $assessment->member->name }}</h3>
                    <span class="px-2 py-0.5 rounded text-[10px] font-mono bg-slate-800 text-slate-300 border border-slate-700">
                        {{ $assessment->member->member_code }}
                    </span>
                </div>
                <p class="text-xs text-slate-400">
                    Sesi Asesmen: <span class="text-white font-semibold">{{ $assessment->assessment_date->translatedFormat('d F Y') }}</span> 
                    @if($assessment->trainer)
                        • Trainer: <span class="text-emerald-400 font-semibold">{{ $assessment->trainer->name }}</span>
                    @endif
                </p>
            </div>
        </div>

        <div class="flex items-center gap-2">
            <a href="{{ route('assessments.member', $assessment->member_id) }}"
               class="px-3.5 py-2 rounded-xl bg-cyan-500/10 hover:bg-cyan-500/20 text-cyan-400 border border-cyan-500/30 text-xs font-bold flex items-center gap-1.5 transition-all">
                <i class="fa-solid fa-chart-line text-xs"></i>
                <span>Riwayat Lengkap</span>
            </a>

            <a href="{{ route('assessments.print', $assessment) }}" target="_blank"
               class="px-3.5 py-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-200 text-xs font-bold flex items-center gap-1.5 transition-all">
                <i class="fa-solid fa-print text-xs text-emerald-400"></i>
                <span>Cetak Kartu</span>
            </a>

            <a href="{{ route('assessments.edit', $assessment) }}"
               class="px-3.5 py-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-amber-400 text-xs font-bold flex items-center gap-1.5 transition-all">
                <i class="fa-solid fa-pen-to-square text-xs"></i>
                <span>Edit</span>
            </a>

            <form method="POST" action="{{ route('assessments.destroy', $assessment) }}"
                  onsubmit="return confirm('Apakah Anda yakin ingin menghapus data asesmen ini? Tindakan ini tercatat di audit log.')"
                  class="inline">
                @csrf
                @method('DELETE')
                <button type="submit"
                        class="p-2 rounded-xl bg-rose-500/10 hover:bg-rose-500/20 text-rose-400 border border-rose-500/30 text-xs transition-colors"
                        title="Hapus Asesmen">
                    <i class="fa-solid fa-trash-can"></i>
                </button>
            </form>
        </div>
    </div>

    <!-- COMPARISON DELTA BANNER (Perbandingan dengan Penimbangan Sebelumnya) -->
    @if($previous)
        @php
            $deltaWeight = round($assessment->weight - $previous->weight, 1);
            $deltaFat = ($assessment->body_fat_percentage && $previous->body_fat_percentage) ? round($assessment->body_fat_percentage - $previous->body_fat_percentage, 1) : null;
            $deltaMuscle = ($assessment->muscle_mass && $previous->muscle_mass) ? round($assessment->muscle_mass - $previous->muscle_mass, 1) : null;
            $deltaWaist = ($assessment->waist_circumference && $previous->waist_circumference) ? round($assessment->waist_circumference - $previous->waist_circumference, 1) : null;
        @endphp
        <div class="p-4 rounded-2xl bg-gradient-to-r from-slate-900 to-[#0B0F19] border border-slate-800">
            <div class="flex items-center justify-between mb-3 pb-2 border-b border-slate-800">
                <div class="flex items-center gap-2 text-xs font-bold text-white">
                    <i class="fa-solid fa-code-compare text-cyan-400"></i>
                    <span>Perbandingan dengan Sesi Sebelumnya ({{ $previous->assessment_date->format('d/m/Y') }})</span>
                </div>
                <span class="text-[11px] text-slate-400 font-mono">
                    Selisih {{ $assessment->assessment_date->diffInDays($previous->assessment_date) }} hari
                </span>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                <!-- Berat Badan Delta -->
                <div class="p-3 rounded-xl bg-slate-900/80 border border-slate-800">
                    <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block mb-0.5">Berat Badan</span>
                    <div class="flex items-baseline gap-2">
                        <span class="text-lg font-black text-white font-mono">{{ $assessment->weight }} kg</span>
                        <span class="text-xs font-bold font-mono {{ $deltaWeight < 0 ? 'text-emerald-400' : ($deltaWeight > 0 ? 'text-amber-400' : 'text-slate-400') }}">
                            {{ $deltaWeight > 0 ? '+' : '' }}{{ $deltaWeight }} kg
                        </span>
                    </div>
                </div>

                <!-- Body Fat Delta -->
                <div class="p-3 rounded-xl bg-slate-900/80 border border-slate-800">
                    <span class="text-[10px] font-bold uppercase tracking-wider text-amber-400 block mb-0.5">Lemak Tubuh (Fat %)</span>
                    <div class="flex items-baseline gap-2">
                        <span class="text-lg font-black text-amber-300 font-mono">{{ $assessment->body_fat_percentage ?? '-' }}%</span>
                        @if($deltaFat !== null)
                            <span class="text-xs font-bold font-mono {{ $deltaFat < 0 ? 'text-emerald-400' : ($deltaFat > 0 ? 'text-rose-400' : 'text-slate-400') }}">
                                {{ $deltaFat > 0 ? '+' : '' }}{{ $deltaFat }}%
                            </span>
                        @endif
                    </div>
                </div>

                <!-- Muscle Mass Delta -->
                <div class="p-3 rounded-xl bg-slate-900/80 border border-slate-800">
                    <span class="text-[10px] font-bold uppercase tracking-wider text-cyan-400 block mb-0.5">Massa Otot</span>
                    <div class="flex items-baseline gap-2">
                        <span class="text-lg font-black text-cyan-300 font-mono">{{ $assessment->muscle_mass ?? '-' }} kg</span>
                        @if($deltaMuscle !== null)
                            <span class="text-xs font-bold font-mono {{ $deltaMuscle > 0 ? 'text-emerald-400' : ($deltaMuscle < 0 ? 'text-rose-400' : 'text-slate-400') }}">
                                {{ $deltaMuscle > 0 ? '+' : '' }}{{ $deltaMuscle }} kg
                            </span>
                        @endif
                    </div>
                </div>

                <!-- Lingkar Pinggang Delta -->
                <div class="p-3 rounded-xl bg-slate-900/80 border border-slate-800">
                    <span class="text-[10px] font-bold uppercase tracking-wider text-rose-400 block mb-0.5">Lingkar Pinggang</span>
                    <div class="flex items-baseline gap-2">
                        <span class="text-lg font-black text-white font-mono">{{ $assessment->waist_circumference ?? '-' }} cm</span>
                        @if($deltaWaist !== null)
                            <span class="text-xs font-bold font-mono {{ $deltaWaist < 0 ? 'text-emerald-400' : ($deltaWaist > 0 ? 'text-amber-400' : 'text-slate-400') }}">
                                {{ $deltaWaist > 0 ? '+' : '' }}{{ $deltaWaist }} cm
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- MAIN METRICS & BODY COMPOSITION -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- Left: BMI & Primary Stats Card -->
        <div class="p-6 rounded-2xl bg-[#0B0F19] border border-slate-800 space-y-5">
            <div class="text-center pb-4 border-b border-slate-800">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400 block mb-1">Indeks Massa Tubuh (BMI)</span>
                <div class="text-4xl font-black text-white font-mono my-1">
                    {{ $assessment->bmi ?? '-' }}
                </div>
                @if($assessment->bmi)
                    @php $badge = $assessment->bmi_badge; @endphp
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-extrabold uppercase border {{ $badge['bg'] }}">
                        <i class="fa-solid {{ $badge['icon'] }}"></i>
                        <span>{{ $badge['label'] }}</span>
                    </span>
                @endif

                <div class="grid grid-cols-2 gap-2 mt-4 pt-4 border-t border-slate-800/80 text-left">
                    <div class="p-2.5 rounded-xl bg-slate-900 border border-slate-800">
                        <span class="text-[10px] text-slate-400 block uppercase font-bold">Berat Badan</span>
                        <span class="text-base font-bold text-white font-mono">{{ $assessment->weight }} kg</span>
                    </div>
                    <div class="p-2.5 rounded-xl bg-slate-900 border border-slate-800">
                        <span class="text-[10px] text-slate-400 block uppercase font-bold">Tinggi Badan</span>
                        <span class="text-base font-bold text-white font-mono">{{ $assessment->height ? $assessment->height . ' cm' : '-' }}</span>
                    </div>
                </div>
            </div>

            <!-- Target & Program -->
            <div>
                <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400 block mb-1.5">Program & Target Klien</span>
                <div class="p-3 rounded-xl bg-slate-900 border border-slate-800 flex items-center justify-between">
                    <span class="font-bold text-emerald-400 text-xs">{{ $assessment->fitness_goal ?: 'Program Kebugaran Umum' }}</span>
                    <i class="fa-solid fa-bullseye text-emerald-400"></i>
                </div>
            </div>

            <!-- Progress Photo if Available -->
            @if($assessment->photo_path)
                <div>
                    <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400 block mb-1.5">Foto Dokumentasi Sesi</span>
                    <div class="rounded-xl overflow-hidden border border-slate-800">
                        <img src="{{ asset('storage/' . $assessment->photo_path) }}" alt="Foto Progres" class="w-full h-48 object-cover">
                    </div>
                </div>
            @endif
        </div>

        <!-- Middle & Right: Composition & Measurements (2 Columns on Desktop) -->
        <div class="lg:col-span-2 space-y-6">

            <!-- Bio-Impedance Metrics Grid -->
            <div class="p-6 rounded-2xl bg-[#0B0F19] border border-slate-800 space-y-4">
                <h4 class="text-xs font-bold uppercase tracking-wider text-slate-300 flex items-center gap-2">
                    <i class="fa-solid fa-dna text-cyan-400"></i>
                    <span>Analisis Komposisi Tubuh & Metabolisme</span>
                </h4>

                <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                    <div class="p-3.5 rounded-xl bg-slate-900 border border-slate-800">
                        <span class="text-[10px] font-bold uppercase tracking-wider text-amber-400 block mb-0.5">Body Fat</span>
                        <div class="text-xl font-black text-amber-300 font-mono">{{ $assessment->body_fat_percentage ?? '-' }} <span class="text-xs font-normal text-slate-400">%</span></div>
                        <span class="text-[10px] text-slate-500">Persentase Lemak</span>
                    </div>

                    <div class="p-3.5 rounded-xl bg-slate-900 border border-slate-800">
                        <span class="text-[10px] font-bold uppercase tracking-wider text-cyan-400 block mb-0.5">Massa Otot</span>
                        <div class="text-xl font-black text-cyan-300 font-mono">{{ $assessment->muscle_mass ?? '-' }} <span class="text-xs font-normal text-slate-400">kg</span></div>
                        <span class="text-[10px] text-slate-500">Skeletal Muscle</span>
                    </div>

                    <div class="p-3.5 rounded-xl bg-slate-900 border border-slate-800">
                        <span class="text-[10px] font-bold uppercase tracking-wider text-emerald-400 block mb-0.5">Kadar Air</span>
                        <div class="text-xl font-black text-emerald-300 font-mono">{{ $assessment->body_water_percentage ?? '-' }} <span class="text-xs font-normal text-slate-400">%</span></div>
                        <span class="text-[10px] text-slate-500">Total Body Water</span>
                    </div>

                    <div class="p-3.5 rounded-xl bg-slate-900 border border-slate-800">
                        <span class="text-[10px] font-bold uppercase tracking-wider text-rose-400 block mb-0.5">Lemak Visceral</span>
                        <div class="text-xl font-black text-rose-300 font-mono">{{ $assessment->visceral_fat ?? '-' }}</div>
                        <span class="text-[10px] text-slate-500">Tingkat Lemak Perut</span>
                    </div>

                    <div class="p-3.5 rounded-xl bg-slate-900 border border-slate-800">
                        <span class="text-[10px] font-bold uppercase tracking-wider text-violet-400 block mb-0.5">BMR Basal</span>
                        <div class="text-xl font-black text-violet-300 font-mono">{{ $assessment->bmr ?? '-' }} <span class="text-xs font-normal text-slate-400">kkal</span></div>
                        <span class="text-[10px] text-slate-500">Kalori Harian Minimum</span>
                    </div>

                    <div class="p-3.5 rounded-xl bg-slate-900 border border-slate-800">
                        <span class="text-[10px] font-bold uppercase tracking-wider text-slate-300 block mb-0.5">Usia Metabolik</span>
                        <div class="text-xl font-black text-white font-mono">{{ $assessment->metabolic_age ?? '-' }} <span class="text-xs font-normal text-slate-400">thn</span></div>
                        <span class="text-[10px] text-slate-500">Kebugaran Sel</span>
                    </div>
                </div>
            </div>

            <!-- Body Circumferences Table -->
            <div class="p-6 rounded-2xl bg-[#0B0F19] border border-slate-800 space-y-4">
                <h4 class="text-xs font-bold uppercase tracking-wider text-slate-300 flex items-center gap-2">
                    <i class="fa-solid fa-tape text-amber-400"></i>
                    <span>Ukuran Lingkar Tubuh (Sentimeter)</span>
                </h4>

                <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                    <div class="p-3 rounded-xl bg-slate-900 border border-slate-800 flex items-center justify-between">
                        <span class="text-xs text-slate-400">Dada (Chest)</span>
                        <span class="font-mono font-bold text-white text-sm">{{ $assessment->chest_circumference ? $assessment->chest_circumference . ' cm' : '-' }}</span>
                    </div>

                    <div class="p-3 rounded-xl bg-slate-900 border border-slate-800 flex items-center justify-between">
                        <span class="text-xs text-rose-400 font-semibold">Pinggang (Waist)</span>
                        <span class="font-mono font-bold text-rose-300 text-sm">{{ $assessment->waist_circumference ? $assessment->waist_circumference . ' cm' : '-' }}</span>
                    </div>

                    <div class="p-3 rounded-xl bg-slate-900 border border-slate-800 flex items-center justify-between">
                        <span class="text-xs text-slate-400">Pinggul (Hips)</span>
                        <span class="font-mono font-bold text-white text-sm">{{ $assessment->hips_circumference ? $assessment->hips_circumference . ' cm' : '-' }}</span>
                    </div>

                    <div class="p-3 rounded-xl bg-slate-900 border border-slate-800 flex items-center justify-between">
                        <span class="text-xs text-cyan-400 font-semibold">Lengan (Arm/Biceps)</span>
                        <span class="font-mono font-bold text-cyan-300 text-sm">{{ $assessment->arm_circumference ? $assessment->arm_circumference . ' cm' : '-' }}</span>
                    </div>

                    <div class="p-3 rounded-xl bg-slate-900 border border-slate-800 flex items-center justify-between">
                        <span class="text-xs text-slate-400">Paha (Thigh)</span>
                        <span class="font-mono font-bold text-white text-sm">{{ $assessment->thigh_circumference ? $assessment->thigh_circumference . ' cm' : '-' }}</span>
                    </div>

                    <div class="p-3 rounded-xl bg-slate-900 border border-slate-800 flex items-center justify-between">
                        <span class="text-xs text-slate-400">Betis (Calf)</span>
                        <span class="font-mono font-bold text-white text-sm">{{ $assessment->calf_circumference ? $assessment->calf_circumference . ' cm' : '-' }}</span>
                    </div>
                </div>
            </div>

            <!-- Trainer Notes & Feedback -->
            <div class="p-6 rounded-2xl bg-[#0B0F19] border border-slate-800 space-y-3">
                <h4 class="text-xs font-bold uppercase tracking-wider text-slate-300 flex items-center gap-2">
                    <i class="fa-solid fa-comment-dots text-purple-400"></i>
                    <span>Catatan Evaluasi & Rekomendasi Trainer</span>
                </h4>

                <div class="p-4 rounded-xl bg-slate-900/60 border border-slate-800 text-slate-200 text-xs leading-relaxed">
                    {{ $assessment->trainer_notes ?: 'Belum ada catatan khusus yang diberikan pada sesi asesmen ini.' }}
                </div>
            </div>

        </div>
    </div>

</div>
@endsection

