<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('khaleed_mohamed_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->nullable()->constrained('projects')->onDelete('set null');
            $table->date('date'); // التاريخ
            $table->decimal('amount_shekel', 15, 2)->nullable(); // قيمه الدفعه شيكل
            $table->decimal('amount_dollar', 15, 2)->nullable(); // قيمه الدفعه دولار
            $table->enum('paid_by', ['محمد', 'خالد']); // من محمد او خالد دفع
            $table->string('paid_to'); // صرف لمين
            $table->text('expense_details')->nullable(); // بيانات المصاريف
            $table->text('notes')->nullable(); // ملاحظات
            $table->timestamps();
            $table->softDeletes(); // لدعم سلة المحذوفات مستقبلاً
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('khaleed_mohamed_transactions');
    }
};
