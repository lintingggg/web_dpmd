<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Berita;
use App\Models\Agenda;
use App\Models\Galeri;
use App\Models\PengaturanBeranda;

class BerandaController extends Controller
{
    public function index(Request $request)
    {
        $beritaTerkini = Berita::where('is_published', true)
            ->latest('published_at')
            ->take(3)
            ->get();

        // Validasi & sanitasi input bulan/tahun
        $month = $request->integer('month', now()->month);
        $year  = $request->integer('year',  now()->year);

        // Clamp ke nilai valid
        $month = max(1, min(12, $month));
        $year  = max(2000, min(2100, $year));

        $agendaList = Agenda::whereYear('tanggal', $year)
            ->whereMonth('tanggal', $month)
            ->where('is_published', true)
            ->orderBy('tanggal')
            ->get();

        // Format tanggal_ada_acara: array of "YYYY-MM-DD" string
        $tanggalAdaAcara = $agendaList->pluck('tanggal')
            ->map(fn($d) => $d->format('Y-m-d'))
            ->unique()
            ->values()
            ->all();

        // Map timezone
        $tz = config('app.timezone');
        $timezoneLabel = 'WIB'; // Default
        if ($tz === 'Asia/Makassar') $timezoneLabel = 'WITA';
        if ($tz === 'Asia/Jayapura') $timezoneLabel = 'WIT';

        $galeriHighlight = Galeri::where('is_published', true)
            ->whereNotNull('foto')
            ->latest('tanggal_kegiatan')
            ->take(4)
            ->get();

        $pengaturanBeranda = PengaturanBeranda::first();

        return Inertia::render('HalamanUtama', [
            'beritaTerkini' => $beritaTerkini,
            'agendaList' => $agendaList,
            'tanggalAdaAcara' => $tanggalAdaAcara,
            'bulan'           => $month,
            'tahun'           => $year,
            'timezoneLabel'   => $timezoneLabel,
            'galeriHighlight' => $galeriHighlight,
            'pengaturanBeranda' => $pengaturanBeranda,
        ]);
    }
}
