<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_no',
        'user_id',
        'member_id',
        'customer_name',
        'customer_type',
        'subtotal',
        'discount',
        'tax',
        'total_amount',
        'payment_method',
        'cash_received',
        'cash_change',
        'status',
        'notes',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'discount' => 'decimal:2',
        'tax' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'cash_received' => 'decimal:2',
        'cash_change' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function calculateTotalProfit(): float
    {
        return (float) $this->items->sum(function ($item) {
            return ($item->unit_price - $item->cost_price) * $item->quantity;
        });
    }
}
