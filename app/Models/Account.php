<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'code', 'type', 'parent_id', 'is_active'];

    // علاقة لجلب الحساب الأب
    public function parent()
    {
        return $this->belongsTo(Account::class, 'parent_id');
    }

    // علاقة لجلب الحسابات الفرعية
    public function children()
    {
        return $this->hasMany(Account::class, 'parent_id');
    }
}
