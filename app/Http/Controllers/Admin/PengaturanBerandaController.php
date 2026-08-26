<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use App\Models\PengaturanBeranda;
use App\Services\PengaturanBerandaService;
use App\Http\Requests\UpdatePengaturanBerandaRequest;
use Illuminate\Support\Facades\Gate;

class PengaturanBerandaController extends Controller
{
    public function __construct(protected PengaturanBerandaService $pengaturanService) {}

    public function edit()
    {
        Gate::authorize('viewAny', PengaturanBeranda::class);

        $pengaturan = PengaturanBeranda::first();
        return Inertia::render('Admin/PengaturanBeranda', [
            'pengaturan' => $pengaturan
        ]);
    }

    public function update(UpdatePengaturanBerandaRequest $request)
    {
        Gate::authorize('update', PengaturanBeranda::class);

        $this->pengaturanService->updatePengaturan($request->validated(), $request);

        return redirect()->back()->with('success', 'Pengaturan Beranda berhasil diperbarui.');
    }
}
