<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Galeri;
use App\Models\Album;
use Inertia\Inertia;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class GaleriController extends Controller
{
    public function index(Request $request, Album $album)
    {
        $query = $album->galeris();

        if ($request->filled('search')) {
            $query->where('judul', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('status')) {
            if ($request->status === 'publish') {
                $query->where('is_published', true);
            } elseif ($request->status === 'draft') {
                $query->where('is_published', false);
            }
        }

        $galeri = $query->latest()->paginate(8)->withQueryString();

        return Inertia::render('Admin/Galeri', [
            'album' => $album,
            'galeri' => $galeri,
            'filters' => $request->only(['search', 'status']),
        ]);
    }

    public function store(Request $request, Album $album)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:500',
            'deskripsi' => 'nullable|string',
            'tanggal_kegiatan' => 'nullable|date',
            'is_published' => 'boolean',
            'tipe' => 'required|in:foto,video',
            'foto' => $request->tipe === 'video' 
                ? ($request->boolean('is_published') ? 'required|string|max:2000' : 'nullable|string|max:2000')
                : [
                    $request->boolean('is_published') ? 'required' : 'nullable',
                    'file', 'mimes:jpg,jpeg,png,webp', 'max:1024'
                ],
        ]);

        DB::beginTransaction();
        try {
            if ($request->tipe === 'foto' && $request->hasFile('foto')) {
                $path = $request->file('foto')->store('galeri', 'public');
                $validated['foto'] = $path;
            } elseif ($request->tipe === 'video' && $request->filled('foto')) {
                $videoUrl = $request->foto;
                if (preg_match('/src=["\']([^"\']+)["\']/', $videoUrl, $matches)) {
                    $videoUrl = $matches[1];
                }
                $validated['foto'] = $videoUrl;
            }

            $validated['is_published'] = $request->boolean('is_published');
            $validated['album_id'] = $album->id;

            Galeri::create($validated);
            DB::commit();

            return redirect()->back()->with('message', 'Media berhasil ditambahkan ke album');
        } catch (\Throwable $e) {
            DB::rollBack();
            if (isset($path)) Storage::disk('public')->delete($path);
            throw $e;
        }
    }

    public function update(Request $request, Galeri $galeri)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:500',
            'deskripsi' => 'nullable|string',
            'tanggal_kegiatan' => 'nullable|date',
            'is_published' => 'boolean',
            'tipe' => 'required|in:foto,video',
            'foto' => $request->tipe === 'video' 
                ? ($request->boolean('is_published') && !$galeri->foto ? 'required|string|max:2000' : 'nullable|string|max:2000')
                : [
                    ($request->boolean('is_published') && !$galeri->foto) ? 'required' : 'nullable',
                    'file', 'mimes:jpg,jpeg,png,webp', 'max:1024'
                ],
        ]);

        DB::beginTransaction();
        try {
            $fotoLamaPath = $galeri->foto;

            if ($request->tipe === 'foto' && $request->hasFile('foto')) {
                $newPath = $request->file('foto')->store('galeri', 'public');
                $validated['foto'] = $newPath;
            } elseif ($request->tipe === 'video' && $request->filled('foto')) {
                $videoUrl = $request->foto;
                if (preg_match('/src=["\']([^"\']+)["\']/', $videoUrl, $matches)) {
                    $videoUrl = $matches[1];
                }
                $validated['foto'] = $videoUrl;
            } else {
                unset($validated['foto']);
            }

            $validated['is_published'] = $request->boolean('is_published');

            $galeri->update($validated);

            // Delete old photo if a new file was uploaded, OR if type changed to video
            if ($fotoLamaPath && !str_starts_with($fotoLamaPath, 'http')) {
                if ($request->hasFile('foto') || $request->tipe === 'video') {
                    if (Storage::disk('public')->exists($fotoLamaPath)) {
                        Storage::disk('public')->delete($fotoLamaPath);
                    }
                }
            }

            DB::commit();

            return redirect()->back()->with('message', 'Media berhasil diperbarui');
        } catch (\Throwable $e) {
            DB::rollBack();
            if (isset($newPath)) Storage::disk('public')->delete($newPath);
            throw $e;
        }
    }

    public function destroy(Galeri $galeri)
    {
        DB::beginTransaction();
        try {
            if ($galeri->foto && !str_starts_with($galeri->foto, 'http') && Storage::disk('public')->exists($galeri->foto)) {
                Storage::disk('public')->delete($galeri->foto);
            }

            $galeri->delete();
            DB::commit();

            return redirect()->back()->with('message', 'Media berhasil dihapus');
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
