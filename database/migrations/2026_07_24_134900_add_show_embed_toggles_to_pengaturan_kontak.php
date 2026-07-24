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
            $table->boolean('show_instagram')->default(true);
            $table->boolean('show_tiktok')->default(true);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pengaturan_kontak', function (Blueprint $table) {
            $table->dropColumn(['show_instagram', 'show_tiktok']);
        });
    }
};
