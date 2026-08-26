<?php

namespace App\Services;

use App\Models\Album;
use Illuminate\Support\Facades\Storage;
use App\Services\ActivityLogger;

class AlbumService
{
    public function storeAlbum(array $data, $request): Album
    {
        if ($request->hasFile('cover')) {
            $data['cover'] = $request->file('cover')->store('album_covers', 'public');
        }

        $data['is_published'] = $request->boolean('is_published');
        $album = Album::create($data);

        ActivityLogger::log('Membuat Album', "Membuat album galeri baru: {$album->nama}", $album, null, $album->toArray());

        return $album;
    }

    public function updateAlbum(Album $album, array $data, $request): Album
    {
        if ($request->hasFile('cover')) {
            if ($album->cover && Storage::disk('public')->exists($album->cover)) {
                Storage::disk('public')->delete($album->cover);
            }
            $data['cover'] = $request->file('cover')->store('album_covers', 'public');
        } else {
            unset($data['cover']);
        }

        $data['is_published'] = $request->boolean('is_published');
        $oldValues = $album->toArray();
        $album->update($data);

        ActivityLogger::log('Mengubah Album', "Mengubah data album galeri: {$album->nama}", $album, $oldValues, $album->fresh()->toArray());

        return $album;
    }

    public function deleteAlbum(Album $album): void
    {
        if ($album->cover && Storage::disk('public')->exists($album->cover)) {
            Storage::disk('public')->delete($album->cover);
        }

        // Delete all gallery items related
        foreach ($album->galeris as $galeri) {
            if ($galeri->foto && !str_starts_with($galeri->foto, 'http') && Storage::disk('public')->exists($galeri->foto)) {
                Storage::disk('public')->delete($galeri->foto);
            }
            $galeri->delete();
        }

        $oldValues = $album->toArray();
        $album->delete();
        
        ActivityLogger::log('Menghapus Album', "Menghapus album galeri: {$album->nama}", $album, $oldValues, null);
    }
}
