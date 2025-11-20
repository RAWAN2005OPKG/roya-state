<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('price_lists', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('type', ['percentage', 'fixed']); // نوع التسعير: نسبة مئوية أو سعر ثابت
            $table->decimal('value', 8, 2)->nullable(); // قيمة النسبة (مثال: 15.00) أو null للسعر الثابت
            $table->boolean('is_active')->default(true);
            $table->softDeletes();
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('price_lists');
    }
};
