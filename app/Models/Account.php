<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    use HasFactory;

    protected $fillable = [
        'description',
        'amount',
        'type',
        'due_date',
        'paid',
        // Adicionar mais campos conforme necessário
    ];
}
