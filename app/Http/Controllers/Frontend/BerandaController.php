<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Berita;
use App\Models\Pengumuman;
use App\Models\Galeri;
use App\Models\PengaturanBeranda;

class BerandaController extends Controller
{
    public function index()
    {
        $beritaTerkini = Berita::where('is_published', true)
            ->latest('published_at')
            ->take(3)
            ->get();

        $pengumumanList = Pengumuman::where('is_published', true)
            ->latest('tanggal')
            ->take(4)
            ->get();

        $galeriHighlight = Galeri::where('is_published', true)
            ->whereNotNull('foto')
            ->latest('tanggal_kegiatan')
            ->take(4)
            ->get();

        $pengaturanBeranda = PengaturanBeranda::first();

        return Inertia::render('HalamanUtama', [
            'beritaTerkini' => $beritaTerkini,
            'pengumumanList' => $pengumumanList,
            'galeriHighlight' => $galeriHighlight,
            'pengaturanBeranda' => $pengaturanBeranda,
        ]);
    }
}
