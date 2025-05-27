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
        Schema::create('tahun_ajaran', function (Blueprint $table) {
            $table->id();
            $table->string('tahun_ajaran');
            $table->date('periode_mulai');
            $table->date('periode_selesai');
            $table->boolean('status')->default(0);
            $table->integer('semester')->default(1)->comment('1 = Ganjil, 2 = Genap');
            $table->timestamps();

            $table->unique(['tahun_ajaran', 'semester'], 'tahun_ajaran_semester_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tahun_ajaran');
    }
};
