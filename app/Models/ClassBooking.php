<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassBooking extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'class_schedule_id',
        'booking_date',
        'checked_in',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function classSchedule()
    {
        return $this->belongsTo(ClassSchedule::class);
    }

}
