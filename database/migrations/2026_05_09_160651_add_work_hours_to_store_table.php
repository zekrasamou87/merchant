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
        // تأكد من اسم الجدول هنا (stores) حسب ملف الـ Model الذي أرسلته
        Schema::table('stores', function (Blueprint $table) {
            $table->string('off_days')->nullable()->after('closing_time'); 
            $table->boolean('is_always_open')->default(false)->after('off_days');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stores', function (Blueprint $table) {
            // حذف الحقول في حال التراجع عن الـ Migration
            $table->dropColumn(['off_days', 'is_always_open']);
        });
    }
};
