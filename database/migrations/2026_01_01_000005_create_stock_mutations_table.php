<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stock_mutations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('supplement_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->enum('type', ['in', 'out', 'adjustment']);
            $table->integer('quantity'); // positive amount changed
            $table->integer('stock_before');
            $table->integer('stock_after');
            $table->string('reference_type')->default('manual_audit'); // 'pos_sale', 'restock', 'damage', 'expired', 'manual_audit'
            $table->string('reference_id')->nullable();
            $table->decimal('cost_per_unit', 12, 2)->nullable();
            $table->string('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_mutations');
    }
};
