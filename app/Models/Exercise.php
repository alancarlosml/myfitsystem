<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Exercise extends Model
{
    use HasFactory;

    protected $fillable = [
        'establishment_id',
        'category_id',
        'name',
        'description',
        'exercise_picture',
        'active',
    ];

    // Relacionamento com a tabela Establishment
    public function establishment()
    {
        return $this->belongsTo(Establishment::class);
    }

    // Relacionamento com a tabela Category
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function workouts()
    {
        return $this->hasMany(Workout::class);
    }
}
