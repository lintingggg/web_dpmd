<?php

namespace App\Services;

use App\Models\Berita;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Mews\Purifier\Facades\Purifier;
use Illuminate\Support\Facades\Auth;

class BeritaService
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(array $data)
    {
        DB::beginTransaction();
        try {
            $path = null;
            if (isset($data['thumbnail']) && $data['thumbnail'] instanceof \Illuminate\Http\UploadedFile) {
                $path = $data['thumbnail']->store('berita', 'public');
                $data['thumbnail'] = $path;
            }

            // Slug deduplication
            $slug = Str::slug($data['judul']);
            $count = Berita::withTrashed()->where('slug', 'like', $slug . '%')->count();
            $data['slug'] = $count ? "{$slug}-{$count}" : $slug;

            // Sanitize konten
            $data['konten'] = Purifier::clean($data['konten'], 'berita');

            // Set penulis and user_id
            $data['penulis'] = Auth::user()?->name ?? 'Admin';
            $data['user_id'] = Auth::id();
            
            // Set published_at if published
            if (!empty($data['is_published'])) {
                $data['published_at'] = now();
            }

            $berita = Berita::create($data);
            \App\Services\ActivityLogger::log('Membuat Berita', "Membuat berita baru: {$berita->judul}", $berita, null, $berita->toArray());
            
            DB::commit();
            return $berita;
        } catch (\Throwable $e) {
            DB::rollBack();
            if (isset($path)) Storage::disk('public')->delete($path);
            throw $e;
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Berita $berita, array $data)
    {
        DB::beginTransaction();
        try {
            $path = null;
            if (isset($data['thumbnail']) && $data['thumbnail'] instanceof \Illuminate\Http\UploadedFile) {
                // Delete old thumbnail
                if ($berita->thumbnail && Storage::disk('public')->exists($berita->thumbnail)) {
                    Storage::disk('public')->delete($berita->thumbnail);
                }
                $path = $data['thumbnail']->store('berita', 'public');
                $data['thumbnail'] = $path;
            } else {
                unset($data['thumbnail']); // Prevent overwriting with null
            }

            $data['konten'] = Purifier::clean($data['konten'], 'berita');

            if (!empty($data['is_published']) && !$berita->published_at) {
                $data['published_at'] = now();
            }

            $oldValues = $berita->toArray();
            $berita->update($data);
            
            \App\Services\ActivityLogger::log('Mengubah Berita', "Mengubah data berita: {$berita->judul}", $berita, $oldValues, $berita->fresh()->toArray());
            
            DB::commit();
            return $berita;
        } catch (\Throwable $e) {
            DB::rollBack();
            if (isset($path)) Storage::disk('public')->delete($path);
            throw $e;
        }
    }

    /**
     * Remove the specified resource from storage (soft delete).
     */
    public function delete(Berita $berita)
    {
        DB::beginTransaction();
        try {
            $oldValues = $berita->toArray();
            $berita->delete(); // Soft delete, thumbnail is NOT deleted
            \App\Services\ActivityLogger::log('Menghapus Berita', "Menghapus berita: {$berita->judul}", $berita, $oldValues, null);
            DB::commit();
            return true;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
