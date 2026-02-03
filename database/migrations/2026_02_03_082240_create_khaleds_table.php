<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('khaleds', function (Blueprint $table) {
            $table->id();
            $table->date('voucher_date');
            $table->enum('type', ['receipt', 'payment']); // قبض أو صرف
            $table->enum('payment_method', ['cash', 'bank_transfer', 'check']);
            $table->text('description');
            $table->decimal('amount', 15, 2);
            $table->string('currency', 3);
            $table->decimal('exchange_rate', 10, 4)->default(1.0000);
            $table->decimal('amount_ils', 15, 2); // القيمة النهائية بالشيكل

            // Relationships
            $table->foreignId('from_bank_account_id')->nullable()->constrained('bank_accounts')->nullOnDelete();
            $table->foreignId('to_bank_account_id')->nullable()->constrained('bank_accounts')->nullOnDelete();
            $table->foreignId('project_id')->nullable()->constrained('projects')->nullOnDelete();
            $table->foreignId('client_id')->nullable()->constrained('clients')->nullOnDelete();
            $table->foreignId('investor_id')->nullable()->constrained('investors')->nullOnDelete();

            // Optional Fields based on payment method
            $table->foreignId('cash_safe_id')->nullable()->constrained('cash_safes')->nullOnDelete();
            $table->string('handler_name')->nullable(); // اسم المستلم/المسلم
            $table->string('handler_role')->nullable(); // وظيفة المستلم/المسلم
            $table->string('check_number')->nullable();
            $table->string('check_owner_name')->nullable();
            $table->string('check_bank_name')->nullable();
            $table->date('check_due_date')->nullable();

            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes(); // For trash functionality
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('khaleds');
    }
};
