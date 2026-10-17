<?php

namespace Database\Seeders;

use App\Models\Booking;
use App\Models\Category;
use App\Models\FitnessClass;
use App\Models\GymPackage;
use App\Models\Member;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\StockMutation;
use App\Models\Supplement;
use App\Models\Trainer;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Users (Owner & Kasir)
        $owner = User::create([
            'name' => 'Coach Marcus (Owner)',
            'email' => 'owner@fitzone.com',
            'password' => Hash::make('password'),
            'role' => 'owner',
            'phone' => '0812-3456-7890',
            'is_active' => true,
        ]);

        $kasir = User::create([
            'name' => 'Sarah Kasir',
            'email' => 'kasir@fitzone.com',
            'password' => Hash::make('password'),
            'role' => 'kasir',
            'phone' => '0813-9876-5432',
            'is_active' => true,
        ]);

        $kasir2 = User::create([
            'name' => 'Budi Frontdesk',
            'email' => 'budi@fitzone.com',
            'password' => Hash::make('password'),
            'role' => 'kasir',
            'phone' => '0821-4567-8910',
            'is_active' => true,
        ]);

        // 2. Categories
        $catWhey = Category::create([
            'name' => 'Whey & Protein',
            'slug' => 'whey-protein',
            'type' => 'supplement',
            'icon' => 'zap',
            'description' => 'Bubuk protein murni untuk pembentukan massa otot dan recovery.',
        ]);

        $catPre = Category::create([
            'name' => 'Pre-Workout & Energy',
            'slug' => 'pre-workout',
            'type' => 'supplement',
            'icon' => 'flame',
            'description' => 'Suplemen pendongkrak stamina, fokus, dan pump saat latihan.',
        ]);

        $catBcaa = Category::create([
            'name' => 'Creatine & BCAA',
            'slug' => 'creatine-bcaa',
            'type' => 'supplement',
            'icon' => 'shield',
            'description' => 'Mencegah katabolik otot dan meningkatkan tenaga maksimal.',
        ]);

        $catFatBurn = Category::create([
            'name' => 'Fat Burner & Diet',
            'slug' => 'fat-burner',
            'type' => 'supplement',
            'icon' => 'activity',
            'description' => 'Termogenik untuk akselerasi pembakaran kalori dan lemak.',
        ]);

        $catBeverage = Category::create([
            'name' => 'Minuman & Healthy Snack',
            'slug' => 'beverage-snack',
            'type' => 'beverage',
            'icon' => 'coffee',
            'description' => 'Ready to drink protein shake, isotonic, dan protein bar.',
        ]);

        $catPass = Category::create([
            'name' => 'Membership & Tiket Gym',
            'slug' => 'membership-pass',
            'type' => 'package',
            'icon' => 'ticket',
            'description' => 'Akses harian gym, paket bulanan, dan sesi kelas.',
        ]);

        // 3. Gym Packages / Day Passes
        $passDaily = GymPackage::create([
            'code' => 'PKG-DAILY',
            'name' => 'Daily Pass (Tiket Masuk Harian)',
            'type' => 'daily',
            'duration_days' => 1,
            'price' => 35000,
            'description' => 'Akses bebas seluruh alat gym selama 1 hari penuh + locker.',
            'is_active' => true,
        ]);

        $passPelajar = GymPackage::create([
            'code' => 'PKG-STUDENT',
            'name' => 'Daily Pass Pelajar / Mahasiswa',
            'type' => 'daily',
            'duration_days' => 1,
            'price' => 25000,
            'description' => 'Tarif khusus pelajar/mahasiswa dengan menunjukkan KTM/kartu pelajar.',
            'is_active' => true,
        ]);

        $passMonthBronze = GymPackage::create([
            'code' => 'PKG-BRONZE-1M',
            'name' => 'Membership 1 Bulan (Bronze)',
            'type' => 'monthly',
            'duration_days' => 30,
            'price' => 250000,
            'description' => 'Akses unlimited gym 30 hari + free consultation trainer awal.',
            'is_active' => true,
        ]);

        $passMonthSilver = GymPackage::create([
            'code' => 'PKG-SILVER-3M',
            'name' => 'Membership 3 Bulan (Silver)',
            'type' => 'monthly',
            'duration_days' => 90,
            'price' => 650000,
            'description' => 'Akses unlimited gym 3 bulan + diskon suplemen 5% + 1 sesi PT gratis.',
            'is_active' => true,
        ]);

        $passAnnualGold = GymPackage::create([
            'code' => 'PKG-GOLD-1Y',
            'name' => 'Membership 1 Tahun VIP (Gold)',
            'type' => 'annual',
            'duration_days' => 365,
            'price' => 2200000,
            'description' => 'Akses VIP 1 tahun, free merchandise gym shaker & tas gym + sauna.',
            'is_active' => true,
        ]);

        // 4. Supplements & Products
        $supplementsData = [
            [
                'sku' => 'SUP-WHEY-001',
                'name' => 'ON Gold Standard 100% Whey 5 lbs',
                'category_id' => $catWhey->id,
                'brand' => 'Optimum Nutrition',
                'unit' => 'Tub 5 lbs (2.27kg)',
                'flavor' => 'Double Rich Chocolate',
                'purchase_price' => 880000,
                'selling_price' => 1100000,
                'stock' => 18,
                'min_stock_alert' => 5,
                'expiry_date' => Carbon::now()->addMonths(14),
                'description' => 'Standar emas protein dunia dengan 24g protein per scoop dan 5.5g BCAA.',
            ],
            [
                'sku' => 'SUP-WHEY-002',
                'name' => 'MuscleTech NitroTech 100% Whey Gold 2.2kg',
                'category_id' => $catWhey->id,
                'brand' => 'MuscleTech',
                'unit' => 'Tub 5 lbs',
                'flavor' => 'French Vanilla Cream',
                'purchase_price' => 820000,
                'selling_price' => 1025000,
                'stock' => 12,
                'min_stock_alert' => 5,
                'expiry_date' => Carbon::now()->addMonths(11),
                'description' => 'Whey peptida & isolat mikro terfilter dengan penyerapan kilat.',
            ],
            [
                'sku' => 'SUP-WHEY-003',
                'name' => 'Evolene Isolene Whey Isolate 50 Servings',
                'category_id' => $catWhey->id,
                'brand' => 'Evolene',
                'unit' => 'Tub 1.5kg',
                'flavor' => 'Coklat Hazelnut',
                'purchase_price' => 580000,
                'selling_price' => 740000,
                'stock' => 4, // LOW STOCK ALERT DEMO!
                'min_stock_alert' => 5,
                'expiry_date' => Carbon::now()->addMonths(9),
                'description' => 'Isolat lokal berkualitas tinggi, rendah laktosa dan zero sugar.',
            ],
            [
                'sku' => 'SUP-PRE-001',
                'name' => 'Cellucor C4 Original Pre-Workout 60 Serv',
                'category_id' => $catPre->id,
                'brand' => 'Cellucor',
                'unit' => 'Tub 390g',
                'flavor' => 'Fruit Punch',
                'purchase_price' => 420000,
                'selling_price' => 550000,
                'stock' => 9,
                'min_stock_alert' => 4,
                'expiry_date' => Carbon::now()->addMonths(16),
                'description' => 'Pre-workout nomor 1 di Amerika dengan CarnoSyn Beta-Alanine dan Kafein.',
            ],
            [
                'sku' => 'SUP-PRE-002',
                'name' => 'JNX The Curse! Ultimate Pre-Workout 50 Serv',
                'category_id' => $catPre->id,
                'brand' => 'JNX Sports',
                'unit' => 'Tub 250g',
                'flavor' => 'Blue Raspberry',
                'purchase_price' => 370000,
                'selling_price' => 485000,
                'stock' => 3, // LOW STOCK ALERT DEMO!
                'min_stock_alert' => 5,
                'expiry_date' => Carbon::now()->addMonths(8),
                'description' => 'Energi meledak tanpa rasa pusing setelah latihan selesai.',
            ],
            [
                'sku' => 'SUP-CREA-001',
                'name' => 'Optimum Micronized Creatine Powder 300g',
                'category_id' => $catBcaa->id,
                'brand' => 'Optimum Nutrition',
                'unit' => 'Tub 300g (60 Servings)',
                'flavor' => 'Unflavored (Tawar)',
                'purchase_price' => 260000,
                'selling_price' => 350000,
                'stock' => 15,
                'min_stock_alert' => 5,
                'expiry_date' => Carbon::now()->addMonths(20),
                'description' => 'Creatine monohidrat murni Creapure untuk peningkatan kekuatan angkatan.',
            ],
            [
                'sku' => 'SUP-BCAA-001',
                'name' => 'Scivation Xtend The Original 7G BCAA 30 Serv',
                'category_id' => $catBcaa->id,
                'brand' => 'Scivation',
                'unit' => 'Tub 420g',
                'flavor' => 'Mango Madness',
                'purchase_price' => 340000,
                'selling_price' => 440000,
                'stock' => 0, // OUT OF STOCK ALERT DEMO!
                'min_stock_alert' => 3,
                'expiry_date' => Carbon::now()->addMonths(10),
                'description' => 'Elektrolit dan rasio asam amino esensial 2:1:1 terbaik di kelasnya.',
            ],
            [
                'sku' => 'SUP-FAT-001',
                'name' => 'MuscleTech Hydroxycut Hardcore Elite 110 Caps',
                'category_id' => $catFatBurn->id,
                'brand' => 'MuscleTech',
                'unit' => 'Botol 110 Kapsul',
                'flavor' => 'Kapsul',
                'purchase_price' => 390000,
                'selling_price' => 520000,
                'stock' => 7,
                'min_stock_alert' => 4,
                'expiry_date' => Carbon::now()->addMonths(15),
                'description' => 'Formula super-termogenik 4-in-1 dengan ekstrak kopi hijau dan Coleus.',
            ],
            [
                'sku' => 'SUP-SNACK-001',
                'name' => 'Barebells High Protein Bar 55g',
                'category_id' => $catBeverage->id,
                'brand' => 'Barebells',
                'unit' => 'Pcs Bar 55g',
                'flavor' => 'Cookies & Cream',
                'purchase_price' => 35000,
                'selling_price' => 50000,
                'stock' => 28,
                'min_stock_alert' => 10,
                'expiry_date' => Carbon::now()->addMonths(5),
                'description' => 'Snack sehat lezat tanpa gula tambahan dengan 20 gram protein murni.',
            ],
            [
                'sku' => 'SUP-BEV-001',
                'name' => 'FitPro Ready to Drink Whey Shake 330ml',
                'category_id' => $catBeverage->id,
                'brand' => 'FitPro',
                'unit' => 'Tetra Pak 330ml',
                'flavor' => 'Choco Banana',
                'purchase_price' => 22000,
                'selling_price' => 32000,
                'stock' => 35,
                'min_stock_alert' => 8,
                'expiry_date' => Carbon::now()->addMonths(3),
                'description' => 'Langsung minum dingin setelah workout, 26g protein.',
            ],
            [
                'sku' => 'SUP-BEV-002',
                'name' => 'Pocari Sweat Ion Isotonic 500ml',
                'category_id' => $catBeverage->id,
                'brand' => 'Otsuka',
                'unit' => 'Botol 500ml',
                'flavor' => 'Original',
                'purchase_price' => 6500,
                'selling_price' => 10000,
                'stock' => 45,
                'min_stock_alert' => 12,
                'expiry_date' => Carbon::now()->addMonths(7),
                'description' => 'Pengganti ion tubuh yang hilang selama sesi latihan intens.',
            ],
            [
                'sku' => 'SUP-ACC-001',
                'name' => 'FitZone Gym Smart Shaker Bottle 700ml',
                'category_id' => $catPass->id,
                'brand' => 'FitZone Official',
                'unit' => 'Pcs',
                'flavor' => 'Matte Black',
                'purchase_price' => 40000,
                'selling_price' => 75000,
                'stock' => 20,
                'min_stock_alert' => 5,
                'expiry_date' => null,
                'description' => 'Shaker BPA-Free dengan kompartemen suplemen dan jaring pengocok.',
            ],
        ];

        $createdSupplements = [];
        foreach ($supplementsData as $item) {
            $supp = Supplement::create($item);
            $createdSupplements[$supp->sku] = $supp;

            // Generate initial stock mutation
            StockMutation::create([
                'supplement_id' => $supp->id,
                'user_id' => $owner->id,
                'type' => 'in',
                'quantity' => $supp->stock + 5,
                'stock_before' => 0,
                'stock_after' => $supp->stock + 5,
                'reference_type' => 'restock',
                'reference_id' => 'RESTOCK-INIT-' . rand(100, 999),
                'cost_per_unit' => $supp->purchase_price,
                'notes' => 'Inisialisasi stok awal gudang suplemen',
            ]);
        }

        // 5. Members
        $membersData = [
            [
                'member_code' => 'MBR-1001',
                'name' => 'Dimas Arya Pratama',
                'phone' => '0812-1111-2222',
                'email' => 'dimas.arya@gmail.com',
                'gender' => 'male',
                'address' => 'Jl. Boulevard No. 12, Jakarta',
                'membership_type' => 'monthly_silver',
                'membership_start' => Carbon::now()->subDays(15),
                'membership_expires_at' => Carbon::now()->addDays(75),
                'status' => 'active',
            ],
            [
                'member_code' => 'MBR-1002',
                'name' => 'Jessica Tanuwijaya',
                'phone' => '0813-3333-4444',
                'email' => 'jessica.tan@gmail.com',
                'gender' => 'female',
                'address' => 'Apartemen Sudirman Tower A-12',
                'membership_type' => 'annual_gold',
                'membership_start' => Carbon::now()->subMonths(2),
                'membership_expires_at' => Carbon::now()->addMonths(10),
                'status' => 'active',
            ],
            [
                'member_code' => 'MBR-1003',
                'name' => 'Rizky Firmansyah',
                'phone' => '0819-5555-6666',
                'email' => 'rizky.firman@gmail.com',
                'gender' => 'male',
                'address' => 'Jl. Anggrek Nelimurni No. 8',
                'membership_type' => 'monthly_bronze',
                'membership_start' => Carbon::now()->subDays(28),
                'membership_expires_at' => Carbon::now()->addDays(2),
                'status' => 'active',
            ],
            [
                'member_code' => 'MBR-1004',
                'name' => 'Amanda Putri',
                'phone' => '0878-7777-8888',
                'email' => 'amanda.putri@yahoo.com',
                'gender' => 'female',
                'address' => 'Jl. Danau Sunter Utara Blok F',
                'membership_type' => 'none',
                'membership_start' => null,
                'membership_expires_at' => null,
                'status' => 'inactive',
            ],
        ];

        $createdMembers = [];
        foreach ($membersData as $m) {
            $createdMembers[] = Member::create($m);
        }

        // 6. Trainers (Personal Trainers)
        $trainersData = [
            [
                'name' => 'Coach Dennis Santoso',
                'phone' => '0812-7001-9001',
                'email' => 'dennis.coach@fitzone.com',
                'specialization' => 'Hypertrophy & Bodybuilding Specialist',
                'hourly_rate' => 175000,
                'bio' => 'Bersertifikasi APKI & IFBB Pro Trainer dengan pengalaman 8 tahun membimbing atlet dan pemula.',
                'is_active' => true,
            ],
            [
                'name' => 'Coach Maya Lestari',
                'phone' => '0813-7002-9002',
                'email' => 'maya.yoga@fitzone.com',
                'specialization' => 'Vinyasa Yoga, Pilates & Mobility',
                'hourly_rate' => 150000,
                'bio' => 'Instruktur RYT-200 Yoga Alliance berfokus pada postur, mobilitas sendi, dan pernapasan.',
                'is_active' => true,
            ],
            [
                'name' => 'Coach Reza Bramantyo',
                'phone' => '0819-7003-9003',
                'email' => 'reza.power@fitzone.com',
                'specialization' => 'Strength & Conditioning, Powerlifting',
                'hourly_rate' => 160000,
                'bio' => 'Mantan atlet angkat berat nasional, spesialis form squat, bench press, deadlift yang aman.',
                'is_active' => true,
            ],
            [
                'name' => 'Coach Cindy Claudia',
                'phone' => '0878-7004-9004',
                'email' => 'cindy.hiit@fitzone.com',
                'specialization' => 'Fat Loss Bootcamp & HIIT Cardio',
                'hourly_rate' => 140000,
                'bio' => 'Program transformasi penurunan berat badan dan body shaping dengan metode interval intensitas tinggi.',
                'is_active' => true,
            ],
        ];

        $createdTrainers = [];
        foreach ($trainersData as $t) {
            $createdTrainers[] = Trainer::create($t);
        }

        // 7. Fitness Classes
        $classesData = [
            [
                'name' => 'Spinning RPM Burn',
                'trainer_id' => $createdTrainers[3]->id,
                'category' => 'Cardio Intense',
                'schedule_day' => 'Senin',
                'start_time' => '18:30:00',
                'end_time' => '19:30:00',
                'room' => 'Spinning Studio 2',
                'capacity' => 15,
                'price_per_session' => 60000,
                'is_active' => true,
            ],
            [
                'name' => 'Power Yoga & Core',
                'trainer_id' => $createdTrainers[1]->id,
                'category' => 'Mind & Body',
                'schedule_day' => 'Selasa',
                'start_time' => '08:00:00',
                'end_time' => '09:15:00',
                'room' => 'Zen Studio A',
                'capacity' => 20,
                'price_per_session' => 50000,
                'is_active' => true,
            ],
            [
                'name' => 'HIIT Fat Loss Bootcamp',
                'trainer_id' => $createdTrainers[3]->id,
                'category' => 'Fat Burning',
                'schedule_day' => 'Rabu',
                'start_time' => '19:00:00',
                'end_time' => '20:00:00',
                'room' => 'Functional Turf Area',
                'capacity' => 18,
                'price_per_session' => 55000,
                'is_active' => true,
            ],
            [
                'name' => 'Barbell Strength 101',
                'trainer_id' => $createdTrainers[2]->id,
                'category' => 'Strength Training',
                'schedule_day' => 'Kamis',
                'start_time' => '17:00:00',
                'end_time' => '18:15:00',
                'room' => 'Free Weight Arena',
                'capacity' => 12,
                'price_per_session' => 65000,
                'is_active' => true,
            ],
            [
                'name' => 'Zumba Party Weekend',
                'trainer_id' => $createdTrainers[1]->id,
                'category' => 'Dance Cardio',
                'schedule_day' => 'Sabtu',
                'start_time' => '09:00:00',
                'end_time' => '10:00:00',
                'room' => 'Studio Utama Lt.2',
                'capacity' => 25,
                'price_per_session' => 45000,
                'is_active' => true,
            ],
        ];

        $createdClasses = [];
        foreach ($classesData as $c) {
            $createdClasses[] = FitnessClass::create($c);
        }

        // 8. Bookings
        $bookingsData = [
            [
                'booking_code' => 'BK-' . date('Ymd') . '-001',
                'user_id' => $kasir->id,
                'member_id' => $createdMembers[0]->id,
                'customer_name' => $createdMembers[0]->name,
                'customer_phone' => $createdMembers[0]->phone,
                'booking_type' => 'personal_trainer',
                'trainer_id' => $createdTrainers[0]->id,
                'fitness_class_id' => null,
                'booking_date' => Carbon::today(),
                'start_time' => '16:00:00',
                'end_time' => '17:00:00',
                'price' => 175000,
                'status' => 'confirmed',
                'payment_status' => 'paid',
                'notes' => 'Fokus latihan dada dan trisep, evaluasi repetisi.',
            ],
            [
                'booking_code' => 'BK-' . date('Ymd') . '-002',
                'user_id' => $kasir->id,
                'member_id' => $createdMembers[1]->id,
                'customer_name' => $createdMembers[1]->name,
                'customer_phone' => $createdMembers[1]->phone,
                'booking_type' => 'fitness_class',
                'trainer_id' => $createdTrainers[3]->id,
                'fitness_class_id' => $createdClasses[0]->id,
                'booking_date' => Carbon::today(),
                'start_time' => '18:30:00',
                'end_time' => '19:30:00',
                'price' => 60000,
                'status' => 'confirmed',
                'payment_status' => 'paid',
                'notes' => 'Peserta request sepeda di baris depan nomor 03.',
            ],
            [
                'booking_code' => 'BK-' . date('Ymd') . '-003',
                'user_id' => $kasir->id,
                'member_id' => null,
                'customer_name' => 'Fajar Maulana',
                'customer_phone' => '0857-1234-9988',
                'booking_type' => 'personal_trainer',
                'trainer_id' => $createdTrainers[2]->id,
                'fitness_class_id' => null,
                'booking_date' => Carbon::tomorrow(),
                'start_time' => '10:00:00',
                'end_time' => '11:00:00',
                'price' => 160000,
                'status' => 'pending',
                'payment_status' => 'unpaid',
                'notes' => 'Trial sesi pertama teknik deadlift aman.',
            ],
            [
                'booking_code' => 'BK-' . Carbon::yesterday()->format('Ymd') . '-004',
                'user_id' => $kasir->id,
                'member_id' => $createdMembers[2]->id,
                'customer_name' => $createdMembers[2]->name,
                'customer_phone' => $createdMembers[2]->phone,
                'booking_type' => 'personal_trainer',
                'trainer_id' => $createdTrainers[0]->id,
                'fitness_class_id' => null,
                'booking_date' => Carbon::yesterday(),
                'start_time' => '15:00:00',
                'end_time' => '16:00:00',
                'price' => 175000,
                'status' => 'completed',
                'payment_status' => 'paid',
                'notes' => 'Sesi back workout & biceps selesai dengan lancar.',
            ],
        ];

        foreach ($bookingsData as $bk) {
            Booking::create($bk);
        }

        // 9. Sample Completed POS Orders & Order Items
        // Order 1: Suplemen & RTD
        $order1 = Order::create([
            'invoice_no' => 'INV-' . date('Ymd') . '-0001',
            'user_id' => $kasir->id,
            'member_id' => $createdMembers[0]->id,
            'customer_name' => $createdMembers[0]->name,
            'customer_type' => 'member',
            'subtotal' => 1182000,
            'discount' => 50000,
            'tax' => 0,
            'total_amount' => 1132000,
            'payment_method' => 'qris',
            'cash_received' => 1132000,
            'cash_change' => 0,
            'status' => 'completed',
            'notes' => 'Diskon member silver 50k',
            'created_at' => Carbon::now()->subHours(3),
        ]);

        OrderItem::create([
            'order_id' => $order1->id,
            'item_type' => 'supplement',
            'supplement_id' => $createdSupplements['SUP-WHEY-001']->id,
            'name' => 'ON Gold Standard 100% Whey 5 lbs',
            'unit_price' => 1100000,
            'cost_price' => 880000,
            'quantity' => 1,
            'subtotal' => 1100000,
        ]);

        OrderItem::create([
            'order_id' => $order1->id,
            'item_type' => 'supplement',
            'supplement_id' => $createdSupplements['SUP-SNACK-001']->id,
            'name' => 'Barebells High Protein Bar 55g',
            'unit_price' => 50000,
            'cost_price' => 35000,
            'quantity' => 1,
            'subtotal' => 50000,
        ]);

        OrderItem::create([
            'order_id' => $order1->id,
            'item_type' => 'supplement',
            'supplement_id' => $createdSupplements['SUP-BEV-001']->id,
            'name' => 'FitPro Ready to Drink Whey Shake 330ml',
            'unit_price' => 32000,
            'cost_price' => 22000,
            'quantity' => 1,
            'subtotal' => 32000,
        ]);

        // Stock mutation for order 1
        StockMutation::create([
            'supplement_id' => $createdSupplements['SUP-WHEY-001']->id,
            'user_id' => $kasir->id,
            'type' => 'out',
            'quantity' => 1,
            'stock_before' => 19,
            'stock_after' => 18,
            'reference_type' => 'pos_sale',
            'reference_id' => $order1->invoice_no,
            'notes' => 'Penjualan POS Invoice #' . $order1->invoice_no,
        ]);

        // Order 2: Walk-in Day Pass & Drink
        $order2 = Order::create([
            'invoice_no' => 'INV-' . date('Ymd') . '-0002',
            'user_id' => $kasir->id,
            'member_id' => null,
            'customer_name' => 'Tamu Walk-In (Aldo)',
            'customer_type' => 'walk_in',
            'subtotal' => 45000,
            'discount' => 0,
            'tax' => 0,
            'total_amount' => 45000,
            'payment_method' => 'cash',
            'cash_received' => 50000,
            'cash_change' => 5000,
            'status' => 'completed',
            'notes' => 'Pembayaran tunai pas',
            'created_at' => Carbon::now()->subHours(2),
        ]);

        OrderItem::create([
            'order_id' => $order2->id,
            'item_type' => 'gym_package',
            'gym_package_id' => $passDaily->id,
            'name' => 'Daily Pass (Tiket Masuk Harian)',
            'unit_price' => 35000,
            'cost_price' => 0,
            'quantity' => 1,
            'subtotal' => 35000,
        ]);

        OrderItem::create([
            'order_id' => $order2->id,
            'item_type' => 'supplement',
            'supplement_id' => $createdSupplements['SUP-BEV-002']->id,
            'name' => 'Pocari Sweat Ion Isotonic 500ml',
            'unit_price' => 10000,
            'cost_price' => 6500,
            'quantity' => 1,
            'subtotal' => 10000,
        ]);
    }
}
