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
        Schema::create('confirmations', function (Blueprint $table) {
            $table->id();
            $table->string('category')->nullable();
            $table->string('confirm_id')->unique(); // رقم التعميد
            $table->date('confirm_date')->nullable();

            // 🔗 العلاقات (Relations)
            $table->foreignId('project_id')->constrained('projects')->onDelete('cascade');
           $table->foreignId('customer_id')->nullable()->constrained('customers')->onDelete('set null');

            // ✳️ باقي الحقول
            $table->string('subject')->nullable();
            $table->string('conf_source')->nullable();
            $table->string('contract_no')->nullable();

            // Contact
            $table->string('contact_person')->nullable();
            $table->string('conf_to')->nullable();

            // Terms
            $table->string('currency')->default('SAR');
            $table->decimal('discount', 8, 2)->nullable();
            $table->decimal('tax', 8, 2)->default(15);
            $table->string('validity')->nullable()->default('60 Days');
            $table->string('payment_terms')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('confirmations');
    }
};
