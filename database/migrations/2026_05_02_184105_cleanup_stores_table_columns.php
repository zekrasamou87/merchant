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
   Schema::table('stores', function (Blueprint $table) {
        // نتحقق من وجود كل عمود قبل محاولة حذفه لتجنب توقف الـ Migration
        
        if (Schema::hasColumn('stores', 'address_text')) {
            $table->dropColumn('address_text');
        }

        if (Schema::hasColumn('stores', 'city')) {
            $table->dropColumn('city');
        }

        if (Schema::hasColumn('stores', 'business_type')) {
            $table->dropColumn('business_type');
        }
    });
}

public function down(): void
{
    Schema::table('stores', function (Blueprint $table) {
        $table->text('address_text')->nullable();
        $table->string('city')->nullable();
        $table->string('business_type')->nullable();
    });
}
};
