<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('bank_transactions', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->string('client_name')->nullable();
            $table->string('client_phone')->nullable();
            $table->string('payer_id_number')->nullable();
            $table->enum('type', ['deposit', 'withdrawal', 'transfer', 'personal_withdrawal']);
            $table->decimal('amount', 15, 2);
            $table->string('currency', 10)->default('شيكل');
            $table->string('project_name')->nullable();
            $table->string('source')->nullable();
            $table->string('transfer_details')->nullable();
            $table->string('transfer_number')->nullable();
            $table->string('beneficiary_name')->nullable();
            $table->string('beneficiary_bank_name')->nullable();
            $table->string('beneficiary_bank_number')->nullable();
            $table->string('cheque_number')->nullable();
            $table->string('cheque_owner_name')->nullable();
            $table->string('payer_bank_name')->nullable();
            $table->string('payer_bank_number')->nullable();
            $table->string('operator');
            $table->string('operator_role')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('other_bank_name')->nullable();
            $table->text('details')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bank_transactions');
    }
};
