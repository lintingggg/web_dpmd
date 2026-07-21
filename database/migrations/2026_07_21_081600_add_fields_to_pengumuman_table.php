<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pengumuman', function (Blueprint $table) {
            $table->text('cuplikan')->nullable()->after('judul');
            $table->string('link_eksternal')->nullable()->after('file_lampiran');
            $table->date('tanggal')->nullable()->after('id'); // Explicit date if needed, or we can use created_at. I'll add tanggal just in case.
        });
    }

    public function down(): void
    {
        Schema::table('pengumuman', function (Blueprint $table) {
            $table->dropColumn(['cuplikan', 'link_eksternal', 'tanggal']);
        });
    }
};
