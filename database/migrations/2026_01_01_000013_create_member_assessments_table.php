<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('member_assessments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('member_id')->constrained('members')->cascadeOnDelete();
            $table->foreignId('trainer_id')->nullable()->constrained('trainers')->nullOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->date('assessment_date');

            // Komposisi Tubuh & Berat
            $table->decimal('weight', 6, 2); // kg
            $table->decimal('height', 6, 2)->nullable(); // cm
            $table->decimal('bmi', 5, 2)->nullable();
            $table->string('bmi_category', 50)->nullable(); // Underweight, Normal, Overweight, Obese
            $table->decimal('body_fat_percentage', 5, 2)->nullable(); // %
            $table->decimal('muscle_mass', 6, 2)->nullable(); // kg
            $table->decimal('body_water_percentage', 5, 2)->nullable(); // %
            $table->integer('visceral_fat')->nullable(); // Level 1-59
            $table->decimal('bone_mass', 5, 2)->nullable(); // kg
            $table->integer('bmr')->nullable(); // kkal
            $table->integer('metabolic_age')->nullable(); // tahun

            // Pengukuran Lingkar Tubuh (Body Circumference in cm)
            $table->decimal('chest_circumference', 6, 2)->nullable(); // Lingkar Dada
            $table->decimal('waist_circumference', 6, 2)->nullable(); // Lingkar Pinggang
            $table->decimal('hips_circumference', 6, 2)->nullable();  // Lingkar Pinggul
            $table->decimal('arm_circumference', 6, 2)->nullable();   // Lingkar Lengan/Biceps
            $table->decimal('thigh_circumference', 6, 2)->nullable(); // Lingkar Paha
            $table->decimal('calf_circumference', 6, 2)->nullable();  // Lingkar Betis

            // Evaluasi & Rekomendasi
            $table->string('fitness_goal', 100)->nullable(); // Fat Loss, Muscle Gain, Endurance, Stamina, dsb
            $table->text('trainer_notes')->nullable(); // Evaluasi & anjuran program latihan/diet
            $table->string('photo_path')->nullable(); // Foto progres tubuh (opsional)

            $table->timestamps();

            // Indexes
            $table->index(['member_id', 'assessment_date']);
            $table->index(['trainer_id', 'assessment_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('member_assessments');
    }
};

