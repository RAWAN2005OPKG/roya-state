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
        Schema::table('investors', function (Blueprint $table) {
            $table->string('unique_id', 20)->unique()->after('id')->nullable();
        });

        //تحديث البيانات الموجودة
         Investor::all()->each(function ($investor) {
           $investor->unique_id = 'IN-' . str_pad($investor->id, 5, '0', STR_PAD_LEFT);
            $investor->save();
         });

        Schema::table('investors', function (Blueprint $table) {
            $table->string('unique_id', 20)->nullable(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('investors', function (Blueprint $table) {
            $table->dropColumn('unique_id');
        });
    }
};
