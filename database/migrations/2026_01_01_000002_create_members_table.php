<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('members', function (Blueprint $table) {
            $table->id();
            $table->string('member_code')->unique();
            $table->string('name');
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->enum('gender', ['male', 'female'])->default('male');
            $table->text('address')->nullable();
            $table->string('membership_type')->default('none'); // 'none', 'daily', 'monthly_bronze', 'monthly_silver', 'annual_gold'
            $table->date('membership_start')->nullable();
            $table->date('membership_expires_at')->nullable();
            $table->string('status')->default('active'); // 'active', 'expired', 'inactive'
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('members');
    }
};
