<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Berita;
use App\Http\Requests\StoreBeritaRequest;
use App\Http\Requests\UpdateBeritaRequest;
use App\Services\BeritaService;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;

class BeritaController extends Controller
{
    public function __construct(protected BeritaService $beritaService) {}

    public function index(Request $request)
    {
        Gate::authorize('viewAny', Berita::class);

        $berita = Berita::query()
            ->filter($request->only(['search', 'status', 'tag']))
            ->latest()
            ->paginate(5)
            ->withQueryString();

        $allTags = Berita::whereNotNull('tags')
            ->pluck('tags')
            ->flatten()
            ->unique()
            ->sort()
            ->values()
            ->toArray();

        return Inertia::render('Admin/Berita', [
            'berita' => $berita,
            'filters' => $request->only(['search', 'status', 'tag']),
            'available_tags' => $allTags,
        ]);
    }

    public function store(StoreBeritaRequest $request)
    {
        Gate::authorize('create', Berita::class);

        $this->beritaService->store($request->validated());

        return redirect()->back()->with('message', 'Berita berhasil ditambahkan');
    }

    public function update(UpdateBeritaRequest $request, Berita $berita)
    {
        Gate::authorize('update', $berita);

        $this->beritaService->update($berita, $request->validated());

        return redirect()->back()->with('message', 'Berita berhasil diperbarui');
    }

    public function destroy(Berita $berita)
    {
        Gate::authorize('delete', $berita);

        $this->beritaService->delete($berita);

        return redirect()->back()->with('message', 'Berita berhasil dihapus');
    }
}

