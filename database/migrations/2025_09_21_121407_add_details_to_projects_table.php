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
        Schema::table('projects', function (Blueprint $table) {
            // حقول مالك المشروع
            $table->string('owner_name');
            $table->string('owner_phone');
            $table->string('owner_id');
    
            // حقول تفاصيل المشروع
            $table->string('project_title');
            $table->string('currency', 10);
            $table->decimal('apartment_price', 15, 2);
            $table->decimal('down_payment', 15, 2);
    
            // حقول تفاصيل الدفع (ستكون نصية لتخزين JSON أو وصف)
            $table->string('payment_method')->nullable();
            $table->text('payment_details')->nullable(); // لتخزين تفاصيل الشيك/البنك/النقد
    
            // حقل لتخزين مسار الصورة/الفيديو
            $table->string('media_path')->nullable();
    
            // تغيير اسم status ليكون بالإنجليزية
            $table->renameColumn('status', 'project_status');
        });
    }
    
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            //
        });
    }
};
