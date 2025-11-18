<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkoutLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'establishment_id',
        'workout_id',
        'exercise_id',
        'student_id',
        'date',
        'completed_sets',
        'completed_reps',
        'rest_duration',
        'exercise_duration',
        'notes',
        'logged_at',
    ];
    
    protected $casts = [
        'date' => 'date',
        'logged_at' => 'datetime',
    ];

    // Relacionamento com a tabela Establishment
    public function establishment()
    {
        return $this->belongsTo(Establishment::class);
    }

    // Relacionamento com a tabela Workout
    public function workout()
    {
        return $this->belongsTo(Workout::class);
    }
    
    // Relacionamento com a tabela Exercise
    public function exercise()
    {
        return $this->belongsTo(Exercise::class);
    }

    // Relacionamento com a tabela Student
    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
