<?php

namespace Tests\Feature;

use App\Models\Setting;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class FitnessModuleTest extends TestCase
{
    use DatabaseMigrations;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }
    public function test_login_page_is_accessible(): void
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
        $response->assertSee('FitZone');
    }

    public function test_owner_can_access_dashboard_and_modules(): void
    {
        $owner = User::where('email', 'owner@fitzone.com')->first();
        $this->actingAs($owner);

        // Dashboard
        $response = $this->get('/dashboard');
        $response->assertStatus(200);
        $response->assertSee('Executive Dashboard');

        // POS
        $response = $this->get('/pos');
        $response->assertStatus(200);
        $response->assertSee('Terminal Kasir');

        // Supplements
        $response = $this->get('/supplements');
        $response->assertStatus(200);
        $response->assertSee('Katalog Suplemen');

        // Bookings
        $response = $this->get('/bookings');
        $response->assertStatus(200);
        $response->assertSee('Daftar Booking');

        // Bookings Create
        $response = $this->get('/bookings/create');
        $response->assertStatus(200);
        $response->assertSee('Buat Jadwal Booking Baru');
        $response->assertSee('x-data="bookingCreateApp()"', false);

        // Gym Packages (Tiket Masuk & Langganan)
        $response = $this->get('/packages');
        $response->assertStatus(200);
        $response->assertSee('Kelola Tiket Masuk Harian & Paket Langganan Gym');

        // Reports (Owner only)
        $response = $this->get('/reports');
        $response->assertStatus(200);
        $response->assertSee('Laporan Keuangan');

        // Users (Owner only)
        $response = $this->get('/users');
        $response->assertStatus(200);
        $response->assertSee('Manajemen Pengguna');
    }

    public function test_gym_package_crud_operations(): void
    {
        $owner = User::where('email', 'owner@fitzone.com')->first();
        $this->actingAs($owner);

        // Create new package
        $response = $this->post('/packages', [
            'code' => 'PKG-TEST-01',
            'name' => 'Paket Weekend Special',
            'type' => 'daily',
            'duration_days' => 2,
            'price' => 50000,
            'description' => 'Akses 2 hari weekend Sabtu & Minggu',
            'is_active' => 1,
        ]);
        $response->assertRedirect();
        $this->assertDatabaseHas('gym_packages', [
            'code' => 'PKG-TEST-01',
            'name' => 'Paket Weekend Special',
            'price' => 50000,
        ]);

        $pkg = \App\Models\GymPackage::where('code', 'PKG-TEST-01')->first();

        // Toggle status
        $this->patch("/packages/{$pkg->id}/toggle");
        $this->assertFalse($pkg->fresh()->is_active);

        // Update
        $this->put("/packages/{$pkg->id}", [
            'code' => 'PKG-TEST-01',
            'name' => 'Paket Weekend VIP',
            'type' => 'daily',
            'duration_days' => 2,
            'price' => 55000,
            'description' => 'Akses weekend + sauna',
            'is_active' => 1,
        ]);
        $this->assertEquals('Paket Weekend VIP', $pkg->fresh()->name);
        $this->assertEquals(55000, (int)$pkg->fresh()->price);

        // Delete
        $this->delete("/packages/{$pkg->id}");
        $this->assertDatabaseMissing('gym_packages', [
            'id' => $pkg->id,
        ]);
    }

    public function test_kasir_can_access_pos_and_restricted_from_owner_reports(): void
    {
        $kasir = User::where('email', 'kasir@fitzone.com')->first();
        $this->actingAs($kasir);

        // Kasir Dashboard
        $response = $this->get('/dashboard');
        $response->assertStatus(200);
        $response->assertSee('Dashboard Kasir Frontdesk');

        // POS
        $response = $this->get('/pos');
        $response->assertStatus(200);

        // Reports (forbidden for Kasir)
        $response = $this->get('/reports');
        $response->assertStatus(403);

        // Users (forbidden for Kasir)
        $response = $this->get('/users');
        $response->assertStatus(403);
    }

    public function test_pos_checkout_deducts_stock_and_creates_order(): void
    {
        $kasir = User::where('email', 'kasir@fitzone.com')->first();
        $this->actingAs($kasir);

        $supplement = \App\Models\Supplement::where('sku', 'SUP-WHEY-001')->first();
        $initialStock = $supplement->stock;

        $payload = [
            'items' => [
                [
                    'type' => 'supplement',
                    'id' => $supplement->id,
                    'name' => $supplement->name,
                    'price' => $supplement->selling_price,
                    'quantity' => 2,
                ]
            ],
            'discount' => 10000,
            'payment_method' => 'cash',
            'cash_received' => ($supplement->selling_price * 2) - 10000,
            'customer_type' => 'walk_in',
            'customer_name' => 'Bpk. Hendra Testing',
        ];

        $response = $this->postJson('/pos/checkout', $payload);
        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
        ]);

        // Assert stock decremented by 2
        $this->assertEquals($initialStock - 2, $supplement->fresh()->stock);

        // Assert Order created
        $this->assertDatabaseHas('orders', [
            'customer_name' => 'Bpk. Hendra Testing',
            'payment_method' => 'cash',
            'status' => 'completed',
        ]);

        // Assert Stock Mutation logged
        $this->assertDatabaseHas('stock_mutations', [
            'supplement_id' => $supplement->id,
            'type' => 'out',
            'quantity' => 2,
            'reference_type' => 'pos_sale',
        ]);
    }

    public function test_app_settings_access_and_permissions(): void
    {
        $owner = User::where('email', 'owner@fitzone.com')->first();
        $kasir = User::where('email', 'kasir@fitzone.com')->first();

        // Kasir should be forbidden (403)
        $this->actingAs($kasir);
        $response = $this->get('/settings');
        $response->assertStatus(403);

        // Owner should have access (200)
        $this->actingAs($owner);
        $response = $this->get('/settings');
        $response->assertStatus(200);
        $response->assertSee('Pengaturan');
        $response->assertSee('Identitas Utama Gym');
    }

    public function test_owner_can_update_app_settings_and_logo(): void
    {
        Storage::fake('public');
        $owner = User::where('email', 'owner@fitzone.com')->first();
        $this->actingAs($owner);

        // 1x1 transparent PNG in base64 to avoid GD extension dependency
        $png1x1 = base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNk+M9QDwADhgGAWjR9awAAAABJRU5ErkJggg==');
        $fakeLogo = UploadedFile::fake()->createWithContent('custom_gym_logo.png', $png1x1);

        $updateData = [
            'app_name' => 'TITAN GYM INDONESIA',
            'app_tagline' => 'Hardcore Training & Health Hub',
            'phone' => '0811-9988-7766',
            'email' => 'cs@titangym.id',
            'address' => 'Jl. Mega Kuningan Barat No. 12, Jakarta',
            'receipt_footer' => "Salam Sehat dari Titan Gym!\nBuild Your Legacy.",
            'social_instagram' => '@titangym.official',
            'currency_symbol' => 'Rp',
            'theme_color' => 'cyan',
            'logo' => $fakeLogo,
        ];

        $response = $this->put('/settings', $updateData);
        $response->assertStatus(302);
        $response->assertSessionHas('success');

        // Verify database updated
        $this->assertDatabaseHas('settings', [
            'app_name' => 'TITAN GYM INDONESIA',
            'phone' => '0811-9988-7766',
            'email' => 'cs@titangym.id',
            'social_instagram' => '@titangym.official',
            'theme_color' => 'cyan',
        ]);

        $setting = Setting::current();
        $this->assertEquals('TITAN GYM INDONESIA', $setting->app_name);
        $this->assertEquals('cyan', $setting->theme_color);
        $this->assertNotNull($setting->logo_path);
        Storage::disk('public')->assertExists($setting->logo_path);

        // Verify dynamic update is visible on dashboard / app layout
        $dashResponse = $this->get('/dashboard');
        $dashResponse->assertStatus(200);
        $dashResponse->assertSee('TITAN GYM INDONESIA');

        // Test removing logo
        $removeResponse = $this->put('/settings', [
            'app_name' => 'TITAN GYM INDONESIA',
            'remove_logo' => 1,
        ]);
        $removeResponse->assertStatus(302);
        $this->assertNull(Setting::current()->logo_path);
    }
}
