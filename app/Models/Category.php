<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'active',
    ];

    /**
     * Get the establishments that belong to the category.
     */
    public function establishments()
    {
        return $this->belongsToMany(Establishment::class, 'category_establishment');
    }

}
