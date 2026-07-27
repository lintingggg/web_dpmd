<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengaturanBeranda extends Model
{
    use HasFactory;

    protected $table = 'pengaturan_beranda';

    protected $fillable = [
        'hero_title',
        'hero_description',
        'hero_image_1',
        'hero_image_2',
        'hero_image_3',
    ];
}
