<?php

namespace App\Models;

use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MemberAssessment extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'member_id',
        'trainer_id',
        'user_id',
        'assessment_date',
        'weight',
        'height',
        'bmi',
        'bmi_category',
        'body_fat_percentage',
        'muscle_mass',
        'body_water_percentage',
        'visceral_fat',
        'bone_mass',
        'bmr',
        'metabolic_age',
        'chest_circumference',
        'waist_circumference',
        'hips_circumference',
        'arm_circumference',
        'thigh_circumference',
        'calf_circumference',
        'fitness_goal',
        'trainer_notes',
        'photo_path',
    ];

    protected $casts = [
        'assessment_date' => 'date',
        'weight' => 'decimal:2',
        'height' => 'decimal:2',
        'bmi' => 'decimal:2',
        'body_fat_percentage' => 'decimal:2',
        'muscle_mass' => 'decimal:2',
        'body_water_percentage' => 'decimal:2',
        'bone_mass' => 'decimal:2',
        'chest_circumference' => 'decimal:2',
        'waist_circumference' => 'decimal:2',
        'hips_circumference' => 'decimal:2',
        'arm_circumference' => 'decimal:2',
        'thigh_circumference' => 'decimal:2',
        'calf_circumference' => 'decimal:2',
        'visceral_fat' => 'integer',
        'bmr' => 'integer',
        'metabolic_age' => 'integer',
    ];

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }

    public function trainer(): BelongsTo
    {
        return $this->belongsTo(Trainer::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get module name for activity audit log.
     */
    public function getModuleForActivity(): string
    {
        return 'Asesmen Kebugaran';
    }

    /**
     * Identifiable label for audit trail.
     */
    public function getIdentifierForActivity(): string
    {
        $memberName = $this->member?->name ?? 'Member #' . $this->member_id;
        $date = $this->assessment_date ? $this->assessment_date->format('d/m/Y') : '-';
        return "{$memberName} ({$date}) - {$this->weight} kg";
    }

    /**
     * Calculate BMI and determine category.
     */
    public static function calculateBmiMetrics(float $weight, ?float $height): array
    {
        if (! $height || $height <= 0) {
            return ['bmi' => null, 'category' => null];
        }

        $heightInMeters = $height / 100;
        $bmi = round($weight / ($heightInMeters * $heightInMeters), 1);

        $category = match (true) {
            $bmi < 18.5 => 'Underweight',
            $bmi <= 24.9 => 'Normal',
            $bmi <= 29.9 => 'Overweight',
            default => 'Obese',
        };

        return [
            'bmi' => $bmi,
            'category' => $category,
        ];
    }

    /**
     * Get BMI Badge styling and Indonesian label.
     */
    public function getBmiBadgeAttribute(): array
    {
        return match ($this->bmi_category) {
            'Underweight' => [
                'bg' => 'bg-cyan-500/10 text-cyan-400 border-cyan-500/20',
                'label' => 'Underweight (Kurus)',
                'icon' => 'fa-arrow-down',
            ],
            'Normal' => [
                'bg' => 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20',
                'label' => 'Normal (Ideal)',
                'icon' => 'fa-check-circle',
            ],
            'Overweight' => [
                'bg' => 'bg-amber-500/10 text-amber-400 border-amber-500/20',
                'label' => 'Overweight (Kelebihan)',
                'icon' => 'fa-triangle-exclamation',
            ],
            'Obese' => [
                'bg' => 'bg-rose-500/10 text-rose-400 border-rose-500/20',
                'label' => 'Obese (Obesitas)',
                'icon' => 'fa-circle-exclamation',
            ],
            default => [
                'bg' => 'bg-slate-500/10 text-slate-400 border-slate-500/20',
                'label' => 'Belum Dihitung',
                'icon' => 'fa-minus',
            ]
        };
    }

    /**
     * Retrieve immediately preceding assessment of the same member.
     */
    public function getPreviousAssessment(): ?self
    {
        return static::where('member_id', $this->member_id)
            ->where(function ($query) {
                $query->where('assessment_date', '<', $this->assessment_date)
                      ->orWhere(function ($q) {
                          $q->where('assessment_date', '=', $this->assessment_date)
                            ->where('id', '<', $this->id);
                      });
            })
            ->orderBy('assessment_date', 'desc')
            ->orderBy('id', 'desc')
            ->first();
    }
}

