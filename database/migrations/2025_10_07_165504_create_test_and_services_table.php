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
        Schema::create('test_and_services', function (Blueprint $table) {
       $table->id();
        $table->string('name');
        $table->string('method')->nullable();
        $table->string('unit')->nullable();
        $table->decimal('default_price', 10, 2)->default(0);
        $table->string('category')->nullable();
        $table->boolean('active')->default(true);
        $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('test_and_services');
    }
};
