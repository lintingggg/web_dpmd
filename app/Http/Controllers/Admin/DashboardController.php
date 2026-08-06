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

        // Get recent activities from ActivityLog
        $user = \Illuminate\Support\Facades\Auth::user();
        $query = \App\Models\ActivityLog::with('user');
        
        if ($user->role !== 'superadmin') {
            $query->where('subject_type', \App\Models\Berita::class);
        }

        $recentActivities = $query->latest()->take(6)->get()->map(function ($log) {
            $type = $log->subject_type ? class_basename($log->subject_type) : 'Sistem';
            return [
                'type' => $type,
                'title' => $log->description,
                'status' => $log->action,
                'author' => $log->user ? $log->user->name : 'Anonim',
                'time' => $log->created_at->diffForHumans(),
                'updated_at' => $log->created_at
            ];
        });

        $agendaTerdekat = Agenda::where('tanggal', '>=', date('Y-m-d'))
                                ->orderBy('tanggal', 'asc')
                                ->take(3)
                                ->get();

        return Inertia::render('Dashboard', [
            'totalBerita' => $totalBerita,
            'totalAgenda' => $totalAgenda,
            'totalDokumen' => $totalDokumen,
            'totalGaleri' => $totalGaleri,
            'recentActivities' => $recentActivities,
            'agendaTerdekat' => $agendaTerdekat
        ]);
    }
}
