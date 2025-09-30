<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Alert extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'message',
        'type', // cheque_due, contract_expiry, payment_due, general
        'priority', // high, medium, low
        'status', // active, dismissed, resolved
        'related_id', // ID of related record (cheque, contract, etc.)
        'related_type', // Type of related record
        'due_date',
        'created_by',
        'assigned_to',
    ];

    protected $casts = [
        'due_date' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // علاقة مع المستخدم الذي أنشأ التنبيه
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // علاقة مع المستخدم المكلف بالتنبيه
    public function assignee()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    // دالة للحصول على لون التنبيه حسب الأولوية
    public function getPriorityColorAttribute()
    {
        return match($this->priority) {
            'high' => 'danger',
            'medium' => 'warning',
            'low' => 'info',
            default => 'secondary'
        };
    }

    // دالة للحصول على أيقونة التنبيه حسب النوع
    public function getTypeIconAttribute()
    {
        return match($this->type) {
            'cheque_due' => 'fas fa-money-check',
            'contract_expiry' => 'fas fa-file-contract',
            'payment_due' => 'fas fa-credit-card',
            'general' => 'fas fa-bell',
            default => 'fas fa-exclamation-triangle'
        };
    }

    // دالة للحصول على النوع بالعربية
    public function getTypeNameAttribute()
    {
        return match($this->type) {
            'cheque_due' => 'شيك مستحق',
            'contract_expiry' => 'عقد منتهي الصلاحية',
            'payment_due' => 'دفعة مستحقة',
            'general' => 'عام',
            default => 'غير محدد'
        };
    }

    // دالة للحصول على الأولوية بالعربية
    public function getPriorityNameAttribute()
    {
        return match($this->priority) {
            'high' => 'عالية',
            'medium' => 'متوسطة',
            'low' => 'منخفضة',
            default => 'غير محدد'
        };
    }

    // دالة للحصول على الحالة بالعربية
    public function getStatusNameAttribute()
    {
        return match($this->status) {
            'active' => 'نشط',
            'dismissed' => 'مرفوض',
            'resolved' => 'محلول',
            default => 'غير محدد'
        };
    }
}
