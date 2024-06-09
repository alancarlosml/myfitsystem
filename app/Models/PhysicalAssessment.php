<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PhysicalAssessment extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'assessment_date',
        'weight',
        'height',
        'body_fat_percentage',
        'muscle_mass_percentage',
        // Adicionar mais campos conforme necessário
    ];

    // Relacionamento com a tabela Student
    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
