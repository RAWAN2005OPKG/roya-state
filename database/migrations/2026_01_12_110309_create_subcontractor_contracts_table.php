<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('subcontractor_contracts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('subcontractor_id')->constrained()->onDelete('cascade');
            $table->foreignId('project_id')->constrained()->onDelete('cascade');
            $table->date('contract_date');
            $table->decimal('contract_value', 15, 2);
            $table->string('currency', 3);
            $table->decimal('exchange_rate', 10, 4);
            $table->decimal('value_in_ils', 15, 2);
            $table->text('contract_details')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('subcontractor_contracts'); }
};
