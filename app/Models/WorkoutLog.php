<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkoutLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'workout_id',
        'student_id',
        'date',
        // Adicionar mais campos conforme necessário
    ];

    // Relacionamento com a tabela Workout
    public function workout()
    {
        return $this->belongsTo(Workout::class);
    }

    // Relacionamento com a tabela Student
    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
