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

        $dokumenList = $query->latest('tanggal_publikasi')->paginate(10)->withQueryString();

        return Inertia::render('DokumenDanPeraturan', [
            'dokumenList' => $dokumenList,
            'filters' => request()->only(['kategori']),
        ]);
    }
}
