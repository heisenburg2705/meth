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
        Schema::create('komponen_biaya', function (Blueprint $table) {
            $table->id();
            $table->string('kode');
            $table->string('nama');
            $table->unsignedBigInteger('biaya');
            $table->enum('jenis_biaya', ['pendaftaran', 'registrasi']);
            $table->boolean('untuk_alumni');
            $table->enum('jenis', ['wajib', 'tambahan']);
            $table->json('jenis_kelamin')->nullable(); // ['l', 'p']
            $table->unsignedInteger('urut')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });

         // Pivot tables
         Schema::create('komponen_biaya_gelombang', function (Blueprint $table) {
            $table->foreignId('komponen_biaya_id')->constrained('komponen_biaya')->cascadeOnDelete();
            $table->foreignId('gelombang_id')->constrained('gelombang')->cascadeOnDelete();
        });

        Schema::create('komponen_biaya_jalur', function (Blueprint $table) {
            $table->foreignId('komponen_biaya_id')->constrained('komponen_biaya')->cascadeOnDelete();
            $table->foreignId('jalur_id')->constrained('jalur')->cascadeOnDelete();
        });

        Schema::create('komponen_biaya_jalur_detail', function (Blueprint $table) {
            $table->foreignId('komponen_biaya_id')->constrained('komponen_biaya')->cascadeOnDelete();
            $table->foreignId('jalur_detail_id')->constrained('jalur_detail')->cascadeOnDelete();
        });

        Schema::create('komponen_biaya_tingkat', function (Blueprint $table) {
            $table->foreignId('komponen_biaya_id')->constrained('komponen_biaya')->cascadeOnDelete();
            $table->foreignId('tingkat_id')->constrained('tingkat')->cascadeOnDelete();
        });

        Schema::create('komponen_biaya_kelas', function (Blueprint $table) {
            $table->foreignId('komponen_biaya_id')->constrained('komponen_biaya')->cascadeOnDelete();
            $table->foreignId('kelas_id')->constrained('kelas')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('komponen_biaya_kelas');
        Schema::dropIfExists('komponen_biaya_tingkat');
        Schema::dropIfExists('komponen_biaya_jalur_detail');
        Schema::dropIfExists('komponen_biaya_jalur');
        Schema::dropIfExists('komponen_biaya_gelombang');
        Schema::dropIfExists('komponen_biaya');
    }
};
