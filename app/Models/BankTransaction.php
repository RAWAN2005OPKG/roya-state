<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BankTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'client_name',
        'client_phone',
        'payer_id_number',
        'type', 
        'amount',
        'currency',
        'project_name',
        'source',
        'transfer_details',
        'transfer_number',
        'beneficiary_name',
        'beneficiary_bank_name',
        'beneficiary_bank_number',
        'cheque_number',
        'cheque_owner_name',
        'payer_bank_name',
        'payer_bank_number',
        'operator',
        'operator_role',
        'bank_name',
        'other_bank_name',
        'details',
        'notes',
    ];

    protected $casts = [
        'date' => 'date',
        'amount' => 'decimal:2',
    ];
}
