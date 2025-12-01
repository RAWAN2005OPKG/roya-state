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
        Schema::create('checks', function (Blueprint $table) {
            $table->id();
            $table->string('check_number')->unique();
            $table->string('type'); // incoming, outgoing
            $table->decimal('amount', 15, 2);
            $table->string('currency', 10);
            $table->date('due_date');
            $table->string('status')->default('in_wallet'); // in_wallet, cashed, returned
            $table->string('holder_name')->nullable(); // اسم صاحب الشيك
            $table->string('bank_name'); // بنك الشيك
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('checks');
    }
};
