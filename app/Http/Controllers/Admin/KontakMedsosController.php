<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\PengaturanKontak;

class KontakMedsosController extends Controller
{
    /**
     * Show the form for editing the specified resource.
     */
    public function edit()
    {
        $kontak = PengaturanKontak::firstOrCreate(['id' => 1]);
        
        return Inertia::render('Admin/KontakMedsos', [
            'kontak' => $kontak
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'alamat'        => 'nullable|string|max:500',
            'email'         => 'nullable|email|max:255',
            'telepon'       => 'nullable|string|max:30',
            'whatsapp'      => 'nullable|string|max:30',
            'jam_kerja'     => 'nullable|string|max:100',
            'koordinat_map' => ['nullable', 'string', 'regex:/^-?\d+(\.\d+)?,\s?-?\d+(\.\d+)?$/'],
            'facebook_url'  => 'nullable|url|max:255',
            'instagram_url' => 'nullable|url|max:255',
            'youtube_url'   => 'nullable|url|max:255',
            'twitter_url'   => 'nullable|url|max:255',
        ], [
            'koordinat_map.regex' => 'Format koordinat tidak valid. Contoh yang benar: -7.0270059, 112.7483669'
        ]);

        PengaturanKontak::updateOrCreate(
            ['id' => 1],
            $validated
        );

        return redirect()->back()->with('message', 'Pengaturan kontak & media sosial berhasil diperbarui.');
    }
}
