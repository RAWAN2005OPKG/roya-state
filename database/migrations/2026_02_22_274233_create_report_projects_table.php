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
        Schema::create('report_projects', function (Blueprint $table) {
            $table->id();

            // --- معلومات التقرير الأساسية ---
            $table->string('name'); // اسم المشروع/التقرير
            $table->string('project_title')->nullable(); // عنوان فرعي أو وصف مختصر

            // --- معلومات المالك ---
            $table->string('owner_name');
            $table->string('owner_phone')->nullable();
            $table->string('owner_id')->nullable(); // رقم هوية المالك

            // --- حالة المشروع وتواريخه ---
            $table->string('project_status')->nullable();
            $table->date('start_date')->nullable();

            // --- المعلومات المالية ---
            $table->decimal('total_budget', 15, 2)->nullable(); // الميزانية الإجمالية
            $table->string('currency', 10)->nullable();

            // --- معلومات إضافية ---
            $table->text('description')->nullable(); // وصف تفصيلي
            $table->string('project_media')->nullable(); // مسار الصورة أو الفيديو المرفق

            $table->timestamps(); // تنشئ created_at و updated_at
            $table->softDeletes(); // لدعم سلة المحذوفات
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('report_projects');
    }
};
