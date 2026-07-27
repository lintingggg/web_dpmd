<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Galeri;
use Inertia\Inertia;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class GaleriController extends Controller
{
    public function index(Request $request)
    {
        $query = Galeri::query();

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
            'galeri' => $galeri,
            'filters' => $request->only(['search', 'status']),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:500',
            'deskripsi' => 'nullable|string',
            'tanggal_kegiatan' => 'nullable|date',
            'is_published' => 'boolean',
            'foto' => [
                $request->boolean('is_published') ? 'required' : 'nullable',
                'file', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'
            ],
        ]);

        DB::beginTransaction();
        try {
            if ($request->hasFile('foto')) {
                $path = $request->file('foto')->store('galeri', 'public');
                $validated['foto'] = $path;
            }

            $validated['is_published'] = $request->boolean('is_published');

            Galeri::create($validated);
            DB::commit();

            return redirect()->back()->with('message', 'Entri galeri berhasil ditambahkan');
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
            // If already published and no new photo provided, the existing photo must exist if they keep it published
            'foto' => [
                ($request->boolean('is_published') && !$galeri->foto) ? 'required' : 'nullable',
                'file', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'
            ],
        ]);

        DB::beginTransaction();
        try {
            $fotoLamaPath = $galeri->foto;

            if ($request->hasFile('foto')) {
                $newPath = $request->file('foto')->store('galeri', 'public');
                $validated['foto'] = $newPath;
            } else {
                unset($validated['foto']);
            }

            $validated['is_published'] = $request->boolean('is_published');

            $galeri->update($validated);

            if ($request->hasFile('foto') && $fotoLamaPath && Storage::disk('public')->exists($fotoLamaPath)) {
                Storage::disk('public')->delete($fotoLamaPath);
            }

            DB::commit();

            return redirect()->back()->with('message', 'Entri galeri berhasil diperbarui');
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
            if ($galeri->foto && Storage::disk('public')->exists($galeri->foto)) {
                Storage::disk('public')->delete($galeri->foto);
            }

            $galeri->delete();
            DB::commit();

            return redirect()->back()->with('message', 'Entri galeri berhasil dihapus');
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
