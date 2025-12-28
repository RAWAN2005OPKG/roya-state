<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class BankTransaction extends Model
{
    use HasFactory, SoftDeletes;    protected $fillable = [
        'bank_account_id', 'transaction_date', 'type', 'amount', 'currency',
        'client_name', 'client_phone', 'payer_id_number', 'project_name',
        'source', 'transfer_number', 'transfer_details',
        'payer_bank_name', 'beneficiary_bank_name',
        'details', 'notes'
    ];

    // تحويل حقل التاريخ تلقائيًا
    protected $casts = [
        'transaction_date' => 'date',
    ];

    public function bankAccount()
    {
        return $this->belongsTo(BankAccount::class);
    }
}
