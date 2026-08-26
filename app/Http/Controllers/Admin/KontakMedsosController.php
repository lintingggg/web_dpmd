<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\PengaturanKontak;
use App\Http\Requests\UpdateKontakMedsosRequest;
use App\Services\KontakMedsosService;
use Illuminate\Support\Facades\Gate;

class KontakMedsosController extends Controller
{
    const VALID_SECTIONS = [
        'kontak',
        'sosmed',
        'embedding'
    ];

    public function __construct(protected KontakMedsosService $kontakService) {}

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(?string $section = null)
    {
        Gate::authorize('viewAny', PengaturanKontak::class);

        $section = $section ?? 'kontak';
        abort_unless(in_array($section, self::VALID_SECTIONS), 404);

        $kontak = PengaturanKontak::firstOrCreate(['id' => 1]);
        
        return Inertia::render('Admin/KontakMedsos', [
            'kontak' => $kontak,
            'section' => $section
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateKontakMedsosRequest $request, string $section)
    {
        Gate::authorize('update', PengaturanKontak::class);

        abort_unless(in_array($section, self::VALID_SECTIONS), 404);

        $this->kontakService->updateKontak($section, $request->validated());

        return redirect()->back()->with('message', 'Pengaturan berhasil diperbarui.');
    }
}
