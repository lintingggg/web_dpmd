<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pengumuman extends Model
{
    protected $table = 'pengumuman';

    protected $fillable = [
        'judul',
        'slug',
        'konten',
        'file_lampiran',
        'is_published',
    ];

    protected static function booted()
    {
        static::saving(function ($pengumuman) {
            if (empty($pengumuman->slug)) {
                $pengumuman->slug = \Illuminate\Support\Str::slug($pengumuman->judul) . '-' . time();
            }
        });
    }
}
