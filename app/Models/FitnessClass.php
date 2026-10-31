<?php

namespace App\Models;

use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FitnessClass extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'name',
        'trainer_id',
        'category',
        'schedule_day',
        'start_time',
        'end_time',
        'room',
        'capacity',
        'price_per_session',
        'is_active',
    ];

    protected $casts = [
        'price_per_session' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function trainer(): BelongsTo
    {
        return $this->belongsTo(Trainer::class);
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }
}
