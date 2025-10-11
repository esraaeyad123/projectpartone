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
        Schema::create('confirmation_files', function (Blueprint $table) {
                 $table->id();
            $table->unsignedBigInteger('confirmation_id');
            $table->string('name');
            $table->string('path');
            $table->string('type')->nullable();
            $table->integer('size')->nullable();
            $table->timestamps();

            $table->foreign('confirmation_id')
                  ->references('id')
                  ->on('confirmations')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('confirmation_files');
    }
};
