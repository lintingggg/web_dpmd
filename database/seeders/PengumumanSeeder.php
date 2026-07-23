<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class PengumumanSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            [
                'tanggal' => Carbon::now()->subDays(1)->format('Y-m-d'),
                'judul' => 'Pemilihan Kepala Desa Serentak Gelombang II Tahun 2024',
                'slug' => Str::slug('Pemilihan Kepala Desa Serentak Gelombang II Tahun 2024'),
                'cuplikan' => 'Jadwal dan tahapan pelaksanaan Pilkades serentak gelombang II di 12 kecamatan telah resmi diterbitkan. Pendaftaran calon akan segera dibuka.',
                'konten' => '<p>Jadwal dan tahapan pelaksanaan Pilkades serentak gelombang II di 12 kecamatan telah resmi diterbitkan. Pendaftaran calon akan segera dibuka. Silakan hubungi panitia tingkat desa masing-masing.</p>',
                'is_published' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'tanggal' => Carbon::now()->subDays(3)->format('Y-m-d'),
                'judul' => 'Hasil Evaluasi Kinerja BUMDes Semester 1',
                'slug' => Str::slug('Hasil Evaluasi Kinerja BUMDes Semester 1'),
                'cuplikan' => 'Laporan hasil evaluasi dan pemeringkatan BUMDes berprestasi se-Kabupaten Bangkalan untuk periode paruh pertama tahun anggaran berjalan.',
                'konten' => '<p>Laporan hasil evaluasi dan pemeringkatan BUMDes berprestasi se-Kabupaten Bangkalan untuk periode paruh pertama tahun anggaran berjalan. Detail laporan dapat didownload pada lampiran.</p>',
                'is_published' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'tanggal' => Carbon::now()->subDays(7)->format('Y-m-d'),
                'judul' => 'Pendataan SDGs Desa Tahap Pemutakhiran',
                'slug' => Str::slug('Pendataan SDGs Desa Tahap Pemutakhiran'),
                'cuplikan' => 'Diinstruksikan kepada seluruh relawan pendata SDGs Desa untuk segera menyelesaikan tahap pemutakhiran data keluarga paling lambat akhir bulan ini.',
                'konten' => '<p>Diinstruksikan kepada seluruh relawan pendata SDGs Desa untuk segera menyelesaikan tahap pemutakhiran data keluarga paling lambat akhir bulan ini.</p>',
                'is_published' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'tanggal' => Carbon::now()->subDays(10)->format('Y-m-d'),
                'judul' => 'Jadwal Pencairan Dana Desa Tahap 3',
                'slug' => Str::slug('Jadwal Pencairan Dana Desa Tahap 3'),
                'cuplikan' => 'Informasi terkait syarat dokumen pengajuan dan batas akhir waktu penyampaian LPPDes untuk pencairan Dana Desa tahap ketiga.',
                'konten' => '<p>Informasi terkait syarat dokumen pengajuan dan batas akhir waktu penyampaian LPPDes untuk pencairan Dana Desa tahap ketiga. Harap diperhatikan dengan saksama.</p>',
                'is_published' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ];

        DB::table('pengumuman')->insert($data);
    }
}
