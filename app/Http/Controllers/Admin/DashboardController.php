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

        $recentActivities = $query->latest()->take(5)->get()->map(function ($log) {
            return [
                'id' => $log->id,
                'description' => $log->description,
                'subject_type' => $log->subject_type,
                'event' => $log->action, // Maps 'action' to 'event' for the badge
                'created_at' => $log->created_at->toIso8601String(),
            ];
        });

        $agendaTerdekat = Agenda::where('tanggal', '>=', date('Y-m-d'))
                                ->orderBy('tanggal', 'asc')
                                ->take(3)
                                ->get();

        // Query visitor stats for the last 7 days optimized to a single query
        $startDate = now()->subDays(6)->toDateString();
        $endDate = now()->toDateString();
        
        $visitorCounts = \App\Models\VisitorLog::whereBetween('visit_date', [$startDate, $endDate])
            ->selectRaw('visit_date, count(*) as count')
            ->groupBy('visit_date')
            ->pluck('count', 'visit_date');

        $visitorStats = [];
        $dayLabels = [
            'Monday' => 'Sen',
            'Tuesday' => 'Sel',
            'Wednesday' => 'Rab',
            'Thursday' => 'Kam',
            'Friday' => 'Jum',
            'Saturday' => 'Sab',
            'Sunday' => 'Min'
        ];

        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $dateString = $date->toDateString();
            $englishDay = $date->format('l');
            $label = $dayLabels[$englishDay] ?? substr($englishDay, 0, 3);
            
            $count = $visitorCounts[$dateString] ?? 0;
            
            $visitorStats[] = [
                'label' => $label,
                'count' => $count,
                'date' => $dateString
            ];
        }

        return Inertia::render('Dashboard', [
            'totalBerita' => $totalBerita,
            'totalAgenda' => $totalAgenda,
            'totalDokumen' => $totalDokumen,
            'totalGaleri' => $totalGaleri,
            'recentActivities' => $recentActivities,
            'agendaTerdekat' => $agendaTerdekat,
            'visitorStats' => $visitorStats
        ]);
    }
}
