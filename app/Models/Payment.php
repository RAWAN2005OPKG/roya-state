<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
       'contract_id', 'payable_id',
        'payable_type',
        'type',
        'payment_date',
        'amount',
        'currency',
        'exchange_rate',
        'amount_ils',
        'method',
        'check_number',
        'due_date',
        'check_owner',
        'check_type',
        'sender_bank_account_id',
        'receiver_bank_account_id',
        'transaction_reference',
        'delivered_by',
        'received_by',
        'notes',
    ];

    // خاصية لتحويل أنواع البيانات تلقائياً
    protected $casts = [
        'payment_date' => 'date',
        'due_date' => 'date', // تأكد من أن اسم الحقل في قاعدة البيانات هو due_date
        'amount' => 'float',
        'exchange_rate' => 'float',
        'amount_ils' => 'float',
    ];

    /**
     * علاقة Polymorphic: الكيان الذي تم الدفع له/منه (عميل/مستثمر/مقاول)
     */
    public function payable()
    {
        return $this->morphTo();
    }
  public function contract()
    {
        return $this->belongsTo(Contract::class);
    }
    /**
     * علاقة لجلب بيانات حساب البنك المرسل (إذا كان لديك مودل BankAccount)
     */
    public function senderAccount()
    {
        // افترض أن لديك مودل BankAccount
        return $this->belongsTo(BankAccount::class, 'sender_bank_account_id');
    }

    /**
     * علاقة لجلب بيانات حساب البنك المستقبل (إذا كان لديك مودل BankAccount)
     */
    public function receiverAccount()
    {
        // افترض أن لديك مودل BankAccount
        return $this->belongsTo(BankAccount::class, 'receiver_bank_account_id');
    }
    public function contracts()
{
    return $this->morphMany(Contract::class, 'contractable');
}

}
