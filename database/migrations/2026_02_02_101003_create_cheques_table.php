<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('checks', function (Blueprint $table) {
            $table->id();
            $table->string('check_number')->unique(); // رقم الشيك

            // 1. التفاصيل الأساسية
            $table->enum('type', ['payable', 'receivable']); // نوع الشيك: دفع (صادر) أو قبض (وارد)
            $table->enum('status', ['pending', 'cashed', 'returned', 'cancelled'])->default('pending'); // الحالة: بالانتظار، تم صرفه، مرتجع، ملغي
            $table->date('issue_date'); // تاريخ تحرير الشيك
            $table->date('due_date'); // تاريخ الاستحقاق

            // 2. تفاصيل العميل/المستفيد
            $table->string('party_name'); // اسم العميل أو المورد أو المستفيد
            $table->string('party_phone')->nullable(); // رقم هاتف الطرف الآخر

            // 3. التفاصيل المالية
            $table->decimal('amount', 15, 2);
            $table->string('currency', 3);
            $table->decimal('exchange_rate', 10, 4)->default(1.0000);
            $table->decimal('amount_ils', 15, 2); // المبلغ بالشيكل (محسوب تلقائياً)

            // 4. تفاصيل البنك
            $table->string('bank_name'); // اسم بنك الشيك (البنك المسحوب عليه)
            // الحساب البنكي الذي سيتم إيداع الشيك فيه (في حالة شيكات القبض)
            $table->foreignId('deposit_bank_account_id')->nullable()->constrained('bank_accounts');
            // الحساب البنكي الذي صدر منه الشيك (في حالة شيكات الدفع)
            $table->foreignId('payment_bank_account_id')->nullable()->constrained('bank_accounts');

            // 5. الربط والتواقيع
            $table->foreignId('project_id')->nullable()->constrained('projects');
            $table->foreignId('project_unit_id')->nullable()->constrained('project_units');
            $table->string('payer_signature')->nullable(); // توقيع الدافع (يمكن تخزين مسار صورة)
            $table->string('recipient_signature')->nullable(); // توقيع المستلم (يمكن تخزين مسار صورة)
            $table->text('notes')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('checks');
    }
};
