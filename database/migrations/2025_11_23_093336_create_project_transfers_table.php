<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('project_transfers', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->foreignId('expense_id')->constrained('expenses'); // افترض أن جدول المصاريف اسمه expenses
            $table->foreignId('from_project_id')->constrained('projects'); // افترض أن جدول المشاريع اسمه projects
            $table->foreignId('to_project_id')->constrained('projects');
            $table->decimal('amount', 15, 2);
            $table->text('reason');
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('project_transfers');
    }
};
