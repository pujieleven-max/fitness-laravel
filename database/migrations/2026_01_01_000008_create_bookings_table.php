<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->string('booking_code')->unique();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('member_id')->nullable()->constrained()->nullOnDelete();
            $table->string('customer_name');
            $table->string('customer_phone')->nullable();
            $table->string('booking_type')->default('personal_trainer'); // 'personal_trainer', 'fitness_class', 'gym_slot'
            $table->foreignId('trainer_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('fitness_class_id')->nullable()->constrained()->nullOnDelete();
            $table->date('booking_date');
            $table->time('start_time');
            $table->time('end_time');
            $table->decimal('price', 12, 2)->default(0);
            $table->string('status')->default('confirmed'); // 'pending', 'confirmed', 'completed', 'cancelled'
            $table->string('payment_status')->default('unpaid'); // 'unpaid', 'paid'
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
