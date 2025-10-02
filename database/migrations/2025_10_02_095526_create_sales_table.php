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
    Schema::create('sales', function (Blueprint $table) {
        $table->id();

        $table->date('sale_date');
        $table->string('customer_name');
        $table->string('project_name')->nullable();
        $table->decimal('amount_paid', 10, 2);
        $table->string('seller_name')->nullable();
        $table->string('payment_method');
        $table->text('notes')->nullable();
        $table->date('expense_date')->after('id')->nullable();

        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
    Schema::table('expenses', function (Blueprint $table) {
            // أضف هذا السطر لحذف العمود عند التراجع
            $table->dropColumn('expense_date');
        });    }
};
