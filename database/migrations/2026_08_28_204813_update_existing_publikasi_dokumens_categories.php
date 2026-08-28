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
        // Update to specific categories based on keywords in title
        \DB::table('publikasi_dokumens')
            ->where('judul', 'like', '%LAKIP%')
            ->update(['kategori' => 'LAKIP']);

        \DB::table('publikasi_dokumens')
            ->where(function($q) {
                $q->where('judul', 'like', '%Perjanjian Kinerja%')
                  ->orWhere('judul', 'like', '%(PK)%');
            })
            ->update(['kategori' => 'Perjanjian Kinerja']);

        \DB::table('publikasi_dokumens')
            ->where('judul', 'like', '%SAKIP%')
            ->update(['kategori' => 'SAKIP']);

        \DB::table('publikasi_dokumens')
            ->where(function($q) {
                $q->where('judul', 'like', '%Transparansi%')
                  ->orWhere('judul', 'like', '%APBD%');
            })
            ->update(['kategori' => 'Transparansi APBD']);

        // Default rest of old categories to 'Data Perencanaan'
        \DB::table('publikasi_dokumens')
            ->whereIn('kategori', ['perencanaan', 'peraturan', 'lainnya'])
            ->update(['kategori' => 'Data Perencanaan']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No rollback necessary as categories have changed permanently
    }
};
