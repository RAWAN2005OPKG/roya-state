<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('bank_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bank_account_id')->constrained('bank_accounts')->onDelete('cascade');
            $table->date('date');
            $table->string('type');
            $table->decimal('amount', 15, 2);
            $table->string('currency', 10);
            $table->string('client_name')->nullable();
            $table->string('client_phone')->nullable();
            $table->string('payer_id_number')->nullable();
            $table->string('project_name')->nullable();
            $table->string('source')->nullable();
            $table->string('transfer_details')->nullable();
            $table->string('transfer_number')->nullable();
            $table->string('payer_bank_name')->nullable();
            $table->string('payer_bank_number')->nullable();
            $table->string('beneficiary_bank_name')->nullable();
            $table->string('beneficiary_bank_number')->nullable();
            $table->text('details')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }
    public function down(): void { Schema::dropIfExists('bank_transactions'); }
};
