# 🏋️‍♂️ FitZone Gym - POS & Fitness Management System

[![Laravel Version](https://img.shields.io/badge/Laravel-12.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)](https://laravel.com)
[![PHP Version](https://img.shields.io/badge/PHP-%3E%3D%208.2-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://php.net)
[![TailwindCSS](https://img.shields.io/badge/Tailwind_CSS-v4-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white)](https://tailwindcss.com)
[![Vite](https://img.shields.io/badge/Vite-7.x-646CFF?style=for-the-badge&logo=vite&logoColor=white)](https://vitejs.dev)
[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg?style=for-the-badge)](https://opensource.org/licenses/MIT)

**FitZone Gym** adalah aplikasi web komprehensif untuk manajemen operasional pusat kebugaran (fitness center / gym) dan sistem kasir (Point of Sale). Dirancang dengan antarmuka modern bernuansa *Cyber Dark Mode & Glassmorphism*, aplikasi ini membantu pemilik gym dan kasir frontdesk dalam mengelola keanggotaan, penjualan suplemen, tiket gym, sesi personal trainer, jadwal kelas, hingga analisis laba/rugi keuangan secara real-time.

---

## 🌟 Fitur Utama

### 🛒 1. Point of Sale (POS) Kasir Terintegrasi
- **Katalog Produk Dinamis**: Pencarian dan filter cepat produk suplemen, minuman berenergi, serta tiket masuk & paket langganan gym.
- **Dukungan Pelanggan**: Transaksi untuk member terdaftar maupun tamu umum (*Walk-in Customer*).
- **Kalkulasi Otomatis**: Perhitungan otomatis subtotal, diskon fleksibel, pajak, total tagihan, dan uang kembalian tunai.
- **Multi-Metode Pembayaran**: Mendukung pembayaran *Cash*, *QRIS*, dan *Transfer Bank*.
- **Cetak Struk Transaksi**: Halaman cetak invoice/struk termal siap cetak dengan logo gym dan catatan kaki kustom.
- **Pembatalan Pesanan (*Void Order*)**: Khusus role Owner, pesanan yang dibatalkan akan otomatis mengembalikan stok suplemen (*stock rollback*).

### 📦 2. Manajemen Inventaris Suplemen & Mutasi Stok
- **Katalog Suplemen Lengkap**: Manajemen SKU, barcode, kategori, satuan (pcs, botol, scoop, box), harga beli (HPP), dan harga jual.
- **Peringatan Stok Rendah (*Low Stock Alert*)**: Notifikasi visual ketika stok suplemen mencapai batas minimum (*reorder point*).
- **Restock Masuk**: Form pencatatan penambahan stok dari supplier dengan riwayat biaya beli.
- **Penyesuaian Stok Manual (*Stock Adjustment*)**: Fitur audit stok untuk koreksi fisik, barang rusak, kadaluarsa, atau sampel.
- **Kartu Riwayat Mutasi Stok (*Audit Trail*)**: Pencatatan riwayat setiap pergerakan stok keluar/masuk beserta referensi nomor invoice dan petugas kasir.

### 👥 3. Keanggotaan & Data Member (Gym Membership)
- **Data Member Terpusat**: Nama, nomor telepon/WhatsApp, email, tanggal lahir, kontak darurat, dan nomor kartu RFID/member code.
- **Masa Aktif Keanggotaan**: Tanggal mulai dan tanggal jatuh tempo dengan indikator status aktif atau kadaluarsa.
- **Pencarian Cepat**: Mempermudah frontdesk memilih data member saat checkout tiket atau reservasi kelas.

### 🎟️ 4. Tiket Harian & Paket Langganan (Gym Packages)
- **Paket Fleksibel**: Konfigurasi Tiket Harian (*Daily Pass*), Tiket Mingguan, Paket Bulanan, hingga Langganan Tahunan.
- **Aktivasi Paket**: Tombol cepat aktifkan/nonaktifkan ketersediaan paket untuk tampilan kasir POS.

### 🧘‍♀️ 5. Trainer & Jadwal Kelas Kebugaran
- **Manajemen Personal Trainer**: Biodata pelatih, spesialisasi keahlian (Bodybuilding, Yoga, HIIT, Cardio), tarif sesi, dan nomor kontak.
- **Jadwal Kelas Fitness**: Pengaturan jadwal kelas (Zumba, Yoga, Pilates, Spinning, Crossfit), batas kapasitas kuota peserta, dan instruktur penanggung jawab.

### 📅 6. Reservasi & Booking Sesi
- **Booking Jadwal Latihan**: Pendaftaran member untuk sesi Personal Trainer maupun kelas kebugaran.
- **Pelacakan Status**: Indikator status booking (*Pending*, *Confirmed*, *Completed*, *Cancelled*).
- **Tampilan Kalender Jadwal**: Visualisasi agenda latihan per hari/minggu untuk mengontrol kapasitas studio.

### 📊 7. Laporan Finansial & Analisis Laba Rugi (Owner Only)
- **Ringkasan Keuangan**: Total omset bruto, total HPP/modal, dan laba kotor (*Gross Profit*).
- **Filter Fleksibel**: Laporan harian, mingguan, bulanan, atau rentang tanggal kustom (*custom date range*).
- **Komparasi Penjualan**: Analisis pendapatan kategori suplemen vs paket gym.
- **Rekapitulasi Pembayaran**: Proporsi pembayaran via Tunai, QRIS, dan Transfer.
- **Cetak Laporan**: Siap diexport atau dicetak langsung untuk keperluan rekap akuntansi.

### 🔐 8. Kontrol Akses Berbasis Peran (Role-Based Access Control)
- **Owner**: Hak akses penuh ke seluruh modul, laporan laba rugi, manajemen staf pengguna, pembatalan invoice transaksi, dan konfigurasi gym.
- **Kasir / Frontdesk**: Dikhususkan untuk operasional harian: kasir POS, stok suplemen, pendaftaran member, dan pencatatan booking.
- **Manajemen Pengguna**: Tambah, edit, nonaktifkan/aktifkan akun kasir serta reset password.

### 🎨 9. Pengaturan Gym & Tampilan Dinamis
- **Identitas Bisnis**: Kustomisasi Nama Gym, Tagline, Alamat, No. WhatsApp, Email, dan Akun Instagram.
- **Upload Logo**: Unggah logo gym resmi yang langsung diterapkan di dashboard, login hero, dan struk POS.
- **Pengaturan Mata Uang & Struk**: Simbol mata uang (Rp) dan catatan motivasi/ucapan terima kasih di struk belanja.
- **Kustomisasi Tema Cyber**: Pilihan palet warna aksen (*Cyber Emerald*, *Electric Cyan*, *Sunset Gold*, *Flame Orange*, *Neon Rose*, *Ultra Violet*).
- **Dark & Light Mode Switcher**: Dukungan mode gelap (*default*) dan terang dengan penyimpanan preferensi di browser (*LocalStorage*).

---

## 🛠️ Teknologi yang Digunakan

| Komponen | Teknologi / Pustaka |
| :--- | :--- |
| **Backend Framework** | [Laravel 12](https://laravel.com) |
| **Bahasa Pemrograman** | [PHP 8.2+](https://php.net) |
| **Database** | MySQL / MariaDB (XAMPP Compatible) |
| **Styling & CSS** | [Tailwind CSS v4](https://tailwindcss.com) |
| **Build Tool & Bundler**| [Vite 7](https://vitejs.dev) |
| **Interaktivitas UI** | [Alpine.js 3](https://alpinejs.dev) |
| **Data Visualization** | [Chart.js](https://www.chartjs.org) |
| **Ikonografi** | [FontAwesome 6 Free](https://fontawesome.com) |
| **Tipografi** | Google Fonts (*Plus Jakarta Sans*, *Outfit*, *JetBrains Mono*) |

---

## 💻 Persyaratan Sistem

Sebelum menjalankan aplikasi, pastikan komputer/server Anda telah terpasang:
- **PHP >= 8.2** dengan ekstensi aktif:
  - `pdo_mysql`, `mbstring`, `openssl`, `tokenizer`, `xml`, `ctype`, `json`, `bcmath`, `fileinfo`, `gd`
- **Composer >= 2.x**
- **Node.js >= 18.x** dan **NPM**
- **MySQL >= 5.7** atau **MariaDB >= 10.4** (disarankan menggunakan XAMPP di Windows)
- **Web Browser Modern** (Chrome, Firefox, Edge, Safari)

---

## 🚀 Panduan Instalasi Cepat

Ikuti langkah-langkah di bawah ini untuk menjalankan aplikasi di lingkungan lokal (Local Development):

### 1. Clone Repository
```bash
git clone https://github.com/pujieleven-max/fitness-laravel.git
cd fitness-laravel
```

### 2. Install Dependensi PHP & Node.js
```bash
# Install PHP dependencies
composer install

# Install Javascript dependencies
npm install
```

### 3. Konfigurasi Environment File (`.env`)
Salin file `.env.example` menjadi `.env`:
```bash
# Windows PowerShell
copy .env.example .env

# Atau Command Prompt / Bash
cp .env.example .env
```

Buka file `.env` dan sesuaikan koneksi database MySQL Anda:
```env
APP_NAME="FitZone Gym & POS"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=fitness_laravel
DB_USERNAME=root
DB_PASSWORD=
```

> **Catatan:** Buat database baru bernama `fitness_laravel` di MySQL / phpMyAdmin terlebih dahulu sebelum menjalankan migrasi.

### 4. Generate Application Key
```bash
php artisan key:generate
```

### 5. Jalankan Migrasi & Seeder Database
Perintah ini akan membuat semua tabel database beserta data demo awal (pengguna, kategori, suplemen, paket, trainer, kelas, dan contoh transaksi):
```bash
php artisan migrate --seed
```

### 6. Buat Tautan Simbolik Storage (Storage Link)
Perintah ini wajib dijalankan agar logo gym dan berkas gambar yang diunggah dapat diakses dari browser:
```bash
php artisan storage:link
```

### 7. Jalankan Aplikasi
Buka 2 tab terminal untuk menjalankan server aplikasi dan asset bundler:

**Terminal 1 (Laravel Server):**
```bash
php artisan serve
```

**Terminal 2 (Vite Frontend Compiler):**
```bash
npm run dev
```

Aplikasi kini dapat diakses melalui browser di alamat:
👉 **[http://127.0.0.1:8000](http://127.0.0.1:8000)** atau **[http://localhost:8000](http://localhost:8000)**

---

## 🔑 Akun Demo Bawaan

Setelah menjalankan `php artisan migrate --seed`, Anda dapat langsung masuk menggunakan akun default berikut:

| Peran (Role) | Email | Password | Akses & Wewenang |
| :--- | :--- | :--- | :--- |
| **Owner (Pemilik)** | `owner@fitzone.com` | `password` | **Akses Penuh**: Laporan Laba/Rugi, Kelola Pengguna, Pembatalan Transaksi, Pengaturan Gym, POS & Master Data. |
| **Kasir 1 (Frontdesk)** | `kasir@fitzone.com` | `password` | **Operasional Kasir**: Transaksi POS, Cek Stok & Restock Suplemen, Registrasi Member, Booking Kelas. |
| **Kasir 2 (Budi)** | `budi@fitzone.com` | `password` | **Operasional Kasir**: Akses kasir frontdesk shift alternatif. |

---

## 📂 Struktur Direktori Utama

```text
fitness-laravel/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── AuthController.php          # Otentikasi login & logout
│   │   │   ├── BookingController.php       # Reservasi kelas & personal trainer
│   │   │   ├── DashboardController.php     # Ringkasan analitik & grafik performa
│   │   │   ├── GymPackageController.php    # Tiket harian & paket membership gym
│   │   │   ├── MemberController.php        # Manajemen keanggotaan member
│   │   │   ├── PosController.php           # Kasir POS, invoice, struk & void order
│   │   │   ├── ReportController.php        # Laporan finansial & laba kotor
│   │   │   ├── SettingController.php       # Pengaturan profil, logo, & tema gym
│   │   │   ├── SupplementController.php    # Suplemen, restock & mutasi stok
│   │   │   ├── TrainerController.php       # Personal trainer & jadwal kelas
│   │   │   └── UserController.php          # Manajemen user (Owner & Kasir)
│   │   └── Middleware/
│   │       └── RoleMiddleware.php          # Filter otorisasi hak akses peran
│   └── Models/                             # Eloquent Models (12 Model terelasi)
├── database/
│   ├── migrations/                         # Skema database terstruktur
│   └── seeders/
│       └── DatabaseSeeder.php              # Data dummy lengkap untuk pengujian
├── public/
│   └── storage/                            # Symlink berkas upload publik
├── resources/
│   ├── css/                                # Tailwind styling
│   └── views/
│       ├── layouts/
│       │   └── app.blade.php               # Master layout (Dark/Light & Cyber Theme)
│       ├── auth/                           # Halaman login
│       ├── bookings/                       # Modul reservasi & kalender
│       ├── dashboard/                      # Widget dasbor analitik
│       ├── members/                        # Modul data anggota gym
│       ├── packages/                       # Modul paket membership
│       ├── pos/                            # Layar kasir POS & cetak struk
│       ├── reports/                        # Modul laporan & laba rugi
│       ├── settings/                       # Modul pengaturan sistem
│       ├── supplements/                    # Modul inventaris suplemen
│       ├── trainers/                       # Modul pelatih & kelas
│       └── users/                          # Modul user management
└── routes/
    └── web.php                             # Rute aplikasi & proteksi hak akses
```

---

## ⚙️ Skrip Composer Tambahan

Proyek ini telah dilengkapi skrip praktis di `composer.json`:

```bash
# Setup otomatis proyek baru (install vendor, copy env, key gen, migrate, build assets)
composer run setup

# Menjalankan development mode dengan concurrency (Server, Vite, Logs)
composer run dev

# Menjalankan pengujian (Automated Tests)
composer run test
```

---

## 🤝 Kontribusi

Kontribusi selalu terbuka! Jika Anda memiliki saran perbaikan, fitur tambahan, atau laporan bug:
1. *Fork* repository ini.
2. Buat branch fitur baru (`git checkout -b feature/fitur-keren`).
3. *Commit* perubahan Anda (`git commit -m 'Menambahkan fitur keren'`).
4. *Push* ke branch (`git push origin feature/fitur-keren`).
5. Buka sebuah *Pull Request*.

---

## 📄 Lisensi

Proyek ini dirilis di bawah lisensi terbuka [MIT License](LICENSE). Bebas digunakan, dikembangkan, dan disesuaikan untuk keperluan komersial maupun personal.
