<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PerformanceLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'exercise_id',
        'sets',
        'repetitions',
        'weight',
        'effort_percentage',
        'notes',
        // Adicionar mais campos conforme necessário
    ];

    // Relacionamento com a tabela Student
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    // Relacionamento com a tabela Exercise
    public function exercise()
    {
        return $this->belongsTo(Exercise::class);
    }
}
