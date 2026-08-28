<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PublikasiDokumen extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'judul',
        'kategori',
        'tahun',
        'file_dokumen',
        'deskripsi',
        'is_published',
    ];

    protected $casts = [
        'is_published' => 'boolean',
    ];

    // Konstanta Kategori
    public const CATEGORY_PERENCANAAN = 'Data Perencanaan';
    public const CATEGORY_LAKIP = 'LAKIP';
    public const CATEGORY_PERJANJIAN_KINERJA = 'Perjanjian Kinerja';
    public const CATEGORY_SAKIP = 'SAKIP';
    public const CATEGORY_TRANSPARANSI_APBD = 'Transparansi APBD';

    public const CATEGORIES = [
        self::CATEGORY_PERENCANAAN,
        self::CATEGORY_LAKIP,
        self::CATEGORY_PERJANJIAN_KINERJA,
        self::CATEGORY_SAKIP,
        self::CATEGORY_TRANSPARANSI_APBD,
    ];

    public const CATEGORY_LABELS = [
        self::CATEGORY_PERENCANAAN => 'Data Perencanaan',
        self::CATEGORY_LAKIP => 'LAKIP',
        self::CATEGORY_PERJANJIAN_KINERJA => 'Perjanjian Kinerja',
        self::CATEGORY_SAKIP => 'SAKIP',
        self::CATEGORY_TRANSPARANSI_APBD => 'Transparansi APBD',
    ];
}
