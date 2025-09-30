<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('payment_vouchers', function (Blueprint $table) {
            $table->id();
            $table->date('transaction_date');
            $table->unsignedBigInteger('contact_id')->nullable();
            $table->decimal('amount', 15, 2);
            $table->string('currency', 10)->default('شيكل');
            $table->string('payment_method', 50); // cash, bank_transaction, check
            $table->string('payment_source', 50)->nullable(); // خزينة/بنك
            // cash details
            $table->string('cash_receiver')->nullable();
            $table->string('cash_receiver_other')->nullable();
            $table->string('cash_receiver_job')->nullable();
            // bank details
            $table->string('sender_bank')->nullable();
            $table->string('sender_bank_other')->nullable();
            $table->string('sender_bank_branch')->nullable();
            $table->string('receiver_bank')->nullable();
            $table->string('receiver_bank_other')->nullable();
            $table->string('receiver_bank_branch')->nullable();
            $table->string('transaction_id')->nullable();
            // check details
            $table->string('check_number')->nullable();
            $table->string('check_owner')->nullable();
            $table->string('check_holder')->nullable();
            $table->date('check_due_date')->nullable();
            $table->date('check_receive_date')->nullable();
            // purpose
            $table->unsignedBigInteger('project_id')->nullable();
            $table->text('purpose_description')->nullable();
            // receiver
            $table->string('receiver_name')->nullable();
            $table->string('receiver_signature')->nullable();
           
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_vouchers');
    }
};
