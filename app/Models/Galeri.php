<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Galeri extends Model
{
    use HasFactory;

    protected $fillable = [
        'judul',
        'deskripsi',
        'foto',
        'tanggal_kegiatan',
        'is_published',
        'album_id',
        'tipe',
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'tanggal_kegiatan' => 'date',
    ];

    public function album()
    {
        return $this->belongsTo(Album::class, 'album_id');
    }

    protected $appends = ['foto_url'];

    public function getFotoUrlAttribute(): ?string
    {
        if (!$this->foto) return null;
        if (str_starts_with($this->foto, 'http') || str_starts_with($this->foto, '//')) {
            return $this->foto;
        }
        return asset('storage/' . $this->foto);
    }
}
