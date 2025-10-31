<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'project_name', 'project_title', 'start_date', 'end_date', 'currency',
        'apartment_price', 'down_payment', 'project_status', 'project_media', 'budget',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'apartment_price' => 'decimal:2',
        'down_payment' => 'decimal:2',
        'budget' => 'decimal:2',
    ];

    
    public function contracts()
    {
        return $this->hasMany(Contract::class);
    }

   
    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }

 
    public function getCustomersAttribute()
    {
        $this->loadMissing('contracts.contractable');
        return $this->contracts
            ->where('contractable_type', Customer::class)
            ->map->contractable
            ->unique('id');
    }

    
    public function getInvestorsAttribute()
    {
        $this->loadMissing('contracts.contractable');
        return $this->contracts
            ->where('contractable_type', Investor::class)
            ->map->contractable
            ->unique('id');
    }
    
    
    public function getTotalInvestmentsValueAttribute()
    {
        return $this->contracts()
            ->where('contractable_type', Investor::class)
            ->sum('investment_amount');
    }
}
