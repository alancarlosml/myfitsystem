<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StudentContracts extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'student_id',
        'establishment_id',
        'service_name',
        'amount',
        'payment_date',
        'payment_type',
        'start_date',
        'end_date',
        'active',
    ];

    protected $dates = [
        'payment_date',
        'start_date',
        'end_date',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function establishment()
    {
        return $this->belongsTo(Establishment::class);
    }
}
