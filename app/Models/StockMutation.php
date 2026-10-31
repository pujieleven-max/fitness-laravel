<?php

namespace App\Models;

use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockMutation extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'supplement_id',
        'user_id',
        'type', // 'in', 'out', 'adjustment'
        'quantity',
        'stock_before',
        'stock_after',
        'reference_type', // 'pos_sale', 'restock', 'damage', 'expired', 'manual_audit'
        'reference_id',
        'cost_per_unit',
        'notes',
    ];

    protected $casts = [
        'cost_per_unit' => 'decimal:2',
    ];

    public function supplement(): BelongsTo
    {
        return $this->belongsTo(Supplement::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
