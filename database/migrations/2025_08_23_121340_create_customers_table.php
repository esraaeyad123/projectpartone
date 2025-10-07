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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('customer_id')->unique(); // AAMC-xxxxx
            $table->string('customer_name');          // Customer Name
            $table->string('arabic_name')->nullable(); // Arabic Name
            $table->string('customer_legal_name')->nullable(); // Legal Name
            $table->enum('customer_type', ['Contractor','Consultant','Supplier','Private','Owner','Other','Governmental'])->nullable();
            $table->boolean('potential')->default(false);
            $table->string('legacy_acc_no')->nullable();
            $table->date('date_registered')->nullable();
            $table->string('phone')->nullable();
            $table->string('country')->nullable();
            $table->string('arabic_location')->nullable();
            $table->string('city')->nullable();
            $table->string('district')->nullable();
            $table->string('street')->nullable();
            $table->string('post_code')->nullable();
            $table->string('address_block')->nullable();
            $table->string('po_box')->nullable();
            $table->string('building_no')->nullable();
            $table->string('payment_terms')->nullable();
            $table->decimal('discount', 10, 2)->default(0);
            $table->boolean('cash')->default(false);
            $table->decimal('credit_limit', 15, 2)->default(0);
            $table->string('vat_profile')->nullable()->default('Standard VAT');
            $table->string('trn_tin')->nullable();
            $table->string('registration_no')->nullable();
            $table->boolean('restrict_deliveries')->default(false);
            $table->boolean('restrict_orders')->default(false);
            $table->boolean('restrict_quotations')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
