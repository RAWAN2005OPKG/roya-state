<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bank_accounts', function (Blueprint $table) {
            $table->id();
            // الربط مع جدول البنوك
            $table->foreignId('bank_id')->constrained('banks')->onDelete('cascade');
            $table->string('account_name');
            $table->string('account_number')->unique();
            $table->string('iban')->nullable()->unique();
            $table->string('currency', 10);
            $table->decimal('current_balance', 15, 2)->default(0.00);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bank_accounts');
    }
};
