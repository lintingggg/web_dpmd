<?php

namespace App\Services;

use App\Models\Galeri;
use App\Models\Album;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use App\Services\ActivityLogger;

class GaleriService
{
    public function storeGaleri(Album $album, array $data, $request): Galeri
    {
        DB::beginTransaction();
        try {
            if ($request->tipe === 'foto' && $request->hasFile('foto')) {
                $path = $request->file('foto')->store('galeri', 'public');
                $data['foto'] = $path;
            } elseif ($request->tipe === 'video' && $request->filled('foto')) {
                $videoUrl = $request->foto;
                if (preg_match('/src=["\']([^"\']+)["\']/', $videoUrl, $matches)) {
                    $videoUrl = $matches[1];
                }
                $data['foto'] = $videoUrl;
            }

            $data['is_published'] = $request->boolean('is_published');
            $data['album_id'] = $album->id;

            $galeri = Galeri::create($data);
            ActivityLogger::log('Membuat Galeri', "Membuat item galeri baru: {$galeri->judul}", $galeri, null, $galeri->toArray());
            DB::commit();

            return $galeri;
        } catch (\Throwable $e) {
            DB::rollBack();
            if (isset($path)) Storage::disk('public')->delete($path);
            throw $e;
        }
    }

    public function updateGaleri(Galeri $galeri, array $data, $request): Galeri
    {
        DB::beginTransaction();
        try {
            $fotoLamaPath = $galeri->foto;

            if ($request->tipe === 'foto' && $request->hasFile('foto')) {
                $newPath = $request->file('foto')->store('galeri', 'public');
                $data['foto'] = $newPath;
            } elseif ($request->tipe === 'video' && $request->filled('foto')) {
                $videoUrl = $request->foto;
                if (preg_match('/src=["\']([^"\']+)["\']/', $videoUrl, $matches)) {
                    $videoUrl = $matches[1];
                }
                $data['foto'] = $videoUrl;
            } else {
                unset($data['foto']);
            }

            $data['is_published'] = $request->boolean('is_published');

            $oldValues = $galeri->toArray();
            $galeri->update($data);
            ActivityLogger::log('Mengubah Galeri', "Mengubah data galeri: {$galeri->judul}", $galeri, $oldValues, $galeri->fresh()->toArray());

            // Delete old photo if a new file was uploaded, OR if type changed to video
            if ($fotoLamaPath && !str_starts_with($fotoLamaPath, 'http')) {
                if ($request->hasFile('foto') || $request->tipe === 'video') {
                    if (Storage::disk('public')->exists($fotoLamaPath)) {
                        Storage::disk('public')->delete($fotoLamaPath);
                    }
                }
            }

            DB::commit();

            return $galeri;
        } catch (\Throwable $e) {
            DB::rollBack();
            if (isset($newPath)) Storage::disk('public')->delete($newPath);
            throw $e;
        }
    }

    public function deleteGaleri(Galeri $galeri): void
    {
        DB::beginTransaction();
        try {
            if ($galeri->foto && !str_starts_with($galeri->foto, 'http') && Storage::disk('public')->exists($galeri->foto)) {
                Storage::disk('public')->delete($galeri->foto);
            }

            $oldValues = $galeri->toArray();
            $galeri->delete();
            ActivityLogger::log('Menghapus Galeri', "Menghapus item galeri: {$galeri->judul}", $galeri, $oldValues, null);
            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
