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
        'establishment_id',
    ];

    /**
     * Get the establishment that owns this category (if created by admin).
     */
    public function establishment()
    {
        return $this->belongsTo(Establishment::class);
    }

    /**
     * Get the establishments that have this category selected.
     */
    public function establishments()
    {
        return $this->belongsToMany(Establishment::class, 'category_establishment');
    }

    /**
     * Check if category is owned by MyFitSystem (super admin).
     */
    public function isSystemOwned()
    {
        return is_null($this->establishment_id);
    }

    /**
     * Get owner name (MyFitSystem or establishment name).
     */
    public function getOwnerNameAttribute()
    {
        if ($this->isSystemOwned()) {
            return 'MyFitSystem';
        }
        
        return $this->establishment ? $this->establishment->name : 'N/A';
    }

}
