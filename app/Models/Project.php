<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'start_date',
        'owner_name',
        'owner_phone',
        'owner_id',
        'project_title',
        'currency',
        'apartment_price',
        'down_payment',
        'project_status',
        'payment_method',
        'cash_receiver',
        'cash_receiver_other',
        'cash_receiver_job',
        'sender_bank',
        'sender_bank_other',
        'sender_branch',
        'receiver_bank',
        'receiver_bank_other',
        'receiver_branch',
        'transaction_id',
        'check_number',
        'check_owner',
        'check_holder',
        'check_due_date',
        'check_receive_date',
        'project_media',
        'land_cost',
        'excavation_cost',
        'engineers_cost',
        'licensing_cost',
        'materials_cost',
        'finishing_cost',
        'total_budget',
    ];

    protected $casts = [
        'start_date' => 'date',
        'check_due_date' => 'date',
        'check_receive_date' => 'date',
        'apartment_price' => 'decimal:2',
        'down_payment' => 'decimal:2',
        'land_cost' => 'decimal:2',
        'excavation_cost' => 'decimal:2',
        'engineers_cost' => 'decimal:2',
        'licensing_cost' => 'decimal:2',
        'materials_cost' => 'decimal:2',
        'finishing_cost' => 'decimal:2',
        'total_budget' => 'decimal:2',
    ];
}
