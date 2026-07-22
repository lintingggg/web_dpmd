<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BidangTugas extends Model
{
    use HasFactory;

    protected $table = 'bidang_tugas';

    protected $fillable = [
        'pemdes_gambar',
        'pemdes_konten',
        'pemberdayaan_gambar',
        'pemberdayaan_konten',
        'lembaga_gambar',
        'lembaga_konten',
        'sekretariat_gambar',
        'sekretariat_konten',
    ];
}
