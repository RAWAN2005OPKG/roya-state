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
    public function up(): void
    {
        Schema::create('employees', function (Blueprint $table) {
            // الأعمدة الأساسية
            $table->id();
            $table->string('name');
            $table->string('position');
            $table->string('email')->unique()->nullable();
            $table->string('phone')->nullable();

            // الأعمدة المالية
            $table->decimal('salary', 10, 2); // 10 أرقام إجمالاً، مع رقمين بعد الفاصلة
            $table->string('currency', 10)->default('ILS'); // عملة افتراضية هي الشيكل

            // بيانات الدفع البنكي (اختيارية)
            $table->string('iban')->nullable();
            $table->string('wallet_name')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('bank_branch')->nullable();

            // أعمدة Laravel القياسية
            $table->timestamps(); // تنشئ created_at و updated_at
            $table->softDeletes(); // تنشئ deleted_at لدعم سلة المحذوفات
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
