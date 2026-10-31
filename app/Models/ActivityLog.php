<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ActivityLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'user_name',
        'user_role',
        'action',
        'module',
        'subject_type',
        'subject_id',
        'description',
        'old_values',
        'new_values',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Manually record a custom activity log.
     */
    public static function record(
        string $action,
        string $module,
        string $description,
        ?Model $model = null,
        ?array $oldValues = null,
        ?array $newValues = null
    ): self {
        $user = Auth::user();

        return self::create([
            'user_id' => $user?->id,
            'user_name' => $user?->name ?? 'Publik / Sistem',
            'user_role' => $user?->role ?? 'guest',
            'action' => strtoupper($action),
            'module' => $module,
            'subject_type' => $model ? get_class($model) : null,
            'subject_id' => $model ? (string) $model->getKey() : null,
            'description' => $description,
            'old_values' => $oldValues,
            'new_values' => $newValues,
            'ip_address' => request()?->ip(),
            'user_agent' => request() ? Str::limit(request()->userAgent(), 250) : null,
        ]);
    }

    /**
     * Get UI Badge styling depending on action.
     */
    public function getActionBadge(): array
    {
        return match ($this->action) {
            'INSERT' => [
                'bg' => 'bg-emerald-500/10 dark:bg-emerald-500/15 text-emerald-600 dark:text-emerald-400 border-emerald-500/30',
                'icon' => 'fa-plus-circle',
                'label' => 'INSERT (Tambah Data)',
            ],
            'UPDATE' => [
                'bg' => 'bg-blue-500/10 dark:bg-blue-500/15 text-blue-600 dark:text-blue-400 border-blue-500/30',
                'icon' => 'fa-pen-to-square',
                'label' => 'UPDATE (Ubah Data)',
            ],
            'DELETE' => [
                'bg' => 'bg-rose-500/10 dark:bg-rose-500/15 text-rose-600 dark:text-rose-400 border-rose-500/30',
                'icon' => 'fa-trash-can',
                'label' => 'DELETE (Hapus Data)',
            ],
            'LOGIN' => [
                'bg' => 'bg-purple-500/10 dark:bg-purple-500/15 text-purple-600 dark:text-purple-400 border-purple-500/30',
                'icon' => 'fa-right-to-bracket',
                'label' => 'LOGIN (Masuk)',
            ],
            'LOGOUT' => [
                'bg' => 'bg-slate-500/10 dark:bg-slate-500/15 text-slate-600 dark:text-slate-400 border-slate-500/30',
                'icon' => 'fa-arrow-right-from-bracket',
                'label' => 'LOGOUT (Keluar)',
            ],
            'CANCEL' => [
                'bg' => 'bg-amber-500/10 dark:bg-amber-500/15 text-amber-600 dark:text-amber-400 border-amber-500/30',
                'icon' => 'fa-ban',
                'label' => 'CANCEL (Batal)',
            ],
            default => [
                'bg' => 'bg-slate-500/10 text-slate-400 border-slate-500/30',
                'icon' => 'fa-circle-info',
                'label' => $this->action,
            ]
        };
    }

    /**
     * Translate field names to human friendly Indonesian labels.
     */
    public static function fieldLabel(string $field): string
    {
        $labels = [
            'name' => 'Nama',
            'email' => 'Email',
            'phone' => 'No. Telepon / WhatsApp',
            'role' => 'Peran (Role)',
            'is_active' => 'Status Aktif',
            'sku' => 'SKU / Barcode',
            'brand' => 'Merek / Brand',
            'unit' => 'Satuan / Unit',
            'flavor' => 'Rasa / Varian',
            'purchase_price' => 'Harga Beli (Modal)',
            'selling_price' => 'Harga Jual',
            'stock' => 'Stok',
            'min_stock_alert' => 'Batas Minimum Stok',
            'expiry_date' => 'Tanggal Kedaluwarsa',
            'description' => 'Deskripsi',
            'member_code' => 'Nomor Anggota (Kode)',
            'gender' => 'Jenis Kelamin',
            'address' => 'Alamat',
            'membership_type' => 'Jenis Paket Membership',
            'membership_start' => 'Mulai Member',
            'membership_expires_at' => 'Kedaluwarsa Member',
            'status' => 'Status',
            'payment_status' => 'Status Pembayaran',
            'payment_method' => 'Metode Pembayaran',
            'subtotal' => 'Subtotal',
            'discount' => 'Diskon',
            'tax' => 'Pajak',
            'total_amount' => 'Total Pembayaran',
            'cash_received' => 'Uang Diterima',
            'cash_change' => 'Kembalian',
            'customer_name' => 'Nama Pelanggan',
            'customer_type' => 'Tipe Pelanggan',
            'invoice_no' => 'No. Invoice',
            'code' => 'Kode Paket / Layanan',
            'price' => 'Tarif / Harga',
            'duration_days' => 'Durasi (Hari)',
            'booking_code' => 'Kode Booking',
            'booking_date' => 'Tanggal Booking',
            'start_time' => 'Jam Mulai',
            'end_time' => 'Jam Selesai',
            'specialization' => 'Spesialisasi Trainer',
            'hourly_rate' => 'Tarif Per Sesi',
            'capacity' => 'Kapasitas Peserta',
            'schedule_day' => 'Hari Jadwal',
            'app_name' => 'Nama Gym / Aplikasi',
            'app_tagline' => 'Tagline Gym',
            'theme_color' => 'Warna Tema',
            'receipt_footer' => 'Catatan Struk',
            'weight' => 'Berat Badan (kg)',
            'height' => 'Tinggi Badan (cm)',
            'bmi' => 'Indeks Massa Tubuh (BMI)',
            'body_fat_percentage' => 'Lemak Tubuh (Body Fat %)',
            'muscle_mass' => 'Massa Otot (kg)',
            'body_water_percentage' => 'Kadar Air Tubuh (%)',
            'visceral_fat' => 'Lemak Visceral',
            'bone_mass' => 'Massa Tulang (kg)',
            'bmr' => 'BMR (kkal)',
            'metabolic_age' => 'Usia Metabolik',
            'chest_circumference' => 'Lingkar Dada (cm)',
            'waist_circumference' => 'Lingkar Pinggang (cm)',
            'hips_circumference' => 'Lingkar Pinggul (cm)',
            'arm_circumference' => 'Lingkar Lengan (cm)',
            'thigh_circumference' => 'Lingkar Paha (cm)',
            'calf_circumference' => 'Lingkar Betis (cm)',
            'fitness_goal' => 'Target Kebugaran',
            'trainer_notes' => 'Catatan Trainer',
            'assessment_date' => 'Tanggal Asesmen',
        ];

        return $labels[$field] ?? ucwords(str_replace('_', ' ', $field));
    }
}

