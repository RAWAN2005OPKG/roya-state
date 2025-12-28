<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Contract extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
    'contractable_id',
    'contractable_type',
    'contract_id',
    'project_id',
    'signing_date',
    'investment_amount',
    'currency',
    'status',
    'terms',
    'attachment',
    'details', // هذا هو حقل الـ JSON المهم
];


    protected $casts = [
        'signing_date' => 'date',
        'investment_amount' => 'decimal:2',
    ];

    public function contractable()
    {
        return $this->morphTo();
    }


    public function project()
    {
        return $this->belongsTo(Project::class);
    }
    public function payments()
{
    return $this->hasMany(Payment::class);
}
public function getTotalPaidAttribute()
{
    return $this->payments()->sum('amount');
}


public function getRemainingAmountAttribute()
{
    return $this->investment_amount - $this->total_paid;
}
}
