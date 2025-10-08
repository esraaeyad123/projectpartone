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
        Schema::create('equipment_files', function (Blueprint $table) {
              $table->id();
        $table->unsignedBigInteger('equipment_id'); // ربط الملف بالمعدة
        $table->string('name'); // اسم الملف
        $table->string('path'); // مسار التخزين
        $table->string('type')->nullable(); // نوع الملف (pdf, docx, etc.)
        $table->integer('size')->nullable(); // الحجم بالكيلوبايت
        $table->timestamps();

        // مفتاح أجنبي للمعدة
        $table->foreign('equipment_id')->references('id')->on('equipments')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('equipment_files');
    }
};
