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
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->string('payee');
            $table->string('phone', 50)->nullable();
            $table->string('job', 100)->nullable();
            $table->string('id_number', 50)->nullable();
            $table->string('project_name');
            $table->decimal('amount', 10, 2);
            $table->string('currency', 10);
            $table->string('payment_method', 50);
            $table->string('payment_source', 50);
            $table->string('cash_receiver', 100)->nullable();
            $table->string('cash_receiver_other', 100)->nullable();
            $table->string('receiver_job', 100)->nullable();
            $table->string('sender_bank', 100)->nullable();
            $table->string('other_sender_bank', 100)->nullable();
            $table->string('sender_branch', 100)->nullable();
            $table->string('receiver_bank', 100)->nullable();
            $table->string('other_receiver_bank', 100)->nullable();
            $table->string('receiver_branch', 100)->nullable();
            $table->string('transaction_id', 100)->nullable();
            $table->string('check_number', 100)->nullable();
            $table->string('check_owner', 100)->nullable();
            $table->string('check_holder', 100)->nullable();
            $table->date('check_due_date')->nullable();
            $table->date('check_receive_date')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};

