<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
{
    Schema::create('contracts', function (Blueprint $table) {
        $table->id();

        $table->morphs('contractable');

        $table->foreignId('project_id')->nullable()->constrained('projects')->onDelete('set null');

        $table->string('contract_id')->unique();
        $table->date('signing_date');
        $table->string('status')->default('active');
        $table->decimal('investment_amount', 15, 2);
        $table->string('currency', 10)->default('ILS');
        $table->text('terms')->nullable();
        $table->json('details')->nullable();
        $table->string('attachment')->nullable();

        $table->softDeletes();
            $table->timestamps();
    });
}
    public function down(): void {
        Schema::table('contracts', function (Blueprint $table) {
            $table->dropColumn('details');
        });
    }
};
