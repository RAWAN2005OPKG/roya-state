<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('expenses', function (Blueprint $table) {
            // التأكد من وجود الأعمدة المطلوبة للربط المتعدد (Polymorphic Relationship)
            // إذا لم يكن العمود موجوداً، قم بإضافته.

            if (!Schema::hasColumn('expenses', 'payable_type')) {
                // هذا هو العمود الذي يسبب الخطأ
                $table->string('payable_type')->after('id');
            }
            if (!Schema::hasColumn('expenses', 'payable_id')) {
                $table->unsignedBigInteger('payable_id')->after('payable_type');
            }

            // إضافة SoftDeletes إذا لم يكن موجوداً، فهو مفيد دائماً
            if (!Schema::hasColumn('expenses', 'deleted_at')) {
                $table->softDeletes();
            }
        });
    }

    public function down(): void
    {
    }
};
