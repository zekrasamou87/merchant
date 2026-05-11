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
    Schema::create('application_requests', function (Blueprint $table) {
        $table->id();
        $table->foreignId('store_id')->constrained()->onDelete('cascade');
        $table->string('incoming_number'); // رقم الوارد
        $table->date('application_date');
        $table->enum('status', ['pending', 'accepted', 'rejected'])->default('pending');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('application_requests');
    }
};
