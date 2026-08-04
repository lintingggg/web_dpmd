<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProfilDinas extends Model
{
    use HasFactory;

    protected $table = 'profil_dinas';

    protected $fillable = [
        'kadis_nama',
        'kadis_nip',
        'sambutan_teks',
        'kadis_foto',
        'visi_teks',
        'misi_teks',
        'tupoksi_teks',
        'struktur_keterangan',
        'struktur_gambar',
        'kode_etik_teks',
        'maklumat_teks',
        'motto_teks',
        'maklumat_dokumen',
        'tupoksi_dokumen',
        'kode_etik_dokumen',
    ];
}
