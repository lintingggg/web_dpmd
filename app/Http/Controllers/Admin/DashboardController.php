<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Berita;
use App\Models\Agenda;
use App\Models\PublikasiDokumen;
use App\Models\Galeri;

class DashboardController extends Controller
{
    public function index()
    {
        $totalBerita = Berita::count();
        $totalAgenda = Agenda::count();
        $totalDokumen = PublikasiDokumen::count();
        $totalGaleri = Galeri::count();

        // Get recent activities from multiple models
        $recentBerita = Berita::orderBy('updated_at', 'desc')->take(3)->get()->map(function ($item) {
            return [
                'type' => 'Berita',
                'title' => $item->judul,
                'status' => $item->is_published ? 'Dipublikasikan' : 'Draft',
                'author' => $item->penulis ?? 'Admin',
                'time' => $item->updated_at->diffForHumans(),
                'updated_at' => $item->updated_at
            ];
        });

        $recentAgenda = Agenda::orderBy('updated_at', 'desc')->take(3)->get()->map(function ($item) {
            return [
                'type' => 'Agenda',
                'title' => $item->judul,
                'status' => 'Aktif',
                'author' => 'Admin',
                'time' => $item->updated_at->diffForHumans(),
                'updated_at' => $item->updated_at
            ];
        });

        $recentDokumen = PublikasiDokumen::orderBy('updated_at', 'desc')->take(3)->get()->map(function ($item) {
            return [
                'type' => 'Dokumen',
                'title' => $item->judul,
                'status' => 'Diunggah',
                'author' => 'Admin',
                'time' => $item->updated_at->diffForHumans(),
                'updated_at' => $item->updated_at
            ];
        });
        
        $recentGaleri = Galeri::orderBy('updated_at', 'desc')->take(3)->get()->map(function ($item) {
            return [
                'type' => 'Galeri',
                'title' => $item->judul,
                'status' => 'Diunggah',
                'author' => 'Admin',
                'time' => $item->updated_at->diffForHumans(),
                'updated_at' => $item->updated_at
            ];
        });

        $recentActivities = collect()
            ->merge($recentBerita)
            ->merge($recentAgenda)
            ->merge($recentDokumen)
            ->merge($recentGaleri)
            ->sortByDesc('updated_at')
            ->take(6)
            ->values();

        return Inertia::render('Dashboard', [
            'totalBerita' => $totalBerita,
            'totalAgenda' => $totalAgenda,
            'totalDokumen' => $totalDokumen,
            'totalGaleri' => $totalGaleri,
            'recentActivities' => $recentActivities
        ]);
    }
}
