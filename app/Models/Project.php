<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; // ✅ تأكد من وجود هذا السطر

class Project extends Model
{
    use HasFactory, SoftDeletes; // ✅ تأكد من وجود SoftDeletes هنا

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = []; // استخدام guarded أسهل إذا كانت كل الحقول قابلة للتعبئة

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'start_date' => 'date',
        'check_due_date' => 'date',
        'check_receive_date' => 'date',
        'apartment_price' => 'decimal:2',
        'down_payment' => 'decimal:2',
        'land_cost' => 'decimal:2',
        'excavation_cost' => 'decimal:2',
        'engineers_cost' => 'decimal:2',
        'licensing_cost' => 'decimal:2',
        'materials_cost' => 'decimal:2',
        'finishing_cost' => 'decimal:2',
        'total_budget' => 'decimal:2',
    ];
}
