<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up(): void
{
    Schema::create('investors', function (Blueprint $table) {
        $table->id();
        $table->string('unique_id')->unique();
        $table->string('name');
        $table->string('company')->nullable();
        $table->string('id_number')->nullable()->unique();
        $table->string('phone')->nullable();
        $table->text('notes')->nullable();
        $table->timestamps();
        $table->softDeletes();
    });
}


    public function down(): void
    {
        Schema::dropIfExists('investors');
    }
};
