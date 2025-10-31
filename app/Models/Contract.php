<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Contract extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
         'contractable_id', 'contractable_type', 'project_id', 'contract_id',
    'signing_date', 'status', 'investment_amount', 'currency', 'terms',
    'attachment', 'details',
    ];

    protected $casts = [
        'signing_date' => 'date',
        'investment_amount' => 'decimal:2',
    ];

    public function contractable()
    {
        return $this->morphTo();
    }


    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
