<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('investor_project', function (Blueprint $table) {
            $table->id();
            $table->foreignId('investor_id')->constrained('investors')->onDelete('cascade');
            $table->foreignId('project_id')->constrained('projects')->onDelete('cascade');

            // بيانات الاستثمار الإضافية
            $table->decimal('investment_percentage', 5, 2)->nullable();
            $table->decimal('invested_amount', 15, 2);
            $table->string('currency', 10);
            $table->text('notes')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('investor_project');
    }
};
