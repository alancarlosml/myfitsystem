<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassSchedule extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'modality_id',
        'establishment_id',
        'description',
        'class_date',
        'start_time',
        'end_time',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array<string>
     */
    protected $dates = [
        'class_date',
        'start_time',
        'end_time',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    /**
     * Get the modality that owns the class schedule.
     */
    public function modality()
    {
        return $this->belongsTo(Modality::class);
    }

    /**
     * Get the establishment that owns the class schedule.
     */
    public function establishment()
    {
        return $this->belongsTo(Establishment::class);
    }

    public function class_bookings()
    {
        return $this->hasMany(ClassBooking::class);
    }
}
