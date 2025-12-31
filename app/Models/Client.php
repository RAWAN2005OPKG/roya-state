<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'phone',
        'id_number',
        'address',
        'notes',
        'unique_id' // <--- تأكد من وجوده هنا
    ];

    /**
     * هذا هو الجزء الجديد والمهم.
     * سيتم تشغيل هذا الكود تلقائياً قبل إنشاء أي عميل جديد.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($client) {
            // أنشئ رقماً تعريفياً فريداً يعتمد على الوقت الحالي وجزء عشوائي
            // مثال: CL-1672482187-A3F
            $client->unique_id = 'CL-' . time() . '-' . strtoupper(substr(md5(uniqid()), 0, 3));
        });
    }

    public function units()
    {
        return $this->belongsToMany(ProjectUnit::class, 'client_project_unit')
                    ->withPivot([
                        'sale_price', 'currency', 'exchange_rate', 'sale_price_ils',
                        'sale_date', 'contract_details'
                    ])
                    ->withTimestamps();
    }
}
