<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up() {
    Schema::create('inspections', function (Blueprint $table) {
        $table->id();
        $table->foreignId('store_id')->constrained()->onDelete('cascade');
        $table->dateTime('inspection_date');
        $table->enum('result', ['committed', 'needs_correction', 'violating']);
        $table->text('notes')->nullable();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inspections');
    }
};
