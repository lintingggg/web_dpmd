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
    public const CATEGORY_PERENCANAAN = 'perencanaan';
    public const CATEGORY_PERATURAN = 'peraturan';
    public const CATEGORY_LAINNYA = 'lainnya';

    public const CATEGORIES = [
        self::CATEGORY_PERENCANAAN,
        self::CATEGORY_PERATURAN,
        self::CATEGORY_LAINNYA,
    ];

    public const CATEGORY_LABELS = [
        self::CATEGORY_PERENCANAAN => 'Dokumen Perencanaan',
        self::CATEGORY_PERATURAN => 'Produk Peraturan',
        self::CATEGORY_LAINNYA => 'Dokumen Lainnya',
    ];
}
