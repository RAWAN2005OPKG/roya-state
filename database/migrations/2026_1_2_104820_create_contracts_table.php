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
            $table->morphs('contractable');
            $table->foreignId('project_id')->nullable()->constrained('projects')->onDelete('set null');
            $table->date('contract_date');
            $table->text('contract_details')->nullable();
            $table->string('attachment')->nullable();
            $table->decimal('investment_amount', 15, 2);
            $table->string('currency', 10);
            $table->decimal('exchange_rate', 10, 4)->default(1.0000);
            $table->decimal('investment_amount_ils', 15, 2);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contracts');
    }
};
