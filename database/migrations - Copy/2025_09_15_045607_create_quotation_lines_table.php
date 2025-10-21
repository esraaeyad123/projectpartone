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
        Schema::create('quotation_lines', function (Blueprint $table) {
            $table->id();
               $table->foreignId('quotation_id')
          ->constrained('quotation_headers')
          ->onDelete('cascade');

    // لو فيه ربط مع جدول الأسعار
    $table->foreignId('price_list_id')
          ->nullable()
          ->constrained('price_lists')
          ->onDelete('set null');

    $table->string('description')->nullable();
    $table->string('category')->nullable();
    $table->string('type')->nullable();
    $table->string('method')->nullable();
    $table->integer('quantity')->default(1);
    $table->decimal('price', 10, 2)->default(0);
    $table->boolean('price_only')->default(false);
    $table->decimal('total', 10, 2)->default(0);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quotation_lines');
    }
};
