<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\BidangTugas;
use App\Services\BidangTugasService;
use App\Http\Requests\UpdateBidangTugasRequest;
use Illuminate\Support\Facades\Gate;

class BidangTugasController extends Controller
{
    const VALID_SECTIONS = [
        'pemdes', 
        'pemberdayaan', 
        'lembaga', 
        'sekretariat'
    ];

    public function __construct(protected BidangTugasService $bidangTugasService) {}

    public function edit(?string $section = null)
    {
        Gate::authorize('viewAny', BidangTugas::class);

        $section = $section ?? 'pemdes';
        abort_unless(in_array($section, self::VALID_SECTIONS), 404);

        $bidangTugas = BidangTugas::firstOrCreate(['id' => 1]);
        
        return Inertia::render('Admin/BidangTugas', [
            'bidangTugas' => $bidangTugas,
            'section' => $section
        ]);
    }

    public function update(UpdateBidangTugasRequest $request, string $section)
    {
        Gate::authorize('update', BidangTugas::class);
        abort_unless(in_array($section, self::VALID_SECTIONS), 404);
        
        $bidang = BidangTugas::firstOrCreate(['id' => 1]);

        $this->bidangTugasService->updateSection($bidang, $section, $request->validated(), $request);

        return redirect()->back()->with('message', 'Perubahan bidang tugas berhasil disimpan.');
    }
}

