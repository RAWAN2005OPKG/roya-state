<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class BankAccount extends Model
{
    protected $fillable = [
        'bank_id', 'account_name', 'account_number', 'iban',
        'currency', 'current_balance', 'is_active'
    ];


    public function bank()
    {
        return $this->belongsTo(Bank::class);
    }


    public function transactions()
    {
        return $this->hasMany(BankTransaction::class);
    }
}
