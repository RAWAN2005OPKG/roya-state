<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Client extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'unique_id',
        'name',
        'phone',
        'id_number',
        'address',
        'notes',
    ];

    protected $appends = [
        'total_due',
        'total_paid',
        'remaining_balance'
    ];

    /**
     * علاقة الوحدات المشتراة (Many-to-Many مع ProjectUnit)
     */
    public function units()
    {
        return $this->belongsToMany(ProjectUnit::class, 'client_unit', 'client_id', 'project_unit_id')
            ->withPivot('sale_price', 'currency', 'sale_date', 'contract_details')
            ->withTimestamps();
    }

    /**
     * علاقة الدفعات (Polymorphic)
     */
    public function payments()
    {
        return $this->morphMany(Payment::class, 'payable');
    }

    // Accessors لحساب الأرصدة

    /**
     * إجمالي المبلغ المستحق على العميل (قيمة الوحدات المباعة)
     */
    public function getTotalDueAttribute()
    {
        // يجب أن يتم توحيد العملة هنا (يفضل تحويل كل شيء إلى الشيكل)
        // هنا نفترض أن sale_price في جدول client_unit هي القيمة النهائية
        return $this->units->sum(function ($unit) {
            // إذا كانت sale_price مخزنة بعملة مختلفة، يجب إضافة منطق التحويل هنا
            return $unit->pivot->sale_price;
        });
    }

    /**
     * إجمالي المبلغ المدفوع من العميل (بالشيكل)
     */
    public function getTotalPaidAttribute()
    {
        // نستخدم amount_ils المخزنة في جدول payments لضمان التوحيد
        return $this->payments()->where('type', 'in')->sum('amount_ils');
    }

    /**
     * المبلغ المتبقي على العميل (بالشيكل)
     */
    public function getRemainingBalanceAttribute()
    {
        // يجب توحيد العملة في getTotalDueAttribute قبل إجراء هذا الطرح
        // هنا نفترض أن getTotalDueAttribute يعيد قيمة بالشيكل
        return $this->total_due - $this->total_paid;
    }
}
