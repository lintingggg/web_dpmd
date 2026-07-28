<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\PublikasiDokumen;

class DokumenController extends Controller
{
    public function index(Request $request)
    {
        $query = PublikasiDokumen::where('is_published', true);
        
        // Cek kategori filter dari query string
        if ($request->has('kategori') && $request->kategori !== '') {
            $query->where('kategori', $request->kategori);
        }
        
        // Cek search dari query string
        if ($request->has('search') && $request->search !== '') {
            $searchTerm = '%' . $request->search . '%';
            $query->where(function($q) use ($searchTerm) {
                $q->where('judul', 'like', $searchTerm)
                  ->orWhere('deskripsi', 'like', $searchTerm);
            });
        }
        
        $dokumenList = $query->latest()->paginate(10)->withQueryString();

        return Inertia::render('DokumenDanPeraturan', [
            'dokumenList' => $dokumenList,
            'filters' => request()->only(['kategori', 'search', 'per_page']),
        ]);
    }

    public function show(string $id)
    {
        $dokumen = PublikasiDokumen::where('is_published', true)->findOrFail($id);
        
        return Inertia::render('DokumenDetail', [
            'dokumen' => $dokumen
        ]);
    }
}
