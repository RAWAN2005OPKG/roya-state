<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SubcontractorContract extends Model
{
    use HasFactory;

    protected $fillable = [
        'subcontractor_id', 'project_id', 'contract_date', 'contract_value',
        'currency', 'exchange_rate', 'value_in_ils', 'contract_details'
    ];

    // علاقة لجلب بيانات المورد صاحب العقد
    public function subcontractor()
    {
        return $this->belongsTo(Subcontractor::class);
    }

    // علاقة لجلب بيانات المشروع المرتبط بالعقد
    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
