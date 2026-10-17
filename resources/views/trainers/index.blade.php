@extends('layouts.app')

@section('title', 'Trainer & Kelas Studio')
@section('page-title', 'Personal Trainers & Kelas Studio Gym')
@section('page-subtitle', 'Daftar instruktur kebugaran berlisensi dan jadwal rutin kelas studio')

@section('content')
<div class="space-y-6" x-data="{
    activeTab: 'trainers',
    showAddTrainerModal: false,
    showAddClassModal: false,
    showEditTrainerModal: false,
    selectedTrainerId: null,
    trainerName: '',
    trainerPhone: '',
    trainerEmail: '',
    trainerSpec: '',
    trainerRate: 150000,
    trainerBio: '',
    trainerActive: true
}">

    <!-- Tab Selector -->
    <div class="flex items-center justify-between border-b border-slate-800 pb-3">
        <div class="flex items-center gap-3">
            <button @click="activeTab = 'trainers'"
                    :class="activeTab === 'trainers' ? 'bg-gradient-to-r from-emerald-500 to-teal-400 text-slate-950 font-black shadow-lg shadow-emerald-500/20' : 'bg-slate-900 text-slate-400 hover:text-white border border-slate-800'"
                    class="px-4 py-2 rounded-xl text-xs font-bold flex items-center gap-2 transition-all">
                <i class="fa-solid fa-person-running"></i>
                <span>Personal Trainers ({{ $trainers->count() }})</span>
            </button>

            <button @click="activeTab = 'classes'"
                    :class="activeTab === 'classes' ? 'bg-gradient-to-r from-cyan-500 to-teal-400 text-slate-950 font-black shadow-lg shadow-cyan-500/20' : 'bg-slate-900 text-slate-400 hover:text-white border border-slate-800'"
                    class="px-4 py-2 rounded-xl text-xs font-bold flex items-center gap-2 transition-all">
                <i class="fa-solid fa-users"></i>
                <span>Kelas Studio Gym ({{ $classes->count() }})</span>
            </button>
        </div>

        <div>
            <button x-show="activeTab === 'trainers'" @click="showAddTrainerModal = true"
                    class="px-4 py-2 rounded-xl bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-black text-xs flex items-center gap-2 transition-all">
                <i class="fa-solid fa-plus"></i>
                <span>Tambah Trainer</span>
            </button>

            <button x-show="activeTab === 'classes'" @click="showAddClassModal = true"
                    class="px-4 py-2 rounded-xl bg-cyan-500 hover:bg-cyan-400 text-slate-950 font-black text-xs flex items-center gap-2 transition-all" style="display: none;">
                <i class="fa-solid fa-plus"></i>
                <span>Jadwalkan Kelas</span>
            </button>
        </div>
    </div>

    <!-- TAB 1: TRAINERS -->
    <div x-show="activeTab === 'trainers'" class="space-y-4">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($trainers as $tr)
                <div class="p-5 rounded-2xl bg-[#0B0F19] border border-slate-800 hover:border-slate-700 transition-all flex flex-col justify-between group">
                    <div>
                        <div class="flex items-start justify-between gap-3 mb-3">
                            <div class="flex items-center gap-3">
                                <div class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-emerald-600 to-teal-400 text-white flex items-center justify-center font-black text-lg shadow-md group-hover:scale-105 transition-transform">
                                    {{ strtoupper(substr($tr->name, 0, 1)) }}
                                </div>
                                <div>
                                    <h4 class="font-bold text-white text-sm group-hover:text-emerald-400 transition-colors">{{ $tr->name }}</h4>
                                    <p class="text-[11px] font-semibold text-emerald-400 mt-0.5">{{ $tr->specialization }}</p>
                                </div>
                            </div>
                            
                            @if($tr->is_active)
                                <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                            @else
                                <span class="w-2 h-2 rounded-full bg-slate-600"></span>
                            @endif
                        </div>

                        <p class="text-xs text-slate-400 line-clamp-3 mb-4 leading-relaxed">
                            {{ $tr->bio ?: 'Instruktur privat fitness berpengalaman membimbing program latihan dan postur.' }}
                        </p>
                    </div>

                    <div class="pt-3 border-t border-slate-800/80 flex items-center justify-between">
                        <div>
                            <span class="text-[10px] text-slate-500 uppercase font-bold block">Tarif per Sesi</span>
                            <span class="text-sm font-black text-white font-mono">Rp {{ number_format($tr->hourly_rate, 0, ',', '.') }}</span>
                        </div>

                        <div class="flex items-center gap-1.5">
                            <button @click="selectedTrainerId = {{ $tr->id }};
                                            trainerName = '{{ addslashes($tr->name) }}';
                                            trainerPhone = '{{ $tr->phone }}';
                                            trainerEmail = '{{ $tr->email }}';
                                            trainerSpec = '{{ addslashes($tr->specialization) }}';
                                            trainerRate = {{ (int)$tr->hourly_rate }};
                                            trainerBio = '{{ addslashes($tr->bio) }}';
                                            trainerActive = {{ $tr->is_active ? 'true' : 'false' }};
                                            showEditTrainerModal = true"
                                    class="p-2 rounded-lg bg-slate-900 hover:bg-slate-800 text-slate-300 hover:text-white transition-colors"
                                    title="Edit Trainer">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </button>

                            @if(Auth::user()->isOwner())
                                <form method="POST" action="{{ route('trainers.destroy', $tr->id) }}" onsubmit="return confirm('Hapus profil trainer {{ $tr->name }}?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 rounded-lg bg-rose-500/10 hover:bg-rose-500/20 text-rose-400 border border-rose-500/30 transition-colors" title="Hapus">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- TAB 2: FITNESS CLASSES -->
    <div x-show="activeTab === 'classes'" class="space-y-4" style="display: none;">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($classes as $c)
                <div class="p-5 rounded-2xl bg-[#0B0F19] border border-cyan-500/20 hover:border-cyan-500/50 transition-all flex flex-col justify-between group">
                    <div>
                        <div class="flex items-center justify-between gap-2 mb-2">
                            <span class="inline-flex px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase bg-cyan-500/20 text-cyan-300 font-mono">
                                Setiap Hari {{ $c->schedule_day }}
                            </span>
                            <span class="text-xs font-mono font-bold text-white">{{ substr($c->start_time, 0, 5) }} - {{ substr($c->end_time, 0, 5) }}</span>
                        </div>

                        <h4 class="font-bold text-white text-base group-hover:text-cyan-300 transition-colors">{{ $c->name }}</h4>
                        <p class="text-xs text-slate-400 mt-1">
                            <i class="fa-solid fa-location-dot text-slate-500 mr-1"></i> {{ $c->room }} &bull; Max {{ $c->capacity }} peserta
                        </p>

                        <div class="mt-3 p-3 rounded-xl bg-slate-900 border border-slate-800 text-xs flex items-center gap-2.5">
                            <i class="fa-solid fa-user-tie text-cyan-400"></i>
                            <div>
                                <span class="text-[10px] text-slate-500 block uppercase">Instruktur / Trainer</span>
                                <span class="font-bold text-slate-200">{{ $c->trainer ? $c->trainer->name : '-' }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="pt-4 mt-4 border-t border-slate-800 flex items-center justify-between">
                        <div>
                            <span class="text-[10px] text-slate-500 uppercase font-bold block">Biaya Sesi</span>
                            <span class="text-sm font-black text-emerald-400 font-mono">Rp {{ number_format($c->price_per_session, 0, ',', '.') }}</span>
                        </div>

                        @if(Auth::user()->isOwner())
                            <form method="POST" action="{{ route('classes.destroy', $c->id) }}" onsubmit="return confirm('Hapus jadwal kelas {{ $c->name }}?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-2 rounded-lg bg-rose-500/10 hover:bg-rose-500/20 text-rose-400 border border-rose-500/30 transition-colors" title="Hapus">
                                    <i class="fa-solid fa-trash-can"></i>
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- MODAL ADD TRAINER -->
    <div x-show="showAddTrainerModal" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/80 backdrop-blur-md" style="display: none;">
        
        <div class="bg-slate-950 border border-slate-800 rounded-3xl max-w-lg w-full p-6 shadow-2xl relative" @click.away="showAddTrainerModal = false">
            <div class="flex items-center justify-between pb-4 border-b border-slate-800">
                <h3 class="text-base font-bold text-white flex items-center gap-2">
                    <i class="fa-solid fa-person-running text-emerald-400"></i>
                    <span>Tambah Personal Trainer Baru</span>
                </h3>
                <button @click="showAddTrainerModal = false" class="text-slate-400 hover:text-white">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <form method="POST" action="{{ route('trainers.store') }}" class="space-y-4 mt-4">
                @csrf

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5">Nama Coach / Trainer</label>
                    <input type="text" name="name" required placeholder="Contoh: Coach Alex Wijaya"
                           class="w-full px-3.5 py-2 rounded-xl bg-slate-900 border border-slate-700 text-white text-xs focus:outline-none focus:border-emerald-500">
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5">Spesialisasi</label>
                        <input type="text" name="specialization" required placeholder="Contoh: Hypertrophy & Fat Loss"
                               class="w-full px-3.5 py-2 rounded-xl bg-slate-900 border border-slate-700 text-white text-xs focus:outline-none focus:border-emerald-500">
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5">Tarif per Sesi (Rp)</label>
                        <input type="number" name="hourly_rate" value="150000" min="0" required
                               class="w-full px-3.5 py-2 rounded-xl bg-slate-900 border border-slate-700 text-white font-mono text-xs focus:outline-none focus:border-emerald-500">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5">No. WhatsApp</label>
                        <input type="text" name="phone" placeholder="0812-xxxx-xxxx"
                               class="w-full px-3.5 py-2 rounded-xl bg-slate-900 border border-slate-700 text-white text-xs focus:outline-none focus:border-emerald-500">
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5">Email</label>
                        <input type="email" name="email" placeholder="coach@fitzone.com"
                               class="w-full px-3.5 py-2 rounded-xl bg-slate-900 border border-slate-700 text-white text-xs focus:outline-none focus:border-emerald-500">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5">Bio Singkat / Sertifikasi</label>
                    <textarea name="bio" rows="2" placeholder="Sertifikasi APKI / pengalaman melatih..."
                              class="w-full px-3.5 py-2 rounded-xl bg-slate-900 border border-slate-700 text-white text-xs focus:outline-none focus:border-emerald-500"></textarea>
                </div>

                <div class="pt-3 border-t border-slate-800 flex items-center justify-end gap-2.5">
                    <button type="button" @click="showAddTrainerModal = false" class="px-4 py-2 rounded-xl bg-slate-900 hover:bg-slate-800 text-slate-400 text-xs font-bold">
                        Batal
                    </button>
                    <button type="submit" class="px-5 py-2.5 rounded-xl bg-emerald-500 hover:bg-emerald-400 text-slate-950 text-xs font-black uppercase tracking-wider shadow-lg shadow-emerald-500/20">
                        Simpan Trainer
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL EDIT TRAINER -->
    <div x-show="showEditTrainerModal" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/80 backdrop-blur-md" style="display: none;">
        
        <div class="bg-slate-950 border border-slate-800 rounded-3xl max-w-lg w-full p-6 shadow-2xl relative" @click.away="showEditTrainerModal = false">
            <div class="flex items-center justify-between pb-4 border-b border-slate-800">
                <h3 class="text-base font-bold text-white flex items-center gap-2">
                    <i class="fa-solid fa-pen-to-square text-emerald-400"></i>
                    <span>Edit Profil Trainer</span>
                </h3>
                <button @click="showEditTrainerModal = false" class="text-slate-400 hover:text-white">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <form :action="'{{ url('trainers') }}/' + (selectedTrainerId || '')" method="POST" class="space-y-4 mt-4">
                @csrf
                @method('PUT')

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5">Nama Coach / Trainer</label>
                    <input type="text" name="name" x-model="trainerName" required
                           class="w-full px-3.5 py-2 rounded-xl bg-slate-900 border border-slate-700 text-white text-xs focus:outline-none focus:border-emerald-500">
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5">Spesialisasi</label>
                        <input type="text" name="specialization" x-model="trainerSpec" required
                               class="w-full px-3.5 py-2 rounded-xl bg-slate-900 border border-slate-700 text-white text-xs focus:outline-none focus:border-emerald-500">
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5">Tarif per Sesi (Rp)</label>
                        <input type="number" name="hourly_rate" x-model.number="trainerRate" min="0" required
                               class="w-full px-3.5 py-2 rounded-xl bg-slate-900 border border-slate-700 text-white font-mono text-xs focus:outline-none focus:border-emerald-500">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5">No. WhatsApp</label>
                        <input type="text" name="phone" x-model="trainerPhone"
                               class="w-full px-3.5 py-2 rounded-xl bg-slate-900 border border-slate-700 text-white text-xs focus:outline-none focus:border-emerald-500">
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5">Status Aktif</label>
                        <select name="is_active" x-model="trainerActive" class="w-full px-3.5 py-2 rounded-xl bg-slate-900 border border-slate-700 text-white text-xs focus:outline-none focus:border-emerald-500">
                            <option :value="true">Aktif</option>
                            <option :value="false">Nonaktif</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5">Bio Singkat / Sertifikasi</label>
                    <textarea name="bio" x-model="trainerBio" rows="2"
                              class="w-full px-3.5 py-2 rounded-xl bg-slate-900 border border-slate-700 text-white text-xs focus:outline-none focus:border-emerald-500"></textarea>
                </div>

                <div class="pt-3 border-t border-slate-800 flex items-center justify-end gap-2.5">
                    <button type="button" @click="showEditTrainerModal = false" class="px-4 py-2 rounded-xl bg-slate-900 hover:bg-slate-800 text-slate-400 text-xs font-bold">
                        Batal
                    </button>
                    <button type="submit" class="px-5 py-2.5 rounded-xl bg-emerald-500 hover:bg-emerald-400 text-slate-950 text-xs font-black uppercase tracking-wider shadow-lg shadow-emerald-500/20">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL ADD CLASS -->
    <div x-show="showAddClassModal" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/80 backdrop-blur-md" style="display: none;">
        
        <div class="bg-slate-950 border border-slate-800 rounded-3xl max-w-lg w-full p-6 shadow-2xl relative" @click.away="showAddClassModal = false">
            <div class="flex items-center justify-between pb-4 border-b border-slate-800">
                <h3 class="text-base font-bold text-white flex items-center gap-2">
                    <i class="fa-solid fa-users text-cyan-400"></i>
                    <span>Jadwalkan Kelas Studio Baru</span>
                </h3>
                <button @click="showAddClassModal = false" class="text-slate-400 hover:text-white">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <form method="POST" action="{{ route('classes.store') }}" class="space-y-4 mt-4">
                @csrf

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5">Nama Kelas</label>
                    <input type="text" name="name" required placeholder="Contoh: Spinning RPM Burn / HIIT Bootcamp"
                           class="w-full px-3.5 py-2 rounded-xl bg-slate-900 border border-slate-700 text-white text-xs focus:outline-none focus:border-cyan-500">
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5">Instruktur / Trainer</label>
                        <select name="trainer_id" class="w-full px-3.5 py-2 rounded-xl bg-slate-900 border border-slate-700 text-white text-xs focus:outline-none focus:border-cyan-500">
                            <option value="">-- Pilih Instruktur --</option>
                            @foreach($trainers as $tr)
                                <option value="{{ $tr->id }}">{{ $tr->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5">Hari Rutin</label>
                        <select name="schedule_day" class="w-full px-3.5 py-2 rounded-xl bg-slate-900 border border-slate-700 text-white text-xs focus:outline-none focus:border-cyan-500">
                            <option value="Senin">Senin</option>
                            <option value="Selasa">Selasa</option>
                            <option value="Rabu">Rabu</option>
                            <option value="Kamis">Kamis</option>
                            <option value="Jumat">Jumat</option>
                            <option value="Sabtu">Sabtu</option>
                            <option value="Minggu">Minggu</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5">Jam Mulai</label>
                        <input type="time" name="start_time" value="18:30" required
                               class="w-full px-3.5 py-2 rounded-xl bg-slate-900 border border-slate-700 text-white text-xs focus:outline-none focus:border-cyan-500">
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5">Jam Selesai</label>
                        <input type="time" name="end_time" value="19:30" required
                               class="w-full px-3.5 py-2 rounded-xl bg-slate-900 border border-slate-700 text-white text-xs focus:outline-none focus:border-cyan-500">
                    </div>
                </div>

                <div class="grid grid-cols-3 gap-3">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5">Ruangan</label>
                        <input type="text" name="room" value="Studio A" required
                               class="w-full px-3.5 py-2 rounded-xl bg-slate-900 border border-slate-700 text-white text-xs focus:outline-none focus:border-cyan-500">
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5">Kapasitas</label>
                        <input type="number" name="capacity" value="20" min="1" required
                               class="w-full px-3.5 py-2 rounded-xl bg-slate-900 border border-slate-700 text-white text-xs focus:outline-none focus:border-cyan-500">
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5">Tarif Sesi (Rp)</label>
                        <input type="number" name="price_per_session" value="50000" min="0" required
                               class="w-full px-3.5 py-2 rounded-xl bg-slate-900 border border-slate-700 text-white font-mono text-xs focus:outline-none focus:border-cyan-500">
                    </div>
                </div>

                <input type="hidden" name="category" value="Studio Group Class">

                <div class="pt-3 border-t border-slate-800 flex items-center justify-end gap-2.5">
                    <button type="button" @click="showAddClassModal = false" class="px-4 py-2 rounded-xl bg-slate-900 hover:bg-slate-800 text-slate-400 text-xs font-bold">
                        Batal
                    </button>
                    <button type="submit" class="px-5 py-2.5 rounded-xl bg-cyan-500 hover:bg-cyan-400 text-slate-950 text-xs font-black uppercase tracking-wider shadow-lg shadow-cyan-500/20">
                        Jadwalkan Kelas
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
