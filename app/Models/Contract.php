<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Contract extends Model
{
    use HasFactory , SoftDeletes; 

    protected $fillable = [
        'contract_id',
        'signing_date',
        'status', 
        'client_name',
        'client_email',
        'client_phone',
        'client_alt_phone',
        'client_id_number',
        'property_type',
        'property_location',
        'investment_amount',
        'duration_months',
        'payment_method',
        'apartment_price',
        'first_payment_date',
        'down_payment_initial',
        'down_payment_other',
        'profit_percentage',
        'remaining_amount',
        'cash_receiver',
        'cash_receiver_other',
        'cash_receiver_job',
        'cash_receipt_date',
        'sender_bank',
        'sender_bank_other',
        'sender_bank_branch',
        'receiver_bank',
        'receiver_bank_other',
        'receiver_bank_branch',
        'transaction_reference',
        'transaction_date',
        'check_number',
        'check_owner',
        'check_holder',
        'check_bank',
        'check_bank_other',
        'check_bank_branch',
        'check_due_date',
        'check_receipt_date',
    ];

    protected $casts = [
        'signing_date' => 'date',
        'first_payment_date' => 'date',
        'cash_receipt_date' => 'date',
        'transaction_date' => 'date',
        'check_due_date' => 'date',
        'check_receipt_date' => 'date',
        'investment_amount' => 'decimal:2',
        'apartment_price' => 'decimal:2',
        'down_payment_initial' => 'decimal:2',
        'down_payment_other' => 'decimal:2',
        'profit_percentage' => 'decimal:2',
        'remaining_amount' => 'decimal:2',
    ];
}
