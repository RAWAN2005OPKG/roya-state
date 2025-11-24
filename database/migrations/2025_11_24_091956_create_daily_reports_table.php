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
        Schema::create('daily_reports', function (Blueprint $table) {
            $table->id();
            $table->string('description'); // وصف الحركة
            $table->decimal('amount', 15, 2); // المبلغ
            $table->string('type'); // نوع الحركة (مثال: expense, sale, transfer)
            $table->unsignedBigInteger('source_id')->nullable(); // معرّف المصدر (فاتورة، مصروف)
            $table->string('source_type')->nullable(); // نوع المصدر
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('daily_reports');
    }
};
