@extends('layouts.app')

@section('title', 'Data Member Gym')
@section('page-title', 'Kelola Member & Langganan Gym')
@section('page-subtitle', 'Database member aktif, jenis paket langganan, dan masa berlaku')

@section('content')
<div class="space-y-6" x-data="{
    showAddModal: false,
    showEditModal: false,
    selectedMemberId: null,
    editName: '',
    editPhone: '',
    editEmail: '',
    editGender: 'male',
    editAddress: '',
    editPlan: 'monthly_bronze',
    editExpires: '',
    editStatus: 'active'
}">

    <!-- Stats -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="p-4 rounded-2xl bg-[#0B0F19] border border-slate-800">
            <span class="text-xs font-bold uppercase tracking-wider text-slate-400 block mb-1">Total Member Terdaftar</span>
            <div class="text-2xl font-black text-white font-mono">{{ $totalMembers }}</div>
        </div>

        <div class="p-4 rounded-2xl bg-[#0B0F19] border border-emerald-500/30">
            <span class="text-xs font-bold uppercase tracking-wider text-emerald-400 block mb-1">Member Aktif</span>
            <div class="text-2xl font-black text-emerald-400 font-mono">{{ $activeCount }}</div>
        </div>

        <div class="p-4 rounded-2xl bg-[#0B0F19] border border-rose-500/30">
            <span class="text-xs font-bold uppercase tracking-wider text-rose-400 block mb-1">Langganan Kedaluwarsa</span>
            <div class="text-2xl font-black text-rose-400 font-mono">{{ $expiredCount }}</div>
        </div>
    </div>

    <!-- Filter & Add Button -->
    <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
        <form method="GET" action="{{ route('members.index') }}" class="flex-1 w-full flex flex-wrap items-center gap-2.5">
            <div class="relative flex-1 min-w-[200px]">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-500">
                    <i class="fa-solid fa-magnifying-glass text-xs"></i>
                </div>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari kode member, nama, atau no HP..."
                       class="w-full pl-9 pr-3 py-2 rounded-xl bg-slate-900 border border-slate-700/80 text-white text-xs placeholder-slate-500 focus:outline-none focus:border-emerald-500">
            </div>

            <select name="membership_type" class="px-3 py-2 rounded-xl bg-slate-900 border border-slate-700/80 text-white text-xs focus:outline-none focus:border-emerald-500">
                <option value="">Semua Paket</option>
                <option value="monthly_bronze" {{ request('membership_type') === 'monthly_bronze' ? 'selected' : '' }}>Bronze (1 Bulan)</option>
                <option value="monthly_silver" {{ request('membership_type') === 'monthly_silver' ? 'selected' : '' }}>Silver (3 Bulan)</option>
                <option value="annual_gold" {{ request('membership_type') === 'annual_gold' ? 'selected' : '' }}>Gold VIP (1 Tahun)</option>
                <option value="daily" {{ request('membership_type') === 'daily' ? 'selected' : '' }}>Daily Pass</option>
            </select>

            <select name="status" class="px-3 py-2 rounded-xl bg-slate-900 border border-slate-700/80 text-white text-xs focus:outline-none focus:border-emerald-500">
                <option value="">Semua Status</option>
                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Aktif</option>
                <option value="expired" {{ request('status') === 'expired' ? 'selected' : '' }}>Expired</option>
                <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Non-aktif</option>
            </select>

            <button type="submit" class="px-3.5 py-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-white text-xs font-bold transition-colors">
                Filter
            </button>
        </form>

        <button @click="showAddModal = true" 
                class="px-4 py-2 rounded-xl bg-gradient-to-r from-emerald-500 to-teal-400 hover:from-emerald-400 hover:to-teal-300 text-slate-950 font-black text-xs flex items-center gap-2 shadow-lg shadow-emerald-500/20 transition-all shrink-0">
            <i class="fa-solid fa-user-plus"></i>
            <span>Daftar Member Baru</span>
        </button>
    </div>

    <!-- Members Table -->
    <div class="bg-[#0B0F19] rounded-2xl border border-slate-800 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-900/80 border-b border-slate-800 text-slate-400 uppercase tracking-wider font-mono text-[11px]">
                    <tr>
                        <th class="py-3.5 px-4">Kode Member</th>
                        <th class="py-3.5 px-4">Nama Lengkap</th>
                        <th class="py-3.5 px-4">Kontak (WhatsApp/Email)</th>
                        <th class="py-3.5 px-4">Paket Langganan</th>
                        <th class="py-3.5 px-4">Masa Berlaku</th>
                        <th class="py-3.5 px-4 text-center">Status</th>
                        <th class="py-3.5 px-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/80">
                    @forelse($members as $mbr)
                        <tr class="hover:bg-slate-900/50 transition-colors">
                            <td class="py-3.5 px-4 whitespace-nowrap font-mono font-bold text-emerald-400">
                                {{ $mbr->member_code }}
                            </td>

                            <td class="py-3.5 px-4">
                                <span class="font-bold text-white text-sm block">{{ $mbr->name }}</span>
                                <span class="text-[11px] text-slate-400 capitalize">{{ $mbr->gender === 'male' ? 'Pria' : 'Wanita' }}</span>
                            </td>

                            <td class="py-3.5 px-4 text-slate-300">
                                <span class="block font-mono">{{ $mbr->phone ?: '-' }}</span>
                                <span class="block text-[11px] text-slate-400">{{ $mbr->email ?: '' }}</span>
                            </td>

                            <td class="py-3.5 px-4">
                                <span class="inline-flex px-2 py-0.5 rounded text-[10px] font-bold uppercase bg-slate-800 text-slate-200 border border-slate-700">
                                    {{ str_replace('_', ' ', $mbr->membership_type) }}
                                </span>
                            </td>

                            <td class="py-3.5 px-4 whitespace-nowrap font-mono">
                                @if($mbr->membership_expires_at)
                                    <span class="block {{ $mbr->isMembershipActive() ? 'text-white' : 'text-rose-400 font-bold' }}">
                                        s/d {{ $mbr->membership_expires_at->format('d M Y') }}
                                    </span>
                                    @if($mbr->isMembershipActive())
                                        <span class="text-[10px] text-slate-400">{{ $mbr->membership_expires_at->diffForHumans() }}</span>
                                    @else
                                        <span class="text-[10px] text-rose-400">Expired</span>
                                    @endif
                                @else
                                    <span class="text-slate-500">-</span>
                                @endif
                            </td>

                            <td class="py-3.5 px-4 text-center whitespace-nowrap">
                                @if($mbr->status === 'active' && $mbr->isMembershipActive())
                                    <span class="inline-flex px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase bg-emerald-500/20 text-emerald-400 border border-emerald-500/30">
                                        Aktif
                                    </span>
                                @elseif($mbr->status === 'expired' || ! $mbr->isMembershipActive())
                                    <span class="inline-flex px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase bg-rose-500/20 text-rose-400 border border-rose-500/30">
                                        Habis
                                    </span>
                                @else
                                    <span class="inline-flex px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase bg-slate-800 text-slate-400 border border-slate-700">
                                        Nonaktif
                                    </span>
                                @endif
                            </td>

                            <td class="py-3.5 px-4 text-right whitespace-nowrap">
                                <div class="flex items-center justify-end gap-1.5">
                                    <button @click="selectedMemberId = {{ $mbr->id }}; 
                                                    editName = '{{ addslashes($mbr->name) }}';
                                                    editPhone = '{{ $mbr->phone }}';
                                                    editEmail = '{{ $mbr->email }}';
                                                    editGender = '{{ $mbr->gender }}';
                                                    editPlan = '{{ $mbr->membership_type }}';
                                                    editExpires = '{{ $mbr->membership_expires_at ? $mbr->membership_expires_at->format('Y-m-d') : '' }}';
                                                    editStatus = '{{ $mbr->status }}';
                                                    showEditModal = true"
                                            class="p-1.5 rounded-lg bg-slate-800 hover:bg-slate-700 text-slate-300 hover:text-white transition-colors"
                                            title="Edit / Perpanjang">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </button>

                                    @if(Auth::user()->isOwner())
                                        <form method="POST" action="{{ route('members.destroy', $mbr->id) }}" onsubmit="return confirm('Hapus data member {{ $mbr->name }}?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-1.5 rounded-lg bg-rose-500/10 hover:bg-rose-500/20 text-rose-400 border border-rose-500/30 transition-colors" title="Hapus">
                                                <i class="fa-solid fa-trash-can"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-12 text-slate-500 text-xs">
                                Tidak ada data member yang ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($members->hasPages())
            <div class="p-4 border-t border-slate-800">
                {{ $members->links() }}
            </div>
        @endif
    </div>

    <!-- MODAL ADD MEMBER -->
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
                    <i class="fa-solid fa-user-plus text-emerald-400"></i>
                    <span>Daftar Member Gym Baru</span>
                </h3>
                <button @click="showAddModal = false" class="text-slate-400 hover:text-white">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <form method="POST" action="{{ route('members.store') }}" class="space-y-4 mt-4">
                @csrf

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5">Nama Lengkap</label>
                    <input type="text" name="name" required placeholder="Nama member baru"
                           class="w-full px-3.5 py-2 rounded-xl bg-slate-900 border border-slate-700 text-white text-xs focus:outline-none focus:border-emerald-500">
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5">No. WhatsApp</label>
                        <input type="text" name="phone" placeholder="0812-xxxx-xxxx"
                               class="w-full px-3.5 py-2 rounded-xl bg-slate-900 border border-slate-700 text-white text-xs focus:outline-none focus:border-emerald-500">
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5">Email</label>
                        <input type="email" name="email" placeholder="email@contoh.com"
                               class="w-full px-3.5 py-2 rounded-xl bg-slate-900 border border-slate-700 text-white text-xs focus:outline-none focus:border-emerald-500">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5">Jenis Kelamin</label>
                        <select name="gender" class="w-full px-3.5 py-2 rounded-xl bg-slate-900 border border-slate-700 text-white text-xs focus:outline-none focus:border-emerald-500">
                            <option value="male">Pria</option>
                            <option value="female">Wanita</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5">Pilihan Paket Langganan</label>
                        <select name="membership_type" class="w-full px-3.5 py-2 rounded-xl bg-slate-900 border border-slate-700 text-white text-xs focus:outline-none focus:border-emerald-500">
                            <option value="monthly_bronze">Bronze 1 Bulan (30 Hari)</option>
                            <option value="monthly_silver">Silver 3 Bulan (90 Hari)</option>
                            <option value="annual_gold">Gold VIP 1 Tahun (365 Hari)</option>
                            <option value="daily">Daily Pass</option>
                            <option value="none">Tanpa Paket (Hanya Data)</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5">Mulai Tanggal</label>
                        <input type="date" name="membership_start" value="{{ date('Y-m-d') }}"
                               class="w-full px-3.5 py-2 rounded-xl bg-slate-900 border border-slate-700 text-white text-xs focus:outline-none focus:border-emerald-500">
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5">Masa Berlaku s/d</label>
                        <input type="date" name="membership_expires_at" value="{{ date('Y-m-d', strtotime('+30 days')) }}"
                               class="w-full px-3.5 py-2 rounded-xl bg-slate-900 border border-slate-700 text-white text-xs focus:outline-none focus:border-emerald-500">
                    </div>
                </div>

                <div class="pt-3 border-t border-slate-800 flex items-center justify-end gap-2.5">
                    <button type="button" @click="showAddModal = false" class="px-4 py-2 rounded-xl bg-slate-900 hover:bg-slate-800 text-slate-400 text-xs font-bold">
                        Batal
                    </button>
                    <button type="submit" class="px-5 py-2.5 rounded-xl bg-emerald-500 hover:bg-emerald-400 text-slate-950 text-xs font-black uppercase tracking-wider shadow-lg shadow-emerald-500/20">
                        Simpan Member Baru
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL EDIT MEMBER -->
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
                    <span>Edit Member & Perpanjang</span>
                </h3>
                <button @click="showEditModal = false" class="text-slate-400 hover:text-white">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <form :action="'{{ url('members') }}/' + (selectedMemberId || '')" method="POST" class="space-y-4 mt-4">
                @csrf
                @method('PUT')

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5">Nama Member</label>
                    <input type="text" name="name" x-model="editName" required
                           class="w-full px-3.5 py-2 rounded-xl bg-slate-900 border border-slate-700 text-white text-xs focus:outline-none focus:border-cyan-500">
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5">No. WhatsApp</label>
                        <input type="text" name="phone" x-model="editPhone"
                               class="w-full px-3.5 py-2 rounded-xl bg-slate-900 border border-slate-700 text-white text-xs focus:outline-none focus:border-cyan-500">
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5">Status Akun</label>
                        <select name="status" x-model="editStatus" class="w-full px-3.5 py-2 rounded-xl bg-slate-900 border border-slate-700 text-white text-xs focus:outline-none focus:border-cyan-500">
                            <option value="active">Aktif</option>
                            <option value="expired">Expired</option>
                            <option value="inactive">Nonaktif</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5">Jenis Kelamin</label>
                        <select name="gender" x-model="editGender" class="w-full px-3.5 py-2 rounded-xl bg-slate-900 border border-slate-700 text-white text-xs focus:outline-none focus:border-cyan-500">
                            <option value="male">Pria</option>
                            <option value="female">Wanita</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5">Paket</label>
                        <select name="membership_type" x-model="editPlan" class="w-full px-3.5 py-2 rounded-xl bg-slate-900 border border-slate-700 text-white text-xs focus:outline-none focus:border-cyan-500">
                            <option value="monthly_bronze">Bronze 1 Bulan</option>
                            <option value="monthly_silver">Silver 3 Bulan</option>
                            <option value="annual_gold">Gold VIP 1 Tahun</option>
                            <option value="daily">Daily Pass</option>
                            <option value="none">Tanpa Paket</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5">Masa Berlaku s/d (Perpanjang)</label>
                    <input type="date" name="membership_expires_at" x-model="editExpires"
                           class="w-full px-3.5 py-2 rounded-xl bg-slate-900 border border-slate-700 text-white text-xs focus:outline-none focus:border-cyan-500">
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
