<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Subcontractor extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'id_number',
        'phone',
        'specialization',
        'notes',
        'unique_id',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->unique_id)) {
                $model->unique_id = 'SUB-' . (self::query()->max('id') + 1);
            }
        });
    }

    /**
     * علاقة المشاريع التي يعمل بها المقاول
     */
    public function projects(): BelongsToMany
    {
        return $this->belongsToMany(Project::class, 'project_subcontractor')
            ->withPivot([
                'contract_value',
                'currency',
                'exchange_rate',
                'contract_value_ils',
                'contract_details',
                'contract_date'
            ])
            ->withTimestamps();
    }

    /**
     * علاقة الدفعات (لصرف الدفعات للمقاول)
     */
    public function payments()
    {
        return $this->morphMany(Payment::class, 'payable');
    }

    // --- Accessors لحساب القيم المالية ---

    /**
     * إجمالي قيمة عقود المقاول (بالشيكل)
     */
    public function getTotalContractsValueAttribute()
    {
        return $this->projects->sum('pivot.contract_value_ils');
    }

    /**
     * إجمالي المبالغ المدفوعة للمقاول
     */
    public function getTotalPaidAttribute()
    {
        // نفترض أن الدفعات للمقاول هي من نوع 'out' (صرف)
        return $this->payments()->where('type', 'out')->sum('amount_ils');
    }

    /**
     * الرصيد المتبقي للمقاول
     */
    public function getRemainingBalanceAttribute()
    {
        return $this->total_contracts_value - $this->total_paid;
    }
}
