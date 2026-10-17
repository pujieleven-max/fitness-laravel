@extends('layouts.app')

@section('title', 'Kelola Pengguna Kasir & Owner')
@section('page-title', 'Manajemen Pengguna (Owner & Kasir)')
@section('page-subtitle', 'Pengaturan hak akses sistem, akun kasir frontdesk, dan kredensial')

@section('content')
<div class="space-y-6" x-data="{
    showAddModal: false,
    showEditModal: false,
    selectedUserId: null,
    editName: '',
    editEmail: '',
    editPhone: '',
    editRole: 'kasir',
    editActive: true
}">

    <!-- Stats -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="p-4 rounded-2xl bg-[#0B0F19] border border-slate-800">
            <span class="text-xs font-bold uppercase tracking-wider text-slate-400 block mb-1">Total Pengguna</span>
            <div class="text-2xl font-black text-white font-mono">{{ $users->total() }}</div>
        </div>

        <div class="p-4 rounded-2xl bg-[#0B0F19] border border-amber-500/30">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold uppercase tracking-wider text-amber-400 block mb-1">Akun Owner (Admin)</span>
                <i class="fa-solid fa-crown text-amber-400"></i>
            </div>
            <div class="text-2xl font-black text-amber-300 font-mono">{{ $totalOwners }}</div>
            <p class="text-[11px] text-amber-400/80 mt-1">Akses penuh finansial & sistem</p>
        </div>

        <div class="p-4 rounded-2xl bg-[#0B0F19] border border-emerald-500/30">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold uppercase tracking-wider text-emerald-400 block mb-1">Akun Kasir Frontdesk</span>
                <i class="fa-solid fa-cash-register text-emerald-400"></i>
            </div>
            <div class="text-2xl font-black text-emerald-400 font-mono">{{ $totalKasir }}</div>
            <p class="text-[11px] text-emerald-400/80 mt-1">Operasional POS & Booking</p>
        </div>
    </div>

    <!-- Add Button -->
    <div class="flex justify-between items-center">
        <div>
            <h3 class="text-sm font-bold text-white">Daftar Akun Pengguna</h3>
            <p class="text-xs text-slate-400">Akun yang memiliki izin akses login ke sistem FitZone</p>
        </div>

        <button @click="showAddModal = true"
                class="px-4 py-2.5 rounded-xl bg-gradient-to-r from-emerald-500 to-teal-400 hover:from-emerald-400 hover:to-teal-300 text-slate-950 font-black text-xs flex items-center gap-2 shadow-lg shadow-emerald-500/20 transition-all">
            <i class="fa-solid fa-user-plus"></i>
            <span>Tambah Pengguna</span>
        </button>
    </div>

    <!-- Users Table -->
    <div class="bg-[#0B0F19] rounded-2xl border border-slate-800 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-900/80 border-b border-slate-800 text-slate-400 uppercase tracking-wider font-mono text-[11px]">
                    <tr>
                        <th class="py-3.5 px-4">Nama Pengguna</th>
                        <th class="py-3.5 px-4">Email Login</th>
                        <th class="py-3.5 px-4">No. HP</th>
                        <th class="py-3.5 px-4 text-center">Peran (Role)</th>
                        <th class="py-3.5 px-4 text-center">Status</th>
                        <th class="py-3.5 px-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/80">
                    @foreach($users as $u)
                        <tr class="hover:bg-slate-900/50 transition-colors">
                            <td class="py-3.5 px-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-xl flex items-center justify-center font-bold text-xs text-white {{ $u->isOwner() ? 'bg-amber-500/20 text-amber-300 border border-amber-500/30' : 'bg-emerald-500/20 text-emerald-300 border border-emerald-500/30' }}">
                                        {{ strtoupper(substr($u->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <span class="font-bold text-white text-sm block">{{ $u->name }}</span>
                                        @if($u->id === Auth::id())
                                            <span class="text-[10px] text-emerald-400 font-semibold">(Anda Sedang Login)</span>
                                        @endif
                                    </div>
                                </div>
                            </td>

                            <td class="py-3.5 px-4 font-mono text-slate-300">
                                {{ $u->email }}
                            </td>

                            <td class="py-3.5 px-4 font-mono text-slate-400">
                                {{ $u->phone ?: '-' }}
                            </td>

                            <td class="py-3.5 px-4 text-center">
                                @if($u->isOwner())
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-extrabold uppercase bg-amber-500/20 text-amber-300 border border-amber-500/30">
                                        <i class="fa-solid fa-crown text-[9px]"></i> Owner
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-extrabold uppercase bg-emerald-500/20 text-emerald-400 border border-emerald-500/30">
                                        <i class="fa-solid fa-cash-register text-[9px]"></i> Kasir
                                    </span>
                                @endif
                            </td>

                            <td class="py-3.5 px-4 text-center">
                                @if($u->is_active)
                                    <span class="inline-flex px-2 py-0.5 rounded-full text-[10px] font-bold uppercase bg-emerald-500/20 text-emerald-400 border border-emerald-500/30">
                                        Aktif
                                    </span>
                                @else
                                    <span class="inline-flex px-2 py-0.5 rounded-full text-[10px] font-bold uppercase bg-rose-500/20 text-rose-400 border border-rose-500/30">
                                        Nonaktif
                                    </span>
                                @endif
                            </td>

                            <td class="py-3.5 px-4 text-right whitespace-nowrap">
                                <div class="flex items-center justify-end gap-1.5">
                                    <button @click="selectedUserId = {{ $u->id }};
                                                    editName = '{{ addslashes($u->name) }}';
                                                    editEmail = '{{ $u->email }}';
                                                    editPhone = '{{ $u->phone }}';
                                                    editRole = '{{ $u->role }}';
                                                    editActive = {{ $u->is_active ? 'true' : 'false' }};
                                                    showEditModal = true"
                                            class="p-2 rounded-lg bg-slate-800 hover:bg-slate-700 text-slate-300 hover:text-white transition-colors"
                                            title="Edit Akun">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </button>

                                    @if($u->id !== Auth::id())
                                        <form method="POST" action="{{ route('users.toggle', $u->id) }}">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" 
                                                    class="p-2 rounded-lg {{ $u->is_active ? 'bg-amber-500/10 text-amber-400 hover:bg-amber-500/20' : 'bg-emerald-500/10 text-emerald-400 hover:bg-emerald-500/20' }} transition-colors"
                                                    title="{{ $u->is_active ? 'Nonaktifkan Akun' : 'Aktifkan Akun' }}">
                                                <i class="fa-solid {{ $u->is_active ? 'fa-user-slash' : 'fa-user-check' }}"></i>
                                            </button>
                                        </form>

                                        <form method="POST" action="{{ route('users.destroy', $u->id) }}" onsubmit="return confirm('Hapus akun pengguna {{ $u->name }}?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-2 rounded-lg bg-rose-500/10 hover:bg-rose-500/20 text-rose-400 border border-rose-500/30 transition-colors" title="Hapus">
                                                <i class="fa-solid fa-trash-can"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- MODAL ADD USER -->
    <div x-show="showAddModal" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/80 backdrop-blur-md" style="display: none;">
        
        <div class="bg-slate-950 border border-slate-800 rounded-3xl max-w-md w-full p-6 shadow-2xl relative" @click.away="showAddModal = false">
            <div class="flex items-center justify-between pb-4 border-b border-slate-800">
                <h3 class="text-base font-bold text-white flex items-center gap-2">
                    <i class="fa-solid fa-user-plus text-emerald-400"></i>
                    <span>Tambah Akun Pengguna Baru</span>
                </h3>
                <button @click="showAddModal = false" class="text-slate-400 hover:text-white">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <form method="POST" action="{{ route('users.store') }}" class="space-y-4 mt-4">
                @csrf

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5">Nama Lengkap</label>
                    <input type="text" name="name" required placeholder="Contoh: Rian Frontdesk"
                           class="w-full px-3.5 py-2.5 rounded-xl bg-slate-900 border border-slate-700 text-white text-xs focus:outline-none focus:border-emerald-500">
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5">Email Login</label>
                    <input type="email" name="email" required placeholder="rian@fitzone.com"
                           class="w-full px-3.5 py-2.5 rounded-xl bg-slate-900 border border-slate-700 text-white text-xs focus:outline-none focus:border-emerald-500">
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5">Peran / Role</label>
                        <select name="role" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-900 border border-slate-700 text-white text-xs focus:outline-none focus:border-emerald-500">
                            <option value="kasir">Kasir Frontdesk</option>
                            <option value="owner">Owner (Admin)</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5">No. WhatsApp</label>
                        <input type="text" name="phone" placeholder="0812-xxxx-xxxx"
                               class="w-full px-3.5 py-2.5 rounded-xl bg-slate-900 border border-slate-700 text-white text-xs focus:outline-none focus:border-emerald-500">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5">Kata Sandi (Password)</label>
                    <input type="password" name="password" required placeholder="Minimal 6 karakter"
                           class="w-full px-3.5 py-2.5 rounded-xl bg-slate-900 border border-slate-700 text-white text-xs focus:outline-none focus:border-emerald-500">
                </div>

                <div class="pt-3 border-t border-slate-800 flex items-center justify-end gap-2.5">
                    <button type="button" @click="showAddModal = false" class="px-4 py-2 rounded-xl bg-slate-900 hover:bg-slate-800 text-slate-400 text-xs font-bold">
                        Batal
                    </button>
                    <button type="submit" class="px-5 py-2.5 rounded-xl bg-emerald-500 hover:bg-emerald-400 text-slate-950 text-xs font-black uppercase tracking-wider shadow-lg shadow-emerald-500/20">
                        Buat Akun
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL EDIT USER -->
    <div x-show="showEditModal" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/80 backdrop-blur-md" style="display: none;">
        
        <div class="bg-slate-950 border border-slate-800 rounded-3xl max-w-md w-full p-6 shadow-2xl relative" @click.away="showEditModal = false">
            <div class="flex items-center justify-between pb-4 border-b border-slate-800">
                <h3 class="text-base font-bold text-white flex items-center gap-2">
                    <i class="fa-solid fa-pen-to-square text-cyan-400"></i>
                    <span>Edit Data Pengguna</span>
                </h3>
                <button @click="showEditModal = false" class="text-slate-400 hover:text-white">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <form :action="'{{ url('users') }}/' + (selectedUserId || '')" method="POST" class="space-y-4 mt-4">
                @csrf
                @method('PUT')

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5">Nama Lengkap</label>
                    <input type="text" name="name" x-model="editName" required
                           class="w-full px-3.5 py-2.5 rounded-xl bg-slate-900 border border-slate-700 text-white text-xs focus:outline-none focus:border-cyan-500">
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5">Email Login</label>
                    <input type="email" name="email" x-model="editEmail" required
                           class="w-full px-3.5 py-2.5 rounded-xl bg-slate-900 border border-slate-700 text-white text-xs focus:outline-none focus:border-cyan-500">
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5">Peran / Role</label>
                        <select name="role" x-model="editRole" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-900 border border-slate-700 text-white text-xs focus:outline-none focus:border-cyan-500">
                            <option value="kasir">Kasir Frontdesk</option>
                            <option value="owner">Owner (Admin)</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5">No. WhatsApp</label>
                        <input type="text" name="phone" x-model="editPhone"
                               class="w-full px-3.5 py-2.5 rounded-xl bg-slate-900 border border-slate-700 text-white text-xs focus:outline-none focus:border-cyan-500">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5">Reset Password Baru (Opsional)</label>
                    <input type="password" name="password" placeholder="Kosongkan jika tidak diubah"
                           class="w-full px-3.5 py-2.5 rounded-xl bg-slate-900 border border-slate-700 text-white text-xs focus:outline-none focus:border-cyan-500">
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
