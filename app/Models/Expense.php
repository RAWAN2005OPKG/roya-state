<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Expense extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'date',
        'payee',
        'phone',
        'job',
        'id_number',
        'project_id',
        'amount',
        'currency',
        'payment_method',
        'payment_source',
        'cash_receiver',
        'cash_receiver_other',
        'receiver_job',
        'sender_bank',
        'other_sender_bank',
        'sender_branch',
        'receiver_bank',
        'other_receiver_bank',
        'receiver_branch',
        'transaction_id',
        'check_number',
        'check_owner',
        'check_holder',
        'check_due_date',
        'check_receive_date',
        'notes',
    ];

    protected $casts = [
        'date' => 'date',
        'amount' => 'decimal:2',
        'check_due_date' => 'date',
        'check_receive_date' => 'date',
    ];
}
