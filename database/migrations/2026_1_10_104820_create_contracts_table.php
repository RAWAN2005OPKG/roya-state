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
        Schema::create('contracts', function (Blueprint $table) {
            $table->id();

            // --- الأعمدة الأساسية ---
            $table->foreignId('client_id')->constrained('clients')->onDelete('cascade');
            $table->foreignId('project_unit_id')->constrained('project_units')->onDelete('cascade');
            $table->date('contract_date');
            $table->string('status')->default('active'); // (active, completed, cancelled)

            // --- الأعمدة المالية ---
            $table->decimal('total_amount', 15, 2);
            $table->string('currency', 3);
            $table->decimal('exchange_rate', 10, 4)->default(1);
            $table->decimal('total_amount_ils', 15, 2);

            // --- أعمدة إضافية ---
            $table->text('notes')->nullable();
            $table->string('attachment')->nullable();

            $table->timestamps();
            $table->softDeletes(); // لإضافة الحذف الناعم
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contracts');
    }
};
