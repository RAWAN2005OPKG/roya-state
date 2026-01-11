<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Client extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name', 'unique_id', 'id_number', 'phone', 'address', 'notes',
    ];

    /**
     * دالة Boot لإنشاء ID فريد تلقائياً.
     */
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->unique_id)) {
                $model->unique_id = 'CLT-' . time() . '-' . random_int(100, 999);
            }
        });
    }

    /**
     * علاقة: العميل يمكن أن يشتري عدة وحدات (عقود بيع).
     */
    public function contracts()
    {
        return $this->hasMany(Contract::class);
    }

    /**
     * علاقة: العميل يمكن أن يكون له عدة دفعات (قيود).
     */
    public function payments()
    {
        return $this->morphMany(Payment::class, 'payable');
    }

    // --- دوال الحسابات المالية التلقائية (Accessors) ---

    /**
     * [Accessor] حساب إجمالي قيمة العقود المستحقة على العميل بالشيكل.
     */
    public function getTotalDueIlsAttribute(): float
    {
        return (float) $this->contracts()->sum('total_amount_ils');
    }

    /**
     * [Accessor] حساب إجمالي المبالغ التي دفعها العميل بالشيكل.
     */
    public function getTotalPaidIlsAttribute(): float
    {
        return (float) $this->payments()->where('type', 'in')->sum('amount_ils');
    }

    /**
     * [Accessor] حساب الرصيد المتبقي على العميل بالشيكل.
     */
    public function getRemainingBalanceAttribute(): float
    {
        return $this->total_due_ils - $this->total_paid_ils;
    }
}
