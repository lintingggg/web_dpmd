<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PublikasiDokumen;

class PublikasiDokumenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $dokumens = [
            // Perencanaan
            [
                'judul' => 'Rencana Strategis DPMD 2024–2029',
                'kategori' => PublikasiDokumen::CATEGORY_PERENCANAAN,
                'tahun' => 2024,
            ],
            [
                'judul' => 'Laporan Kinerja Instansi Pemerintah (LAKIP) 2023',
                'kategori' => PublikasiDokumen::CATEGORY_PERENCANAAN,
                'tahun' => 2023,
            ],
            [
                'judul' => 'Perjanjian Kinerja Tahun 2024',
                'kategori' => PublikasiDokumen::CATEGORY_PERENCANAAN,
                'tahun' => 2024,
            ],
            [
                'judul' => 'Rencana Kerja DPMD Tahun 2025',
                'kategori' => PublikasiDokumen::CATEGORY_PERENCANAAN,
                'tahun' => 2025,
            ],
            // Peraturan
            [
                'judul' => 'Peraturan Daerah No. 5 Tahun 2023 tentang Desa',
                'kategori' => PublikasiDokumen::CATEGORY_PERATURAN,
                'tahun' => 2023,
            ],
            [
                'judul' => 'Perbup Tata Cara Pemilihan Kepala Desa',
                'kategori' => PublikasiDokumen::CATEGORY_PERATURAN,
                'tahun' => 2022,
            ],
            [
                'judul' => 'SK Kepala Dinas tentang Pengelolaan Keuangan Desa',
                'kategori' => PublikasiDokumen::CATEGORY_PERATURAN,
                'tahun' => 2024,
            ],
            // Lainnya
            [
                'judul' => 'SOP Pelayanan Pencairan Dana Desa',
                'kategori' => PublikasiDokumen::CATEGORY_LAINNYA,
                'tahun' => 2024,
            ],
            [
                'judul' => 'Panduan Teknis Aplikasi Siskeudes',
                'kategori' => PublikasiDokumen::CATEGORY_LAINNYA,
                'tahun' => 2023,
            ],
            [
                'judul' => 'Materi Sosialisasi BUMDes 2024',
                'kategori' => PublikasiDokumen::CATEGORY_LAINNYA,
                'tahun' => 2024,
            ],
        ];

        foreach ($dokumens as $dokumen) {
            PublikasiDokumen::create(array_merge($dokumen, [
                'file_dokumen' => null, // Biarkan null sebagai contoh
                'deskripsi' => 'Deskripsi untuk ' . $dokumen['judul'],
                'is_published' => true,
            ]));
        }
    }
}
