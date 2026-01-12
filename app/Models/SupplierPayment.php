<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SupplierPayment extends Model // <-- 1. تم تغيير اسم الكلاس
{
    use HasFactory, SoftDeletes;

    /**
     * اسم الجدول في قاعدة البيانات الذي يتعامل معه هذا المودل.
     * @var string
     */
    protected $table = 'expenses'; // <-- 2. هذا السطر يربط المودل بالجدول القديم

    protected $fillable = [
        'expense_date',
        'source_of_funds',
        'paid_by',
        'amount',
        'payable_type',
        'payable_id',
        'notes',
    ];

    protected $casts = [
        'expense_date' => 'date',
        'amount' => 'decimal:2',
    ];

    /**
     * العلاقة لجلب الجهة التي تم الدفع لها (المورد).
     */
    public function payable()
    {
        return $this->morphTo();
    }
}
