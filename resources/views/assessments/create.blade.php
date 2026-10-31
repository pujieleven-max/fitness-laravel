@extends('layouts.app')

@section('title', 'Catat Asesmen Kebugaran Member')
@section('page-title', 'Form Asesmen & Penimbangan Kebugaran')
@section('page-subtitle', 'Input data penimbangan berat badan, lingkar tubuh, massa otot, & persentase lemak klien')

@section('content')
<div class="max-w-4xl mx-auto" x-data="assessmentFormApp()">

    <form method="POST" action="{{ route('assessments.store') }}" enctype="multipart/form-data" class="space-y-6">
        @csrf

        <!-- 1. IDENTITAS KLIEN & TANGGAL SESI -->
        <div class="bg-[#0B0F19] rounded-2xl border border-slate-800 p-6 space-y-4">
            <div class="flex items-center gap-2.5 pb-3 border-b border-slate-800">
                <div class="w-8 h-8 rounded-lg bg-emerald-500/10 text-emerald-400 flex items-center justify-center font-bold">
                    <i class="fa-solid fa-user-check text-xs"></i>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-white">1. Identitas Klien Member & Trainer</h3>
                    <p class="text-xs text-slate-400">Pilih member yang ditimbang dan trainer penanggung jawab sesi</p>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                <!-- Member Picker -->
                <div class="sm:col-span-2 lg:col-span-1">
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5">
                        Pilih Member <span class="text-rose-400">*</span>
                    </label>
                    <select name="member_id" required class="select2 w-full px-3 py-2.5 rounded-xl bg-slate-900 border border-slate-700 text-white text-xs">
                        <option value="">-- Pilih Member --</option>
                        @foreach($members as $m)
                            <option value="{{ $m->id }}" {{ (old('member_id', $selectedMember?->id) == $m->id) ? 'selected' : '' }}>
                                {{ $m->member_code }} - {{ $m->name }} ({{ ucfirst($m->gender) }})
                            </option>
                        @endforeach
                    </select>
                    @error('member_id')
                        <p class="text-rose-400 text-[11px] mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Trainer Picker -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5">Trainer Pendamping</label>
                    <select name="trainer_id" class="select2 w-full px-3 py-2.5 rounded-xl bg-slate-900 border border-slate-700 text-white text-xs">
                        <option value="">-- Pilih Trainer (Opsional) --</option>
                        @foreach($trainers as $t)
                            <option value="{{ $t->id }}" {{ old('trainer_id') == $t->id ? 'selected' : '' }}>
                                {{ $t->name }} ({{ $t->specialization }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Tanggal Asesmen -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5">
                        Tanggal Asesmen <span class="text-rose-400">*</span>
                    </label>
                    <input type="date" name="assessment_date" value="{{ old('assessment_date', date('Y-m-d')) }}" required
                           class="w-full px-3.5 py-2.5 rounded-xl bg-slate-900 border border-slate-700 text-white text-xs focus:outline-none focus:border-emerald-500">
                </div>
            </div>

            <!-- Target Kebugaran Klien -->
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5">Target Kebugaran / Program Sesi</label>
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-2">
                    <label class="flex items-center gap-2 p-2.5 rounded-xl bg-slate-900 border border-slate-800 hover:border-slate-700 cursor-pointer text-xs text-slate-300">
                        <input type="radio" name="fitness_goal" value="Fat Loss & Slimming" {{ old('fitness_goal') == 'Fat Loss & Slimming' ? 'checked' : '' }} class="text-emerald-500 focus:ring-0">
                        <span>Fat Loss (Turun Lemak)</span>
                    </label>
                    <label class="flex items-center gap-2 p-2.5 rounded-xl bg-slate-900 border border-slate-800 hover:border-slate-700 cursor-pointer text-xs text-slate-300">
                        <input type="radio" name="fitness_goal" value="Muscle Hypertrophy" {{ old('fitness_goal') == 'Muscle Hypertrophy' ? 'checked' : '' }} class="text-emerald-500 focus:ring-0">
                        <span>Muscle Gain (Massa Otot)</span>
                    </label>
                    <label class="flex items-center gap-2 p-2.5 rounded-xl bg-slate-900 border border-slate-800 hover:border-slate-700 cursor-pointer text-xs text-slate-300">
                        <input type="radio" name="fitness_goal" value="Body Recomposition" {{ old('fitness_goal', 'Body Recomposition') == 'Body Recomposition' ? 'checked' : '' }} class="text-emerald-500 focus:ring-0">
                        <span>Body Recomposition</span>
                    </label>
                    <label class="flex items-center gap-2 p-2.5 rounded-xl bg-slate-900 border border-slate-800 hover:border-slate-700 cursor-pointer text-xs text-slate-300">
                        <input type="radio" name="fitness_goal" value="Stamina & Endurance" {{ old('fitness_goal') == 'Stamina & Endurance' ? 'checked' : '' }} class="text-emerald-500 focus:ring-0">
                        <span>Stamina & Fleksibilitas</span>
                    </label>
                </div>
            </div>
        </div>

        <!-- 2. PENGUKURAN BERAT BADAN & KOMPOSISI UTAMA (LIVE BMI GAUGE) -->
        <div class="bg-[#0B0F19] rounded-2xl border border-slate-800 p-6 space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-slate-800">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-lg bg-cyan-500/10 text-cyan-400 flex items-center justify-center font-bold">
                        <i class="fa-solid fa-weight-scale text-xs"></i>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-white">2. Komposisi Tubuh & Berat Badan</h3>
                        <p class="text-xs text-slate-400">Parameter timbangan bio-impedance & kalkulasi otomatis BMI</p>
                    </div>
                </div>

                <!-- Live BMI Badge Gauge -->
                <div x-show="calculatedBmi" class="flex items-center gap-2 px-3 py-1.5 rounded-xl border" :class="bmiColorClass">
                    <i class="fa-solid fa-gauge text-xs"></i>
                    <span class="font-mono font-bold text-xs" x-text="'BMI ' + calculatedBmi"></span>
                    <span class="text-[10px] font-extrabold uppercase" x-text="'(' + bmiCategory + ')'"></span>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                <!-- Berat Badan -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5">
                        Berat Badan (kg) <span class="text-rose-400">*</span>
                    </label>
                    <div class="relative">
                        <input type="number" step="0.1" name="weight" x-model.number="weight" @input="recalcBmi()" required
                               placeholder="Contoh: 72.5"
                               class="w-full px-3.5 py-2.5 rounded-xl bg-slate-900 border border-slate-700 text-white font-mono text-sm focus:outline-none focus:border-emerald-500">
                        <span class="absolute right-3 top-1/2 -translate-y-1/2 text-xs font-bold text-slate-500">kg</span>
                    </div>
                    @error('weight')
                        <p class="text-rose-400 text-[11px] mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Tinggi Badan -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5">
                        Tinggi Badan (cm)
                    </label>
                    <div class="relative">
                        <input type="number" step="0.1" name="height" x-model.number="height" @input="recalcBmi()"
                               placeholder="Contoh: 175"
                               class="w-full px-3.5 py-2.5 rounded-xl bg-slate-900 border border-slate-700 text-white font-mono text-sm focus:outline-none focus:border-emerald-500">
                        <span class="absolute right-3 top-1/2 -translate-y-1/2 text-xs font-bold text-slate-500">cm</span>
                    </div>
                </div>

                <!-- Body Fat Percentage -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-amber-400 mb-1.5">
                        Body Fat Percentage (%)
                    </label>
                    <div class="relative">
                        <input type="number" step="0.1" name="body_fat_percentage" value="{{ old('body_fat_percentage') }}"
                               placeholder="Contoh: 21.4"
                               class="w-full px-3.5 py-2.5 rounded-xl bg-slate-900 border border-slate-700 text-amber-300 font-mono text-sm focus:outline-none focus:border-amber-500">
                        <span class="absolute right-3 top-1/2 -translate-y-1/2 text-xs font-bold text-amber-500/70">%</span>
                    </div>
                </div>

                <!-- Muscle Mass -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-cyan-400 mb-1.5">
                        Massa Otot (kg)
                    </label>
                    <div class="relative">
                        <input type="number" step="0.1" name="muscle_mass" value="{{ old('muscle_mass') }}"
                               placeholder="Contoh: 48.2"
                               class="w-full px-3.5 py-2.5 rounded-xl bg-slate-900 border border-slate-700 text-cyan-300 font-mono text-sm focus:outline-none focus:border-cyan-500">
                        <span class="absolute right-3 top-1/2 -translate-y-1/2 text-xs font-bold text-cyan-500/70">kg</span>
                    </div>
                </div>

                <!-- Body Water -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5">
                        Kadar Air Tubuh (%)
                    </label>
                    <div class="relative">
                        <input type="number" step="0.1" name="body_water_percentage" value="{{ old('body_water_percentage') }}"
                               placeholder="Contoh: 54.0"
                               class="w-full px-3.5 py-2.5 rounded-xl bg-slate-900 border border-slate-700 text-white font-mono text-sm focus:outline-none focus:border-emerald-500">
                        <span class="absolute right-3 top-1/2 -translate-y-1/2 text-xs font-bold text-slate-500">%</span>
                    </div>
                </div>

                <!-- Lemak Perut / Visceral Fat -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5">
                        Lemak Visceral (Tingkat 1 - 59)
                    </label>
                    <input type="number" name="visceral_fat" value="{{ old('visceral_fat') }}" min="1" max="59"
                           placeholder="Contoh: 6 (Ideal: 1-9)"
                           class="w-full px-3.5 py-2.5 rounded-xl bg-slate-900 border border-slate-700 text-white font-mono text-sm focus:outline-none focus:border-emerald-500">
                </div>

                <!-- BMR -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5">
                        BMR (Basal Metabolic Rate)
                    </label>
                    <div class="relative">
                        <input type="number" name="bmr" value="{{ old('bmr') }}"
                               placeholder="Contoh: 1650"
                               class="w-full px-3.5 py-2.5 rounded-xl bg-slate-900 border border-slate-700 text-white font-mono text-sm focus:outline-none focus:border-emerald-500">
                        <span class="absolute right-3 top-1/2 -translate-y-1/2 text-xs font-bold text-slate-500">kkal</span>
                    </div>
                </div>

                <!-- Massa Tulang -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5">
                        Massa Tulang (Bone Mass)
                    </label>
                    <div class="relative">
                        <input type="number" step="0.1" name="bone_mass" value="{{ old('bone_mass') }}"
                               placeholder="Contoh: 3.1"
                               class="w-full px-3.5 py-2.5 rounded-xl bg-slate-900 border border-slate-700 text-white font-mono text-sm focus:outline-none focus:border-emerald-500">
                        <span class="absolute right-3 top-1/2 -translate-y-1/2 text-xs font-bold text-slate-500">kg</span>
                    </div>
                </div>

                <!-- Usia Metabolik -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5">
                        Usia Metabolik (Tahun)
                    </label>
                    <input type="number" name="metabolic_age" value="{{ old('metabolic_age') }}"
                           placeholder="Contoh: 24"
                           class="w-full px-3.5 py-2.5 rounded-xl bg-slate-900 border border-slate-700 text-white font-mono text-sm focus:outline-none focus:border-emerald-500">
                </div>
            </div>
        </div>

        <!-- 3. PENGUKURAN LINGKAR TUBUH (BODY CIRCUMFERENCE IN CM) -->
        <div class="bg-[#0B0F19] rounded-2xl border border-slate-800 p-6 space-y-4">
            <div class="flex items-center gap-2.5 pb-3 border-b border-slate-800">
                <div class="w-8 h-8 rounded-lg bg-amber-500/10 text-amber-400 flex items-center justify-center font-bold">
                    <i class="fa-solid fa-tape text-xs"></i>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-white">3. Pengukuran Lingkar Tubuh (Sentimeter)</h3>
                    <p class="text-xs text-slate-400">Catat perkembangan lingkar badan untuk melihat perubahan bentuk fisik (body tape)</p>
                </div>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3">
                <!-- Dada -->
                <div>
                    <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-300 mb-1">Dada (Chest)</label>
                    <div class="relative">
                        <input type="number" step="0.5" name="chest_circumference" value="{{ old('chest_circumference') }}"
                               placeholder="cm" class="w-full px-3 py-2 rounded-xl bg-slate-900 border border-slate-700 text-white font-mono text-xs focus:outline-none focus:border-emerald-500">
                        <span class="absolute right-2.5 top-1/2 -translate-y-1/2 text-[10px] text-slate-500">cm</span>
                    </div>
                </div>

                <!-- Pinggang -->
                <div>
                    <label class="block text-[11px] font-bold uppercase tracking-wider text-rose-400 mb-1">Pinggang (Waist)</label>
                    <div class="relative">
                        <input type="number" step="0.5" name="waist_circumference" value="{{ old('waist_circumference') }}"
                               placeholder="cm" class="w-full px-3 py-2 rounded-xl bg-slate-900 border border-slate-700 text-rose-300 font-mono text-xs focus:outline-none focus:border-rose-500">
                        <span class="absolute right-2.5 top-1/2 -translate-y-1/2 text-[10px] text-rose-500/70">cm</span>
                    </div>
                </div>

                <!-- Pinggul -->
                <div>
                    <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-300 mb-1">Pinggul (Hips)</label>
                    <div class="relative">
                        <input type="number" step="0.5" name="hips_circumference" value="{{ old('hips_circumference') }}"
                               placeholder="cm" class="w-full px-3 py-2 rounded-xl bg-slate-900 border border-slate-700 text-white font-mono text-xs focus:outline-none focus:border-emerald-500">
                        <span class="absolute right-2.5 top-1/2 -translate-y-1/2 text-[10px] text-slate-500">cm</span>
                    </div>
                </div>

                <!-- Lengan -->
                <div>
                    <label class="block text-[11px] font-bold uppercase tracking-wider text-cyan-400 mb-1">Lengan (Arm)</label>
                    <div class="relative">
                        <input type="number" step="0.5" name="arm_circumference" value="{{ old('arm_circumference') }}"
                               placeholder="cm" class="w-full px-3 py-2 rounded-xl bg-slate-900 border border-slate-700 text-cyan-300 font-mono text-xs focus:outline-none focus:border-cyan-500">
                        <span class="absolute right-2.5 top-1/2 -translate-y-1/2 text-[10px] text-cyan-500/70">cm</span>
                    </div>
                </div>

                <!-- Paha -->
                <div>
                    <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-300 mb-1">Paha (Thigh)</label>
                    <div class="relative">
                        <input type="number" step="0.5" name="thigh_circumference" value="{{ old('thigh_circumference') }}"
                               placeholder="cm" class="w-full px-3 py-2 rounded-xl bg-slate-900 border border-slate-700 text-white font-mono text-xs focus:outline-none focus:border-emerald-500">
                        <span class="absolute right-2.5 top-1/2 -translate-y-1/2 text-[10px] text-slate-500">cm</span>
                    </div>
                </div>

                <!-- Betis -->
                <div>
                    <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-300 mb-1">Betis (Calf)</label>
                    <div class="relative">
                        <input type="number" step="0.5" name="calf_circumference" value="{{ old('calf_circumference') }}"
                               placeholder="cm" class="w-full px-3 py-2 rounded-xl bg-slate-900 border border-slate-700 text-white font-mono text-xs focus:outline-none focus:border-emerald-500">
                        <span class="absolute right-2.5 top-1/2 -translate-y-1/2 text-[10px] text-slate-500">cm</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- 4. CATATAN TRAINER & FOTO PROGRES -->
        <div class="bg-[#0B0F19] rounded-2xl border border-slate-800 p-6 space-y-4">
            <div class="flex items-center gap-2.5 pb-3 border-b border-slate-800">
                <div class="w-8 h-8 rounded-lg bg-purple-500/10 text-purple-400 flex items-center justify-center font-bold">
                    <i class="fa-solid fa-notes-medical text-xs"></i>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-white">4. Catatan Evaluasi & Foto Progres</h3>
                    <p class="text-xs text-slate-400">Rekomendasi pola makan, menu latihan, dan dokumentasi visual</p>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <!-- Notes -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5">Evaluasi & Anjuran Program Trainer</label>
                    <textarea name="trainer_notes" rows="4"
                              placeholder="Tuliskan catatan evaluasi progres, perubahan intensitas latihan, atau saran defisit/surplus kalori..."
                              class="w-full px-3.5 py-2.5 rounded-xl bg-slate-900 border border-slate-700 text-white text-xs focus:outline-none focus:border-emerald-500">{{ old('trainer_notes') }}</textarea>
                </div>

                <!-- Foto Progres -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5">Foto Progres Fisik (Opsional)</label>
                    <div class="p-4 rounded-xl border border-dashed border-slate-700 bg-slate-900/50 text-center">
                        <i class="fa-solid fa-cloud-arrow-up text-2xl text-slate-500 mb-2"></i>
                        <input type="file" name="photo" accept="image/*"
                               class="w-full text-xs text-slate-400 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-slate-800 file:text-white hover:file:bg-slate-700">
                        <p class="text-[10px] text-slate-500 mt-2">Maksimal 3MB. Format: JPG, PNG, WEBP.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Submit Dock -->
        <div class="flex items-center justify-between p-4 rounded-2xl bg-slate-900/80 border border-slate-800">
            <a href="{{ route('assessments.index') }}"
               class="px-4 py-2.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-300 font-bold text-xs transition-colors">
                Batal
            </a>

            <button type="submit"
                    class="px-6 py-2.5 rounded-xl bg-gradient-to-r from-emerald-500 to-teal-400 hover:from-emerald-400 hover:to-teal-300 text-slate-950 font-black text-xs flex items-center gap-2 shadow-lg shadow-emerald-500/20 transition-all">
                <i class="fa-solid fa-check"></i>
                <span>Simpan Asesmen Kebugaran</span>
            </button>
        </div>
    </form>

</div>

@push('scripts')
<script>
    function assessmentFormApp() {
        return {
            weight: {{ old('weight', $previousAssessment?->weight ?? '') ?: "null" }},
            height: {{ old('height', $previousAssessment?->height ?? '') ?: "null" }},
            calculatedBmi: null,
            bmiCategory: '',
            bmiColorClass: '',

            init() {
                this.recalcBmi();
            },

            recalcBmi() {
                if (!this.weight || !this.height || this.height <= 0) {
                    this.calculatedBmi = null;
                    this.bmiCategory = '';
                    this.bmiColorClass = '';
                    return;
                }

                const hMeters = this.height / 100;
                const bmi = (this.weight / (hMeters * hMeters)).toFixed(1);
                this.calculatedBmi = bmi;

                if (bmi < 18.5) {
                    this.bmiCategory = 'Underweight';
                    this.bmiColorClass = 'bg-cyan-500/10 text-cyan-400 border-cyan-500/30';
                } else if (bmi <= 24.9) {
                    this.bmiCategory = 'Ideal / Normal';
                    this.bmiColorClass = 'bg-emerald-500/10 text-emerald-400 border-emerald-500/30';
                } else if (bmi <= 29.9) {
                    this.bmiCategory = 'Overweight';
                    this.bmiColorClass = 'bg-amber-500/10 text-amber-400 border-amber-500/30';
                } else {
                    this.bmiCategory = 'Obese';
                    this.bmiColorClass = 'bg-rose-500/10 text-rose-400 border-rose-500/30';
                }
            }
        };
    }
</script>
@endpush
@endsection

