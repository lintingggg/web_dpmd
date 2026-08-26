<?php

namespace App\Services;

use App\Models\PengaturanKontak;
use App\Services\ActivityLogger;

class KontakMedsosService
{
    public function updateKontak(string $section, array $data): PengaturanKontak
    {
        $kontakLama = PengaturanKontak::find(1);
        $oldValues = $kontakLama ? $kontakLama->toArray() : [];

        $kontak = PengaturanKontak::updateOrCreate(
            ['id' => 1],
            $data
        );

        ActivityLogger::log('Mengubah Pengaturan', 'Memperbarui Pengaturan Kontak & Media Sosial (' . ucfirst($section) . ')', $kontak, $oldValues, $kontak->fresh()->toArray());

        return $kontak;
    }
}
