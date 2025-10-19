<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reportprojects', function (Blueprint $table) {

            $table->id();
            $table->string('name'); // project_name
            $table->date('start_date')->nullable(); // due_date
            $table->string('owner_name');
            $table->string('owner_phone');
            $table->string('owner_id');
            $table->string('project_title');
            $table->string('currency', 10)->nullable(); // ils, jod, usd
            $table->decimal('apartment_price', 15, 2)->nullable();
            $table->decimal('down_payment', 15, 2)->nullable();
            $table->string('project_status', 50)->nullable(); // enum-like values stored as string

            // Payment method and details
            $table->string('payment_method')->nullable(); // نقداً/تحويل بنكي/شيك
            // Cash details
            $table->string('cash_receiver')->nullable();
            $table->string('cash_receiver_other')->nullable();
            $table->string('cash_receiver_job')->nullable();
            // Bank transfer details
            $table->string('sender_bank')->nullable();
            $table->string('sender_bank_other')->nullable();
            $table->string('sender_branch')->nullable();
            $table->string('receiver_bank')->nullable();
            $table->string('receiver_bank_other')->nullable();
            $table->string('receiver_branch')->nullable();
            $table->string('transaction_id')->nullable();
            // Cheque details
            $table->string('check_number')->nullable();
            $table->string('check_owner')->nullable();
            $table->string('check_holder')->nullable();
            $table->date('check_due_date')->nullable();
            $table->date('check_receive_date')->nullable();

            // Media
            $table->string('project_media')->nullable();

            // Estimated costs
            $table->decimal('land_cost', 15, 2)->nullable();
            $table->decimal('excavation_cost', 15, 2)->nullable();
            $table->decimal('engineers_cost', 15, 2)->nullable();
            $table->decimal('licensing_cost', 15, 2)->nullable();
            $table->decimal('materials_cost', 15, 2)->nullable();
            $table->decimal('finishing_cost', 15, 2)->nullable();
            $table->decimal('total_budget', 15, 2)->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reportprojects');
    }
};
