<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // هذا هو الجدول الوسيط لتخزين العقود بين المقاولين والمشاريع
        Schema::create('project_subcontractor', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained('projects')->onDelete('cascade');
            $table->foreignId('subcontractor_id')->constrained('subcontractors')->onDelete('cascade');

            // تفاصيل العقد
            $table->decimal('contract_value', 15, 2); // قيمة العقد
            $table->string('currency', 10);
            $table->decimal('exchange_rate', 10, 4)->default(1.0000);
            $table->decimal('contract_value_ils', 15, 2); // قيمة العقد بالشيكل
            $table->text('contract_details')->nullable();
            $table->date('contract_date');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('project_subcontractor');
    }
};
