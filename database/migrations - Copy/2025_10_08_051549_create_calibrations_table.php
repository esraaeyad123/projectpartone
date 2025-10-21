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
    Schema::create('calibrations', function (Blueprint $table) {
    $table->id();
    $table->unsignedBigInteger('equipment_id'); // ربط بالـ Equipment
    $table->string('calib_method')->nullable();
    $table->string('calib_procedure_no')->nullable();
    $table->date('last_calib_date')->nullable();
    $table->date('scheduled')->nullable(); // هذا الحقل الجديد
    $table->date('next_calib_date')->nullable();
    $table->boolean('reminder')->default(false);
    $table->boolean('has_next_calibration')->default(false);
    $table->boolean('only_one')->default(false); // هذا الحقل الجديد
    $table->boolean('calibrated_externally')->default(false);
    $table->string('provider')->nullable();
    $table->string('internally_by')->nullable();
    $table->string('last_certificate')->nullable();
    $table->enum('calibration_status', ['completed', 'scheduled', 'overdue'])->default('scheduled');
    $table->foreign('equipment_id')->references('id')->on('equipments')->onDelete('cascade');
    $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('calibrations');
    }
};
