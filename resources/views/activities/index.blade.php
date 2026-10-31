@extends('layouts.app')

@section('title', 'Log Aktivitas & Tracking User')
@section('page-title', 'Log Aktivitas & Audit Trail (Tracking User)')
@section('page-subtitle', 'Pelacakan riwayat INSERT, UPDATE, DELETE & identitas pengguna untuk menelusuri penyebab jika terjadi kesalahan data')

@section('content')
<div class="space-y-6" x-data="activityLogApp()">

    <!-- Stats Overview Cards -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Total Log -->
        <div class="p-4 rounded-2xl bg-[#0B0F19] border border-slate-800">
            <div class="flex items-center justify-between mb-1">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Total Aktivitas</span>
                <i class="fa-solid fa-list-check text-slate-400"></i>
            </div>
            <div class="text-2xl font-black text-white font-mono">{{ number_format($totalActivities) }}</div>
            <p class="text-[11px] text-slate-500 mt-1">Riwayat operasi tercatat</p>
        </div>

        <!-- Total INSERT -->
        <div class="p-4 rounded-2xl bg-[#0B0F19] border border-emerald-500/30">
            <div class="flex items-center justify-between mb-1">
                <span class="text-xs font-bold uppercase tracking-wider text-emerald-400">INSERT (Tambah Data)</span>
                <i class="fa-solid fa-plus-circle text-emerald-400"></i>
            </div>
            <div class="text-2xl font-black text-emerald-400 font-mono">{{ number_format($insertCount) }}</div>
            <p class="text-[11px] text-emerald-400/70 mt-1">Entri baru dibuat</p>
        </div>

        <!-- Total UPDATE -->
        <div class="p-4 rounded-2xl bg-[#0B0F19] border border-blue-500/30">
            <div class="flex items-center justify-between mb-1">
                <span class="text-xs font-bold uppercase tracking-wider text-blue-400">UPDATE (Ubah Data)</span>
                <i class="fa-solid fa-pen-to-square text-blue-400"></i>
            </div>
            <div class="text-2xl font-black text-blue-400 font-mono">{{ number_format($updateCount) }}</div>
            <p class="text-[11px] text-blue-400/70 mt-1">Perubahan nilai tersimpan</p>
        </div>

        <!-- Total DELETE -->
        <div class="p-4 rounded-2xl bg-[#0B0F19] border border-rose-500/30">
            <div class="flex items-center justify-between mb-1">
                <span class="text-xs font-bold uppercase tracking-wider text-rose-400">DELETE (Hapus Data)</span>
                <i class="fa-solid fa-trash-can text-rose-400"></i>
            </div>
            <div class="text-2xl font-black text-rose-400 font-mono">{{ number_format($deleteCount) }}</div>
            <p class="text-[11px] text-rose-400/70 mt-1">Snapshot cadangan tersimpan</p>
        </div>
    </div>

    <!-- Filter and Search Bar -->
    <div class="p-4 rounded-2xl bg-[#0B0F19] border border-slate-800">
        <form method="GET" action="{{ route('activities.index') }}" class="space-y-3">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-6 gap-3">
                <!-- Search text -->
                <div class="lg:col-span-2">
                    <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-400 mb-1">Pencarian Cepat</label>
                    <div class="relative">
                        <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-slate-500 text-xs"></i>
                        <input type="text" name="search" value="{{ request('search') }}"
                               placeholder="Cari deskripsi, pelaku, IP, no invoice..."
                               class="w-full pl-9 pr-3 py-2 bg-slate-900 border border-slate-800 rounded-xl text-xs text-white placeholder-slate-500 focus:border-amber-500 focus:outline-none focus:ring-1 focus:ring-amber-500 transition-colors">
                    </div>
                </div>

                <!-- Action Filter -->
                <div>
                    <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-400 mb-1">Jenis Aksi</label>
                    <select name="action" class="w-full py-2 px-3 bg-slate-900 border border-slate-800 rounded-xl text-xs text-white focus:border-amber-500 focus:outline-none focus:ring-1 focus:ring-amber-500">
                        <option value="">Semua Aksi</option>
                        <option value="INSERT" {{ request('action') === 'INSERT' ? 'selected' : '' }}>INSERT (Tambah)</option>
                        <option value="UPDATE" {{ request('action') === 'UPDATE' ? 'selected' : '' }}>UPDATE (Ubah)</option>
                        <option value="DELETE" {{ request('action') === 'DELETE' ? 'selected' : '' }}>DELETE (Hapus)</option>
                        <option value="CANCEL" {{ request('action') === 'CANCEL' ? 'selected' : '' }}>CANCEL (Batal POS)</option>
                        <option value="LOGIN" {{ request('action') === 'LOGIN' ? 'selected' : '' }}>LOGIN (Masuk)</option>
                        <option value="LOGOUT" {{ request('action') === 'LOGOUT' ? 'selected' : '' }}>LOGOUT (Keluar)</option>
                    </select>
                </div>

                <!-- User Filter -->
                <div>
                    <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-400 mb-1">Pelaku (User)</label>
                    <select name="user_id" class="w-full py-2 px-3 bg-slate-900 border border-slate-800 rounded-xl text-xs text-white focus:border-amber-500 focus:outline-none focus:ring-1 focus:ring-amber-500">
                        <option value="">Semua Pengguna</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                                {{ $user->name }} ({{ ucfirst($user->role) }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Module Filter -->
                <div>
                    <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-400 mb-1">Modul / Entitas</label>
                    <select name="module" class="w-full py-2 px-3 bg-slate-900 border border-slate-800 rounded-xl text-xs text-white focus:border-amber-500 focus:outline-none focus:ring-1 focus:ring-amber-500">
                        <option value="">Semua Modul</option>
                        @foreach($modules as $mod)
                            <option value="{{ $mod }}" {{ request('module') === $mod ? 'selected' : '' }}>{{ $mod }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Filter Actions -->
                <div class="flex items-end gap-2">
                    <button type="submit"
                            class="flex-1 py-2 px-3 rounded-xl bg-gradient-to-r from-amber-500 to-orange-500 hover:from-amber-400 hover:to-orange-400 text-slate-950 font-black text-xs flex items-center justify-center gap-1.5 shadow-md shadow-amber-500/20 transition-all">
                        <i class="fa-solid fa-filter text-xs"></i>
                        <span>Filter</span>
                    </button>
                    @if(request()->hasAny(['search', 'action', 'user_id', 'module', 'date_from', 'date_to']))
                        <a href="{{ route('activities.index') }}"
                           class="py-2 px-3 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-300 text-xs font-bold transition-all flex items-center justify-center"
                           title="Reset Filter">
                            <i class="fa-solid fa-arrow-rotate-left"></i>
                        </a>
                    @endif
                </div>
            </div>

            <!-- Date Range Filter -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-2 border-t border-slate-800/80">
                <div class="flex items-center gap-2">
                    <span class="text-[11px] text-slate-400 font-bold uppercase whitespace-nowrap">Rentang Tanggal:</span>
                    <input type="date" name="date_from" value="{{ request('date_from') }}"
                           class="w-full py-1.5 px-3 bg-slate-900 border border-slate-800 rounded-xl text-xs text-white focus:border-amber-500 focus:outline-none">
                    <span class="text-xs text-slate-500">s/d</span>
                    <input type="date" name="date_to" value="{{ request('date_to') }}"
                           class="w-full py-1.5 px-3 bg-slate-900 border border-slate-800 rounded-xl text-xs text-white focus:border-amber-500 focus:outline-none">
                </div>
                <div class="flex items-center justify-end text-[11px] text-slate-500">
                    <i class="fa-solid fa-shield-halved text-amber-400 mr-1.5"></i>
                    Semua riwayat mutasi tersimpan otomatis & tidak dapat direkayasa.
                </div>
            </div>
        </form>
    </div>

    <!-- Activities Table -->
    <div class="bg-[#0B0F19] rounded-2xl border border-slate-800 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-900/80 border-b border-slate-800 text-slate-400 uppercase tracking-wider font-mono text-[11px]">
                    <tr>
                        <th class="py-3.5 px-4">Waktu</th>
                        <th class="py-3.5 px-4">Pelaku (User)</th>
                        <th class="py-3.5 px-4 text-center">Aksi</th>
                        <th class="py-3.5 px-4">Modul</th>
                        <th class="py-3.5 px-4">Deskripsi Aktivitas</th>
                        <th class="py-3.5 px-4">Alamat IP</th>
                        <th class="py-3.5 px-4 text-right">Detail Audit</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/80">
                    @forelse($activities as $act)
                        @php
                            $badge = $act->getActionBadge();
                        @endphp
                        <tr class="hover:bg-slate-900/50 transition-colors">
                            <!-- Waktu -->
                            <td class="py-3.5 px-4 whitespace-nowrap">
                                <div class="font-mono text-slate-200 font-semibold">{{ $act->created_at->format('d/m/Y H:i:s') }}</div>
                                <div class="text-[10px] text-slate-500">{{ $act->created_at->diffForHumans() }}</div>
                            </td>

                            <!-- Pelaku -->
                            <td class="py-3.5 px-4 whitespace-nowrap">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-7 h-7 rounded-lg flex items-center justify-center font-bold text-xs text-white {{ $act->user_role === 'owner' ? 'bg-amber-500/20 text-amber-300 border border-amber-500/30' : ($act->user_role === 'kasir' ? 'bg-emerald-500/20 text-emerald-300 border border-emerald-500/30' : 'bg-slate-700 text-slate-300') }}">
                                        {{ strtoupper(substr($act->user_name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <span class="font-bold text-white block">{{ $act->user_name }}</span>
                                        <span class="text-[10px] font-mono uppercase {{ $act->user_role === 'owner' ? 'text-amber-400 font-bold' : ($act->user_role === 'kasir' ? 'text-emerald-400 font-semibold' : 'text-slate-400') }}">
                                            {{ $act->user_role ?? 'guest' }}
                                        </span>
                                    </div>
                                </div>
                            </td>

                            <!-- Aksi Badge -->
                            <td class="py-3.5 px-4 text-center whitespace-nowrap">
                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-extrabold uppercase border {{ $badge['bg'] }}">
                                    <i class="fa-solid {{ $badge['icon'] }} text-[9px]"></i>
                                    <span>{{ $act->action }}</span>
                                </span>
                            </td>

                            <!-- Modul -->
                            <td class="py-3.5 px-4 whitespace-nowrap">
                                <span class="px-2 py-0.5 rounded-md bg-slate-800 border border-slate-700/60 text-[10px] font-bold text-slate-300">
                                    {{ $act->module }}
                                </span>
                            </td>

                            <!-- Deskripsi -->
                            <td class="py-3.5 px-4 max-w-md">
                                <div class="text-slate-200 line-clamp-2" title="{{ $act->description }}">
                                    {{ $act->description }}
                                </div>
                                @if(!empty($act->old_values) && !empty($act->new_values))
                                    <div class="text-[10px] text-blue-400 mt-0.5 font-mono">
                                        <i class="fa-solid fa-code-compare mr-1"></i>{{ count($act->new_values) }} atribut diubah
                                    </div>
                                @elseif($act->action === 'DELETE')
                                    <div class="text-[10px] text-rose-400 mt-0.5 font-mono">
                                        <i class="fa-solid fa-box-archive mr-1"></i>Snapshot data tersimpan
                                    </div>
                                @endif
                            </td>

                            <!-- IP Address -->
                            <td class="py-3.5 px-4 font-mono text-slate-400 text-[11px] whitespace-nowrap">
                                <span class="px-1.5 py-0.5 rounded bg-slate-900 border border-slate-800">
                                    {{ $act->ip_address ?: '127.0.0.1' }}
                                </span>
                            </td>

                            <!-- Detail Tombol -->
                            <td class="py-3.5 px-4 text-right whitespace-nowrap">
                                <button type="button"
                                        @click="openDetail({{ $act->id }})"
                                        class="px-2.5 py-1.5 rounded-lg bg-slate-800 hover:bg-slate-700 text-slate-300 hover:text-white border border-slate-700 text-xs font-bold transition-all flex items-center gap-1.5 ml-auto">
                                    <i class="fa-solid fa-magnifying-glass-chart text-amber-400"></i>
                                    <span>Lihat Audit</span>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-12 text-center">
                                <div class="w-12 h-12 rounded-2xl bg-slate-900 border border-slate-800 flex items-center justify-center mx-auto mb-3 text-slate-600">
                                    <i class="fa-solid fa-clipboard-list text-xl"></i>
                                </div>
                                <h4 class="text-sm font-bold text-white mb-1">Belum Ada Catatan Log Aktivitas</h4>
                                <p class="text-xs text-slate-400 max-w-sm mx-auto">
                                    Aktivitas operasi INSERT, UPDATE, dan DELETE akan otomatis tercatat di sini saat pengguna melakukan tindakan dalam aplikasi.
                                </p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($activities->hasPages())
            <div class="p-4 border-t border-slate-800 bg-slate-900/30">
                {{ $activities->links() }}
            </div>
        @endif
    </div>

    <!-- DETAIL AUDIT MODAL -->
    <div x-show="detailModalOpen" x-cloak
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/80 backdrop-blur-sm"
         @click.self="detailModalOpen = false"
         @keydown.escape.window="detailModalOpen = false">
        <div x-show="detailModalOpen"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             class="w-full max-w-3xl rounded-3xl bg-[#0B0F19] border border-slate-800 shadow-2xl overflow-hidden flex flex-col max-h-[90vh]">

            <!-- Modal Header -->
            <div class="p-5 border-b border-slate-800 flex items-center justify-between bg-slate-900/50">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-amber-500/10 border border-amber-500/30 flex items-center justify-center text-amber-400">
                        <i class="fa-solid fa-shield-halved text-base"></i>
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <h3 class="text-sm font-bold text-white">Detail Audit Trail & Tracking User</h3>
                            <template x-if="selectedLog">
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold uppercase border"
                                      :class="selectedLog?.badge?.bg"
                                      x-text="selectedLog?.action"></span>
                            </template>
                        </div>
                        <p class="text-xs text-slate-400" x-text="selectedLog ? ('Log ID #' + selectedLog.id + ' • ' + selectedLog.module) : 'Memuat data...'"></p>
                    </div>
                </div>

                <button @click="detailModalOpen = false" class="w-8 h-8 rounded-lg bg-slate-800 hover:bg-slate-700 text-slate-400 hover:text-white flex items-center justify-center text-sm transition-colors">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <!-- Modal Body (Scrollable) -->
            <div class="p-6 overflow-y-auto space-y-5 text-xs flex-1">
                <!-- Loading State -->
                <div x-show="loading" class="py-16 text-center">
                    <i class="fa-solid fa-circle-notch fa-spin text-3xl text-amber-400 mb-3 block"></i>
                    <span class="text-xs text-slate-400 font-mono">Memuat jejak audit forensik...</span>
                </div>

                <template x-if="!loading && selectedLog">
                    <div class="space-y-5">
                        <!-- User Actor & Timestamp Card -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 p-4 rounded-2xl bg-slate-900/60 border border-slate-800">
                            <div>
                                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block mb-1">Pelaku (Pengguna Bertanggung Jawab)</span>
                                <div class="flex items-center gap-2 mt-1">
                                    <i class="fa-solid fa-user-gear text-amber-400 text-sm"></i>
                                    <span class="font-bold text-white text-sm" x-text="selectedLog.user_name"></span>
                                    <span class="px-2 py-0.5 rounded text-[10px] font-mono uppercase bg-slate-800 border border-slate-700 text-amber-400" x-text="selectedLog.user_role"></span>
                                </div>
                            </div>

                            <div>
                                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block mb-1">Waktu Kejadian</span>
                                <div class="flex items-center gap-2 mt-1 font-mono text-slate-200">
                                    <i class="fa-regular fa-clock text-cyan-400 text-sm"></i>
                                    <span class="font-semibold" x-text="selectedLog.created_at"></span>
                                    <span class="text-slate-400 text-[11px]" x-text="'(' + selectedLog.time_ago + ')'"></span>
                                </div>
                            </div>

                            <div>
                                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block mb-1">Alamat IP Klien</span>
                                <div class="font-mono text-slate-300 mt-1 flex items-center gap-2">
                                    <i class="fa-solid fa-network-wired text-slate-500"></i>
                                    <span x-text="selectedLog.ip_address"></span>
                                </div>
                            </div>

                            <div>
                                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block mb-1">Browser / User Agent</span>
                                <div class="font-mono text-slate-400 text-[10px] truncate mt-1" :title="selectedLog.user_agent" x-text="selectedLog.user_agent"></div>
                            </div>
                        </div>

                        <!-- Deskripsi Lengkap -->
                        <div class="p-3.5 rounded-xl bg-slate-900/40 border border-slate-800">
                            <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block mb-1">Deskripsi Lengkap Aktivitas:</span>
                            <p class="text-slate-200 font-medium text-xs leading-relaxed" x-text="selectedLog.description"></p>
                        </div>

                        <!-- View Mode Tabs (Diff vs Raw) -->
                        <div class="flex items-center justify-between border-b border-slate-800 pb-2">
                            <div class="text-xs font-bold text-white flex items-center gap-2">
                                <i class="fa-solid fa-table-list text-amber-400"></i>
                                <span>Inspeksi Perubahan Kolom & Data</span>
                            </div>

                            <div class="flex items-center gap-1 bg-slate-900 p-0.5 rounded-lg border border-slate-800">
                                <button type="button"
                                        @click="viewMode = 'diff'"
                                        :class="viewMode === 'diff' ? 'bg-amber-500 text-slate-950 font-bold' : 'text-slate-400 hover:text-white'"
                                        class="px-2.5 py-1 rounded-md text-[10px] uppercase font-mono transition-all">
                                    Perbandingan (Diff)
                                </button>
                                <button type="button"
                                        @click="viewMode = 'raw'"
                                        :class="viewMode === 'raw' ? 'bg-amber-500 text-slate-950 font-bold' : 'text-slate-400 hover:text-white'"
                                        class="px-2.5 py-1 rounded-md text-[10px] uppercase font-mono transition-all">
                                    JSON Mentah
                                </button>
                            </div>
                        </div>

                        <!-- 1. MODE: DIFF PERBANDINGAN -->
                        <div x-show="viewMode === 'diff'" class="space-y-4">
                            <!-- Kasus UPDATE: Kolom Sebelum vs Sesudah -->
                            <template x-if="selectedLog.action === 'UPDATE' && selectedLog.new_values">
                                <div class="rounded-xl border border-slate-800 overflow-hidden">
                                    <table class="w-full text-left text-xs">
                                        <thead class="bg-slate-900/90 text-slate-400 uppercase font-mono text-[10px] border-b border-slate-800">
                                            <tr>
                                                <th class="py-2.5 px-3 w-1/3">Kolom / Field</th>
                                                <th class="py-2.5 px-3 w-1/3 text-rose-400 bg-rose-500/5">Nilai Sebelum (Old)</th>
                                                <th class="py-2.5 px-3 w-1/3 text-emerald-400 bg-emerald-500/5">Nilai Sesudah (New)</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-slate-800/80 font-mono text-[11px]">
                                            <template x-for="(newVal, field) in selectedLog.new_values" :key="field">
                                                <tr class="hover:bg-slate-900/40">
                                                    <td class="py-2.5 px-3 text-slate-300 font-bold font-sans">
                                                        <span x-text="field"></span>
                                                    </td>
                                                    <td class="py-2.5 px-3 bg-rose-500/5 text-rose-300 break-all">
                                                        <span x-html="formatValue(selectedLog.old_values ? selectedLog.old_values[field] : null)"></span>
                                                    </td>
                                                    <td class="py-2.5 px-3 bg-emerald-500/5 text-emerald-300 break-all font-semibold">
                                                        <span x-html="formatValue(newVal)"></span>
                                                    </td>
                                                </tr>
                                            </template>
                                        </tbody>
                                    </table>
                                </div>
                            </template>

                            <!-- Kasus INSERT: Snapshot data yang baru diinput -->
                            <template x-if="selectedLog.action === 'INSERT' && selectedLog.new_values">
                                <div class="rounded-xl border border-slate-800 overflow-hidden">
                                    <div class="bg-emerald-500/10 border-b border-emerald-500/20 px-3.5 py-2 text-emerald-400 font-bold text-[11px] flex items-center gap-2">
                                        <i class="fa-solid fa-plus-circle"></i>
                                        <span>Data Lengkap yang Baru Dibuat (INSERT Snapshot)</span>
                                    </div>
                                    <table class="w-full text-left text-xs">
                                        <thead class="bg-slate-900 text-slate-400 uppercase font-mono text-[10px] border-b border-slate-800">
                                            <tr>
                                                <th class="py-2 px-3 w-1/3">Kolom / Atribut</th>
                                                <th class="py-2 px-3">Nilai Diinput</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-slate-800/80 font-mono text-[11px]">
                                            <template x-for="(val, field) in selectedLog.new_values" :key="field">
                                                <tr class="hover:bg-slate-900/40">
                                                    <td class="py-2 px-3 text-slate-300 font-sans font-bold" x-text="field"></td>
                                                    <td class="py-2 px-3 text-slate-200 break-all" x-html="formatValue(val)"></td>
                                                </tr>
                                            </template>
                                        </tbody>
                                    </table>
                                </div>
                            </template>

                            <!-- Kasus DELETE: Snapshot data sebelum dihapus (Sangat berguna jika salah hapus!) -->
                            <template x-if="selectedLog.action === 'DELETE' && selectedLog.old_values">
                                <div class="rounded-xl border border-rose-500/30 overflow-hidden">
                                    <div class="bg-rose-500/10 border-b border-rose-500/20 px-3.5 py-2 text-rose-400 font-bold text-[11px] flex items-center gap-2">
                                        <i class="fa-solid fa-trash-can"></i>
                                        <span>Snapshot Data Terakhir Sebelum Dihapus (Dapat Dipulihkan Secara Manual)</span>
                                    </div>
                                    <table class="w-full text-left text-xs">
                                        <thead class="bg-slate-900 text-slate-400 uppercase font-mono text-[10px] border-b border-slate-800">
                                            <tr>
                                                <th class="py-2 px-3 w-1/3">Kolom / Atribut</th>
                                                <th class="py-2 px-3">Nilai Sebelum Dihapus</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-slate-800/80 font-mono text-[11px]">
                                            <template x-for="(val, field) in selectedLog.old_values" :key="field">
                                                <tr class="hover:bg-slate-900/40">
                                                    <td class="py-2 px-3 text-slate-300 font-sans font-bold" x-text="field"></td>
                                                    <td class="py-2 px-3 text-rose-200 break-all" x-html="formatValue(val)"></td>
                                                </tr>
                                            </template>
                                        </tbody>
                                    </table>
                                </div>
                            </template>

                            <!-- Kasus lainnya atau tidak ada values tersimpan -->
                            <template x-if="!selectedLog.new_values && !selectedLog.old_values">
                                <div class="p-6 text-center text-slate-500 bg-slate-900/40 rounded-xl border border-slate-800">
                                    <i class="fa-solid fa-circle-info text-slate-600 text-2xl mb-2 block"></i>
                                    <span>Tidak ada payload perubahan nilai khusus untuk aktivitas tipe ini.</span>
                                </div>
                            </template>
                        </div>

                        <!-- 2. MODE: RAW JSON -->
                        <div x-show="viewMode === 'raw'" class="space-y-3">
                            <div class="rounded-xl bg-slate-950 p-4 border border-slate-800 font-mono text-[11px] text-slate-300 overflow-x-auto max-h-80">
                                <pre x-text="JSON.stringify({
                                    id: selectedLog.id,
                                    action: selectedLog.action,
                                    module: selectedLog.module,
                                    user: { name: selectedLog.user_name, role: selectedLog.user_role },
                                    ip: selectedLog.ip_address,
                                    agent: selectedLog.user_agent,
                                    timestamp: selectedLog.created_at,
                                    old_values: selectedLog.old_values,
                                    new_values: selectedLog.new_values
                                }, null, 2)"></pre>
                            </div>
                        </div>
                    </div>
                </template>
            </div>

            <!-- Modal Footer -->
            <div class="p-4 border-t border-slate-800 bg-slate-900/50 flex items-center justify-between">
                <div class="text-[11px] text-slate-400">
                    <i class="fa-solid fa-lock text-emerald-400 mr-1"></i>
                    Catatan ini dilindungi integritasnya untuk kebutuhan audit forensik.
                </div>
                <button type="button" @click="detailModalOpen = false"
                        class="px-4 py-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-white font-bold text-xs transition-colors">
                    Tutup
                </button>
            </div>
        </div>
    </div>

</div>

@push('scripts')
<script>
    function activityLogApp() {
        return {
            detailModalOpen: false,
            loading: false,
            selectedLog: null,
            viewMode: 'diff', // 'diff' or 'raw'
            async openDetail(id) {
                this.detailModalOpen = true;
                this.loading = true;
                this.selectedLog = null;
                this.viewMode = 'diff';
                try {
                    const res = await fetch('{{ url('/activities') }}/' + id, {
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    });
                    this.selectedLog = await res.json();
                } catch (e) {
                    console.error('Error fetching activity log detail:', e);
                } finally {
                    this.loading = false;
                }
            },
            formatValue(val) {
                if (val === null || val === undefined || val === '') {
                    return '<span class="text-slate-500 italic">(kosong)</span>';
                }
                if (typeof val === 'boolean') {
                    return val 
                        ? '<span class="text-emerald-400 font-bold">Aktif (True)</span>' 
                        : '<span class="text-rose-400 font-bold">Nonaktif (False)</span>';
                }
                if (typeof val === 'object') {
                    return JSON.stringify(val);
                }
                return String(val);
            }
        };
    }
</script>
@endpush
@endsection
