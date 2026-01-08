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
        Schema::create('investor_project', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->onDelete('cascade');
            $table->foreignId('investor_id')->constrained()->onDelete('cascade');
            $table->decimal('investment_percentage', 5, 2)->nullable(); // نسبة الاستثمار
            $table->decimal('invested_amount', 15, 2);                 // المبلغ الأصلي
            $table->string('currency', 3);                             // العملة (USD, JOD, ILS)
            $table->decimal('exchange_rate', 10, 4);                   // سعر الصرف
            $table->decimal('invested_amount_ils', 15, 2);             // القيمة المحولة للشيكل
            $table->text('notes')->nullable();                         // ملاحظات

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_investor');
    }
};
