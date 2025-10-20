<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Investment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'investor_id',   // أضيفي هذا
        'project_id',    // أضيفي هذا
        'date',
        'project',
        'type',
        'amount',
        'currency',
        'share_percentage',
        'payment_method',
        'down_payment_other',
        'first_payment_date',
        'remaining_amount',
        'cash_receiver',
        'cash_receiver_job',
        'cash_receipt_date',
        'sender_bank',
        'receiver_bank',
        'transaction_reference',
        'transaction_date',
        'check_number',
        'check_owner',
        'check_bank',
        'check_due_date',
        'contract_id',
        'notes',
        'status',
    ];

    public function investor()
    {
        return $this->belongsTo(Investor::class);
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
