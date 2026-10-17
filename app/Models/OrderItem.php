<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'item_type', // 'supplement', 'gym_package', 'booking'
        'supplement_id',
        'gym_package_id',
        'booking_id',
        'name',
        'unit_price',
        'cost_price',
        'quantity',
        'subtotal',
    ];

    protected $casts = [
        'unit_price' => 'decimal:2',
        'cost_price' => 'decimal:2',
        'quantity' => 'integer',
        'subtotal' => 'decimal:2',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function supplement(): BelongsTo
    {
        return $this->belongsTo(Supplement::class);
    }

    public function gymPackage(): BelongsTo
    {
        return $this->belongsTo(GymPackage::class);
    }

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }
}
