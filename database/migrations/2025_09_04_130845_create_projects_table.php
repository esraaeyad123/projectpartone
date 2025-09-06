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
        Schema::create('projects', function (Blueprint $table) {
        $table->id();

        // Reference يولد تلقائي مثل AAMC-1001
        $table->string('reference')->unique();

        $table->string('name');
        $table->string('arabic_name')->nullable();

        // يولد تلقائياً
        $table->date('registration_date')->nullable();

        $table->string('region')->nullable();

        // علاقات
        $table->foreignId('customer_id')->constrained('customers')->onDelete('cascade');

        // الأطراف الأخرى (اختياري)
        $table->string('owner')->nullable();
        $table->string('consultant')->nullable();
        $table->string('contractor')->nullable();

        $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
