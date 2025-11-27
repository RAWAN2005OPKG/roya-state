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
    Schema::create('bank_accounts', function (Blueprint $table) {
        $table->id();
        $table->string('bank_name'); // اسم البنك
        $table->string('account_name'); // اسم صاحب الحساب
        $table->string('account_number')->unique(); // رقم الحساب
        $table->string('iban')->nullable()->unique(); // رقم الآيبان
        $table->decimal('balance', 15, 2)->default(0.00); // الرصيد الحالي
        $table->boolean('is_active')->default(true); // حالة الحساب
        $table->timestamps();
        $table->softDeletes(); // لإضافة الحذف المؤقت
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bank_accounts');
    }
};
