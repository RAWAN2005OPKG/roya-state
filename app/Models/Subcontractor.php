<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Facades\DB;

class Subcontractor extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'unique_id', 'name', 'specialization', 'id_number', 'phone', 'notes',
    ];

    protected static function booted()
    {
        static::creating(function ($subcontractor) {
            $lastId = self::max('id') ?? 0;
            $subcontractor->unique_id = 'SUB-' . ($lastId + 1);
        });
    }


    public function contracts()
    {
        return $this->hasMany(SubcontractorContract::class);
    }

    /**
     * علاقة مع الدفعات
     */
    public function payments()
    {
        return $this->morphMany(SupplierPayment::class, 'payable');
    }

    // ===================================================================
    // ===== هذا هو الجزء الذي كان يحتوي على الخطأ وتم تصحيحه بالكامل =====
    // ===================================================================

    /**
     * حساب إجمالي قيمة العقود بالشيكل.
     * الآن هو يجمع من الجدول الصحيح (subcontractor_contracts) وباستخدام الأعمدة الصحيحة.
     */
    protected function totalContractsValue(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->contracts()->sum(DB::raw('contract_value * exchange_rate'))
        );
    }

    /**
     * حساب إجمالي المبالغ المدفوعة لهذا المورد.
     */
    protected function totalPaid(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->payments()->sum('amount')
        );
    }

    /**
     * حساب الرصيد المتبقي للمورد.
     */
    protected function remainingBalance(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->total_contracts_value - $this->total_paid
        );
    }
}
