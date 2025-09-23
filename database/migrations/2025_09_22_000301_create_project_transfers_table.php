<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('project_transfers', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->string('name');
            $table->string('id_number')->nullable();
            $table->string('phone')->nullable();
            $table->unsignedBigInteger('from_project_id');
            $table->unsignedBigInteger('to_project_id');
            $table->string('currency', 10)->default('شيكل');
            $table->decimal('amount', 15, 2);
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('project_transfers');
    }
};
