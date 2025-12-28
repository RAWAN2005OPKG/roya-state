<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // إضافة عمود للمخزون، من نوع عدد صحيح، قيمته الافتراضية 0
            $table->integer('stock')->default(0)->after('name'); // يمكنك تغيير after('name') لوضعه بعد عمود آخر
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('stock');
        });
    }
};
