<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Employee extends Model
{
    use HasFactory , SoftDeletes;

    protected $fillable = [
        'name',
        'position',
        'email',
        'phone',
        'iban',
        'salary',
        'currency',
        'wallet_name',
        'wallet_other_name',
        'bank_name',
        'other_bank_name',
        'bank_branch',
    ];

    protected $casts = [
        'salary' => 'decimal:2',
    ];
     public function salaryPayments()
    {
        return $this->hasMany(SalaryPayment::class);
    }
}

