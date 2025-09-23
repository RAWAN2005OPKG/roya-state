<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('fund_transfers', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->string('from_account', 50);
            $table->string('to_account', 50);
            $table->string('name');
            $table->string('id_number')->nullable();
            $table->string('phone')->nullable();
            $table->string('currency', 10)->default('شيكل');
            $table->decimal('amount', 15, 2);
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fund_transfers');
    }
};
