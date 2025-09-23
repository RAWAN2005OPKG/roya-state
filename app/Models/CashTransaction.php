<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CashTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'type', 
        'amount',
        'currency',
        'project_name',
        'source',
        'beneficiary',
        'operator',
        'operator_role',
        'details',
        'notes',
        'payer_id_number',
        'client_phone',
    ];

    protected $casts = [
        'date' => 'date',
        'amount' => 'decimal:2',
    ];
}
