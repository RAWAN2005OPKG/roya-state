<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Check extends Model {
    use HasFactory, SoftDeletes;
    protected $fillable = ['check_number', 'type', 'amount', 'currency', 'due_date', 'status', 'holder_name', 'bank_name', 'notes'];
    protected $casts = ['due_date' => 'date'];
}
