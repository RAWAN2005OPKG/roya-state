<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('cash_transactions', function (Blueprint $table) {
            $table->id();
            $table->date('transaction_date'); // التاريخ
            $table->enum('type', ['in', 'out']); // نوع الحركة: إيداع (in) أو سحب (out)
            $table->string('source'); // مصدر المبلغ (مثال: دفعة من عميل، سلفة، شراء أدوات)
            $table->decimal('amount', 15, 2); // قيمة المبلغ
            $table->string('currency', 3); // العملة (ILS, USD, JOD)
            $table->decimal('exchange_rate', 10, 4)->default(1.0000); // سعر الصرف
            $table->decimal('amount_ils', 15, 2); // القيمة المحولة إلى شيكل
            $table->text('details')->nullable(); // التفاصيل / الملاحظات
            $table->timestamps();
            $table->softDeletes();
        });
    }
    public function down(): void {
        Schema::dropIfExists('cash_transactions');
    }
};
