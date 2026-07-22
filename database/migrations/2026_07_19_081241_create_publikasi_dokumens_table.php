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
        Schema::create('publikasi_dokumens', function (Blueprint $table) {
            $table->id();
            $table->string('judul', 500);
            $table->string('kategori', 50);      // string, bukan enum -> mudah diperluas
            $table->smallInteger('tahun')->unsigned();
            $table->string('file_dokumen')->nullable();
            $table->text('deskripsi')->nullable();
            $table->boolean('is_published')->default(true);
            $table->softDeletes();                // untuk audit trail dokumen resmi
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('publikasi_dokumens');
    }
};
