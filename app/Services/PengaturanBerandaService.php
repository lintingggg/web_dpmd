<?php

namespace App\Services;

use App\Models\PengaturanBeranda;
use Illuminate\Support\Facades\Storage;
use App\Services\ActivityLogger;

class PengaturanBerandaService
{
    public function updatePengaturan(array $data, $request): PengaturanBeranda
    {
        $pengaturan = PengaturanBeranda::first();
        if (!$pengaturan) {
            $pengaturan = new PengaturanBeranda();
        }

        $pengaturan->hero_title = $data['hero_title'] ?? null;
        $pengaturan->hero_description = $data['hero_description'] ?? null;

        // Handle Image 1
        if ($request->hasFile('hero_image_1')) {
            if ($pengaturan->hero_image_1 && Storage::disk('public')->exists($pengaturan->hero_image_1)) {
                Storage::disk('public')->delete($pengaturan->hero_image_1);
            }
            $path = $request->file('hero_image_1')->store('hero', 'public');
            $pengaturan->hero_image_1 = $path;
        }

        // Handle Image 2
        if ($request->hasFile('hero_image_2')) {
            if ($pengaturan->hero_image_2 && Storage::disk('public')->exists($pengaturan->hero_image_2)) {
                Storage::disk('public')->delete($pengaturan->hero_image_2);
            }
            $path = $request->file('hero_image_2')->store('hero', 'public');
            $pengaturan->hero_image_2 = $path;
        }

        // Handle Image 3
        if ($request->hasFile('hero_image_3')) {
            if ($pengaturan->hero_image_3 && Storage::disk('public')->exists($pengaturan->hero_image_3)) {
                Storage::disk('public')->delete($pengaturan->hero_image_3);
            }
            $path = $request->file('hero_image_3')->store('hero', 'public');
            $pengaturan->hero_image_3 = $path;
        }

        $oldValues = $pengaturan->exists ? $pengaturan->getOriginal() : [];
        $pengaturan->save();

        ActivityLogger::log('Mengubah Pengaturan', 'Memperbarui Pengaturan Banner Utama / Beranda', $pengaturan, $oldValues, $pengaturan->fresh()->toArray());

        return $pengaturan;
    }
}
