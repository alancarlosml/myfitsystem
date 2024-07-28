<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable, SoftDeletes, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'cpf',
        'phone',
        'active',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_user')
                    ->withPivot('establishment_id')
                    ->withTimestamps();
    }

    public function details()
    {
        return $this->hasOne(UserDetail::class);
    }

    public function establishments()
    {
        return $this->belongsToMany(Establishment::class, 'role_user')
                    ->withPivot('role_id', 'active')
                    ->withTimestamps();
    }

    public function getEstablishmentsActive()
    {
        return $this->belongsToMany(Establishment::class, 'role_user')
                    ->join('roles', 'role_user.role_id', '=', 'roles.id')
                    ->withPivot('role_id', 'active')
                    ->wherePivot('establishments.active', 1)
                    ->withTimestamps();
    }

    public function getRoleForEstablishment($establishmentId)
    {
        $roleUser = $this->establishments()->where('establishment_id', $establishmentId)->first();
        return $roleUser ? Role::find($roleUser->pivot->role_id) : null;
    }

    public function workouts()
    {
        return $this->hasMany(Workout::class);
    }
}
