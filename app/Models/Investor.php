<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Investor extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'id_number',
        'phone',
        'email',
        'jobs',
        'address',
        'notes',
    ];

    public function investments()
    {
        return $this->hasMany(Investment::class);
    }
    public function contracts()
{
    return $this->morphMany(Contract::class, 'contractable');
}
    public function projects(): BelongsToMany
    {
        return $this->belongsToMany(Project::class, 'project_investor')
            ->withPivot('investment_percentage', 'invested_amount', 'notes')
            ->withTimestamps();
    } public function payments()
    {
        return $this->morphMany(Payment::class, 'payable');
    }public function getTotalInvestedAttribute()
    {
        // إجمالي المبلغ المستثمر (بالشيكل)
        return $this->projects->sum(function ($project) {
            return $project->pivot->invested_amount; // يجب أن يكون هذا المبلغ موحداً بالشيكل أو يتم تحويله
        });
    }

    public function getTotalPaidAttribute()
    {
        // إجمالي المبالغ التي صرفت للمستثمر (صرف)
        return $this->payments()->where('type', 'out')->sum('amount_ils');
    }

    public function getRemainingInvestmentAttribute()
    {
        // المبلغ المتبقي للاستثمار (بافتراض أن الدفعات هي صرف للمستثمر)
        return $this->total_invested - $this->total_paid;
    }}
