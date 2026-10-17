<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fitness_classes', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('trainer_id')->nullable()->constrained()->nullOnDelete();
            $table->string('category')->default('General');
            $table->string('schedule_day')->default('Senin'); // 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'
            $table->time('start_time');
            $table->time('end_time');
            $table->string('room')->default('Studio Utama');
            $table->integer('capacity')->default(20);
            $table->decimal('price_per_session', 12, 2)->default(50000);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fitness_classes');
    }
};
