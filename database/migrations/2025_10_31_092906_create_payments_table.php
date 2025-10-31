<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();

            // --- الربط بالعقد ---
            // هذا هو الحقل الأهم، فهو يربط كل دفعة بالعقد الخاص بها
            $table->foreignId('contract_id')->constrained('contracts')->onDelete('cascade');

            // --- تفاصيل الدفعة ---
            $table->decimal('amount', 15, 2); // قيمة هذه الدفعة
            $table->string('currency', 10);   // عملة هذه الدفعة
            $table->date('payment_date');       // تاريخ الدفع الفعلي
            $table->string('payment_method');   // طريقة الدفع (كاش, شيك, تحويل)
            $table->text('description')->nullable(); // ملاحظات على الدفعة (رقم الشيك, مرجع التحويل, إلخ)

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
