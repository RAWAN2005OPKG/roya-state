<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Customer extends Model
{
    use HasFactory , SoftDeletes;

    protected $fillable = [
        'due_date',
        'name',
        'phone',
        'project',
        'unit',
        'agreement_amount',
        'payment_method',
        'currency',
        'paid_to',
        'paid_to_other',
        'bank_name',
        'other_bank_name',
        'other_bank_branch',
        'check_number',
        'check_bank',
        'check_due_date',
        'check_receipt_date',
        'contract_file',
    ];

    protected $casts = [
        'due_date' => 'date',
        'agreement_amount' => 'decimal:2',
        'check_due_date' => 'date',
        'check_receipt_date' => 'date',
    ];
}
