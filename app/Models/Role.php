<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Permission\Models\Role as SpatieRole;

class Role extends SpatieRole
{
    use HasFactory;

    protected $fillable = [
        'name',
        'guard_name'
    ];

    public function roleUsers()
    {
        return $this->belongsToMany(User::class, 'role_user')
                    ->withPivot('establishment_id')
                    ->withTimestamps();
    }
}
