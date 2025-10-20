<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('investments', function (Blueprint $table) {
            $table->id();

            $table->foreignId('investor_id')->constrained('investors')->onDelete('cascade');
            $table->foreignId('project_id')->constrained('projects')->onDelete('cascade');
            $table->date('date')->nullable();

            $table->string('project')->nullable();
            $table->string('type')->nullable();
            $table->decimal('amount', 15, 2)->nullable();
            $table->string('currency')->nullable();
            $table->decimal('share_percentage', 5, 2)->nullable();

            $table->string('payment_method')->nullable();
            $table->decimal('down_payment_other', 15, 2)->nullable();
            $table->date('first_payment_date')->nullable();
            $table->decimal('remaining_amount', 15, 2)->nullable();

            // تفاصيل الدفع النقدي
            $table->string('cash_receiver')->nullable();
            $table->string('cash_receiver_job')->nullable();
            $table->date('cash_receipt_date')->nullable();

            // تفاصيل التحويل البنكي
            $table->string('sender_bank')->nullable();
            $table->string('receiver_bank')->nullable();
            $table->string('transaction_reference')->nullable();
            $table->date('transaction_date')->nullable();

            // تفاصيل الشيك
            $table->string('check_number')->nullable();
            $table->string('check_owner')->nullable();
            $table->string('check_bank')->nullable();
            $table->date('check_due_date')->nullable();

            $table->string('contract_id')->nullable();
            $table->text('notes')->nullable();
            $table->string('status')->default('active');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('investments');
    }
};
