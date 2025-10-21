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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            // Invoice Information

            // 🔹 Invoice Information
            $table->string('invoice_no')->unique();
            $table->date('invoice_date')->nullable();
            $table->string('department')->nullable();
            $table->date('prof_date')->nullable();
            $table->date('account_date')->nullable();
            $table->date('due_date')->nullable();

            // 🔹 Project Relation (ربط مع جدول المشاريع)
            $table->foreignId('project_id')->constrained('projects')->onDelete('cascade');

            // 🔹 Customer Relation (ربط مع جدول العملاء)
            $table->foreignId('customer_id')->constrained('customers')->onDelete('cascade');

            // 🔹 Project & Contract Details
            $table->string('project_code')->nullable();
            $table->string('project_name')->nullable();
            $table->string('contract_no')->nullable();
            $table->string('project')->nullable(); // project description/details

            // 🔹 Customer Information
            $table->string('customer_id_ref')->nullable(); // internal ref like "AAMC-5"
            $table->string('account_no')->nullable();
            $table->string('trn_no')->nullable();
            $table->string('location')->nullable();

            // 🔹 Contact Information
            $table->string('account_manager')->nullable();
            $table->string('contact_person')->nullable();
            $table->string('attn_to')->nullable();
            $table->string('attn_pos')->nullable();
            $table->string('address_email')->nullable();

            // 🔹 Terms & Other Controls
            $table->string('payment_terms')->nullable();
            $table->string('payment_method')->nullable();
            $table->string('vat_profile')->nullable();
            $table->decimal('discount_pct', 5, 2)->default(0);
            $table->decimal('sales_tax_pct', 5, 2)->default(15);
            $table->decimal('retention_pct', 5, 2)->default(0);
            $table->string('currency')->default('SAR');

            // 🔹 الحسابات (Financial Info)
            $table->decimal('net_amount', 10, 2)->default(0);
            $table->decimal('vat_amount', 10, 2)->default(0);
            $table->decimal('total_due', 10, 2)->default(0);
            $table->string('status')->default('Draft');
            $table->integer('items_count')->default(0);


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
