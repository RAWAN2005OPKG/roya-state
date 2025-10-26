<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'phone',
        'project_id',
        'unit',
        'agreement_amount',
        'currency',
        'payment_method',
        'due_date',
        'contract_file',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'agreement_amount' => 'decimal:2',
        'due_date' => 'date',
    ];


    public function project()
    {

        return $this->belongsTo(Project::class);
    }
}
