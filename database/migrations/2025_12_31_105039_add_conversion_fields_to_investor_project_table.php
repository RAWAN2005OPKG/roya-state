<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('investor_project', function (Blueprint $table) {
            // نضيف الحقول بعد حقل العملة لترتيبها
            $table->after('currency', function ($table) {
                $table->decimal('exchange_rate', 10, 4)->default(1.0000); // سعر الصرف المستخدم
                $table->decimal('invested_amount_ils', 15, 2); // المبلغ المحول للشيكل
            });
        });
    }

    public function down(): void
    {
        Schema::table('investor_project', function (Blueprint $table) {
            $table->dropColumn(['exchange_rate', 'invested_amount_ils']);
        });
    }
};
