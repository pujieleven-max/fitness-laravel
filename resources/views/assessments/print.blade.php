<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kartu Asesmen Kebugaran - {{ $assessment->member->name }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        @media print {
            .no-print { display: none !important; }
            body { background: white !important; color: black !important; padding: 0 !important; }
            .print-card { border: 1px solid #ccc !important; box-shadow: none !important; }
        }
        body {
            font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
            background-color: #f1f5f9;
        }
    </style>
</head>
<body class="p-4 sm:p-8 text-slate-800">

    <!-- Top Action Bar (hidden on print) -->
    <div class="max-w-3xl mx-auto mb-6 flex items-center justify-between no-print">
        <a href="{{ route('assessments.show', $assessment) }}" 
           class="px-4 py-2 rounded-xl bg-slate-800 text-white font-bold text-xs flex items-center gap-2 hover:bg-slate-700 transition-colors">
            <i class="fa-solid fa-arrow-left"></i>
            <span>Kembali</span>
        </a>

        <button onclick="window.print()" 
                class="px-5 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs flex items-center gap-2 shadow-lg shadow-emerald-600/20 transition-all">
            <i class="fa-solid fa-print"></i>
            <span>Cetak / Simpan PDF</span>
        </button>
    </div>

    <!-- Printable Progress Card -->
    <div class="max-w-3xl mx-auto bg-white rounded-3xl p-8 sm:p-10 shadow-xl border border-slate-200 print-card">
        
        <!-- Header -->
        <div class="flex items-center justify-between pb-6 border-b-2 border-slate-800">
            <div>
                <h1 class="text-2xl font-black uppercase tracking-tight text-slate-900 font-display">
                    {{ $appSetting->app_name ?? 'FitZone Gym' }}
                </h1>
                <p class="text-xs font-semibold text-emerald-700 tracking-wider uppercase">
                    Fitness & Body Measurement Assessment
                </p>
                <p class="text-[11px] text-slate-500 mt-1">
                    {{ $appSetting->address ?? 'Jl. Gatot Subroto No. 88, Jakarta Selatan' }} • Telp: {{ $appSetting->phone ?? '-' }}
                </p>
            </div>

            <div class="text-right">
                <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider bg-slate-100 text-slate-700 border border-slate-300">
                    Sesi #{{ $assessment->id }}
                </span>
                <p class="text-xs font-bold text-slate-900 mt-2 font-mono">
                    {{ $assessment->assessment_date->translatedFormat('d F Y') }}
                </p>
            </div>
        </div>

        <!-- Member Info Card -->
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 my-6 p-4 rounded-2xl bg-slate-50 border border-slate-200 text-xs">
            <div>
                <span class="text-[10px] font-bold uppercase text-slate-500 block">Nama Member</span>
                <span class="font-bold text-slate-900 text-sm">{{ $assessment->member->name }}</span>
            </div>
            <div>
                <span class="text-[10px] font-bold uppercase text-slate-500 block">ID Member</span>
                <span class="font-mono font-bold text-slate-800">{{ $assessment->member->member_code }}</span>
            </div>
            <div>
                <span class="text-[10px] font-bold uppercase text-slate-500 block">Jenis Kelamin</span>
                <span class="font-semibold text-slate-800">{{ ucfirst($assessment->member->gender) }}</span>
            </div>
            <div>
                <span class="text-[10px] font-bold uppercase text-slate-500 block">Trainer Pendamping</span>
                <span class="font-bold text-emerald-800">{{ $assessment->trainer?->name ?? 'Staf Gym' }}</span>
            </div>
        </div>

        <!-- Core Measurements & BMI Box -->
        <div class="grid grid-cols-3 gap-4 mb-6">
            <div class="p-4 rounded-2xl bg-slate-900 text-white text-center">
                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block mb-1">Berat Badan</span>
                <div class="text-3xl font-black font-mono">{{ $assessment->weight }} <span class="text-xs font-normal text-slate-400">kg</span></div>
            </div>

            <div class="p-4 rounded-2xl bg-slate-900 text-white text-center">
                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block mb-1">Tinggi Badan</span>
                <div class="text-3xl font-black font-mono">{{ $assessment->height ?? '-' }} <span class="text-xs font-normal text-slate-400">cm</span></div>
            </div>

            <div class="p-4 rounded-2xl bg-slate-900 text-white text-center">
                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block mb-1">Indeks BMI</span>
                <div class="text-3xl font-black font-mono text-emerald-400">{{ $assessment->bmi ?? '-' }}</div>
                <span class="text-[10px] font-extrabold uppercase text-slate-300">
                    {{ $assessment->bmi_category ?: 'Kategori BMI' }}
                </span>
            </div>
        </div>

        <!-- Bio-Impedance Composition Table -->
        <div class="mb-6">
            <h3 class="text-xs font-black uppercase tracking-wider text-slate-900 mb-2 pb-1 border-b border-slate-200">
                1. Komposisi Tubuh & Metabolisme
            </h3>
            <div class="grid grid-cols-3 gap-3 text-xs">
                <div class="p-2.5 rounded-xl border border-slate-200 bg-white">
                    <span class="text-[10px] font-bold uppercase text-slate-500 block">Body Fat (%)</span>
                    <span class="text-base font-black font-mono text-slate-900">{{ $assessment->body_fat_percentage ? $assessment->body_fat_percentage . '%' : '-' }}</span>
                </div>
                <div class="p-2.5 rounded-xl border border-slate-200 bg-white">
                    <span class="text-[10px] font-bold uppercase text-slate-500 block">Massa Otot (kg)</span>
                    <span class="text-base font-black font-mono text-slate-900">{{ $assessment->muscle_mass ? $assessment->muscle_mass . ' kg' : '-' }}</span>
                </div>
                <div class="p-2.5 rounded-xl border border-slate-200 bg-white">
                    <span class="text-[10px] font-bold uppercase text-slate-500 block">Kadar Air (%)</span>
                    <span class="text-base font-black font-mono text-slate-900">{{ $assessment->body_water_percentage ? $assessment->body_water_percentage . '%' : '-' }}</span>
                </div>
                <div class="p-2.5 rounded-xl border border-slate-200 bg-white">
                    <span class="text-[10px] font-bold uppercase text-slate-500 block">Lemak Visceral</span>
                    <span class="text-base font-black font-mono text-slate-900">{{ $assessment->visceral_fat ? 'Level ' . $assessment->visceral_fat : '-' }}</span>
                </div>
                <div class="p-2.5 rounded-xl border border-slate-200 bg-white">
                    <span class="text-[10px] font-bold uppercase text-slate-500 block">BMR (Basal Rate)</span>
                    <span class="text-base font-black font-mono text-slate-900">{{ $assessment->bmr ? $assessment->bmr . ' kkal' : '-' }}</span>
                </div>
                <div class="p-2.5 rounded-xl border border-slate-200 bg-white">
                    <span class="text-[10px] font-bold uppercase text-slate-500 block">Usia Metabolik</span>
                    <span class="text-base font-black font-mono text-slate-900">{{ $assessment->metabolic_age ? $assessment->metabolic_age . ' thn' : '-' }}</span>
                </div>
            </div>
        </div>

        <!-- Body Circumferences Table -->
        <div class="mb-6">
            <h3 class="text-xs font-black uppercase tracking-wider text-slate-900 mb-2 pb-1 border-b border-slate-200">
                2. Ukuran Lingkar Tubuh (Sentimeter)
            </h3>
            <div class="grid grid-cols-6 gap-2 text-xs text-center">
                <div class="p-2 rounded-xl bg-slate-50 border border-slate-200">
                    <span class="text-[10px] font-bold uppercase text-slate-500 block">Dada</span>
                    <span class="font-mono font-bold text-slate-900">{{ $assessment->chest_circumference ? $assessment->chest_circumference . ' cm' : '-' }}</span>
                </div>
                <div class="p-2 rounded-xl bg-slate-50 border border-slate-200">
                    <span class="text-[10px] font-bold uppercase text-slate-500 block">Pinggang</span>
                    <span class="font-mono font-bold text-slate-900">{{ $assessment->waist_circumference ? $assessment->waist_circumference . ' cm' : '-' }}</span>
                </div>
                <div class="p-2 rounded-xl bg-slate-50 border border-slate-200">
                    <span class="text-[10px] font-bold uppercase text-slate-500 block">Pinggul</span>
                    <span class="font-mono font-bold text-slate-900">{{ $assessment->hips_circumference ? $assessment->hips_circumference . ' cm' : '-' }}</span>
                </div>
                <div class="p-2 rounded-xl bg-slate-50 border border-slate-200">
                    <span class="text-[10px] font-bold uppercase text-slate-500 block">Lengan</span>
                    <span class="font-mono font-bold text-slate-900">{{ $assessment->arm_circumference ? $assessment->arm_circumference . ' cm' : '-' }}</span>
                </div>
                <div class="p-2 rounded-xl bg-slate-50 border border-slate-200">
                    <span class="text-[10px] font-bold uppercase text-slate-500 block">Paha</span>
                    <span class="font-mono font-bold text-slate-900">{{ $assessment->thigh_circumference ? $assessment->thigh_circumference . ' cm' : '-' }}</span>
                </div>
                <div class="p-2 rounded-xl bg-slate-50 border border-slate-200">
                    <span class="text-[10px] font-bold uppercase text-slate-500 block">Betis</span>
                    <span class="font-mono font-bold text-slate-900">{{ $assessment->calf_circumference ? $assessment->calf_circumference . ' cm' : '-' }}</span>
                </div>
            </div>
        </div>

        <!-- Trainer Notes -->
        <div class="mb-8 p-4 rounded-2xl bg-slate-50 border border-slate-200 text-xs">
            <span class="text-[10px] font-bold uppercase text-slate-500 block mb-1">Catatan Evaluasi & Target Program:</span>
            <p class="text-slate-700 leading-relaxed font-medium">
                {{ $assessment->trainer_notes ?: 'Lanjutkan program latihan terstruktur dan penuhi kebutuhan hidrasi serta asupan protein harian.' }}
            </p>
        </div>

        <!-- Signatures Footer -->
        <div class="grid grid-cols-2 gap-8 pt-8 border-t border-slate-300 text-xs text-center">
            <div>
                <p class="text-slate-500 text-[11px] mb-14">Member Klien Bersangkutan,</p>
                <p class="font-bold text-slate-900 uppercase">({{ $assessment->member->name }})</p>
            </div>

            <div>
                <p class="text-slate-500 text-[11px] mb-14">Personal Trainer / Instruktur,</p>
                <p class="font-bold text-slate-900 uppercase">({{ $assessment->trainer?->name ?? 'Fitness Coach' }})</p>
            </div>
        </div>

    </div>

</body>
</html>

