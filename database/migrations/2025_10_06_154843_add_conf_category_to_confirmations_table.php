<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('confirmations', function (Blueprint $table) {
            // 💡 إضافة عمود جديد من نوع string باسم 'conf_category'
            $table->string('conf_category')->nullable()->after('id'); 
            // تم استخدام after('id') لوضعه مباشرة بعد العمود الرئيسي ID
        });
    }

    public function down(): void
    {
        Schema::table('confirmations', function (Blueprint $table) {
            // 💡 عند التراجع (Rollback)، احذف العمود
            $table->dropColumn('conf_category');
        });
    }
};