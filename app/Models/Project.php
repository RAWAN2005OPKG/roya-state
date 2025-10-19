<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'projects';

    protected $fillable = [
        'due_date',
        'project_name',
        'project_title',
        'currency',
        'apartment_price',
        'down_payment',
        'project_status',
        'project_media',
    ];

    public function investments()
    {
        return $this->hasMany(Investment::class);
    }

    public function totalInvested()
    {
        return $this->investments()->sum('amount');
    }
}
