<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subcontractors', function (Blueprint $table) {
            $table->id();
            $table->string('unique_id')->unique()->nullable(); // رقم تعريفي فريد
            $table->string('name'); // اسم المقاول أو المورد
            $table->string('id_number')->unique()->nullable(); // رقم الهوية أو رقم الشركة
            $table->string('phone')->nullable();
            $table->string('specialization'); // مجال التخصص (بناء، كهرباء، توريد مواد، إلخ)
            $table->text('notes')->nullable();
            $table->softDeletes(); // لدعم الحذف الناعم
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subcontractors');
    }
};
