<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Subcontractor extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'unique_id', 'name', 'specialization', 'id_number', 'phone', 'notes'
    ];

    // علاقة لجلب كل عقود هذا المورد
    public function contracts()
    {
        return $this->hasMany(SubcontractorContract::class);
    }

    // علاقة لجلب كل المصروفات (الدفعات) التي تمت لهذا المورد
    public function SupplierPayment()
    {
        return $this->morphMany(Expense::class, 'payable');
    }

    // دالة تلقائية لإنشاء رقم فريد عند إنشاء مورد جديد
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->unique_id)) {
                $model->unique_id = 'SUB-' . str_pad(static::count() + 1, 5, '0', STR_PAD_LEFT);
            }
        });
    }
}
