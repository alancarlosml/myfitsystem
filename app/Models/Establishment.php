<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Traits\HasRoles;

class Establishment extends Model
{
    use HasFactory, SoftDeletes, HasRoles;


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
                    ->withPivot('role_id', 'active')
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
        return $this->belongsToMany(Category::class, 'category_establishment');
    }

    public function modalities()
    {
        return $this->belongsToMany(Modality::class, 'modality_establishment');
    }

    public function roles()
    {
        return $this->belongsToMany(User::class, 'role_user')
                    ->withPivot('role_id')
                    ->withTimestamps();
    }
}
