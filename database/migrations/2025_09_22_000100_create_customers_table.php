<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->date('due_date')->nullable();
            $table->string('name');
            $table->string('phone')->nullable();
            $table->string('project')->nullable();
            $table->string('unit');
            $table->decimal('agreement_amount', 15, 2);
            $table->string('payment_method', 50);
            $table->string('currency', 10);
            $table->string('paid_to')->nullable();
            $table->string('paid_to_other')->nullable();
            // Bank details
            $table->string('bank_name')->nullable();
            $table->string('other_bank_name')->nullable();
            $table->string('other_bank_branch')->nullable();
            // Check details
            $table->string('check_number')->nullable();
            $table->string('check_bank')->nullable();
            $table->date('check_due_date')->nullable();
            $table->date('check_receipt_date')->nullable();
            // Contract file path
            $table->string('contract_file')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
