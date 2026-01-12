<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('subcontractors', function (Blueprint $table) {
            $table->id();
            $table->string('unique_id')->unique()->nullable(); // رقم فريد مثل العملاء
            $table->string('name');
            $table->string('specialization');
            $table->string('id_number')->nullable()->unique();
            $table->string('phone')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes(); // لإضافة ميزة سلة المحذوفات
        });
    }
    public function down(): void { Schema::dropIfExists('subcontractors'); }
};
