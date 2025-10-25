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

    // العلاقة مع المستثمر
    public function investor()
    {
        return $this->belongsTo(Investor::class);
    }

    // العلاقة مع المشروع
    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
