<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('general_contracts', function (Blueprint $table) {
            $table->id();
            $table->morphs('contractable'); // سيضيف contractable_id و contractable_type
            $table->foreignId('project_id')->nullable()->constrained()->onDelete('set null');
            $table->date('contract_date');
            $table->decimal('contract_value', 15, 2);
            $table->string('currency', 3);
            $table->decimal('exchange_rate', 10, 4)->default(1);
            $table->text('contract_details')->nullable();
            $table->string('attachment')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('general_contracts');
    }
};
