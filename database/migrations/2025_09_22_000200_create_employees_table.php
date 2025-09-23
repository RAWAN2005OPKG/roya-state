<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('position');
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('iban')->nullable();
            $table->decimal('salary', 15, 2)->default(0);
            $table->string('currency', 10)->default('شيكل');
            // wallet
            $table->string('wallet_name')->nullable();
            $table->string('wallet_other_name')->nullable();
            // bank
            $table->string('bank_name')->nullable();
            $table->string('other_bank_name')->nullable();
            $table->string('bank_branch')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
