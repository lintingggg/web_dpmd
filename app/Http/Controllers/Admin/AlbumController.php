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
        Album::create($validated);

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
        $album->update($validated);

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

        $album->delete();
        return redirect()->back()->with('message', 'Album berhasil dihapus');
    }
}
