<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // نقوم بإنشاء الجدول فقط إذا لم يكن موجوداً لتجنب الأخطاء
        if (!Schema::hasTable('accounts')) {
            Schema::create('accounts', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('code')->unique();
                $table->string('type'); // asset, liability, equity, revenue, expense

                // --- هذا هو التعديل المهم ---
                $table->boolean('is_main')->default(false); // رئيسي أم فرعي

                $table->foreignId('parent_id')->nullable()->constrained('accounts')->onDelete('cascade');
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('accounts');
    }
};
