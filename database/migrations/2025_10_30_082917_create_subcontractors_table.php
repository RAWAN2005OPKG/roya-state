<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('subcontractors', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // اسم المقاول أو الشركة
            $table->string('service_type'); // نوع الخدمة (بناء، كهرباء، توريد مواد)
            $table->string('phone')->nullable(); // رقم الهاتف
            $table->string('contact_person')->nullable(); // اسم الشخص المسؤول للتواصل
            $table->timestamps(); // حقول created_at و updated_at
            $table->softDeletes(); // حقل deleted_at لسلة المحذوفات
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('subcontractors');
    }
};
