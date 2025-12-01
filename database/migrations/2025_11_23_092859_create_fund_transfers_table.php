<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
   public function up(): void
{
    Schema::create('fund_transfers', function (Blueprint $table) {
        $table->id();
        $table->date('date'); // تاريخ العملية
        $table->decimal('amount', 15, 2); // المبلغ
        $table->string('currency', 10); // العملة

        $table->string('from_type'); // نوع الحساب المصدر (cash أو bank)
        $table->unsignedBigInteger('from_id'); // رقم الحساب المصدر

        $table->string('to_type'); // نوع الحساب الهدف (cash أو bank)
        $table->unsignedBigInteger('to_id'); // رقم الحساب الهدف

        $table->text('notes')->nullable(); // ملاحظات
        $table->timestamps();
    });
}

    public function down(): void {
        Schema::dropIfExists('fund_transfers');
    }
};
