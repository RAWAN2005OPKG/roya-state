<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectTransfer extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'name',
        'id_number',
        'phone',
        'from_project_id',
        'to_project_id',
        'currency',
        'amount',
        'notes',
    ];

    protected $casts = [
        'date' => 'date',
        'amount' => 'decimal:2',
    ];
}
