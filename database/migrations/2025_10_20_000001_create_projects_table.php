<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('project_name');
            $table->string('project_title')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->string('currency')->nullable();
            $table->decimal('apartment_price', 15, 2)->nullable();
            $table->decimal('down_payment', 15, 2)->nullable();
            $table->decimal('budget', 15, 2)->nullable();
            $table->string('project_status')->nullable();
            $table->string('project_media')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
