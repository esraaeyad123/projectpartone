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
        Schema::create('crushing_tests', function (Blueprint $table) {
        $table->id();

// العلاقة مع العينة
$table->foreignId('sample_id')->constrained('samples')->onDelete('cascade');

// بيانات الاختبار
$table->foreignId('test_method_id')->nullable()->constrained('test_methods')->onDelete('set null');
$table->foreignId('test_location_id')->nullable()->constrained('test_locations')->onDelete('set null');

$table->integer('r_age')->nullable();
$table->integer('a_age')->nullable();
$table->decimal('price', 10, 2)->nullable();
$table->integer('qty')->nullable();
$table->date('scheduled_at')->nullable();
$table->integer('rem')->nullable();
$table->string('report_no')->nullable();
$table->string('rev')->nullable();
$table->string('items')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('crushing_tests');
    }
};
