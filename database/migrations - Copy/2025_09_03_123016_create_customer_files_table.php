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
        Schema::create('customer_files', function (Blueprint $table) {
            $table->id();
           $table->foreignId('customer_id')->constrained()->onDelete('cascade');
    $table->string('name');       // اسم الملف
    $table->string('type');       // نوع الملف (mime)
    $table->string('path');       // مسار التخزين
    $table->integer('size');      // الحجم بالبايت
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer_files');
    }
};
