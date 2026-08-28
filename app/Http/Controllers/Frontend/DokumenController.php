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

            // Map dropdown keys to actual kategori or keyword filters
            if (in_array($k, [PublikasiDokumen::CATEGORY_PERENCANAAN, PublikasiDokumen::CATEGORY_PERATURAN, PublikasiDokumen::CATEGORY_LAINNYA])) {
                $query->where('kategori', $k);
            } else {
                // Special dropdown items mapped to perencanaan with optional keyword narrowing
                switch ($k) {
                    case 'lakip':
                        $query->where('kategori', PublikasiDokumen::CATEGORY_PERENCANAAN)
                              ->where('judul', 'like', '%LAKIP%');
                        break;
                    case 'perjanjian-kinerja':
                    case 'perjanjian_kinerja':
                        $query->where('kategori', PublikasiDokumen::CATEGORY_PERENCANAAN)
                              ->where('judul', 'like', '%Perjanjian Kinerja%');
                        break;
                    case 'sakip':
                        $query->where('kategori', PublikasiDokumen::CATEGORY_PERENCANAAN)
                              ->where('judul', 'like', '%SAKIP%');
                        break;
                    case 'transparansi-apbd':
                    case 'transparansi_apbd':
                        $query->where('kategori', PublikasiDokumen::CATEGORY_PERENCANAAN)
                              ->where(function($q) {
                                  $q->where('judul', 'like', '%Transparansi%')
                                    ->orWhere('judul', 'like', '%APBD%');
                              });
                        break;
                    case 'perencanaan':
                    default:
                        // Fallback to perencanaan
                        $query->where('kategori', PublikasiDokumen::CATEGORY_PERENCANAAN);
                        break;
                }
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
