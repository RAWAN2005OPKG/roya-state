<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CashSafe extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = ['name', 'balance', 'is_active'];
}

