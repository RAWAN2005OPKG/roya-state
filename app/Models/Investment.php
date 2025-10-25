<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Investment extends Model
{
    use HasFactory;

    protected $fillable = [
        'investor_id',
        'project_id',
        'investment_date',
        'investment_type',
        'currency',
        'amount',
        'share_percentage',
        'status',
    ];

    protected $casts = [
        'investment_date' => 'date:Y-m-d',
        'amount' => 'float',
        'share_percentage' => 'float',
    ];

    public function investor()
    {
        return $this->belongsTo(Investor::class);
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
