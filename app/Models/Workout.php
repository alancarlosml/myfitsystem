<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Workout extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'establishment_id',
        'user_id',
        'student_id',
        'exercise_id',
        'order',
        'sets',
        'repetitions',
        'rest_time',
        'notes',
        'active',
    ];

    /**
     * Get the establishment that owns the workout.
     */
    public function establishment()
    {
        return $this->belongsTo(Establishment::class);
    }

    /**
     * Get the user that owns the workout.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the student that owns the workout.
     */
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * Get the exercise that owns the workout.
     */
    public function exercise()
    {
        return $this->belongsTo(Exercise::class);
    }
}
