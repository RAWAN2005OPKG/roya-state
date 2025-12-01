<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CashSafe extends Model {
    use HasFactory, SoftDeletes;
    protected $fillable = ['name', 'initial_balance', 'balance', 'is_active'];
}
