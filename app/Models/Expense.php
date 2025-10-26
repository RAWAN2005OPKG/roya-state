<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Expense extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
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

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'date' => 'date',
        'check_due_date' => 'date',
        'check_receive_date' => 'date',
        'amount' => 'decimal:2',
    ];

     public function project()
 {
         return $this->belongsTo(Project::class);
     }
}

