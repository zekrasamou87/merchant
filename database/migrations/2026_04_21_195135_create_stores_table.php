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
    Schema::create('stores', function (Blueprint $table) {
        $table->id();
        $table->string('store_name');
        $table->string('owner_name');
        $table->string('manager_name')->nullable();
        $table->string('business_type');
        $table->enum('sale_type', ['wholesale', 'retail'])->default('retail');
        
        $table->string('province')->default('اللاذقية');
        $table->string('city');
        $table->string('neighborhood');
        $table->text('address_text');
        $table->decimal('latitude', 10, 8)->nullable();
        $table->decimal('longitude', 11, 8)->nullable();
        
        $table->string('phone_number')->nullable();
        $table->time('opening_time')->nullable();
        $table->time('closing_time')->nullable();
        $table->boolean('delivery_service')->default(false);
        
        $table->string('national_id')->nullable();
        $table->string('property_number')->nullable();
        $table->string('license_number')->unique();
        
        $table->boolean('is_active')->default(true);
        $table->decimal('rating', 3, 2)->default(0);
        $table->string('qr_code')->unique()->nullable();
        $table->date('status_start_date')->nullable();
        $table->date('status_end_date')->nullable();
        
        $table->text('notes')->nullable();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stores');
    }
};
