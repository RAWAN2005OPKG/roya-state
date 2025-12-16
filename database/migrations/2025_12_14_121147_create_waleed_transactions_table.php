<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('waleed_transactions', function (Blueprint $table) {
            $table->id();
            $table->date('date'); // التاريخ
            $table->decimal('amount_shekel', 15, 2)->nullable(); // قيمه الدفعه شيكل
            $table->decimal('amount_dollar', 15, 2)->nullable(); // قيمه الدفعه دولار
            $table->string('paid_by'); // من وليد الخالص دفع ليد
            $table->string('paid_to'); // صرف لمين
            $table->text('expense_details')->nullable(); // بيانات المصاريف
            $table->text('notes')->nullable(); // ملاحظات
            $table->timestamps();
       $table->softDeletes();          });
    }

    public function down(): void
    {
        Schema::dropIfExists('waleed_transactions');
    }
};
