<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ProfilDinas;

class ProfilDinasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ProfilDinas::updateOrCreate(
            ['id' => 1],
            [
                'kadis_nama' => 'Drs. H. Ahmad Fathoni, M.Si',
                'kadis_nip' => 'NIP. 197001011998031004',
                'sambutan_teks' => '<p>Assalamu\'alaikum Warahmatullahi Wabarakatuh.</p><p>Selamat datang di website resmi Dinas Pemberdayaan Masyarakat dan Desa (DPMD) Kabupaten Bangkalan.</p>',
                'visi_teks' => '<p>Mewujudkan Kabupaten Bangkalan yang Religius, Sejahtera, dan Berdaya Saing.</p>',
                'misi_teks' => '<ol><li>Meningkatkan kualitas sumber daya manusia.</li><li>Meningkatkan tata kelola pemerintahan yang baik.</li></ol>',
                'tupoksi_teks' => '<p>DPMD mempunyai tugas pokok membantu Bupati dalam melaksanakan urusan pemerintahan daerah di bidang pemberdayaan masyarakat dan desa.</p>',
                'kode_etik_teks' => '<p>Kami berkomitmen untuk memberikan pelayanan yang transparan dan akuntabel.</p>',
                'maklumat_teks' => '<p>Kami siap memberikan pelayanan sesuai dengan standar pelayanan yang telah ditetapkan.</p>',
                'motto_teks' => '<p><strong>Melayani dengan Sepenuh Hati</strong></p>',
            ]
        );
    }
}
