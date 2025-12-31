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
        // اسم الجدول يجب أن يكون مطابقاً لما يتوقعه لارافيل
        Schema::create('client_project_unit', function (Blueprint $table) {
            $table->id(); // مفتاح أساسي للجدول نفسه

            // مفاتيح خارجية لربط الجداول
            $table->foreignId('client_id')->constrained('clients')->onDelete('cascade');
            $table->foreignId('project_unit_id')->constrained('project_units')->onDelete('cascade');

            // الحقول الإضافية (الـ pivot data) التي نخزنها
            $table->decimal('sale_price', 15, 2);
            $table->string('currency', 10);
            $table->decimal('exchange_rate', 10, 4)->default(1.0000);
            $table->decimal('sale_price_ils', 15, 2);
            $table->date('sale_date');
            $table->text('contract_details')->nullable();

            $table->timestamps(); // حقول created_at و updated_at

            // (اختياري ولكن موصى به) لمنع ربط نفس العميل بنفس الوحدة أكثر من مرة
            $table->unique(['client_id', 'project_unit_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('client_project_unit');
    }
};
