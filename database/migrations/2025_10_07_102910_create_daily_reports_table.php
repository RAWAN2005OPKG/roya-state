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
    Schema::create('daily_reports', function (Blueprint $table) {
        $table->id();
        $table->date('report_date')->unique(); // تاريخ التقرير (فريد)
        $table->text('achievements')->nullable(); // الإنجازات
        $table->text('issues')->nullable(); // العقبات
        $table->text('decisions')->nullable(); // القرارات
        $table->timestamps();
    });
}


    /** 
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('daily_reports');
    }
};
