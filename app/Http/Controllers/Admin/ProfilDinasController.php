<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use App\Models\ProfilDinas;
use App\Services\ProfilDinasService;
use App\Http\Requests\UpdateProfilDinasRequest;
use Illuminate\Support\Facades\Gate;

class ProfilDinasController extends Controller
{
    const VALID_SECTIONS = [
        'sambutan', 
        'visi-misi', 
        'tupoksi', 
        'struktur', 
        'kode-etik', 
        'maklumat', 
        'motto'
    ];

    public function __construct(protected ProfilDinasService $profilDinasService) {}

    public function edit(?string $section = null)
    {
        Gate::authorize('viewAny', ProfilDinas::class);

        $section = $section ?? 'sambutan';
        abort_unless(in_array($section, self::VALID_SECTIONS), 404);

        $profil = ProfilDinas::firstOrCreate(['id' => 1]);
        
        return Inertia::render('Admin/ProfilDinas', [
            'profil' => $profil,
            'section' => $section
        ]);
    }

    public function update(UpdateProfilDinasRequest $request, string $section)
    {
        Gate::authorize('update', ProfilDinas::class);
        abort_unless(in_array($section, self::VALID_SECTIONS), 404);
        
        $profil = ProfilDinas::firstOrCreate(['id' => 1]);

        $this->profilDinasService->updateSection($profil, $section, $request->validated(), $request);

        return redirect()->back()->with('message', 'Perubahan profil dinas berhasil disimpan.');
    }
}

