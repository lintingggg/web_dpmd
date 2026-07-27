<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengaturanKontak extends Model
{
    use HasFactory;

    protected $table = 'pengaturan_kontak';

    protected $fillable = [
        'alamat',
        'email',
        'telepon',
        'whatsapp',
        'jam_kerja',
        'koordinat_map',
        'facebook_url',
        'instagram_url',
        'youtube_url',
        'twitter_url',
        'tiktok_url',
        'instagram_embed_1',
        'instagram_embed_2',
        'tiktok_embed_1',
        'tiktok_embed_2',
        'youtube_embed_1',
        'youtube_embed_2',
        'facebook_embed_1',
        'facebook_embed_2',
        'twitter_embed_1',
        'twitter_embed_2',
        'show_instagram',
        'show_tiktok',
        'show_youtube',
        'show_facebook',
        'show_twitter',
    ];
}
