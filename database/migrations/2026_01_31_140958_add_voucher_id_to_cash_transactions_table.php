<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('cash_transactions', function (Blueprint $table) {
            // إضافة رقم السند، ونجعله فريداً وقابلاً للبحث السريع
            $table->string('voucher_id')->unique()->nullable();
        });
    }

    public function down(): void {
        Schema::table('cash_transactions', function (Blueprint $table) {
            $table->dropColumn('voucher_id');
        });
    }
};
