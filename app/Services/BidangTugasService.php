<?php

namespace App\Services;

use App\Models\BidangTugas;
use Illuminate\Support\Facades\Storage;
use Mews\Purifier\Facades\Purifier;
use App\Services\ActivityLogger;

class BidangTugasService
{
    public function updateSection(BidangTugas $bidang, string $section, array $data, $request)
    {
        // Sanitasi HTML
        if (isset($data["{$section}_konten"])) {
            $data["{$section}_konten"] = Purifier::clean($data["{$section}_konten"], 'profil_dinas');
        }

        // Handle file uploads
        if ($request->hasFile("{$section}_gambar")) {
            if ($bidang->{"{$section}_gambar"} && Storage::disk('public')->exists($bidang->{"{$section}_gambar"})) {
                Storage::disk('public')->delete($bidang->{"{$section}_gambar"});
            }
            $data["{$section}_gambar"] = $request->file("{$section}_gambar")->store('bidang-tugas', 'public');
        } else {
            unset($data["{$section}_gambar"]);
        }

        // Simpan
        $oldValues = $bidang->toArray();
        $bidang->update($data);

        $sectionNames = [
            'pemdes' => 'Pemerintahan Desa',
            'pemberdayaan' => 'Pemberdayaan Desa',
            'lembaga' => 'Lembaga Kemasyarakatan',
            'sekretariat' => 'Sekretariat'
        ];
        $sectionLabel = $sectionNames[$section] ?? $section;
        
        ActivityLogger::log(
            'Mengubah Bidang Tugas', 
            "Memperbarui data bidang tugas: {$sectionLabel}", 
            $bidang, 
            $oldValues, 
            $bidang->fresh()->toArray()
        );

        return $bidang;
    }
}
