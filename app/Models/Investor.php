<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Investor extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'id_number',
        'phone',
        'email',
        'jobs',
        'address',
        'notes',
    ];

    public function investments()
    {
        return $this->hasMany(Investment::class);
    }
}
