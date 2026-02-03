<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('checks', function (Blueprint $table) {
            $table->id();
            $table->string('check_number');
            $table->string('bank_name');
            $table->date('issue_date');
            $table->date('due_date');
            $table->enum('type', ['receivable', 'payable']);
            $table->string('party_name');
            $table->string('party_phone')->nullable();
            $table->decimal('amount', 15, 2);
            $table->string('currency', 3)->default('ILS');
            $table->decimal('exchange_rate', 10, 4)->default(1.0000);
            $table->decimal('amount_ils', 15, 2);

            // العلاقات المحدثة
            $table->unsignedBigInteger('deposit_bank_account_id')->nullable();
            $table->unsignedBigInteger('payment_bank_account_id')->nullable();
            $table->unsignedBigInteger('project_id')->nullable();
            $table->unsignedBigInteger('project_unit_id')->nullable(); // الحقل الجديد

            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes(); // الحذف الناعم (سلة المحذوفات)
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('checks');
    }
};
