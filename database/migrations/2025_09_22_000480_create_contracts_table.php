<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('contracts', function (Blueprint $table) {
            $table->id();
            // بيانات العقد
            $table->string('contract_id');
            $table->date('signing_date');
            $table->string('status')->default('active'); // active, draft

            // بيانات المستثمر
            $table->string('client_name');
            $table->string('client_email');
            $table->string('client_phone');
            $table->string('client_alt_phone')->nullable();
            $table->string('client_id_number');

            // بيانات العقار
            $table->string('property_type')->nullable();
            $table->string('property_location')->nullable();

            // التفاصيل المالية
            $table->decimal('investment_amount', 15, 2);
            $table->unsignedInteger('duration_months');
            $table->string('payment_method'); // cash, bank_transaction, check
            $table->decimal('apartment_price', 15, 2)->nullable();
            $table->date('first_payment_date')->nullable();
            $table->decimal('down_payment_initial', 15, 2)->nullable();
            $table->decimal('down_payment_other', 15, 2)->nullable();
            $table->decimal('profit_percentage', 5, 2)->nullable();
            $table->decimal('remaining_amount', 15, 2)->nullable();

            // تفاصيل الدفع النقدي
            $table->string('cash_receiver')->nullable();
            $table->string('cash_receiver_other')->nullable();
            $table->string('cash_receiver_job')->nullable();
            $table->date('cash_receipt_date')->nullable();

            // تفاصيل البنك
            $table->string('sender_bank')->nullable();
            $table->string('sender_bank_other')->nullable();
            $table->string('sender_bank_branch')->nullable();
            $table->string('receiver_bank')->nullable();
            $table->string('receiver_bank_other')->nullable();
            $table->string('receiver_bank_branch')->nullable();
            $table->string('transaction_reference')->nullable();
            $table->date('transaction_date')->nullable();

            // تفاصيل الشيك
            $table->string('check_number')->nullable();
            $table->string('check_owner')->nullable();
            $table->string('check_holder')->nullable();
            $table->string('check_bank')->nullable();
            $table->string('check_bank_other')->nullable();
            $table->string('check_bank_branch')->nullable();
            $table->date('check_due_date')->nullable();
            $table->date('check_receipt_date')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contracts');
    }
};
