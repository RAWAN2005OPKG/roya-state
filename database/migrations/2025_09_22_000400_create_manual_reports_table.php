<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('manual_reports', function (Blueprint $table) {
            $table->id();
            $table->date('report_date');
            $table->text('achievements')->nullable();
            $table->text('issues')->nullable();
            $table->text('decisions')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('manual_reports');
    }
};
