<?php

namespace Tests\Feature;

use App\Models\ActivityLog;
use App\Models\Member;
use App\Models\MemberAssessment;
use App\Models\Trainer;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class MemberAssessmentTest extends TestCase
{
    use DatabaseMigrations;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_assessment_index_accessible_by_authenticated_user(): void
    {
        $kasir = User::where('role', 'kasir')->first();
        $this->actingAs($kasir);

        $response = $this->get('/assessments');
        $response->assertStatus(200);
        $response->assertSee('Fitness & Body Measurement Assessment');
    }

    public function test_assessment_create_page_accessible(): void
    {
        $owner = User::where('role', 'owner')->first();
        $this->actingAs($owner);

        $response = $this->get('/assessments/create');
        $response->assertStatus(200);
        $response->assertSee('Form Asesmen & Penimbangan Kebugaran');
    }

    public function test_store_assessment_creates_record_and_auto_calculates_bmi(): void
    {
        $kasir = User::where('role', 'kasir')->first();
        $this->actingAs($kasir);

        $member = Member::first();
        $trainer = Trainer::first();

        $payload = [
            'member_id' => $member->id,
            'trainer_id' => $trainer->id,
            'assessment_date' => '2026-10-31',
            'weight' => 70.0,
            'height' => 175.0, // 70 / (1.75^2) = 22.86 -> Normal
            'body_fat_percentage' => 18.5,
            'muscle_mass' => 52.0,
            'body_water_percentage' => 56.0,
            'visceral_fat' => 5,
            'bmr' => 1650,
            'chest_circumference' => 98.0,
            'waist_circumference' => 79.0,
            'fitness_goal' => 'Body Recomposition',
            'trainer_notes' => 'Kondisi awal stabil, postur baik.',
        ];

        $response = $this->post('/assessments', $payload);
        $response->assertRedirect();

        $this->assertDatabaseHas('member_assessments', [
            'member_id' => $member->id,
            'trainer_id' => $trainer->id,
            'weight' => 70.0,
            'height' => 175.0,
            'bmi' => 22.9,
            'bmi_category' => 'Normal',
            'body_fat_percentage' => 18.5,
            'muscle_mass' => 52.0,
        ]);

        // Verify that LogsActivity automatically tracked the INSERT
        $this->assertDatabaseHas('activity_logs', [
            'action' => 'INSERT',
            'module' => 'Asesmen Kebugaran',
            'user_id' => $kasir->id,
        ]);
    }

    public function test_update_assessment_recalculates_bmi_and_triggers_activity_log(): void
    {
        $owner = User::where('role', 'owner')->first();
        $this->actingAs($owner);

        $member = Member::first();

        $assessment = MemberAssessment::create([
            'member_id' => $member->id,
            'assessment_date' => '2026-10-30',
            'weight' => 85.0,
            'height' => 170.0, // 85 / (1.7^2) = 29.4 -> Overweight
            'bmi' => 29.4,
            'bmi_category' => 'Overweight',
            'body_fat_percentage' => 26.0,
        ]);

        // Update weight down to 75kg -> 75 / (1.7^2) = 26.0
        $response = $this->put("/assessments/{$assessment->id}", [
            'member_id' => $member->id,
            'assessment_date' => '2026-10-30',
            'weight' => 75.0,
            'height' => 170.0,
            'body_fat_percentage' => 22.0,
            'trainer_notes' => 'Progres signifikan dalam penurunan berat.',
        ]);

        $response->assertRedirect();

        $assessment->refresh();
        $this->assertEquals(75.0, (float) $assessment->weight);
        $this->assertEquals(26.0, (float) $assessment->bmi);

        // Verify activity log UPDATE
        $this->assertDatabaseHas('activity_logs', [
            'action' => 'UPDATE',
            'module' => 'Asesmen Kebugaran',
            'user_id' => $owner->id,
        ]);
    }

    public function test_destroy_assessment_triggers_activity_log(): void
    {
        $owner = User::where('role', 'owner')->first();
        $this->actingAs($owner);

        $member = Member::first();

        $assessment = MemberAssessment::create([
            'member_id' => $member->id,
            'assessment_date' => '2026-10-25',
            'weight' => 68.0,
            'height' => 165.0,
        ]);

        $assessmentId = $assessment->id;
        $response = $this->delete("/assessments/{$assessmentId}");
        $response->assertRedirect('/assessments');

        $this->assertDatabaseMissing('member_assessments', [
            'id' => $assessmentId,
        ]);

        // Verify activity log DELETE
        $this->assertDatabaseHas('activity_logs', [
            'action' => 'DELETE',
            'module' => 'Asesmen Kebugaran',
            'subject_id' => (string) $assessmentId,
        ]);
    }

    public function test_member_history_and_print_card_render(): void
    {
        $kasir = User::where('role', 'kasir')->first();
        $this->actingAs($kasir);

        $member = Member::first();

        $assessment = MemberAssessment::create([
            'member_id' => $member->id,
            'assessment_date' => '2026-10-31',
            'weight' => 72.0,
            'height' => 175.0,
            'bmi' => 23.5,
            'bmi_category' => 'Normal',
            'body_fat_percentage' => 20.0,
            'muscle_mass' => 50.0,
        ]);

        // Member History Page
        $response = $this->get("/assessments/member/{$member->id}");
        $response->assertStatus(200);
        $response->assertSee('Transformasi & Progres Kebugaran Member');
        $response->assertSee($member->name);

        // Print Card Page
        $response = $this->get("/assessments/{$assessment->id}/print");
        $response->assertStatus(200);
        $response->assertSee('Fitness & Body Measurement Assessment', false);
        $response->assertSee($member->name);
    }
}
