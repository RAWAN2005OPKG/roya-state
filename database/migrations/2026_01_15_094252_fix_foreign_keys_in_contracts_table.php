<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('contracts', function (Blueprint $table) {
            // --- 1. إصلاح عمود الربط مع المشاريع (Projects) ---
            if (!Schema::hasColumn('contracts', 'project_id')) {
                $table->foreignId('project_id')
                      ->nullable()
                      ->after('id') // وضعه في البداية للترتيب
                      ->constrained('projects')
                      ->onDelete('cascade');
            }

            // --- 2. إصلاح عمود الربط مع الموردين (Subcontractors) ---
            if (!Schema::hasColumn('contracts', 'subcontractor_id')) {
                // سيتم إضافة هذا العمود بعد عمود project_id الذي تأكدنا من وجوده
                $table->foreignId('subcontractor_id')
                      ->nullable()
                      ->after('project_id')
                      ->constrained('subcontractors')
                      ->onDelete('cascade');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('contracts', function (Blueprint $table) {
            if (Schema::hasColumn('contracts', 'project_id')) {
                $table->dropForeign(['project_id']);
                $table->dropColumn('project_id');
            }
            if (Schema::hasColumn('contracts', 'subcontractor_id')) {
                $table->dropForeign(['subcontractor_id']);
                $table->dropColumn('subcontractor_id');
            }
        });
    }
};
