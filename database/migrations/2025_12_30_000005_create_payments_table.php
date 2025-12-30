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
            $table->morphs('payable'); // payable_id, payable_type (لربطها بالعميل/المستثمر/المقاول)

            $table->enum('type', ['in', 'out'])->comment('نوع الحركة: قبض (in) أو صرف (out)');
            $table->date('payment_date')->comment('تاريخ الدفعة');

            // تفاصيل المبلغ والعملة
            $table->decimal('amount', 15, 2)->comment('المبلغ المدفوع بالعملة الأصلية');
            $table->enum('currency', ['ILS', 'USD', 'JOD'])->default('ILS')->comment('العملة الأصلية');
            $table->decimal('exchange_rate', 8, 4)->default(1)->comment('سعر الصرف مقابل الشيكل (ILS)');
            $table->decimal('amount_ils', 15, 2)->comment('القيمة المعادلة بالشيكل');

            // تفاصيل طريقة الدفع
            $table->enum('method', ['cash', 'bank_transfer', 'check'])->comment('طريقة الدفع');

            // تفاصيل الشيك (إذا كانت الطريقة 'check')
            $table->string('check_number')->nullable();
            $table->date('due_date')->nullable()->comment('تاريخ استحقاق الشيك');
            $table->string('check_owner')->nullable()->comment('اسم مالك الشيك');
            $table->enum('check_type', ['personal', 'certified'])->nullable();

            // تفاصيل التحويل البنكي (إذا كانت الطريقة 'bank_transfer')
            $table->foreignId('sender_bank_account_id')->nullable()->constrained('bank_accounts')->onDelete('restrict');
            $table->foreignId('receiver_bank_account_id')->nullable()->constrained('bank_accounts')->onDelete('restrict');
            $table->string('transaction_reference')->nullable()->comment('مرجع التحويل البنكي');

            // تفاصيل النقد (إذا كانت الطريقة 'cash')
            $table->string('delivered_by')->nullable()->comment('من سلم المبلغ');
            $table->string('received_by')->nullable()->comment('من استلم المبلغ');

            $table->text('notes')->nullable();
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
