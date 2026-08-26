<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Album;
use Inertia\Inertia;
use App\Services\AlbumService;
use App\Http\Requests\StoreAlbumRequest;
use App\Http\Requests\UpdateAlbumRequest;
use Illuminate\Support\Facades\Gate;

class AlbumController extends Controller
{
    public function __construct(protected AlbumService $albumService) {}

    public function index(Request $request)
    {
        Gate::authorize('viewAny', Album::class);

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

    public function store(StoreAlbumRequest $request)
    {
        Gate::authorize('create', Album::class);

        $this->albumService->storeAlbum($request->validated(), $request);

        return redirect()->back()->with('message', 'Album berhasil dibuat');
    }

    public function update(UpdateAlbumRequest $request, Album $album)
    {
        Gate::authorize('update', $album);

        $this->albumService->updateAlbum($album, $request->validated(), $request);

        return redirect()->back()->with('message', 'Album berhasil diperbarui');
    }

    public function destroy(Album $album)
    {
        Gate::authorize('delete', $album);

        $this->albumService->deleteAlbum($album);
        
        return redirect()->back()->with('message', 'Album berhasil dihapus');
    }
}
