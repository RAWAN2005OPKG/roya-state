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
        Schema::create('project_investor', function (Blueprint $table) {
            $table->id();

            // الأعمدة الأساسية للربط
            $table->foreignId('project_id')->constrained()->onDelete('cascade');
            $table->foreignId('investor_id')->constrained()->onDelete('cascade');

            // --- إضافة كل الأعمدة الجديدة المطلوبة ---
            $table->decimal('investment_percentage', 5, 2)->nullable();
            $table->decimal('invested_amount', 15, 2);
            $table->string('currency', 3);
            $table->decimal('exchange_rate', 10, 4);
            $table->decimal('invested_amount_ils', 15, 2);
            $table->text('notes')->nullable();

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
