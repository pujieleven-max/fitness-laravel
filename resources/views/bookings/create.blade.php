@extends('layouts.app')

@section('title', 'Buat Booking Baru')
@section('page-title', 'Buat Jadwal Booking Baru')
@section('page-subtitle', 'Reservasi sesi Personal Trainer privat atau pendaftaran kelas fitness')

@section('content')
<div class="max-w-3xl mx-auto" x-data="bookingCreateApp()">
    <div class="bg-[#0B0F19] rounded-2xl border border-slate-800 p-6 sm:p-8">
        
        <form method="POST" action="{{ route('bookings.store') }}" class="space-y-6">
            @csrf

            <!-- Booking Type Toggle -->
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-2">Tipe Layanan Booking</label>
                <div class="grid grid-cols-2 gap-3 p-1.5 rounded-2xl bg-slate-900 border border-slate-800">
                    <button type="button" @click="bookingType = 'personal_trainer'; $nextTick(() => { window.initSelect2(); $('select.select2-hidden-accessible').trigger('change.select2'); })"
                            :class="bookingType === 'personal_trainer' ? 'bg-gradient-to-r from-emerald-500 to-teal-400 text-slate-950 font-black shadow-lg shadow-emerald-500/20' : 'text-slate-400 hover:text-white'"
                            class="py-3 rounded-xl text-xs flex items-center justify-center gap-2 transition-all">
                        <i class="fa-solid fa-user-ninja"></i>
                        <span>Personal Trainer (1-on-1 Privat)</span>
                    </button>

                    <button type="button" @click="bookingType = 'fitness_class'; $nextTick(() => { window.initSelect2(); $('select.select2-hidden-accessible').trigger('change.select2'); })"
                            :class="bookingType === 'fitness_class' ? 'bg-gradient-to-r from-cyan-500 to-teal-400 text-slate-950 font-black shadow-lg shadow-cyan-500/20' : 'text-slate-400 hover:text-white'"
                            class="py-3 rounded-xl text-xs flex items-center justify-center gap-2 transition-all">
                        <i class="fa-solid fa-users"></i>
                        <span>Kelas Fitness Studio</span>
                    </button>
                </div>
                <input type="hidden" name="booking_type" :value="bookingType">
            </div>

            <!-- Member Picker / Guest Selector -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5">Pilih Member (Opsional)</label>
                    <select name="member_id" x-model="selectedMemberId" @change="onMemberChange()"
                            class="select2 w-full px-3.5 py-2.5 rounded-xl bg-slate-900 border border-slate-700 text-white text-xs focus:outline-none focus:border-emerald-500">
                        <option value="">-- Tamu Walk-In (Bukan Member) --</option>
                        @foreach($members as $m)
                            <option value="{{ $m->id }}">{{ $m->member_code }} - {{ $m->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5">Nama Pelanggan</label>
                    <input type="text" name="customer_name" x-model="customerName" required placeholder="Nama lengkap pelanggan"
                           class="w-full px-3.5 py-2.5 rounded-xl bg-slate-900 border border-slate-700 text-white text-sm focus:outline-none focus:border-emerald-500">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5">No. WhatsApp / HP</label>
                <input type="text" name="customer_phone" x-model="customerPhone" placeholder="0812-xxxx-xxxx"
                       class="w-full px-3.5 py-2.5 rounded-xl bg-slate-900 border border-slate-700 text-white text-sm focus:outline-none focus:border-emerald-500">
            </div>

            <!-- Trainer or Class Selector -->
            <div x-show="bookingType === 'personal_trainer'">
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5">Pilih Personal Trainer (Coach)</label>
                <select name="trainer_id" x-model="selectedTrainerId" @change="onTrainerChange()"
                        class="select2 w-full px-3.5 py-2.5 rounded-xl bg-slate-900 border border-slate-700 text-white text-sm focus:outline-none focus:border-emerald-500">
                    <option value="">-- Pilih Coach Trainer --</option>
                    @foreach($trainers as $t)
                        <option value="{{ $t->id }}">
                            {{ $t->name }} ({{ $t->specialization }}) - Rp {{ number_format($t->hourly_rate, 0, ',', '.') }}/sesi
                        </option>
                    @endforeach
                </select>
            </div>

            <div x-show="bookingType === 'fitness_class'">
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5">Pilih Kelas Fitness Studio</label>
                <select name="fitness_class_id" x-model="selectedClassId" @change="onClassChange()"
                        class="select2 w-full px-3.5 py-2.5 rounded-xl bg-slate-900 border border-slate-700 text-white text-sm focus:outline-none focus:border-cyan-500">
                    <option value="">-- Pilih Kelas Studio --</option>
                    @foreach($fitnessClasses as $fc)
                        <option value="{{ $fc->id }}">
                            {{ $fc->name }} &bull; Hari {{ $fc->schedule_day }} ({{ substr($fc->start_time, 0, 5) }}-{{ substr($fc->end_time, 0, 5) }}) &bull; Rp {{ number_format($fc->price_per_session, 0, ',', '.') }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Date and Time Slots -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5">Tanggal Sesi</label>
                    <input type="date" name="booking_date" value="{{ date('Y-m-d') }}" required
                           class="w-full px-3.5 py-2.5 rounded-xl bg-slate-900 border border-slate-700 text-white text-sm focus:outline-none focus:border-emerald-500">
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5">Jam Mulai</label>
                    <input type="time" name="start_time" x-model="startTime" required
                           class="w-full px-3.5 py-2.5 rounded-xl bg-slate-900 border border-slate-700 text-white text-sm focus:outline-none focus:border-emerald-500">
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5">Jam Selesai</label>
                    <input type="time" name="end_time" x-model="endTime" required
                           class="w-full px-3.5 py-2.5 rounded-xl bg-slate-900 border border-slate-700 text-white text-sm focus:outline-none focus:border-emerald-500">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <!-- Price -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5">Tarif Sesi (Rp)</label>
                    <input type="number" name="price" x-model.number="price" min="0" required
                           class="w-full px-3.5 py-2.5 rounded-xl bg-slate-900 border border-slate-700 text-emerald-400 font-mono font-bold text-sm focus:outline-none focus:border-emerald-500">
                </div>

                <!-- Payment Status -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5">Status Pembayaran</label>
                    <select name="payment_status" required class="select2 w-full px-3.5 py-2.5 rounded-xl bg-slate-900 border border-slate-700 text-white text-sm focus:outline-none focus:border-emerald-500">
                        <option value="unpaid">Belum Lunas (Bayar di Kasir POS)</option>
                        <option value="paid">Lunas (Sudah Dibayar)</option>
                    </select>
                </div>
            </div>

            <!-- Notes -->
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5">Catatan Khusus / Target Latihan</label>
                <textarea name="notes" rows="2" placeholder="Contoh: Fokus perbaikan teknik deadlift atau ada cedera bahu lama..."
                          class="w-full px-3.5 py-2.5 rounded-xl bg-slate-900 border border-slate-700 text-white text-sm focus:outline-none focus:border-emerald-500"></textarea>
            </div>

            <!-- Submit -->
            <div class="pt-4 border-t border-slate-800 flex items-center justify-end gap-3">
                <a href="{{ route('bookings.index') }}" class="px-5 py-2.5 rounded-xl bg-slate-900 hover:bg-slate-800 text-slate-400 hover:text-white text-xs font-bold">
                    Batal
                </a>
                <button type="submit" 
                        class="px-6 py-2.5 rounded-xl bg-gradient-to-r from-emerald-500 to-teal-400 hover:from-emerald-400 hover:to-teal-300 text-slate-950 font-black text-xs uppercase tracking-wider shadow-lg shadow-emerald-500/25 transition-all">
                    Simpan Booking
                </button>
            </div>
        </form>

    </div>
</div>

@push('scripts')
<script>
    function bookingCreateApp() {
        return {
            bookingType: 'personal_trainer',
            trainers: {!! json_encode($trainers) !!},
            fitnessClasses: {!! json_encode($fitnessClasses) !!},
            members: {!! json_encode($members) !!},
            selectedTrainerId: '',
            selectedClassId: '',
            selectedMemberId: '',
            customerName: '',
            customerPhone: '',
            price: 0,
            startTime: '10:00',
            endTime: '11:00',

            onTrainerChange() {
                const found = this.trainers.find(t => t.id == this.selectedTrainerId);
                if (found) {
                    this.price = parseFloat(found.hourly_rate);
                }
            },

            onClassChange() {
                const found = this.fitnessClasses.find(c => c.id == this.selectedClassId);
                if (found) {
                    this.price = parseFloat(found.price_per_session);
                    if (found.start_time && found.end_time) {
                        this.startTime = found.start_time.substring(0, 5);
                        this.endTime = found.end_time.substring(0, 5);
                    }
                }
            },

            onMemberChange() {
                const found = this.members.find(m => m.id == this.selectedMemberId);
                if (found) {
                    this.customerName = found.name;
                    this.customerPhone = found.phone || '';
                }
            }
        };
    }
</script>
@endpush
@endsection
