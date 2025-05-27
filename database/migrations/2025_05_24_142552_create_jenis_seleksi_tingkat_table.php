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
        Schema::create('jenis_seleksi_tingkat', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jenis_seleksi_id')->constrained('jenis_seleksi')->cascadeOnDelete();
            $table->foreignId('tingkat_id')->constrained('tingkat')->cascadeOnDelete();
            $table->integer('urutan');
            $table->timestamps();
        
            $table->unique(['tingkat_id', 'urutan'], 'unique_urutan_per_tingkat');
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jenis_seleksi_tingkat');
    }
};
