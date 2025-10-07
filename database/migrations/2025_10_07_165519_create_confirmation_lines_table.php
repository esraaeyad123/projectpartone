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
        Schema::create('confirmation_lines', function (Blueprint $table) {
        $table->id();
        $table->foreignId('confirmation_id')->constrained('confirmations')->onDelete('cascade');
        $table->foreignId('service_id')->nullable()->constrained('test_and_services')->onDelete('set null');
        $table->string('service_name')->nullable();
        $table->string('method')->nullable();
        $table->string('unit')->nullable();
        $table->decimal('price', 10, 2)->default(0);
        $table->boolean('price_only')->default(false);
        $table->integer('quantity')->default(1);
        $table->decimal('total', 12, 2)->default(0);
        $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('confirmation_lines');
    }
};
