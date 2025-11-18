<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class StudentGoal extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'establishment_id',
        'goal_type',
        'goal_name',
        'target_value',
        'current_value',
        'unit',
        'start_date',
        'end_date',
        'active',
        'achieved',
        'achieved_at',
        'notes',
    ];

    protected $casts = [
        'target_value' => 'decimal:2',
        'current_value' => 'decimal:2',
        'start_date' => 'date',
        'end_date' => 'date',
        'active' => 'boolean',
        'achieved' => 'boolean',
        'achieved_at' => 'datetime',
    ];

    /**
     * Get the student that owns the goal.
     */
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * Get the establishment associated with the goal.
     */
    public function establishment()
    {
        return $this->belongsTo(Establishment::class);
    }

    /**
     * Calculate progress percentage
     */
    public function getProgressPercentageAttribute()
    {
        if ($this->target_value <= 0) {
            return 0;
        }
        
        $progress = ($this->current_value / $this->target_value) * 100;
        return min(100, max(0, $progress));
    }

    /**
     * Check if goal is overdue
     */
    public function isOverdue()
    {
        if (!$this->end_date) {
            return false;
        }
        
        return $this->end_date < now() && !$this->achieved;
    }

    /**
     * Check if goal is within deadline (expiring soon)
     */
    public function isExpiringSoon($days = 7)
    {
        if (!$this->end_date || $this->achieved) {
            return false;
        }
        
        return $this->end_date <= now()->addDays($days) && $this->end_date > now();
    }

    /**
     * Update current value and check if achieved
     */
    public function updateProgress($newValue)
    {
        $this->current_value = $newValue;
        
        // Check if goal is achieved
        if ($this->current_value >= $this->target_value && !$this->achieved) {
            $this->achieved = true;
            $this->achieved_at = now();
        }
        
        $this->save();
        
        return $this;
    }

    /**
     * Scope for active goals
     */
    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    /**
     * Scope for achieved goals
     */
    public function scopeAchieved($query)
    {
        return $query->where('achieved', true);
    }

    /**
     * Scope for pending goals
     */
    public function scopePending($query)
    {
        return $query->where('achieved', false);
    }

    /**
     * Scope by goal type
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('goal_type', $type);
    }
}
