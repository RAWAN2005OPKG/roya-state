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
        'client_id', 'project_unit_id', 'contract_date', 'status',
        'total_amount', 'currency', 'exchange_rate', 'total_amount_ils',
        'notes', 'attachment'
    ];

    protected $casts = [
        'contract_date' => 'date',
    ];

    public function contractable()
    {
        return $this->morphTo();
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function projectUnit()
    {
        return $this->belongsTo(ProjectUnit::class);
    }


    public function payments()
    {
        return $this->hasMany(Payment::class);
    }


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
