<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
public function up(): void
{
    Schema::create('salary_payments', function (Blueprint $table) {
        $table->id();
        $table->foreignId('employee_id')->constrained('employees')->cascadeOnDelete(); // <-- لربطه بالموظف وحذف الدفعات عند حذف الموظف
        $table->decimal('amount', 15, 2); // المبلغ المدفوع
        $table->date('payment_date'); // تاريخ الدفع الفعلي
        $table->string('salary_month', 7); // الشهر والسنة (مثال: '2024-10')
        $table->text('notes')->nullable(); // ملاحظات (للخصومات أو المكافآت)
        $table->timestamps();
    });
}



    public function down(): void
    {
        Schema::dropIfExists('salary_payments');
    }
};
