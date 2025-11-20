<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('stocktake_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('stocktake_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained();
            $table->integer('system_quantity'); // الكمية في النظام عند الجرد
            $table->integer('actual_quantity'); // الكمية الفعلية
            $table->integer('difference'); // الفرق
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('stocktake_items');
    }
};
