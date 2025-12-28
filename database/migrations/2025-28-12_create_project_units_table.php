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
        Schema::create('project_units', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->onDelete('cascade');
            $table->string('unit_number')->comment('رقم الوحدة/اسمها');
            $table->enum('unit_type', ['apartment', 'villa', 'office', 'land', 'commercial'])->comment('نوع الوحدة');
            $table->integer('floor_number')->nullable()->comment('رقم الطابق');
            $table->decimal('area_sqm', 8, 2)->comment('المساحة بالمتر المربع');
            $table->decimal('expected_price_usd', 15, 2)->comment('السعر المتوقع بالدولار');
            $table->text('specifications')->nullable()->comment('مواصفات الوحدة');
            $table->enum('status', ['available', 'reserved', 'sold'])->default('available')->comment('حالة الوحدة');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_units');
    }
};
