<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();

            // --- الجزء الأهم: العلاقة متعددة الأشكال ---
            // هذا سيضيف حقلين: payable_id (e.g., 5) و payable_type (e.g., 'App\Models\Client')
            $table->morphs('payable');

            // تفاصيل المبلغ
            $table->decimal('amount', 15, 2); // المبلغ بالعملة الأصلية
            $table->string('currency', 10);
            $table->decimal('exchange_rate', 10, 4)->default(1.0000);
            $table->decimal('amount_ils', 15, 2); // المبلغ المحول للشيكل دائماً

            // تفاصيل الحركة
            $table->enum('type', ['in', 'out']); // in = قبض, out = صرف
            $table->enum('method', ['cash', 'bank_transfer', 'check']); // طريقة الدفع
            $table->date('payment_date'); // تاريخ الدفعة
            $table->text('notes')->nullable();

            // حقول اختيارية لتفاصيل طريقة الدفع (يمكن توسيعها)
            $table->string('check_number')->nullable();
            $table->date('check_due_date')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('transaction_reference')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
