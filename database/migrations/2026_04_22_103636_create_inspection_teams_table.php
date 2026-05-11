<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inspection_teams', function (Blueprint $table) {
            // ربط مع جدول الزيارات
            $table->foreignId('inspection_id')->constrained()->onDelete('cascade');
            
            // ربط مع جدول المراقبين
            $table->foreignId('inspector_id')->constrained()->onDelete('cascade');

            // جعل الزوج (زيارة + مراقب) فريد من نوعه لمنع التكرار
            $table->primary(['inspection_id', 'inspector_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inspection_teams');
    }
};