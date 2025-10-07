<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Investment extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    protected $fillable = [
        'investor_id',
        'date',
        'project',
        'type',
        'phone',
        'id_number',
        'job',
        'currency',
        'amount',
        'share_percentage',
        'status',
        'payment_method',
        'payee',
        'payment_date',
        'bank_name',
        'other_bank_name',
        'transaction_id',
        'contract_id',
        'notes',
    ];

    protected $casts = [
        'date' => 'date',
        'payment_date' => 'date',
        'amount' => 'decimal:2',
        'share_percentage' => 'decimal:2',
    ];

    public function investor()
    {
        return $this->belongsTo(Investor::class);
    }
}
