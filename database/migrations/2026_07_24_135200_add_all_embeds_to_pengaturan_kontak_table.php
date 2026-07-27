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
            $table->boolean('show_youtube')->default(true);
            $table->boolean('show_facebook')->default(true);
            $table->boolean('show_twitter')->default(true);

            $table->text('youtube_embed_1')->nullable();
            $table->text('youtube_embed_2')->nullable();
            
            $table->text('facebook_embed_1')->nullable();
            $table->text('facebook_embed_2')->nullable();
            
            $table->text('twitter_embed_1')->nullable();
            $table->text('twitter_embed_2')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pengaturan_kontak', function (Blueprint $table) {
            $table->dropColumn([
                'show_youtube', 'show_facebook', 'show_twitter',
                'youtube_embed_1', 'youtube_embed_2',
                'facebook_embed_1', 'facebook_embed_2',
                'twitter_embed_1', 'twitter_embed_2'
            ]);
        });
    }
};
