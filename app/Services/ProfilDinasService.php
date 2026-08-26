<?php

namespace App\Services;

use App\Models\ProfilDinas;
use Illuminate\Support\Facades\Storage;
use Mews\Purifier\Facades\Purifier;
use App\Services\ActivityLogger;

class ProfilDinasService
{
    public function updateSection(ProfilDinas $profil, string $section, array $data, $request)
    {
        // Sanitasi HTML
        $htmlFields = [
            'sambutan_teks', 'visi_teks', 'misi_teks', 'tupoksi_teks',
            'struktur_keterangan', 'kode_etik_teks', 'maklumat_teks', 'motto_teks'
        ];

        foreach ($htmlFields as $field) {
            if (isset($data[$field])) {
                $data[$field] = Purifier::clean($data[$field], 'profil_dinas');
            }
        }

        // Handle file uploads
        $fileFields = [
            'kadis_foto' => 'profil-dinas/foto',
            'struktur_gambar' => 'profil-dinas/struktur',
            'maklumat_dokumen' => 'profil-dinas/dokumen',
            'tupoksi_dokumen' => 'profil-dinas/dokumen',
            'kode_etik_dokumen' => 'profil-dinas/dokumen',
        ];

        foreach ($fileFields as $field => $path) {
            if ($request->hasFile($field)) {
                if ($profil->{$field} && Storage::disk('public')->exists($profil->{$field})) {
                    Storage::disk('public')->delete($profil->{$field});
                }
                $data[$field] = $request->file($field)->store($path, 'public');
            } else {
                unset($data[$field]); // Cegah penimpaan dengan null
            }
        }

        // Simpan
        $oldValues = $profil->toArray();
        $profil->update($data);

        $sectionNames = [
            'sambutan' => 'Sambutan Kepala Dinas',
            'visi-misi' => 'Visi & Misi',
            'tupoksi' => 'Tugas Pokok & Fungsi',
            'struktur' => 'Struktur Organisasi',
            'kode-etik' => 'Kode Etik Pelayanan',
            'maklumat' => 'Maklumat Pelayanan',
            'motto' => 'Motto Pelayanan',
        ];
        $sectionLabel = $sectionNames[$section] ?? $section;
        
        ActivityLogger::log(
            'Mengubah Profil Dinas', 
            "Memperbarui profil dinas bagian: {$sectionLabel}", 
            $profil, 
            $oldValues, 
            $profil->fresh()->toArray()
        );

        return $profil;
    }
}
