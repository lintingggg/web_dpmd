<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PengaturanKontak;

class PengaturanKontakSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        PengaturanKontak::updateOrCreate(
            ['id' => 1],
            [
                'alamat' => 'Jl. Soekarno Hatta No. 35, Bangkalan',
                'email' => 'dpmd@bangkalankab.go.id',
                'telepon' => '(031) 3095331',
                'whatsapp' => '081234567890',
                'jam_kerja' => 'Senin - Jumat, 08:00 - 15:30 WIB',
                'koordinat_map' => '-7.0270059, 112.7483669',
                'facebook_url' => 'https://facebook.com/dpmdbangkalan',
                'instagram_url' => 'https://instagram.com/dpmdbangkalan',
                'youtube_url' => 'https://youtube.com/@dpmdbangkalan',
                'twitter_url' => 'https://x.com/dpmdbangkalan',
            ]
        );
    }
}
