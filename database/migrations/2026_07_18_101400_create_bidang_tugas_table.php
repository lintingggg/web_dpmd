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
        Schema::create('bidang_tugas', function (Blueprint $table) {
            $table->id();
            $table->string('pemdes_gambar')->nullable();
            $table->longText('pemdes_konten')->nullable();
            $table->string('pemberdayaan_gambar')->nullable();
            $table->longText('pemberdayaan_konten')->nullable();
            $table->string('lembaga_gambar')->nullable();
            $table->longText('lembaga_konten')->nullable();
            $table->string('sekretariat_gambar')->nullable();
            $table->longText('sekretariat_konten')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bidang_tugas');
    }
};
