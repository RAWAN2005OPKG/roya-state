<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Contract extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'contractable_type',
        'contractable_id',
        'project_id',
        'contract_date',
        'contract_value',
        'currency',
        'exchange_rate',
        'contract_details',
        'attachment',
        'client_id', 
    ];

    protected $casts = [
        'contract_date' => 'date',
    ];

    // هذه هي العلاقة السحرية التي كنا نحتاجها
    public function contractable()
    {
        return $this->morphTo();
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
