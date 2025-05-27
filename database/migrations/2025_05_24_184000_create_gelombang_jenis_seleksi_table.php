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
        Schema::create('gelombang_jenis_seleksi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('gelombang_id')->constrained('gelombang')->cascadeOnDelete();
            $table->foreignId('jenis_seleksi_id')->constrained('jenis_seleksi')->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['gelombang_id', 'jenis_seleksi_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gelombang_jenis_seleksi');
    }
};
