<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('app_name')->default('FitZone Gym');
            $table->string('app_tagline')->nullable()->default('POS & Fitness Management');
            $table->string('logo_path')->nullable();
            $table->string('phone')->nullable()->default('0812-3456-7890');
            $table->string('email')->nullable()->default('info@fitzone.com');
            $table->text('address')->nullable();
            $table->text('receipt_footer')->nullable();
            $table->string('social_instagram')->nullable()->default('@fitzone_gym');
            $table->string('currency_symbol', 10)->default('Rp');
            $table->timestamps();
        });

        // Insert initial default setting
        DB::table('settings')->insert([
            'app_name' => 'FitZone Gym',
            'app_tagline' => 'POS & Fitness Management',
            'phone' => '0812-3456-7890',
            'email' => 'info@fitzone.com',
            'address' => 'Jl. Gatot Subroto No. 88, Jakarta Selatan',
            'receipt_footer' => "Terima kasih telah berlatih di tempat kami!\nTetap disiplin & raih target kebugaran Anda.",
            'social_instagram' => '@fitzone_gym',
            'currency_symbol' => 'Rp',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
