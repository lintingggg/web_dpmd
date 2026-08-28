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
            $k = strtolower($request->kategori);

            switch ($k) {
                case 'perencanaan':
                    $query->where('kategori', PublikasiDokumen::CATEGORY_PERENCANAAN);
                    break;
                case 'lakip':
                    $query->where('kategori', PublikasiDokumen::CATEGORY_LAKIP);
                    break;
                case 'perjanjian-kinerja':
                case 'perjanjian_kinerja':
                    $query->where('kategori', PublikasiDokumen::CATEGORY_PERJANJIAN_KINERJA);
                    break;
                case 'sakip':
                    $query->where('kategori', PublikasiDokumen::CATEGORY_SAKIP);
                    break;
                case 'transparansi-apbd':
                case 'transparansi_apbd':
                    $query->where('kategori', PublikasiDokumen::CATEGORY_TRANSPARANSI_APBD);
                    break;
                default:
                    $query->where('kategori', $request->kategori);
                    break;
            }
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
