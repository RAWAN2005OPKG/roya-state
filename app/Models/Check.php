<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cheque extends Model {
    use HasFactory, SoftDeletes;
    protected $guarded = [];
    protected $casts = ['receipt_date' => 'date', 'due_date' => 'date'];

    public function payable() {
        return $this->morphTo();
    }
}
