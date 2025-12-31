<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsToMany; // استيراد النوع

class Investor extends Model
{
    use HasFactory;

    /**
     * الحقول المسموح بتعبئتها
     * لقد أضفت الحقول من الكود الذي أرسلته وقمت بتنظيفها
     */
    protected $fillable = [
        'name',
        'id_number',
        'phone',
        'jobs',
        'address',
        'notes',
        'unique_id',
    ];

    /**
     * إنشاء رقم تعريفي فريد تلقائياً
     */
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->unique_id)) {
                // INV-1, INV-2, etc.
                $model->unique_id = 'INV-' . (self::query()->max('id') + 1);
            }
        });
    }

    /**
     * العلاقة الصحيحة: المشاريع التي يستثمر فيها المستثمر
     * هذه هي العلاقة التي تمثل "الاستثمارات"
     * تأكد من أن اسم الجدول الوسيط هو 'investor_project' كما اتفقنا
     */
    public function projects(): BelongsToMany
    {
        return $this->belongsToMany(Project::class, 'investor_project')
            ->withPivot('investment_percentage', 'invested_amount', 'currency',  'exchange_rate',
                    'invested_amount_ils', 'notes')
            ->withTimestamps();
    }

    /**
     * علاقة الدفعات (ممتازة)
     */
    public function payments()
    {
        return $this->morphMany(Payment::class, 'payable');
    }

    /**
     * علاقة العقود (ممتازة)
     */
    public function contracts()
    {
        return $this->morphMany(Contract::class, 'contractable');
    }

    // --- Accessors لحساب القيم تلقائياً ---

    public function getTotalInvestedAttribute()
    {
        // ملاحظة: هذا يفترض أن كل المبالغ في pivot table بنفس العملة أو تم تحويلها
        return $this->projects->sum('pivot.invested_amount');
    }

    public function getTotalPaidAttribute()
    {
        // إجمالي المبالغ التي صرفت للمستثمر
        return $this->payments()->where('type', 'out')->sum('amount_ils');
    }

    public function getRemainingInvestmentAttribute()
    {
        // المتبقي = إجمالي الاستثمار - إجمالي ما تم صرفه له
        return $this->total_invested - $this->total_paid;
    }
}
