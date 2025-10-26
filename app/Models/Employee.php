<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Employee extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'position',
        'email',
        'phone',
        'salary',
        'currency',
        'iban',
        'wallet_name',
        'bank_name',
        'bank_branch',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array<string, string>
     */
    protected $casts = [
        // هذا يضمن أن حقل الراتب سيعامل دائمًا كرقم عشري
        'salary' => 'decimal:2',
    ];


      public function salaryPayments()
      {
          return $this->hasMany(SalaryPayment::class);
     }

}
