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

            // العلاقة الأساسية
            $table->morphs('payable');
            $table->foreignId('contract_id')->constrained('contracts')->onDelete('cascade');

            // تفاصيل المبلغ
            $table->decimal('amount', 15, 2);
            $table->string('currency', 10);
            $table->decimal('exchange_rate', 10, 4)->default(1.0000);
            $table->decimal('amount_ils', 15, 2);

            // تفاصيل الحركة
            $table->enum('type', ['in', 'out']);
            $table->enum('method', ['cash', 'bank_transfer', 'check']);
            $table->date('payment_date');
            $table->text('notes')->nullable();


            // حقول الدفع النقدي (Cash)
            $table->string('delivered_by')->nullable();
            $table->string('received_by')->nullable();

            // حقول الشيك (Check)
            $table->string('check_number')->nullable();
            $table->date('due_date')->nullable(); // تأكد من أن الاسم مطابق للمودل
            $table->string('check_owner')->nullable();
            $table->string('check_type')->nullable(); // هذا الحقل لم يكن موجوداً

            // حقول التحويل البنكي (Bank Transfer)
            $table->unsignedBigInteger('sender_bank_account_id')->nullable();
            $table->unsignedBigInteger('receiver_bank_account_id')->nullable();
            $table->string('transaction_reference')->nullable();

            // --- نهاية الحقول الجديدة ---

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
