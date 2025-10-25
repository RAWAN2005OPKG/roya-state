<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = []; 

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    public function investments()
{
    return $this->hasMany(Investment::class);
}

    protected $casts = [
        'start_date' => 'date',
        'check_due_date' => 'date',
        'check_receive_date' => 'date',
        'apartment_price' => 'decimal:2',
        'down_payment' => 'decimal:2',
        'land_cost' => 'decimal:2',
        'excavation_cost' => 'decimal:2',
        'engineers_cost' => 'decimal:2',
        'licensing_cost' => 'decimal:2',
        'materials_cost' => 'decimal:2',
        'finishing_cost' => 'decimal:2',
        'total_budget' => 'decimal:2',
    ];
}
