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
        Schema::create('equipments', function (Blueprint $table) {
            $table->id(); // id auto increment
            $table->string('equipment_reference')->unique(); // EQP-0001, EQP-0002 ...

            $table->string('alternative_id')->nullable();
            $table->string('legacy_id')->nullable();
            $table->string('description')->nullable();
            $table->string('serial_number')->nullable();
            $table->string('asset_tag')->nullable();
            $table->string('size')->nullable();

            $table->string('type')->nullable();
            $table->string('make')->nullable();
            $table->string('model')->nullable();

            $table->string('tolerance_basis')->nullable();
            $table->string('tolerance')->nullable();
            $table->string('range_capacity')->nullable();
            $table->string('range_unit')->nullable();

            $table->string('resolution')->nullable();
            $table->string('resolution_unit')->nullable();
            $table->string('traceability')->nullable();
            $table->string('display_type')->nullable();

            $table->string('manufacturer')->nullable();
            $table->string('department')->nullable();
            $table->string('custodian')->nullable();
            $table->string('location')->nullable();

            $table->string('uncertainty')->nullable();
            $table->string('uncertainty_unit')->nullable();
            $table->string('io')->nullable();

            $table->boolean('master_equipment')->default(false);
            $table->string('equipment_status')->nullable();

            // ربط مع جدول المشاريع
            $table->unsignedBigInteger('project_id')->nullable();
            $table->foreign('project_id')->references('id')->on('projects')->onDelete('set null');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('equipments');
    }
};
