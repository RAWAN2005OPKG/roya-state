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
        Schema::create('funds', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // اسم الخزينة/الصندوق
            $table->string('currency', 10)->default('ILS'); // عملة الخزينة
            $table->decimal('initial_balance', 15, 2)->default(0.00); // الرصيد الأولي
            $table->decimal('current_balance', 15, 2)->default(0.00); // الرصيد الحالي (يمكن تحديثه آلياً)
            $table->text('description')->nullable(); // وصف اختياري
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('funds');
    }
};
