<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
Schema::create('purchases', function (Blueprint $table) {
    $table->id();
    $table->foreignId('supplier_id')->constrained('suppliers')->onDelete('cascade');
    $table->string('invoice_number')->unique();
    $table->date('invoice_date');
    $table->date('due_date')->nullable();
    $table->decimal('total_amount', 10, 2);
    $table->decimal('paid_amount', 10, 2)->default(0);
    $table->string('payment_method');
    $table->enum('status', ['paid', 'partially_paid', 'unpaid'])->default('unpaid');
    $table->timestamps();
});

    }
    public function down(): void { Schema::dropIfExists('purchase_items'); }
};
