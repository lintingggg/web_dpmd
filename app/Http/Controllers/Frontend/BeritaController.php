<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Berita;

class BeritaController extends Controller
{
    public function index(Request $request)
    {
        $query = Berita::where('is_published', true);

        if ($request->has('search') && $request->search !== '') {
            $query->where('judul', 'like', '%' . $request->search . '%')
                  ->orWhere('konten', 'like', '%' . $request->search . '%');
        }

        $beritaList = $query->latest('published_at')->paginate(9)->withQueryString();

        return Inertia::render('Berita', [
            'beritaList' => $beritaList,
            'filters' => $request->only(['search']),
        ]);
    }

    public function show($slug)
    {
        $berita = Berita::where('is_published', true)
            ->where('slug', $slug)
            ->firstOrFail();

        // Get 4 related news by tags, or fallback to latest
        $beritaTerkiniQuery = Berita::where('is_published', true)
            ->where('id', '!=', $berita->id);

        if (!empty($berita->tags) && is_array($berita->tags) && count($berita->tags) > 0) {
            $beritaTerkiniQuery->where(function ($q) use ($berita) {
                foreach ($berita->tags as $tag) {
                    $q->orWhereJsonContains('tags', $tag);
                }
            });
        }

        $beritaTerkini = $beritaTerkiniQuery->latest('published_at')->take(4)->get();

        // If no related news found, fallback to latest
        if ($beritaTerkini->isEmpty()) {
            $beritaTerkini = Berita::where('is_published', true)
                ->where('id', '!=', $berita->id)
                ->latest('published_at')
                ->take(4)
                ->get();
        }

        return Inertia::render('BeritaDetail', [
            'berita' => $berita,
            'beritaTerkini' => $beritaTerkini,
        ]);
    }
}
