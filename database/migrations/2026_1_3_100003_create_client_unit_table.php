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
        Schema::create('client_unit', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained()->onDelete('cascade');
            $table->foreignId('project_unit_id')->constrained()->onDelete('cascade');
            $table->decimal('sale_price', 15, 2)->comment('مبلغ البيع الفعلي');
            $table->enum('currency', ['USD', 'SAR', 'EUR'])->default('USD')->comment('عملة البيع');
            $table->date('sale_date')->comment('تاريخ البيع');
            $table->text('contract_details')->nullable();
            $table->timestamps();

            $table->unique(['project_unit_id']); // الوحدة لا يمكن أن تباع لأكثر من عميل
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('client_unit');
    }
};
