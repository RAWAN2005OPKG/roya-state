<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();

            // هذا السطر يربط الدفعة بالعميل أو أي موديل آخر
            $table->morphs('payable');

            // هذا السطر يربط الدفعة بالعقد المحدد
            $table->foreignId('contract_id')->nullable()->constrained('contracts')->nullOnDelete();

            $table->date('payment_date');
            $table->decimal('amount', 15, 2);
            $table->string('currency', 3);
            $table->decimal('exchange_rate', 10, 4)->default(1.0000);
            $table->decimal('amount_ils', 15, 2);
            $table->enum('type', ['in', 'out']); // 'in' = دفعة مقبوضة, 'out' = دفعة مدفوعة
            $table->string('method')->nullable(); // cash, check, bank_transfer
            $table->text('description')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
