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
        Schema::create('tests', function (Blueprint $table) {
           $table->bigIncrements('id');
            $table->string('test_code')->unique();
            $table->string('short_name');
            $table->string('service_group');
            $table->string('department')->nullable();
            $table->boolean('generate_report')->default(false);
            $table->text('description')->nullable();
            $table->string('type')->nullable();
            $table->string('activity_type')->nullable();
            $table->date('date_added')->nullable();
            $table->string('location')->nullable();
            $table->string('test_method')->nullable();
            $table->string('template_name')->nullable();
            $table->string('template_type')->nullable();
            $table->string('file_template')->nullable();
            $table->string('report_designation')->nullable();
            $table->string('report_title')->nullable();
            $table->string('built_in_template')->nullable();
            $table->string('element')->nullable();
            $table->string('uncertainty')->nullable();
            $table->boolean('use_uncertainty')->default(false);
            $table->decimal('unit_price', 10, 2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tests');
    }
};
