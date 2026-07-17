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
    ];
}
