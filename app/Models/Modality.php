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

    // Relacionamento com a tabela ClassSchedule
    public function classSchedules()
    {
        return $this->hasMany(ClassSchedule::class);
    }
}
