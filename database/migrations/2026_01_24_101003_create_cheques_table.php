<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('cheques', function (Blueprint $table) {
            $table->id();
            $table->string('cheque_number')->unique(); // رقم الشيك
            $table->enum('type', ['inbound', 'outbound']); // نوع الشيك: قبض (داخل) أو صرف (خارج)
            $table->date('receipt_date'); // تاريخ استلام الشيك
            $table->date('due_date'); // تاريخ استحقاق الشيك
            $table->decimal('amount', 15, 2); // قيمة الشيك
            $table->string('bank_name'); // اسم البنك

            // لمن/من من تم استلام/صرف الشيك
            $table->morphs('payable'); // (يرتبط بالعميل، المورد، المستثمر، إلخ)

            $table->string('status')->default('pending'); // (pending, collected, bounced) -> (قيد الانتظار، محصّل، مرتجع)
            $table->text('notes')->nullable(); // ملاحظات
            $table->timestamps();
            $table->softDeletes();
        });
    }
    public function down(): void {
        Schema::dropIfExists('cheques');
    }
};
