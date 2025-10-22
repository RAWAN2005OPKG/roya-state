<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('investments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('investor_id')->constrained('investors')->onDelete('cascade');
            $table->foreignId('project_id')->constrained('projects')->onDelete('cascade');
            $table->date('investment_date')->nullable();
            $table->decimal('amount', 15, 2)->default(0);
            $table->string('investment_type')->nullable();
            $table->string('currency')->default('usd');
            $table->decimal('share_percentage', 5, 2)->nullable();
            $table->enum('status', ['active', 'draft'])->default('draft');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('investments');
    }
};
