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
        Schema::create('maintenances', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('equipment_id'); // ربط مع الأجهزة

            // تواريخ الصيانة
            $table->date('last_maint_date')->nullable();
            $table->date('scheduled_date')->nullable();
            $table->date('next_maint_date')->nullable();

            // خيارات (checkboxes)
            $table->boolean('scheduled_maint')->default(false);
            $table->boolean('has_next_maint')->default(false);
            $table->boolean('reminder_maint')->default(false);
            $table->boolean('maint_externally')->default(false);
            $table->boolean('only_one')->default(false);

            // تفاصيل إضافية
            $table->string('occurrence')->nullable(); // مثل: شهري - ربع سنوي
            $table->string('maint_provider')->nullable();
            $table->string('maint_internally_by')->nullable();
            $table->enum('maint_status', ['completed', 'scheduled', 'overdue', 'pending'])->default('scheduled');

            // الربط مع جدول الأجهزة
            $table->foreign('equipment_id')->references('id')->on('equipments')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('maintenances');
    }
};
