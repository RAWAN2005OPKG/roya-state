<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contracts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained('clients')->onDelete('cascade');
            $table->foreignId('project_unit_id')->constrained('project_units')->onDelete('cascade');
            $table->date('contract_date');
            $table->decimal('contract_value', 15, 2);
            $table->string('currency', 3);
            $table->decimal('exchange_rate', 10, 4)->default(1.0000);
            $table->decimal('total_amount_ils', 15, 2);
            $table->decimal('down_payment', 15, 2)->default(0);
            $table->enum('status', ['active', 'completed', 'cancelled'])->default('active');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contracts');
    }
};
