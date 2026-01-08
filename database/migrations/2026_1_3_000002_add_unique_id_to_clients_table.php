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
        Schema::table('clients', function (Blueprint $table) {
            // إضافة حقل فريد غير قابل للقيمة الفارغة
            $table->string('unique_id', 20)->unique()->after('id')->nullable();
        });

        // تحديث البيانات الموجودة (اختياري، لكن يفضل)
        // Client::all()->each(function ($client) {
        //     $client->unique_id = 'CL-' . str_pad($client->id, 5, '0', STR_PAD_LEFT);
        //     $client->save();
        // });

        // بعد التحديث، يمكن إزالة nullable() إذا أردت
        Schema::table('clients', function (Blueprint $table) {
            $table->string('unique_id', 20)->nullable(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->dropColumn('unique_id');
        });
    }
};
