<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Galeri;

class GaleriSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $galeris = [
            [
                'judul' => 'Peringatan Hari Jadi Kabupaten Bangkalan ke-492',
                'deskripsi' => 'Kegiatan peringatan hari jadi kabupaten dengan berbagai acara kebudayaan.',
                'foto' => null, // Simulasi draft tanpa foto atau menggunakan URL placeholder nanti
                'tanggal_kegiatan' => '2026-10-24',
                'is_published' => false,
            ],
            [
                'judul' => 'Kunjungan Kerja Kementerian Desa RI',
                'deskripsi' => 'Kunjungan kerja dalam rangka peninjauan proyek infrastruktur desa binaan.',
                'foto' => null,
                'tanggal_kegiatan' => '2026-09-15',
                'is_published' => false,
            ],
            [
                'judul' => 'Rapat Koordinasi Kepala Desa Se-Kabupaten',
                'deskripsi' => 'Rapat koordinasi rutin untuk membahas evaluasi kinerja pemerintah desa.',
                'foto' => null,
                'tanggal_kegiatan' => '2026-09-02',
                'is_published' => false,
            ],
            [
                'judul' => 'Pelatihan Kewirausahaan BUMDes',
                'deskripsi' => 'Pelatihan peningkatan kapasitas pengurus BUMDes untuk kemandirian ekonomi desa.',
                'foto' => null,
                'tanggal_kegiatan' => '2026-08-12',
                'is_published' => false,
            ]
        ];

        foreach ($galeris as $data) {
            Galeri::create($data);
        }
    }
}
