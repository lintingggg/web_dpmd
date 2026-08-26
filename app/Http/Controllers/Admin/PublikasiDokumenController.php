<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PublikasiDokumen;
use Inertia\Inertia;
use App\Services\PublikasiDokumenService;
use App\Http\Requests\StorePublikasiDokumenRequest;
use App\Http\Requests\UpdatePublikasiDokumenRequest;
use Illuminate\Support\Facades\Gate;

class PublikasiDokumenController extends Controller
{
    public function __construct(protected PublikasiDokumenService $publikasiDokumenService) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        Gate::authorize('viewAny', PublikasiDokumen::class);

        $query = PublikasiDokumen::query();

        if ($request->filled('kategori') && in_array($request->kategori, PublikasiDokumen::CATEGORIES)) {
            $query->where('kategori', $request->kategori);
        } else {
            // Default kategori
            $query->where('kategori', PublikasiDokumen::CATEGORY_PERENCANAAN);
        }

        if ($request->filled('search')) {
            $query->where('judul', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('tahun')) {
            $query->where('tahun', $request->tahun);
        }

        if ($request->filled('status') && in_array($request->status, ['draft', 'publish'])) {
            $query->where('is_published', $request->status === 'publish');
        }

        $dokumen = $query->latest()->paginate(5)->withQueryString();

        return Inertia::render('Admin/PublikasiDokumen', [
            'dokumen' => $dokumen,
            'filters' => $request->only(['kategori', 'search', 'tahun', 'status']),
            'categories' => PublikasiDokumen::CATEGORY_LABELS,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePublikasiDokumenRequest $request)
    {
        Gate::authorize('create', PublikasiDokumen::class);

        $this->publikasiDokumenService->storeDokumen($request->validated(), $request);

        return redirect()->back()->with('message', 'Dokumen publikasi berhasil ditambahkan');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePublikasiDokumenRequest $request, PublikasiDokumen $dokumen)
    {
        Gate::authorize('update', $dokumen);

        $this->publikasiDokumenService->updateDokumen($dokumen, $request->validated(), $request);

        return redirect()->back()->with('message', 'Dokumen publikasi berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PublikasiDokumen $dokumen)
    {
        Gate::authorize('delete', $dokumen);

        $this->publikasiDokumenService->deleteDokumen($dokumen);

        return redirect()->back()->with('message', 'Dokumen publikasi berhasil dihapus');
    }
}
