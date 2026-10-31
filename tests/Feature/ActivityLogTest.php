<?php

namespace Tests\Feature;

use App\Models\ActivityLog;
use App\Models\GymPackage;
use App\Models\Member;
use App\Models\Supplement;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class ActivityLogTest extends TestCase
{
    use DatabaseMigrations;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_insert_activity_is_tracked_with_user_info(): void
    {
        $owner = User::where('role', 'owner')->first();
        $this->actingAs($owner);

        $member = Member::create([
            'member_code' => 'MBR-9999',
            'name' => 'Budi Santoso Audit Test',
            'phone' => '081234567899',
            'email' => 'budi.audit@example.com',
            'gender' => 'male',
            'membership_type' => 'monthly',
            'status' => 'active',
        ]);

        $this->assertDatabaseHas('activity_logs', [
            'action' => 'INSERT',
            'module' => 'Member',
            'user_id' => $owner->id,
            'user_name' => $owner->name,
            'user_role' => 'owner',
            'subject_id' => (string) $member->id,
        ]);

        $log = ActivityLog::where('action', 'INSERT')
            ->where('subject_type', Member::class)
            ->where('subject_id', (string) $member->id)
            ->first();

        $this->assertNotNull($log);
        $this->assertEquals('Budi Santoso Audit Test', $log->new_values['name']);
        $this->assertStringContainsString('Budi Santoso Audit Test', $log->description);
    }

    public function test_update_activity_is_tracked_with_old_and_new_values(): void
    {
        $kasir = User::where('role', 'kasir')->first();
        $this->actingAs($kasir);

        $supplement = Supplement::first();
        $oldPrice = (float) $supplement->selling_price;
        $newPrice = $oldPrice + 15000;

        $supplement->update([
            'selling_price' => $newPrice,
        ]);

        $log = ActivityLog::where('action', 'UPDATE')
            ->where('subject_type', Supplement::class)
            ->where('subject_id', (string) $supplement->id)
            ->latest('id')
            ->first();

        $this->assertNotNull($log);
        $this->assertEquals($kasir->id, $log->user_id);
        $this->assertEquals($kasir->name, $log->user_name);
        $this->assertEquals('kasir', $log->user_role);
        $this->assertEquals($oldPrice, (float) $log->old_values['selling_price']);
        $this->assertEquals($newPrice, (float) $log->new_values['selling_price']);
        $this->assertStringContainsString('Harga Jual', $log->description);
    }

    public function test_delete_activity_is_tracked_with_snapshot(): void
    {
        $owner = User::where('role', 'owner')->first();
        $this->actingAs($owner);

        $pkg = GymPackage::create([
            'code' => 'PKG-DEL-01',
            'name' => 'Paket Akan Dihapus',
            'type' => 'daily',
            'duration_days' => 1,
            'price' => 25000,
            'is_active' => true,
        ]);

        $pkgId = $pkg->id;
        $pkgName = $pkg->name;

        $pkg->delete();

        $log = ActivityLog::where('action', 'DELETE')
            ->where('subject_type', GymPackage::class)
            ->where('subject_id', (string) $pkgId)
            ->first();

        $this->assertNotNull($log);
        $this->assertEquals($owner->id, $log->user_id);
        $this->assertEquals('Paket Akan Dihapus', $log->old_values['name']);
        $this->assertStringContainsString($pkgName, $log->description);
    }

    public function test_login_and_logout_activities_are_recorded(): void
    {
        $user = User::where('email', 'owner@fitzone.com')->first();

        // Perform login
        $response = $this->post('/login', [
            'email' => 'owner@fitzone.com',
            'password' => 'password',
        ]);
        $response->assertRedirect('/dashboard');

        $this->assertDatabaseHas('activity_logs', [
            'action' => 'LOGIN',
            'user_id' => $user->id,
            'module' => 'Autentikasi',
        ]);

        // Perform logout
        $response = $this->post('/logout');
        $response->assertRedirect('/login');

        $this->assertDatabaseHas('activity_logs', [
            'action' => 'LOGOUT',
            'user_id' => $user->id,
            'module' => 'Autentikasi',
        ]);
    }

    public function test_owner_can_access_activities_index_and_json_detail(): void
    {
        $owner = User::where('role', 'owner')->first();
        $this->actingAs($owner);

        // Access index page
        $response = $this->get('/activities');
        $response->assertStatus(200);
        $response->assertSee('Log Aktivitas & Audit Trail');

        // Access JSON show endpoint
        $log = ActivityLog::first();
        $response = $this->getJson("/activities/{$log->id}");
        $response->assertStatus(200);
        $response->assertJson([
            'id' => $log->id,
            'action' => $log->action,
        ]);
    }

    public function test_kasir_cannot_access_activities_page(): void
    {
        $kasir = User::where('role', 'kasir')->first();
        $this->actingAs($kasir);

        $response = $this->get('/activities');
        $response->assertStatus(403);
    }
}
