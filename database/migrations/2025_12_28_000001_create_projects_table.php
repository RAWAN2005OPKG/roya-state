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
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('name')->comment('اسم المشروع');
            $table->string('location')->nullable()->comment('موقع المشروع');
            $table->text('description')->nullable()->comment('وصف تفصيلي للمشروع');
            $table->date('start_date')->comment('تاريخ بدء المشروع');
            $table->date('estimated_end_date')->nullable()->comment('تاريخ الانتهاء المتوقع');
            $table->integer('duration_months')->nullable()->comment('المدة المتوقعة بالشهور');
            $table->string('main_contractor')->nullable()->comment('المقاول الرئيسي');
            $table->string('architect')->nullable()->comment('المهندس المعماري');
            $table->decimal('estimated_cost_usd', 15, 2)->default(0)->comment('التكلفة المتوقعة بالدولار');
            $table->text('notes')->nullable()->comment('ملاحظات إضافية');
            $table->json('attachments')->nullable()->comment('مصفوفة بمسارات ملفات المرفقات');
            $table->integer('completion_percentage')->default(0)->comment('نسبة الإنجاز');
            $table->enum('status', ['planning', 'in_progress', 'completed', 'on_hold'])->default('planning')->comment('حالة المشروع');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
