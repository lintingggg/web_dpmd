<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Berita;
use Illuminate\Support\Str;

class BeritaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $beritas = [
            [
                'judul' => 'Pelatihan Kewirausahaan untuk BUMDes Mandiri Se-Kabupaten',
                'konten' => '<p>DPMD Bangkalan menggelar pelatihan intensif bagi pengurus BUMDes untuk meningkatkan kapasitas manajerial dan inovasi produk lokal guna mendorong kemandirian ekonomi desa.</p><p>Acara ini dihadiri oleh perwakilan dari 50 desa.</p>',
                'tags' => ['Pemberdayaan', 'Kewirausahaan', 'BUMDes'],
                'penulis' => 'Admin DPMD',
                'is_published' => true,
                'published_at' => now()->subDays(2),
            ],
            [
                'judul' => 'Peninjauan Proyek Infrastruktur Desa Bantuan Provinsi 2024',
                'konten' => '<p>Tim evaluasi turun langsung memantau perkembangan pembangunan fisik yang dibiayai bantuan provinsi. Kegiatan ini bertujuan memastikan kualitas pembangunan sesuai standar.</p>',
                'tags' => ['Infrastruktur', 'Desa', 'Kegiatan'],
                'penulis' => 'Admin DPMD',
                'is_published' => true,
                'published_at' => now()->subDays(5),
            ],
            [
                'judul' => 'Sosialisasi Penggunaan Sistem Informasi Desa Terpadu',
                'konten' => '<p>Mendorong percepatan digitalisasi administrasi desa melalui penerapan aplikasi terpadu. Sosialisasi berjalan lancar dengan partisipasi aktif dari para perangkat desa.</p>',
                'tags' => ['Informasi', 'Digitalisasi'],
                'penulis' => 'Admin DPMD',
                'is_published' => true,
                'published_at' => now()->subDays(10),
            ],
            [
                'judul' => 'Rapat Koordinasi Persiapan Lomba Desa Tingkat Provinsi',
                'konten' => '<p>Rapat ini membahas persiapan teknis dan administratif bagi desa-desa yang akan mewakili kabupaten dalam lomba desa tingkat provinsi tahun depan.</p>',
                'tags' => ['Kegiatan', 'Rakor'],
                'penulis' => 'Admin DPMD',
                'is_published' => false,
                'published_at' => null,
            ],
            [
                'judul' => 'Penyaluran Bantuan BLT Dana Desa Tahap IV',
                'konten' => '<p>Bantuan Langsung Tunai (BLT) Dana Desa tahap IV telah mulai disalurkan ke masyarakat yang berhak menerima. Diharapkan bantuan ini dapat meringankan beban ekonomi warga.</p>',
                'tags' => ['Sosial', 'Bantuan', 'Desa'],
                'penulis' => 'Admin DPMD',
                'is_published' => true,
                'published_at' => now()->subDays(15),
            ]
        ];

        foreach ($beritas as $data) {
            $data['slug'] = Str::slug($data['judul']);
            Berita::create($data);
        }
    }
}
