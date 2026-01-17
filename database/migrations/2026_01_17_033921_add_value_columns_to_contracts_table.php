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
            // استخدام after('subcontractor_id') للتأكد من أن الأعمدة السابقة موجودة
            if (!Schema::hasColumn('contracts', 'contract_value')) {
                $table->decimal('contract_value', 15, 2)->after('subcontractor_id');
            }
            if (!Schema::hasColumn('contracts', 'currency')) {
                $table->string('currency', 3)->after('contract_value');
            }
            if (!Schema::hasColumn('contracts', 'exchange_rate')) {
                $table->decimal('exchange_rate', 10, 4)->default(1.0000)->after('currency');
            }
            // ===== الإضافة الجديدة والمهمة هنا =====
            if (!Schema::hasColumn('contracts', 'contract_details')) {
                $table->text('contract_details')->nullable()->after('exchange_rate');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('contracts', function (Blueprint $table) {
            // التأكد من أن الأعمدة موجودة قبل محاولة حذفها
            $columns_to_drop = [];
            if (Schema::hasColumn('contracts', 'contract_value')) $columns_to_drop[] = 'contract_value';
            if (Schema::hasColumn('contracts', 'currency')) $columns_to_drop[] = 'currency';
            if (Schema::hasColumn('contracts', 'exchange_rate')) $columns_to_drop[] = 'exchange_rate';
            if (Schema::hasColumn('contracts', 'contract_details')) $columns_to_drop[] = 'contract_details';

            if (!empty($columns_to_drop)) {
                $table->dropColumn($columns_to_drop);
            }
        });
    }
};
