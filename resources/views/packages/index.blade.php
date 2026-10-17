@extends('layouts.app')

@section('title', 'Tiket Masuk & Paket Gym')
@section('page-title', 'Kelola Tiket Masuk Harian & Paket Langganan Gym')
@section('page-subtitle', 'Manajemen harga tiket harian (Daily Pass), paket bulanan & tahunan, dan masa berlaku')

@section('content')
<div class="space-y-6" x-data="{
    showAddModal: false,
    showEditModal: false,
    selectedPackageId: null,
    editCode: '',
    editName: '',
    editType: 'daily',
    editDuration: 1,
    editPrice: 35000,
    editDescription: '',
    editActive: true,

    onAddTypeChange(e) {
        const type = e.target.value;
        const durationInput = document.getElementById('add_duration');
        if (type === 'daily') durationInput.value = 1;
        else if (type === 'monthly') durationInput.value = 30;
        else if (type === 'annual') durationInput.value = 365;
        else if (type === 'session') durationInput.value = 10;
    }
}">

    <!-- Stats Banner -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
        <div class="p-4 rounded-2xl bg-[#0B0F19] border border-slate-800">
            <span class="text-xs font-bold uppercase tracking-wider text-slate-400 block mb-1">Total Paket Aktif</span>
            <div class="text-2xl font-black text-white font-mono">{{ $totalActive }}</div>
            <p class="text-[11px] text-slate-400 mt-1">Tampil di menu POS</p>
        </div>

        <div class="p-4 rounded-2xl bg-[#0B0F19] border border-cyan-500/30">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold uppercase tracking-wider text-cyan-400 block mb-1">Tiket Masuk Harian</span>
                <i class="fa-solid fa-ticket text-cyan-400"></i>
            </div>
            <div class="text-2xl font-black text-cyan-300 font-mono">{{ $totalDaily }}</div>
            <p class="text-[11px] text-cyan-400/80 mt-1">Akses 1 hari / per visit</p>
        </div>

        <div class="p-4 rounded-2xl bg-[#0B0F19] border border-emerald-500/30">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold uppercase tracking-wider text-emerald-400 block mb-1">Paket Membership</span>
                <i class="fa-solid fa-id-card text-emerald-400"></i>
            </div>
            <div class="text-2xl font-black text-emerald-400 font-mono">{{ $totalMonthly }}</div>
            <p class="text-[11px] text-emerald-400/80 mt-1">Bulanan & Tahunan VIP</p>
        </div>

        <div class="p-4 rounded-2xl bg-[#0B0F19] border border-amber-500/30">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold uppercase tracking-wider text-amber-400 block mb-1">Total Terjual di POS</span>
                <i class="fa-solid fa-cash-register text-amber-400"></i>
            </div>
            <div class="text-2xl font-black text-amber-300 font-mono">{{ $totalPackageSalesCount }}</div>
            <p class="text-[11px] text-amber-400/80 mt-1">Tiket & pass terdistribusi</p>
        </div>
    </div>

    <!-- Filter & Add Button Bar -->
    <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
        
        <form method="GET" action="{{ route('packages.index') }}" class="flex-1 w-full flex flex-wrap items-center gap-2.5">
            <!-- Search -->
            <div class="relative flex-1 min-w-[200px]">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-500">
                    <i class="fa-solid fa-magnifying-glass text-xs"></i>
                </div>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari kode tiket atau nama paket gym..."
                       class="w-full pl-9 pr-3 py-2 rounded-xl bg-slate-900 border border-slate-700/80 text-white text-xs placeholder-slate-500 focus:outline-none focus:border-emerald-500">
            </div>

            <!-- Type Filter -->
            <select name="type" class="select2 min-w-[160px] px-3 py-2 rounded-xl bg-slate-900 border border-slate-700/80 text-white text-xs focus:outline-none focus:border-emerald-500">
                <option value="">Semua Tipe Paket</option>
                <option value="daily" {{ request('type') === 'daily' ? 'selected' : '' }}>Harian (Daily Pass)</option>
                <option value="monthly" {{ request('type') === 'monthly' ? 'selected' : '' }}>Bulanan (Monthly)</option>
                <option value="annual" {{ request('type') === 'annual' ? 'selected' : '' }}>Tahunan (Annual VIP)</option>
                <option value="session" {{ request('type') === 'session' ? 'selected' : '' }}>Paket Sesi / Kunjungan</option>
            </select>

            <!-- Status Filter -->
            <select name="status" class="select2 min-w-[160px] px-3 py-2 rounded-xl bg-slate-900 border border-slate-700/80 text-white text-xs focus:outline-none focus:border-emerald-500">
                <option value="">Semua Status</option>
                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Aktif (Dijual di Kasir)</option>
                <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Nonaktif (Disembunyikan)</option>
            </select>

            <button type="submit" class="px-3.5 py-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-white text-xs font-bold transition-colors">
                Filter
            </button>

            @if(request()->anyFilled(['search', 'type', 'status']))
                <a href="{{ route('packages.index') }}" class="px-3 py-2 rounded-xl bg-slate-900 text-slate-400 hover:text-white border border-slate-800 text-xs">
                    Reset
                </a>
            @endif
        </form>

        <button @click="showAddModal = true" 
                class="px-4 py-2.5 rounded-xl bg-gradient-to-r from-emerald-500 to-teal-400 hover:from-emerald-400 hover:to-teal-300 text-slate-950 font-black text-xs flex items-center gap-2 shadow-lg shadow-emerald-500/20 transition-all shrink-0">
            <i class="fa-solid fa-plus"></i>
            <span>Tambah Paket / Tiket Baru</span>
        </button>
    </div>

    <!-- Grid Cards of Gym Packages -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        @forelse($packages as $pkg)
            <div class="p-5 rounded-2xl bg-[#0B0F19] border transition-all flex flex-col justify-between group {{ $pkg->is_active ? 'border-slate-800 hover:border-slate-700' : 'border-slate-800/50 opacity-60 bg-slate-900/30' }}">
                <div>
                    <!-- Card Top Header -->
                    <div class="flex items-center justify-between gap-2 mb-2.5">
                        <span class="font-mono text-xs font-bold text-slate-400">{{ $pkg->code }}</span>
                        
                        <!-- Type Badges -->
                        @if($pkg->type === 'daily')
                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-extrabold uppercase bg-cyan-500/20 text-cyan-300 border border-cyan-500/30 font-mono">
                                <i class="fa-solid fa-ticket text-[9px]"></i> Tiket Harian
                            </span>
                        @elseif($pkg->type === 'monthly')
                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-extrabold uppercase bg-emerald-500/20 text-emerald-300 border border-emerald-500/30 font-mono">
                                <i class="fa-solid fa-calendar text-[9px]"></i> Bulanan
                            </span>
                        @elseif($pkg->type === 'annual')
                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-extrabold uppercase bg-amber-500/20 text-amber-300 border border-amber-500/30 font-mono">
                                <i class="fa-solid fa-crown text-[9px]"></i> Tahunan VIP
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-extrabold uppercase bg-purple-500/20 text-purple-300 border border-purple-500/30 font-mono">
                                <i class="fa-solid fa-layer-group text-[9px]"></i> Paket Sesi
                            </span>
                        @endif
                    </div>

                    <!-- Package Title -->
                    <h4 class="text-base font-bold text-white group-hover:text-emerald-400 transition-colors">{{ $pkg->name }}</h4>
                    
                    <div class="mt-2 flex items-center gap-2 text-xs text-slate-400 font-medium">
                        <i class="fa-regular fa-clock text-slate-500"></i>
                        <span>Masa Aktif: <strong class="text-slate-200 font-mono">{{ $pkg->duration_days }} Hari</strong></span>
                    </div>

                    <!-- Description -->
                    <p class="text-xs text-slate-400 mt-2.5 leading-relaxed line-clamp-3">
                        {{ $pkg->description ?: 'Akses fasilitas gym dan area latihan FitZone.' }}
                    </p>
                </div>

                <!-- Footer with Price & Actions -->
                <div class="pt-4 mt-4 border-t border-slate-800/80 flex items-center justify-between">
                    <div>
                        <span class="text-[10px] text-slate-500 uppercase font-bold block">Tarif Harga</span>
                        <span class="text-base font-black text-emerald-400 font-mono">
                            Rp {{ number_format($pkg->price, 0, ',', '.') }}
                        </span>
                    </div>

                    <div class="flex items-center gap-1.5">
                        <!-- Toggle Active Status Button -->
                        <form method="POST" action="{{ route('packages.toggle', $pkg->id) }}">
                            @csrf
                            @method('PATCH')
                            <button type="submit" 
                                    class="p-2 rounded-lg text-xs font-bold transition-colors {{ $pkg->is_active ? 'bg-emerald-500/10 text-emerald-400 hover:bg-emerald-500/20 border border-emerald-500/30' : 'bg-slate-800 text-slate-400 hover:text-white' }}"
                                    title="{{ $pkg->is_active ? 'Aktif (Klik untuk sembunyikan dari Kasir)' : 'Nonaktif (Klik untuk tampilkan di Kasir)' }}">
                                <i class="fa-solid {{ $pkg->is_active ? 'fa-eye' : 'fa-eye-slash' }}"></i>
                            </button>
                        </form>

                        <!-- Edit Button -->
                        <button type="button"
                                @click="selectedPackageId = {{ $pkg->id }};
                                        editCode = '{{ addslashes($pkg->code) }}';
                                        editName = '{{ addslashes($pkg->name) }}';
                                        editType = '{{ $pkg->type }}';
                                        editDuration = {{ $pkg->duration_days }};
                                        editPrice = {{ (int)$pkg->price }};
                                        editDescription = '{{ addslashes($pkg->description) }}';
                                        editActive = {{ $pkg->is_active ? 'true' : 'false' }};
                                        showEditModal = true"
                                class="p-2 rounded-lg bg-slate-800 hover:bg-slate-700 text-slate-300 hover:text-white transition-colors"
                                title="Edit Detail Paket">
                            <i class="fa-solid fa-pen-to-square"></i>
                        </button>

                        @if(Auth::user()->isOwner())
                            <!-- Delete Button (Owner Only) -->
                            <form method="POST" action="{{ route('packages.destroy', $pkg->id) }}" onsubmit="return confirm('Hapus permanen paket \'{{ $pkg->name }}\'?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-2 rounded-lg bg-rose-500/10 hover:bg-rose-500/20 text-rose-400 border border-rose-500/30 transition-colors" title="Hapus Paket">
                                    <i class="fa-solid fa-trash-can"></i>
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full py-16 text-center text-slate-500 text-xs">
                <i class="fa-solid fa-ticket text-4xl mb-3 opacity-30"></i>
                <p class="font-bold text-sm text-slate-400">Tidak ada paket gym yang sesuai kriteria pencarian.</p>
                <p class="mt-1">Silakan reset filter atau tambahkan paket tiket baru.</p>
            </div>
        @endforelse
    </div>

    @if($packages->hasPages())
        <div class="p-4 bg-[#0B0F19] rounded-2xl border border-slate-800">
            {{ $packages->links() }}
        </div>
    @endif

    <!-- MODAL ADD PACKAGE -->
    <div x-show="showAddModal" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/80 backdrop-blur-md" style="display: none;">
        
        <div class="bg-slate-950 border border-slate-800 rounded-3xl max-w-lg w-full p-6 shadow-2xl relative" @click.away="showAddModal = false">
            <div class="flex items-center justify-between pb-4 border-b border-slate-800">
                <h3 class="text-base font-bold text-white flex items-center gap-2">
                    <i class="fa-solid fa-ticket text-emerald-400"></i>
                    <span>Tambah Tiket / Paket Gym Baru</span>
                </h3>
                <button @click="showAddModal = false" class="text-slate-400 hover:text-white">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <form method="POST" action="{{ route('packages.store') }}" class="space-y-4 mt-4">
                @csrf

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5">Kode Paket (Otomatis/Kustom)</label>
                        <input type="text" name="code" value="{{ $suggestedCode }}" required placeholder="Contoh: PKG-DAILY-VIP"
                               class="w-full px-3.5 py-2.5 rounded-xl bg-slate-900 border border-slate-700 text-white font-mono text-xs focus:outline-none focus:border-emerald-500">
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5">Tipe Paket</label>
                        <select name="type" @change="onAddTypeChange($event)" class="select2 w-full px-3.5 py-2.5 rounded-xl bg-slate-900 border border-slate-700 text-white text-xs focus:outline-none focus:border-emerald-500">
                            <option value="daily">Harian (Daily Pass)</option>
                            <option value="monthly">Bulanan (Monthly)</option>
                            <option value="annual">Tahunan (Annual VIP)</option>
                            <option value="session">Paket Sesi / Kunjungan</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5">Nama Paket</label>
                    <input type="text" name="name" required placeholder="Contoh: Daily Pass Pelajar / 1 Bulan Unlimited"
                           class="w-full px-3.5 py-2.5 rounded-xl bg-slate-900 border border-slate-700 text-white text-sm focus:outline-none focus:border-emerald-500">
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5">Durasi Berlaku (Hari)</label>
                        <input type="number" id="add_duration" name="duration_days" value="1" min="1" required
                               class="w-full px-3.5 py-2.5 rounded-xl bg-slate-900 border border-slate-700 text-white font-mono text-xs focus:outline-none focus:border-emerald-500">
                        <span class="text-[10px] text-slate-500 mt-1 block">1 hari untuk tiket harian, 30 untuk 1 bulan.</span>
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5">Harga Tiket / Paket (Rp)</label>
                        <input type="number" name="price" value="35000" min="0" required
                               class="w-full px-3.5 py-2.5 rounded-xl bg-slate-900 border border-slate-700 text-emerald-400 font-mono font-bold text-sm focus:outline-none focus:border-emerald-500">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5">Keterangan / Fasilitas</label>
                    <textarea name="description" rows="2" placeholder="Fasilitas locker, free konsultasi, handuk, sauna..."
                              class="w-full px-3.5 py-2 rounded-xl bg-slate-900 border border-slate-700 text-white text-xs focus:outline-none focus:border-emerald-500"></textarea>
                </div>

                <div class="flex items-center gap-2 pt-1">
                    <input type="checkbox" id="add_is_active" name="is_active" value="1" checked
                           class="w-4 h-4 rounded border-slate-700 bg-slate-900 text-emerald-500 focus:ring-emerald-500">
                    <label for="add_is_active" class="text-xs text-slate-300 font-bold cursor-pointer">Langsung tampilkan di Kasir POS</label>
                </div>

                <div class="pt-3 border-t border-slate-800 flex items-center justify-end gap-2.5">
                    <button type="button" @click="showAddModal = false" class="px-4 py-2 rounded-xl bg-slate-900 hover:bg-slate-800 text-slate-400 text-xs font-bold">
                        Batal
                    </button>
                    <button type="submit" class="px-5 py-2.5 rounded-xl bg-emerald-500 hover:bg-emerald-400 text-slate-950 text-xs font-black uppercase tracking-wider shadow-lg shadow-emerald-500/20">
                        Simpan Paket
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL EDIT PACKAGE -->
    <div x-show="showEditModal" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/80 backdrop-blur-md" style="display: none;">
        
        <div class="bg-slate-950 border border-slate-800 rounded-3xl max-w-lg w-full p-6 shadow-2xl relative" @click.away="showEditModal = false">
            <div class="flex items-center justify-between pb-4 border-b border-slate-800">
                <h3 class="text-base font-bold text-white flex items-center gap-2">
                    <i class="fa-solid fa-pen-to-square text-cyan-400"></i>
                    <span>Edit Paket / Tiket Gym</span>
                </h3>
                <button @click="showEditModal = false" class="text-slate-400 hover:text-white">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <form :action="'{{ url('packages') }}/' + (selectedPackageId || '')" method="POST" class="space-y-4 mt-4">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5">Kode Paket</label>
                        <input type="text" name="code" x-model="editCode" required
                               class="w-full px-3.5 py-2.5 rounded-xl bg-slate-900 border border-slate-700 text-white font-mono text-xs focus:outline-none focus:border-cyan-500">
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5">Tipe Paket</label>
                        <select name="type" x-model="editType" class="select2 w-full px-3.5 py-2.5 rounded-xl bg-slate-900 border border-slate-700 text-white text-xs focus:outline-none focus:border-cyan-500">
                            <option value="daily">Harian (Daily Pass)</option>
                            <option value="monthly">Bulanan (Monthly)</option>
                            <option value="annual">Tahunan (Annual VIP)</option>
                            <option value="session">Paket Sesi / Kunjungan</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5">Nama Paket</label>
                    <input type="text" name="name" x-model="editName" required
                           class="w-full px-3.5 py-2.5 rounded-xl bg-slate-900 border border-slate-700 text-white text-sm focus:outline-none focus:border-cyan-500">
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5">Durasi Berlaku (Hari)</label>
                        <input type="number" name="duration_days" x-model.number="editDuration" min="1" required
                               class="w-full px-3.5 py-2.5 rounded-xl bg-slate-900 border border-slate-700 text-white font-mono text-xs focus:outline-none focus:border-cyan-500">
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5">Harga Tiket / Paket (Rp)</label>
                        <input type="number" name="price" x-model.number="editPrice" min="0" required
                               class="w-full px-3.5 py-2.5 rounded-xl bg-slate-900 border border-slate-700 text-emerald-400 font-mono font-bold text-sm focus:outline-none focus:border-cyan-500">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5">Keterangan / Fasilitas</label>
                    <textarea name="description" x-model="editDescription" rows="2"
                              class="w-full px-3.5 py-2 rounded-xl bg-slate-900 border border-slate-700 text-white text-xs focus:outline-none focus:border-cyan-500"></textarea>
                </div>

                <div class="flex items-center gap-2 pt-1">
                    <input type="checkbox" id="edit_is_active" name="is_active" value="1" x-model="editActive"
                           class="w-4 h-4 rounded border-slate-700 bg-slate-900 text-emerald-500 focus:ring-emerald-500">
                    <label for="edit_is_active" class="text-xs text-slate-300 font-bold cursor-pointer">Status Aktif (Tampil di Kasir POS)</label>
                </div>

                <div class="pt-3 border-t border-slate-800 flex items-center justify-end gap-2.5">
                    <button type="button" @click="showEditModal = false" class="px-4 py-2 rounded-xl bg-slate-900 hover:bg-slate-800 text-slate-400 text-xs font-bold">
                        Batal
                    </button>
                    <button type="submit" class="px-5 py-2.5 rounded-xl bg-cyan-500 hover:bg-cyan-400 text-slate-950 text-xs font-black uppercase tracking-wider shadow-lg shadow-cyan-500/20">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
