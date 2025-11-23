<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('fund_transfers', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->decimal('amount', 15, 2);
            $table->string('currency');
            $table->string('from_type'); // 'cash' or 'bank'
            $table->unsignedBigInteger('from_id'); // ID of CashSafe or BankAccount
            $table->string('to_type'); // 'cash' or 'bank'
            $table->unsignedBigInteger('to_id'); // ID of CashSafe or BankAccount
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('fund_transfers');
    }
};
