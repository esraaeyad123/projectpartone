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
        Schema::create('delivery_lines', function (Blueprint $table) {
          $table->id();
        $table->unsignedBigInteger('delivery_id'); // علاقة مع delivery الرئيسي
        $table->string('name')->nullable();
        $table->string('method')->nullable();
        $table->string('unit')->nullable();
        $table->decimal('price', 10, 2)->nullable();
        $table->decimal('price_only', 10, 2)->nullable();
        $table->integer('quantity')->nullable();
        $table->timestamps();

        $table->foreign('delivery_id')->references('id')->on('deliveries')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('delivery_lines');
    }
};
