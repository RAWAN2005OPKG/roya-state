<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reportprojects', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('project_title');
            $table->string('owner_name');
            $table->string('project_status');
            $table->decimal('total_budget', 15, 2);
            $table->string('currency');
            $table->text('description')->nullable();
            $table->text('additional_info')->nullable();
            $table->json('files')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reportprojects');
    }
};
