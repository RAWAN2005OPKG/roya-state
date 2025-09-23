<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('client_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained('customers')->cascadeOnDelete();
            $table->decimal('amount', 15, 2);
            $table->date('date');
            $table->string('paid_to')->nullable();
            $table->string('paid_to_other')->nullable();
            $table->string('payment_method', 50);
            $table->string('currency', 10);
            $table->text('notes')->nullable();
            // Bank details
            $table->string('bank_name')->nullable();
            $table->string('other_bank_name')->nullable();
            $table->string('other_bank_branch')->nullable();
            // Check details
            $table->string('check_number')->nullable();
            $table->string('check_bank')->nullable();
            $table->date('check_due_date')->nullable();
            $table->date('check_receipt_date')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('client_payments');
    }
};
