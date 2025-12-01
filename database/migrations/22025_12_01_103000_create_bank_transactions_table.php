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
        Schema::create('bank_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bank_account_id')->constrained('bank_accounts')->onDelete('cascade');
            $table->string('type'); // deposit, withdrawal, transfer, personal_withdrawal
            $table->decimal('amount', 15, 2);
            $table->string('currency', 10);
            $table->date('date');

            // -- الحقول الإضافية من تصميمك --
            $table->string('client_name')->nullable();
            $table->string('client_phone')->nullable();
            $table->string('payer_id_number')->nullable(); // رقم هوية الدافع
            $table->string('project_name')->nullable();
            $table->string('source')->nullable(); // مصدر المبلغ
            $table->string('transfer_details')->nullable();
            $table->string('transfer_number')->nullable(); // رقم التحويلة

            // -- بيانات البنك المرسل والمستقبل --
            $table->string('payer_bank_name')->nullable(); // البنك المرسل
            $table->string('payer_bank_number')->nullable(); // رقم/فرع حساب المرسل
            $table->string('beneficiary_bank_name')->nullable(); // البنك المستقبل
            $table->string('beneficiary_bank_number')->nullable(); // رقم/فرع حساب المستقبل

            $table->text('details')->nullable(); // تفاصيل إضافية
            $table->text('notes')->nullable(); // ملاحظات

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bank_transactions');
    }
};
