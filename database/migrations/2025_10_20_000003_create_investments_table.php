<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('investments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('investor_id')->constrained('investors')->cascadeOnDelete();
            $table->foreignId('project_id')->constrained('projects')->cascadeOnDelete();
            $table->date('investment_date');          
            $table->string('investment_type');       
            $table->string('currency', 10);          
            $table->decimal('amount', 15, 2);      
            $table->decimal('share_percentage', 5, 2);
            $table->enum('status', ['active', 'complete', 'draft'])->default('draft'); 
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('investments');
    }
};
