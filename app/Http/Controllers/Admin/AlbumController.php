<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Album;
use Inertia\Inertia;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class AlbumController extends Controller
{
    public function index(Request $request)
    {
        $query = Album::query();

        if ($request->filled('search')) {
            $query->where('nama', 'like', '%' . $request->search . '%');
        }

        $albums = $query->latest()->paginate(8)->withQueryString();

        return Inertia::render('Admin/Album', [
            'albums' => $albums,
            'filters' => $request->only(['search']),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'is_published' => 'boolean',
            'cover' => 'nullable|file|image|mimes:jpg,jpeg,png,webp|max:1024',
        ]);

        if ($request->hasFile('cover')) {
            $validated['cover'] = $request->file('cover')->store('album_covers', 'public');
        }

        $validated['is_published'] = $request->boolean('is_published');
        $album = Album::create($validated);

        \App\Services\ActivityLogger::log('Membuat Album', "Membuat album galeri baru: {$album->nama}", $album, null, $album->toArray());

        return redirect()->back()->with('message', 'Album berhasil dibuat');
    }

    public function update(Request $request, Album $album)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'is_published' => 'boolean',
            'cover' => 'nullable|file|image|mimes:jpg,jpeg,png,webp|max:1024',
        ]);

        if ($request->hasFile('cover')) {
            if ($album->cover && Storage::disk('public')->exists($album->cover)) {
                Storage::disk('public')->delete($album->cover);
            }
            $validated['cover'] = $request->file('cover')->store('album_covers', 'public');
        } else {
            unset($validated['cover']);
        }

        $validated['is_published'] = $request->boolean('is_published');
        $oldValues = $album->toArray();
        $album->update($validated);

        \App\Services\ActivityLogger::log('Mengubah Album', "Mengubah data album galeri: {$album->nama}", $album, $oldValues, $album->fresh()->toArray());

        return redirect()->back()->with('message', 'Album berhasil diperbarui');
    }

    public function destroy(Album $album)
    {
        if ($album->cover && Storage::disk('public')->exists($album->cover)) {
            Storage::disk('public')->delete($album->cover);
        }

        // Delete all gallery items related
        foreach ($album->galeris as $galeri) {
            if ($galeri->foto && Storage::disk('public')->exists($galeri->foto)) {
                Storage::disk('public')->delete($galeri->foto);
            }
            $galeri->delete();
        }

        $oldValues = $album->toArray();
        $album->delete();
        \App\Services\ActivityLogger::log('Menghapus Album', "Menghapus album galeri: {$album->nama}", $album, $oldValues, null);
        return redirect()->back()->with('message', 'Album berhasil dihapus');
    }
}
