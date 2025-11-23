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
        Schema::create('accounts', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // اسم الحساب (مثال: النقدية، البنك، مصاريف كهرباء)
            $table->string('code')->unique(); // كود الحساب المحاسبي
            $table->enum('type', ['asset', 'liability', 'equity', 'revenue', 'expense']); // نوع الحساب الرئيسي
            $table->foreignId('parent_id')->nullable()->constrained('accounts')->onDelete('set null'); // الحساب الأب (لعمل الشجرة)
            $table->boolean('is_active')->default(true); // هل الحساب نشط؟
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accounts');
    }
};
