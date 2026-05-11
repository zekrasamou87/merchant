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
    $table->decimal('rating', 5, 2)->default(0)->change(); // ليدعم رقم حتى 100
});

Schema::table('inspections', function (Blueprint $table) {
    $table->integer('rating_score')->after('result')->nullable(); // الدرجة من 100
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
