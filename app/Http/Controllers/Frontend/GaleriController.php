<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Galeri;

class GaleriController extends Controller
{
    public function index()
    {
        $galeriList = Galeri::where('is_published', true)
            ->whereNotNull('foto')
            ->latest('tanggal_kegiatan')
            ->paginate(8);

        return Inertia::render('Galeri', [
            'galeriList' => $galeriList,
        ]);
    }
}
