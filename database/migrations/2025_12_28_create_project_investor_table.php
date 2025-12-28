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
        Schema::create('project_investor', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->onDelete('cascade');
            $table->foreignId('investor_id')->constrained()->onDelete('cascade');
            $table->decimal('investment_percentage', 5, 2)->comment('نسبة الاستثمار في المشروع');
            $table->decimal('invested_amount', 15, 2)->default(0)->comment('المبلغ المستثمر فعلياً');
            $table->text('notes')->nullable();

            $table->unique(['project_id', 'investor_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_investor');
    }
};
