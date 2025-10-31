<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'contract_id',
        'amount',
        'currency',
        'payment_date',
        'payment_method',
        'description',
    ];

    protected $casts = [
        'payment_date' => 'date',
        'amount' => 'decimal:2',
    ];

    /**
     * علاقة لجلب العقد الذي تنتمي إليه هذه الدفعة.
     */
    public function contract()
    {
        return $this->belongsTo(Contract::class);
    }
}
