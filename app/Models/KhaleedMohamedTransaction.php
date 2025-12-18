<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class KhaleedMohamedTransaction extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    /**
     * تحديد العلاقة: كل حركة تتبع لمشروع واحد
     */
    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
