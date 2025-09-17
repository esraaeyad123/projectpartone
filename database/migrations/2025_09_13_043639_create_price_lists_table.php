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
        Schema::create('price_lists', function (Blueprint $table) {
            $table->id();
            $table->string('name');                     // اسم الاختبار / الخدمة
            $table->string('method')->nullable();       // الميثود (ASTM, BS...)
            $table->string('service_id')->unique();     // رقم الخدمة (زي LIMS-001 أو 102218)
            $table->string('unit')->nullable();         // وحدة القياس (NO., Each ...)
            $table->decimal('price', 10, 2);            // السعر الأساسي
            $table->boolean('price_only')->default(false);
            $table->integer('quantity')->default(1); // الكمية الافتراضية
            $table->boolean('active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('price_lists');
    }
};
