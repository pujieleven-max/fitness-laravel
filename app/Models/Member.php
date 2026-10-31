<?php

namespace App\Models;

use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Member extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'member_code',
        'name',
        'phone',
        'email',
        'gender',
        'address',
        'membership_type',
        'membership_start',
        'membership_expires_at',
        'status',
    ];

    protected $casts = [
        'membership_start' => 'date',
        'membership_expires_at' => 'date',
    ];

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    public function assessments(): HasMany
    {
        return $this->hasMany(MemberAssessment::class)->orderBy('assessment_date', 'desc');
    }

    public function latestAssessment()
    {
        return $this->hasOne(MemberAssessment::class)->latestOfMany('assessment_date');
    }

    public function isMembershipActive(): bool
    {
        if ($this->membership_type === 'none') {
            return false;
        }

        return $this->membership_expires_at && $this->membership_expires_at->isFuture();
    }
}
