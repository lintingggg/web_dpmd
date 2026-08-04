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
        Schema::table('profil_dinas', function (Blueprint $table) {
            $table->string('tupoksi_dokumen')->nullable()->after('tupoksi_teks');
            $table->string('kode_etik_dokumen')->nullable()->after('kode_etik_teks');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('profil_dinas', function (Blueprint $table) {
            $table->dropColumn(['tupoksi_dokumen', 'kode_etik_dokumen']);
        });
    }
};
