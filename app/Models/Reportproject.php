<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ReportProject extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The table associated with the model.
     * اسم الجدول في قاعدة البيانات.
     */
    protected $table = 'report_projects';

    /**
     * The attributes that are mass assignable.
     * الحقول التي نسمح بتعبئتها من خلال النماذج.
     */
    protected $fillable = [
        'name',
        'project_title',
        'owner_name',
        'owner_phone',
        'owner_id',
        'project_status',
        'start_date',
        'total_budget',
        'currency',
        'description',
        'project_media',
    ];

    /**
     * The attributes that should be cast to native types.
     * هذا يضمن أن الحقول ستعامل دائمًا بأنواع البيانات الصحيحة.
     */
    protected $casts = [
        'total_budget' => 'decimal:2',
        'start_date' => 'date',
    ];


      public function payments()
      {
         return $this->hasMany(Payment::class);
      }

}
