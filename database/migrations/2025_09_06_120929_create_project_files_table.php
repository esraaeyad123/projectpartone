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
        Schema::create('project_files', function (Blueprint $table) {
             $table->id();
    $table->unsignedBigInteger('project_id');
    $table->string('name'); // اسم الملف
    $table->string('path'); // مسار التخزين
    $table->string('type')->nullable(); // نوع الملف (pdf, docx...)
    $table->integer('size')->nullable(); // الحجم بالكيلوبايت
    $table->timestamps();

    $table->foreign('project_id')->references('id')->on('projects')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_files');
    }
};
