<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contracts', function (Blueprint $table) {
            $table->id();

            // --- 1. حقول العلاقة متعددة الأشكال (Polymorphic) ---
            // هذان الحقلان يربطان العقد بالعميل أو المستثمر أو المقاول
            $table->morphs('contractable'); // ينشئ عمودين: contractable_id و contractable_type

            // --- 2. تفاصيل العقد الأساسية ---
            $table->string('contract_id')->unique(); // رقم العقد الفريد
            $table->foreignId('project_id')->nullable()->constrained('projects')->nullOnDelete(); // المشروع المرتبط
            $table->date('signing_date'); // تاريخ التوقيع
            $table->decimal('investment_amount', 15, 2); // قيمة العقد
            $table->string('currency', 10)->default('ILS'); // العملة
            $table->string('status')->default('draft'); // (نشط, مسودة, مكتمل, ملغي)
            $table->text('terms')->nullable(); // الشروط والأحكام
            $table->string('attachment')->nullable(); // مسار ملف العقد المرفق

            // --- 3. حقول خاصة بكل نوع عقد (JSON لتوفير المرونة) ---
            // استخدام حقل JSON واحد لتخزين البيانات الإضافية يمنع وجود أعمدة فارغة كثيرة
            $table->json('details')->nullable();

            $table->timestamps();
            $table->softDeletes(); // لإضافة ميزة الحذف المبدئي
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contracts');
    }
};
