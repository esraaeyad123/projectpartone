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
        Schema::create('deliveries', function (Blueprint $table) {
              $table->id();
    $table->string('delivery_no')->unique();
    $table->date('delivery_date')->nullable();
    $table->string('department')->nullable();

    // علاقة بالمشروع
    $table->unsignedBigInteger('project_id')->nullable();
    $table->string('project_code')->nullable();
    $table->string('project_no')->nullable();
    $table->string('project_name')->nullable();
    $table->text('project_details')->nullable();

    // علاقة بالعميل
    $table->unsignedBigInteger('customer_id')->nullable();
    $table->string('customer_id_ref')->nullable();
    $table->string('account_no')->nullable();
    $table->string('location')->nullable();

    // معلومات الاتصال
    $table->string('contact_person')->nullable();
    $table->string('attn_to')->nullable();
    $table->string('attn_pos')->nullable();
    $table->string('address_email')->nullable();

    // معلومات التسليم
    $table->string('prepared_by')->nullable();
    $table->string('delivered_by')->nullable();
    $table->string('received_by')->nullable();
    $table->date('date_received')->nullable();

    // ✅ حالة التسليم
    $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');

    $table->timestamps();

    // العلاقات
    $table->foreign('customer_id')->references('id')->on('customers')->onDelete('set null');
    $table->foreign('project_id')->references('id')->on('projects')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('deliveries');
    }
};
