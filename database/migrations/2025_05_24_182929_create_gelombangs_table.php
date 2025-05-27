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
        Schema::create('gelombang', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tahun_ajaran_id')->constrained('tahun_ajaran')->onDelete('restrict');
            $table->foreignId('tingkat_id')->constrained('tingkat')->onDelete('restrict');

            $table->string('nama');
            $table->string('kode');

            $table->date('tanggal_awal');
            $table->date('tanggal_akhir');

            $table->date('registrasi_ulang_awal')->nullable();
            $table->date('registrasi_ulang_akhir')->nullable();

            $table->date('tanggal_awal_seleksi')->nullable();
            $table->date('tanggal_akhir_seleksi')->nullable();
            $table->date('tanggal_pengumuman')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->unique(['tahun_ajaran_id', 'kode']);
            $table->unique(['tahun_ajaran_id', 'nama']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gelombang');
    }
};
