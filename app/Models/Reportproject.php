<?php
// app/Models/Project.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'project_title',
        'owner_name',
        'project_status',
        'total_budget',
        'currency',
        'description',
        'additional_info',
        'files',
    ];

    protected $dates = ['deleted_at'];
}
