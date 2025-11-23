<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class ProjectTransfer extends Model {
    protected $fillable = ['date', 'expense_id', 'from_project_id', 'to_project_id', 'amount', 'reason'];
}
