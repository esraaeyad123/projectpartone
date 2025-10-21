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
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('employee_reference')->unique();
            $table->string('initials', 10)->nullable();
            $table->string('first_name');
            $table->string('mid_name')->nullable();
            $table->string('last_name');
            $table->string('full_name');
            $table->string('email')->unique()->nullable();
            $table->unsignedBigInteger('supervisor_id')->nullable();
            $table->string('ctta')->nullable();
            $table->string('business_unit')->nullable();
            $table->string('department')->nullable();
            $table->string('title')->nullable();
            $table->string('job_rules')->nullable(); // مثال: "Office Staff,Site Staff"
            $table->foreign('supervisor_id')->references('id')->on('employees')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
