<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('waleed_transactions', function (Blueprint $table) {
            // هذه الدالة تضيف عمود `deleted_at` من نوع timestamp ويقبل القيم الفارغة
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::table('waleed_transactions', function (Blueprint $table) {
            // هذه الدالة تقوم بحذف عمود `deleted_at` عند التراجع
            $table->dropSoftDeletes();
        });
    }
};
