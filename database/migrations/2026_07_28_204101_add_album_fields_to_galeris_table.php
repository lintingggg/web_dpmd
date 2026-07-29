<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('galeris', function (Blueprint $table) {
            $table->foreignId('album_id')->nullable()->constrained('albums')->onDelete('cascade');
            $table->string('tipe')->default('foto'); // 'foto' or 'video'
        });
    }

    public function down(): void
    {
        Schema::table('galeris', function (Blueprint $table) {
            $table->dropForeign(['album_id']);
            $table->dropColumn(['album_id', 'tipe']);
        });
    }
};
