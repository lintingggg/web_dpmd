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
        Schema::table('pengaturan_kontak', function (Blueprint $table) {
            $table->string('tiktok_url')->nullable();
            $table->text('instagram_embed_1')->nullable();
            $table->text('instagram_embed_2')->nullable();
            $table->text('tiktok_embed_1')->nullable();
            $table->text('tiktok_embed_2')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pengaturan_kontak', function (Blueprint $table) {
            $table->dropColumn([
                'tiktok_url',
                'instagram_embed_1',
                'instagram_embed_2',
                'tiktok_embed_1',
                'tiktok_embed_2'
            ]);
        });
    }
};
