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
        Schema::create('samples', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
            $table->string('sample_no')->unique();
            $table->string('sample_type')->nullable();
            $table->string('sample_source')->nullable();
            $table->foreignId('lab_id')->nullable()->constrained('labs')->onDelete('set null');
            $table->foreignId('sample_type_id')->nullable()->constrained('sample_types')->onDelete('set null');
            $table->foreignId('project_id')->nullable()->constrained('projects')->nullOnDelete();
            $table->string('sample_description')->nullable();
            $table->string('rfi_wir')->nullable();
            $table->string('structure_ref')->nullable();
            $table->string('sampling_method')->nullable();
            $table->dateTime('received_at')->nullable();
            $table->integer('total_received')->default(0);
            $table->string('spec_prop_by')->nullable();
            $table->dateTime('sampled_at')->nullable();
            $table->string('received_by')->nullable();
            $table->string('sampled_by')->nullable();
            $table->dateTime('casted_at')->nullable();
            $table->string('samp_brought_by')->nullable();
            $table->string('comp_equipment')->nullable();
            $table->string('witness')->nullable();
            $table->string('mtd_of_comp')->nullable();
            $table->string('cement_content')->nullable();
            $table->string('notes')->nullable();
            $table->string('cement_type')->nullable();
            $table->string('nominal_size')->nullable();
            $table->string('class')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('samples');
    }
};
