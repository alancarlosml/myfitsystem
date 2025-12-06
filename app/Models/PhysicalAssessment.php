<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PhysicalAssessment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'establishment_id',
        'student_id',
        'user_id', // Professor who conducted the assessment
        'assessment_date',
        'weight',
        'height',
        'body_fat_percentage',
        'muscle_mass_percentage',
        'bmi',
        'body_age',
        'metabolic_rate',
        'chest_measurement',
        'waist_measurement',
        'hip_measurement',
        'arm_measurement',
        'thigh_measurement',
        'shoulder_measurement',
        'forearm_measurement',
        'leg_measurement',
        'resting_heart_rate',
        'max_heart_rate',
        'post_exercise_heart_rate',
        'blood_pressure_systolic',
        'blood_pressure_diastolic',
        'postural_photos',
        'observations',
        'goals',
        'recommendations',
    ];

    protected $dates = [
        'assessment_date',
        'deleted_at',
    ];

    protected $casts = [
        'assessment_date' => 'date',
        'weight' => 'decimal:2',
        'height' => 'decimal:2',
        'body_fat_percentage' => 'decimal:2',
        'muscle_mass_percentage' => 'decimal:2',
        'bmi' => 'decimal:2',
        'body_age' => 'integer',
        'metabolic_rate' => 'integer',
        'postural_photos' => 'array',
    ];

    // Relacionamento com a tabela Student
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    // Relacionamento com a tabela Establishment
    public function establishment()
    {
        return $this->belongsTo(Establishment::class);
    }

    // Relacionamento com a tabela User (Professor)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Helper methods para cálculos
    public function getBmiAttribute()
    {
        if ($this->height && $this->weight) {
            $heightInMeters = $this->height / 100;
            return round($this->weight / ($heightInMeters * $heightInMeters), 1);
        }
        return null;
    }

    public function getBmiCategoryAttribute()
    {
        if ($this->bmi) {
            if ($this->bmi < 18.5) return 'Abaixo do peso';
            if ($this->bmi < 25) return 'Peso normal';
            if ($this->bmi < 30) return 'Sobrepeso';
            if ($this->bmi < 35) return 'Obesidade grau I';
            if ($this->bmi < 40) return 'Obesidade grau II';
            return 'Obesidade grau III';
        }
        return null;
    }
}
