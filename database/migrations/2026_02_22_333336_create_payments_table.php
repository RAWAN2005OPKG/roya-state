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

            // الأعمدة الأساسية
            $table->morphs('payable'); // payable_id, payable_type
            $table->foreignId('contract_id')->nullable()->constrained('contracts')->onDelete('set null');

            $table->enum('type', ['in', 'out']); // in = قبض, out = صرف
            $table->string('method'); // cash, bank, check
            $table->date('payment_date');

            // الأعمدة المالية
            $table->decimal('amount', 15, 2);
            $table->string('currency', 3);
            $table->decimal('exchange_rate', 10, 4);
            $table->decimal('amount_ils', 15, 2);

            // العمود المفقود الذي قمنا بإضافته
            $table->text('notes')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
