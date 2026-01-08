<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Investor extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'unique_id',
        'company',
        'id_number',
        'phone',
        'notes',
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->unique_id)) {
                $model->unique_id = 'INV-' . (self::max('id') + 1);
            }
        });
    }

    /**
     * علاقة: المستثمر يمكن أن يستثمر في عدة مشاريع
     */
    public function projects()
    {
        return $this->belongsToMany(Project::class, 'project_investor')
            ->withPivot('investment_percentage', 'invested_amount', 'invested_amount_ils', 'currency', 'exchange_rate', 'notes')
            ->withTimestamps();
    }

    /**
     * علاقة: المستثمر يمكن أن يكون له عدة عقود
     */
    public function contracts()
    {
        return $this->morphMany(Contract::class, 'contractable');
    }

    /**
     * علاقة: المستثمر يمكن أن يكون له عدة دفعات (قيود)
     */
    public function payments()
    {
        return $this->morphMany(Payment::class, 'payable');
    }

    // --- دوال الحسابات المالية التلقائية (Accessors) ---

    /**
     * [Accessor] حساب إجمالي المبالغ المستثمرة من هذا المستثمر بالشيكل
     */
    public function getTotalInvestedAttribute(): float
    {
        // يجمع كل المبالغ المستثمرة بالشيكل من الجدول الوسيط
        return (float) $this->projects()->sum('invested_amount_ils');
    }

    /**
     * [Accessor] حساب إجمالي المبالغ المدفوعة (المصروفة) لهذا المستثمر بالشيكل
     */
    public function getTotalPaidAttribute(): float
    {
        // يجمع كل الدفعات من نوع "صرف" (out)
        return (float) $this->payments()->where('type', 'out')->sum('amount_ils');
    }

    /**
     * [Accessor] حساب الرصيد المتبقي للمستثمر بالشيكل
     */
    public function getRemainingBalanceAttribute(): float
    {
        // إجمالي الاستثمار - إجمالي ما تم صرفه له
        return $this->total_invested - $this->total_paid;
    }
}
