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
        if (Schema::hasColumn('stores', 'city')) {
            $table->dropColumn('city'); // التخلص من العمود المسبب للخطأ
        }
    });
}

public function down(): void
{
    Schema::table('stores', function (Blueprint $table) {
        $table->string('city')->nullable();
    });
}
};
