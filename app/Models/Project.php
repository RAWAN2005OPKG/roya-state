<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'project_name',
        'description',
        'budget',
        'start_date',
        'end_date',
    ];

   public function investments()
{
    return $this->hasMany(Investment::class);
}
public function totalInvested()
{
    return $this->investments()->sum('amount'); // حقل 'amount' هو مبلغ الاستثمار
}

}
