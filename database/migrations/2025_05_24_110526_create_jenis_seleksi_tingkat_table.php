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
            $table->foreignId('jenis_seleksi_id')
                ->constrained('jenis_seleksi')
                ->onDelete('cascade');
            $table->foreignId('tingkat_id')
                ->constrained('tingkat')
                ->onDelete('cascade');
            $table->integer('urutan')->default(1);
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['jenis_seleksi_id', 'tingkat_id']);
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
