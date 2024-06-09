<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Establishment extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'name',
        'type',
        'owner',
        'cnpj',
        'phone',
        'address',
        'social_network',
        'website',
        'active',
    ];

    /**
     * Get the contracts that belongs to the establishment.
     */
    public function contracts()
    {
        return $this->hasMany(EstablishmentContracts::class)->orderBy('end_date', 'desc');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'role_user')
                    ->withPivot('role_id')
                    ->withTimestamps();
    }

    public function students()
    {
        return $this->belongsToMany(Student::class, 'student_establishment');
    }

    public function exercises()
    {
        return $this->hasMany(Exercise::class);
    }

    public function workouts()
    {
        return $this->hasMany(Workout::class);
    }

    public function categories()
    {
        return $this->hasMany(Category::class);
    }

    public function modalities()
    {
        return $this->hasMany(Modality::class);
    }
}
