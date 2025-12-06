<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Modality extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'establishment_id',
        'name',
        'description',
        'active',
    ];

    public function establishment()
    {
        return $this->belongsTo(Establishment::class);
    }

    /**
     * Get the establishments that have this modality selected.
     */
    public function establishments()
    {
        return $this->belongsToMany(Establishment::class, 'modality_establishment');
    }

    /**
     * Check if modality is owned by MyFitSystem (super admin).
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

    // Relacionamento com a tabela ClassSchedule
    public function classSchedules()
    {
        return $this->hasMany(ClassSchedule::class);
    }
}
