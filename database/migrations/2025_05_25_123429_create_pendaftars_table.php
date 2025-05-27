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
        Schema::create('pendaftar', function (Blueprint $table) {
            $table->id();
            
            // Foreign keys untuk merujuk ke tabel master
            $table->foreignId('tingkat_id')->constrained('tingkat')->nullable();
            $table->foreignId('gelombang_id')->constrained('gelombang')->nullable();
            $table->foreignId('kelas_id')->constrained('kelas')->nullable();
            $table->foreignId('jalur_id')->constrained('jalur')->nullable();
            $table->foreignId('sub_jalur_id')->constrained('jalur_detail')->nullable();
            $table->foreignId('tahun_id')->constrained('tahun_ajaran')->nullable();
            
            // Foreign key untuk orang tua
            $table->foreignId('orang_tua_id')->constrained('orang_tua');
            
            // Kolom-kolom lainnya
            $table->string('nama_lengkap');
            $table->string('tempat_lahir');
            $table->date('tanggal_lahir');
            $table->enum('jenis_kelamin', ['Laki-Laki', 'Perempuan']);
            $table->string('asal_sekolah')->nullable();
            $table->string('nama_ayah')->nullable();
            $table->string('nama_ibu')->nullable();
            $table->string('nomor_hp_ayah')->nullable();
            $table->string('nomor_hp_ibu')->nullable();
            $table->timestamps();
            
            // Kolom Soft Delete
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pendaftar');
    }
};
