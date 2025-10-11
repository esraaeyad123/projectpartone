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
        Schema::create('uncertainty_records', function (Blueprint $table) {
           $table->id();
            $table->unsignedBigInteger('equipment_id');   // الربط مع المعدات
            $table->string('uncertainty')->nullable();
            $table->date('date')->nullable();
            $table->timestamps();

            // العلاقة مع الجدول الرئيسي
            $table->foreign('equipment_id')->references('id')->on('equipments')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('uncertainty_records');
    }
};
