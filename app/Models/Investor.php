<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Investor extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'id_number',
        'phone',
        'email',
        'address',
        'notes',
    ];

    public function investments()
    {
        return $this->hasMany(Investment::class);
    }
}
