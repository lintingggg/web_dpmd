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
        $albums = \App\Models\Album::where('is_published', true)
            ->latest()
            ->paginate(8);

        return Inertia::render('Galeri', [
            'albums' => $albums,
        ]);
    }

    public function show($id)
    {
        $album = \App\Models\Album::where('is_published', true)->findOrFail($id);
        
        $galeriList = Galeri::where('album_id', $id)
            ->where('is_published', true)
            ->whereNotNull('foto')
            ->latest('tanggal_kegiatan')
            ->paginate(12);

        return Inertia::render('GaleriDetail', [
            'album' => $album,
            'galeriList' => $galeriList,
        ]);
    }
}
