<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->string('payee');
            $table->string('phone')->nullable();
            $table->string('job')->nullable();
            $table->string('id_number')->nullable();
            $table->unsignedBigInteger('project_id')->nullable();
            $table->decimal('amount', 15, 2);
            $table->string('currency', 10);
            $table->string('payment_method', 50);
            $table->string('payment_source', 50);

            // Cash details
            $table->string('cash_receiver')->nullable();
            $table->string('cash_receiver_other')->nullable();
            $table->string('receiver_job')->nullable();

            // Bank transfer details
            $table->string('sender_bank')->nullable();
            $table->string('other_sender_bank')->nullable();
            $table->string('sender_branch')->nullable();
            $table->string('receiver_bank')->nullable();
            $table->string('other_receiver_bank')->nullable();
            $table->string('receiver_branch')->nullable();
            $table->string('transaction_id')->nullable();

            // Cheque details
            $table->string('check_number')->nullable();
            $table->string('check_owner')->nullable();
            $table->string('check_holder')->nullable();
            $table->date('check_due_date')->nullable();
            $table->date('check_receive_date')->nullable();

            // Notes
            $table->text('notes')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};
