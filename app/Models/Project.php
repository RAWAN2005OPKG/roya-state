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
    'project_title',
    'start_date',
    'end_date',
    'currency',
    'apartment_price',
    'down_payment',
    'project_status',
    'project_media',
    'budget',
];

   public function investments()
{
    return $this->hasMany(Investment::class);
}
public function totalInvested()
{
    return $this->investments()->sum('amount'); // حقل 'amount' هو مبلغ الاستثمار
}
 public function customers()
    {
        return $this->hasMany(Customer::class);
    }
      public function totalInvested()
    {
        return $this->investments()->sum('amount');
    }
}
