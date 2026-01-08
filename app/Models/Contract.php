<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Contract extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'contractable_id',
        'contractable_type',
        'project_id',
        'project_unit_id', // مهم جداً لربط العقد بالوحدة
        'contract_date',
        'contract_details',
        'investment_amount',
        'currency',
        'exchange_rate',
        'investment_amount_ils',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'contract_date' => 'date',
        'investment_amount' => 'decimal:2',
        'investment_amount_ils' => 'decimal:2',
    ];

    /**
     * علاقة Polymorphic: صاحب العقد (عميل، مستثمر، الخ)
     */
    public function contractable()
    {
        return $this->morphTo();
    }

    /**
     * علاقة: العقد يتبع لمشروع واحد
     */
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * علاقة: العقد مرتبط بوحدة سكنية واحدة
     */
    public function projectUnit()
    {
        return $this->belongsTo(ProjectUnit::class);
    }

    /**
     * علاقة: العقد الواحد له عدة دفعات
     */
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    // --- دوال الحسابات المالية التلقائية (Accessors) ---

    /**
     * [Accessor] حساب إجمالي المبالغ المدفوعة لهذا العقد بالشيكل
     */
    public function getTotalPaidAttribute(): float
    {
        // يجمع فقط الدفعات المرتبطة بهذا العقد
        return (float) $this->payments()->where('type', 'in')->sum('amount_ils');
    }

    /**
     * [Accessor] حساب المبلغ المتبقي من هذا العقد بالشيكل
     */
    public function getRemainingBalanceAttribute(): float
    {
        // قيمة العقد بالشيكل - إجمالي المدفوع على هذا العقد
        return (float) $this->investment_amount_ils - $this->total_paid;
    }
}
