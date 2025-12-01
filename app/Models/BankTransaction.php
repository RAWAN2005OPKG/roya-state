<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BankTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'bank_account_id', 'type', 'amount', 'currency', 'date',
        'client_name', 'client_phone', 'payer_id_number', 'project_name',
        'source', 'transfer_details', 'transfer_number', 'payer_bank_name',
        'payer_bank_number', 'beneficiary_bank_name', 'beneficiary_bank_number',
        'details', 'notes',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    public function bankAccount()
    {
        return $this->belongsTo(BankAccount::class);
    }
}
