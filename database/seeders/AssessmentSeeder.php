<?php

namespace Database\Seeders;

use App\Models\Member;
use App\Models\MemberAssessment;
use App\Models\Trainer;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class AssessmentSeeder extends Seeder
{
    public function run(): void
    {
        $m1 = Member::first();
        $m2 = Member::skip(1)->first();
        $t1 = Trainer::first();
        $t2 = Trainer::skip(1)->first();
        $u = User::where('role', 'owner')->first() ?? User::first();

        if ($m1 && $t1) {
            MemberAssessment::create([
                'member_id' => $m1->id,
                'trainer_id' => $t1->id,
                'user_id' => $u?->id,
                'assessment_date' => Carbon::now()->subWeeks(4),
                'weight' => 78.5,
                'height' => 174.0,
                'bmi' => 25.9,
                'bmi_category' => 'Overweight',
                'body_fat_percentage' => 24.5,
                'muscle_mass' => 48.0,
                'body_water_percentage' => 52.0,
                'visceral_fat' => 8,
                'bmr' => 1680,
                'metabolic_age' => 29,
                'chest_circumference' => 99.0,
                'waist_circumference' => 88.0,
                'hips_circumference' => 98.0,
                'arm_circumference' => 32.5,
                'thigh_circumference' => 58.0,
                'calf_circumference' => 37.0,
                'fitness_goal' => 'Fat Loss & Slimming',
                'trainer_notes' => 'Sesi awal. Fokus perbaikan mobilitas, teknik squat, dan defisit kalori harian 300 kkal.',
            ]);

            MemberAssessment::create([
                'member_id' => $m1->id,
                'trainer_id' => $t1->id,
                'user_id' => $u?->id,
                'assessment_date' => Carbon::now()->subWeeks(2),
                'weight' => 76.2,
                'height' => 174.0,
                'bmi' => 25.2,
                'bmi_category' => 'Overweight',
                'body_fat_percentage' => 22.8,
                'muscle_mass' => 48.8,
                'body_water_percentage' => 53.5,
                'visceral_fat' => 7,
                'bmr' => 1690,
                'metabolic_age' => 27,
                'chest_circumference' => 99.5,
                'waist_circumference' => 85.0,
                'hips_circumference' => 97.0,
                'arm_circumference' => 33.0,
                'thigh_circumference' => 57.0,
                'calf_circumference' => 37.0,
                'fitness_goal' => 'Fat Loss & Slimming',
                'trainer_notes' => 'Progres sangat bagus! Lemak berkurang 1.7%, lingkar pinggang menyusut 3 cm.',
            ]);

            MemberAssessment::create([
                'member_id' => $m1->id,
                'trainer_id' => $t1->id,
                'user_id' => $u?->id,
                'assessment_date' => Carbon::now()->subDays(1),
                'weight' => 74.0,
                'height' => 174.0,
                'bmi' => 24.4,
                'bmi_category' => 'Normal',
                'body_fat_percentage' => 20.5,
                'muscle_mass' => 49.5,
                'body_water_percentage' => 55.0,
                'visceral_fat' => 6,
                'bmr' => 1710,
                'metabolic_age' => 25,
                'chest_circumference' => 100.0,
                'waist_circumference' => 82.0,
                'hips_circumference' => 96.0,
                'arm_circumference' => 33.5,
                'thigh_circumference' => 56.5,
                'calf_circumference' => 36.5,
                'fitness_goal' => 'Fat Loss & Slimming',
                'trainer_notes' => 'Target tercapai: Masuk kategori BMI Normal (Ideal)! Massa otot naik 1.5 kg dan total turun 4.5 kg.',
            ]);
        }

        if ($m2 && $t2) {
            MemberAssessment::create([
                'member_id' => $m2->id,
                'trainer_id' => $t2->id,
                'user_id' => $u?->id,
                'assessment_date' => Carbon::now()->subWeeks(1),
                'weight' => 54.5,
                'height' => 163.0,
                'bmi' => 20.5,
                'bmi_category' => 'Normal',
                'body_fat_percentage' => 21.0,
                'muscle_mass' => 37.5,
                'body_water_percentage' => 57.0,
                'visceral_fat' => 3,
                'bmr' => 1350,
                'metabolic_age' => 22,
                'chest_circumference' => 86.0,
                'waist_circumference' => 66.0,
                'hips_circumference' => 92.0,
                'arm_circumference' => 25.0,
                'thigh_circumference' => 51.0,
                'calf_circumference' => 33.0,
                'fitness_goal' => 'Body Recomposition',
                'trainer_notes' => 'Fokus pembentukan lower body dan core stability. Rekomendasi asupan protein 90g per hari.',
            ]);
        }
    }
}

