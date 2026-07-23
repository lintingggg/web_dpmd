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
        Schema::create('profil_dinas', function (Blueprint $table) {
            $table->id();
            $table->string('kadis_nama')->nullable();
            $table->string('kadis_nip')->nullable();
            $table->text('sambutan_teks')->nullable();       
            $table->string('kadis_foto')->nullable();       
            $table->text('visi_teks')->nullable();           
            $table->text('misi_teks')->nullable();           
            $table->text('tupoksi_teks')->nullable();        
            $table->text('struktur_keterangan')->nullable(); 
            $table->string('struktur_gambar')->nullable();  
            $table->text('kode_etik_teks')->nullable();     
            $table->text('maklumat_teks')->nullable();      
            $table->text('motto_teks')->nullable();         
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profil_dinas');
    }
};
