<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Album extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'deskripsi',
        'cover',
        'is_published',
    ];

    public function galeris()
    {
        return $this->hasMany(Galeri::class, 'album_id');
    }
}
