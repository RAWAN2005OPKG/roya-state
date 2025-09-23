<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('investments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('investor_id')->constrained('investors')->cascadeOnDelete();
            $table->date('date');
            $table->string('project');
            $table->string('type')->nullable();
            $table->string('phone')->nullable();
            $table->string('id_number')->nullable();
            $table->string('job')->nullable();
            $table->string('currency', 10)->default('شيكل');
            $table->decimal('amount', 15, 2);
            $table->decimal('share_percentage', 5, 2)->default(0);
            $table->enum('status', ['active', 'completed', 'cancelled'])->default('active');
            $table->string('payment_method')->nullable();
            $table->string('payee')->nullable();
            $table->date('payment_date')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('other_bank_name')->nullable();
            $table->string('transaction_id')->nullable();
            $table->string('contract_id')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('investments');
    }
};
