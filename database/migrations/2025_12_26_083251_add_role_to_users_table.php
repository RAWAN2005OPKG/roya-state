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
        Schema::table('users', function (Blueprint $table) {
            // إضافة عمود 'role' بعد عمود 'email' (للتنظيم)
            // يمكن أن يكون له قيم مثل: admin, sales, customer, ...
            // نعطيه قيمة افتراضية 'user' لمنع المشاكل مع المستخدمين الحاليين
            $table->string('role')->default('user')->after('email');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // هذا الكود يقوم بحذف العمود عند التراجع عن الـ migration
            $table->dropColumn('role');
        });
    }
};
