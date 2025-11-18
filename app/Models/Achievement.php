<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Achievement extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'icon',
        'badge_color',
        'type',
        'required_value',
        'active',
    ];

    protected $casts = [
        'required_value' => 'integer',
        'active' => 'boolean',
    ];

    /**
     * Get students that have earned this achievement
     */
    public function students()
    {
        return $this->belongsToMany(Student::class, 'achievement_student')
            ->withPivot('earned_at')
            ->withTimestamps();
    }

    /**
     * Check if a student has earned this achievement
     */
    public function isEarnedBy($studentId)
    {
        return $this->students()->where('student_id', $studentId)->exists();
    }

    /**
     * Scope for active achievements
     */
    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    /**
     * Scope by type
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('type', $type);
    }
}
