<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Khaled extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    protected $casts = [
        'voucher_date' => 'date',
        'check_due_date' => 'date',
    ];

    /**
     * علاقة السند بالحساب البنكي (المرسل).
     */
    public function fromBankAccount()
    {
        return $this->belongsTo(BankAccount::class, 'from_bank_account_id');
    }

    /**
     * علاقة السند بالحساب البنكي (المستقبل).
     */
    public function toBankAccount()
    {
        return $this->belongsTo(BankAccount::class, 'to_bank_account_id');
    }

    /**
     * علاقة السند بالمشروع.
     */
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * علاقة السند بالعميل.
     */
    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * علاقة السند بالمستثمر.
     */
    public function investor()
    {
        return $this->belongsTo(Investor::class);
    }


}
