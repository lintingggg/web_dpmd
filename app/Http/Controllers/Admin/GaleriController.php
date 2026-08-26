<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Galeri;
use App\Models\Album;
use Inertia\Inertia;
use App\Services\GaleriService;
use App\Http\Requests\StoreGaleriRequest;
use App\Http\Requests\UpdateGaleriRequest;
use Illuminate\Support\Facades\Gate;

class GaleriController extends Controller
{
    public function __construct(protected GaleriService $galeriService) {}

    public function index(Request $request, Album $album)
    {
        Gate::authorize('viewAny', Galeri::class);

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

    public function store(StoreGaleriRequest $request, Album $album)
    {
        Gate::authorize('create', Galeri::class);

        $this->galeriService->storeGaleri($album, $request->validated(), $request);

        return redirect()->back()->with('message', 'Media berhasil ditambahkan ke album');
    }

    public function update(UpdateGaleriRequest $request, Galeri $galeri)
    {
        Gate::authorize('update', $galeri);

        $this->galeriService->updateGaleri($galeri, $request->validated(), $request);

        return redirect()->back()->with('message', 'Media berhasil diperbarui');
    }

    public function destroy(Galeri $galeri)
    {
        Gate::authorize('delete', $galeri);

        $this->galeriService->deleteGaleri($galeri);

        return redirect()->back()->with('message', 'Media berhasil dihapus');
    }
}
