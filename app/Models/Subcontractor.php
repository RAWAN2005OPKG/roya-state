<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Subcontractor extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * الحقول التي يمكن تعبئتها بشكل جماعي.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'service_type',
        'phone',
        'contact_person',
    ];

    /**
     * المقاول يمكن أن يكون لديه العديد من العقود.
     * هذه هي العلاقة التي تربطه بالعقود باستخدام تقنية "العلاقات المتعددة الأشكال".
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function contracts()
    {
        return $this->morphMany(Contract::class, 'contractable');
    }
}
