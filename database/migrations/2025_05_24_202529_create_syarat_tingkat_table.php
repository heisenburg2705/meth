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
        Schema::create('syarat_tingkat_select', function (Blueprint $table) {
            $table->id();
            $table->foreignId('syarat_id')->constrained('syarat')->cascadeOnDelete();
            $table->foreignId('tingkat_id')->constrained('tingkat')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('syarat_tingkat_select');
    }
};
