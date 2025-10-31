<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use HasFactory, SoftDeletes;


    protected $fillable = [
        'name',
        'phone',
        'email',
        'address',
        'agreement_amount',
        'currency',
    ];


    protected $casts = [
        'agreement_amount' => 'decimal:2', //  لضمان التعامل معه كرقم عشري
    ];


    public function contracts()
    {
        return $this->morphMany(Contract::class, 'contractable');
    }
}
