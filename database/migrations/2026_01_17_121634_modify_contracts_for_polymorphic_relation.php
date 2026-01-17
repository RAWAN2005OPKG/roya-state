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
        Schema::table('contracts', function (Blueprint $table) {
            // الخطوة 1: جعل العمود القديم اختيارياً
            $table->unsignedBigInteger('client_id')->nullable()->change();

            // الخطوة 2: إضافة الأعمدة الجديدة للعلاقة المتعددة
            // morphs هو اختصار لـ unsignedBigInteger('contractable_id') و string('contractable_type')
            $table->morphs('contractable');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('contracts', function (Blueprint $table) {
            // لا يمكن التراجع عن جعل العمود nullable بسهولة، لذا سنركز على حذف الأعمدة الجديدة
            $table->dropMorphs('contractable');
        });
    }
};
