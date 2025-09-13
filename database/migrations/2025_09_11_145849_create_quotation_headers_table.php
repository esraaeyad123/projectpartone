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
        Schema::create('quotation_headers', function (Blueprint $table) {
           $table->id();

            $table->foreignId('customer_id')->constrained('customers')->onDelete('cascade');
            $table->foreignId('project_id')->constrained('projects')->onDelete('cascade');
            $table->foreignId('contact_id')->nullable()->constrained('contacts')->onDelete('set null');

            $table->string('quote_category')->nullable();
            $table->string('quote_no');
            $table->string('rev')->nullable();
            $table->date('quote_date')->nullable();
            $table->string('legacy_no')->nullable();
            $table->date('legacy_date')->nullable();
            $table->string('subject')->nullable();
            $table->string('currency')->nullable();
            $table->decimal('discount', 10, 2)->nullable();
            $table->decimal('vat', 10, 2)->nullable();
            $table->integer('validity_days')->nullable();
            $table->string('payment_terms')->nullable();
            $table->string('method')->nullable();
            $table->text('remarks')->nullable();
            $table->string('quote_file')->nullable();
            $table->string('file_status')->nullable();
            $table->boolean('declined')->default(false);
            $table->text('declined_message')->nullable();
            $table->decimal('total_lines', 10, 2)->nullable();
            $table->decimal('discount_amount', 10, 2)->nullable();
            $table->decimal('tax_amount', 10, 2)->nullable();
            $table->decimal('grand_total', 10, 2)->nullable();
             $table->string('overall_status')->nullable();
             $table->string('last_confirmation')->nullable();
             $table->string('last_confirmed')->nullable();
             $table->boolean('use_alt_form')->default(0);
            $table->string('inquiry')->nullable();
            $table->text('project_details')->nullable();
            $table->string('contact_from')->nullable();

    $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quotation_headers');
    }
};
