<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('cheques', function (Blueprint $table) {
            $table->id();
            $table->date('cheque_date')->nullable();
            $table->date('due_date')->nullable();
            $table->enum('type', ['incoming', 'outgoing']);
            $table->string('cheque_number')->nullable();
            $table->string('transfer_number')->nullable();
            $table->string('owner_name')->nullable();
            $table->string('holder_name')->nullable();
            $table->string('payer_id_number')->nullable();
            $table->string('client_phone')->nullable();
            $table->string('beneficiary_name')->nullable();
            $table->string('project_name')->nullable();
            $table->string('currency', 10)->default('شيكل');
            $table->decimal('amount', 15, 2);
            $table->string('bank_name')->nullable();
            $table->string('other_bank_name')->nullable();
            $table->string('bank_branch')->nullable();
            $table->string('account_number')->nullable();
            $table->string('operator')->nullable();
            $table->string('transfer_details')->nullable();
            $table->text('notes')->nullable();
            $table->enum('status', ['in_wallet', 'cashed', 'returned'])->default('in_wallet');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cheques');
    }
};
