<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * تشغيل الهجرة (حذف العمود بأمان)
     */
    public function up(): void
    {
        Schema::table('confirmations', function (Blueprint $table) {
            // 💡 التحقق أولاً: احذف العمود فقط إذا كان موجودًا
            if (Schema::hasColumn('confirmations', 'conf_desc')) {
                $table->dropColumn('conf_desc');
            }
        });
    }

    /**
     * التراجع عن الهجرة (إعادة إضافة العمود للاحتياط)
     */
    public function down(): void
    {
        Schema::table('confirmations', function (Blueprint $table) {
            // إضافة العمود للاحتياط في حال التراجع
            $table->string('conf_desc')->nullable(); 
        });
    }
};