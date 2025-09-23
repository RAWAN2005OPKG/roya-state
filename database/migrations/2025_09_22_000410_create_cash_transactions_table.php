<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('cash_transactions', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->enum('type', ['deposit', 'withdrawal', 'personal_withdrawal']);
            $table->decimal('amount', 15, 2);
            $table->string('currency', 10)->default('شيكل');
            $table->string('project_name')->nullable();
            $table->string('source')->nullable();
            $table->string('beneficiary')->nullable();
            $table->string('operator');
            $table->string('operator_role')->nullable();
            $table->text('details')->nullable();
            $table->text('notes')->nullable();
            $table->string('payer_id_number')->nullable();
            $table->string('client_phone')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cash_transactions');
    }
};
