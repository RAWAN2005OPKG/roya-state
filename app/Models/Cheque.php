<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cheque extends Model
{
    use HasFactory;

    protected $fillable = [
        'cheque_date',
        'due_date',
        'type', // incoming, outgoing
        'cheque_number',
        'transfer_number',
        'owner_name',
        'holder_name',
        'payer_id_number',
        'client_phone',
        'beneficiary_name',
        'project_name',
        'currency',
        'amount',
        'bank_name',
        'other_bank_name',
        'bank_branch',
        'account_number',
        'operator',
        'transfer_details',
        'notes',
        'status', // in_wallet, cashed, returned
    ];

    protected $casts = [
        'cheque_date' => 'date',
        'due_date' => 'date',
        'amount' => 'decimal:2',
    ];
}
