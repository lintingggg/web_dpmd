<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\BidangTugas;
use Illuminate\Support\Facades\Storage;
use Mews\Purifier\Facades\Purifier;

class BidangTugasController extends Controller
{
    const VALID_SECTIONS = [
        'pemdes', 
        'pemberdayaan', 
        'lembaga', 
        'sekretariat'
    ];

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(?string $section = null)
    {
        $section = $section ?? 'pemdes';
        abort_unless(in_array($section, self::VALID_SECTIONS), 404);

        $bidangTugas = BidangTugas::firstOrCreate(['id' => 1]);
        
        return Inertia::render('Admin/BidangTugas', [
            'bidangTugas' => $bidangTugas,
            'section' => $section
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $section)
    {
        abort_unless(in_array($section, self::VALID_SECTIONS), 404);
        
        $bidang = BidangTugas::firstOrCreate(['id' => 1]);

        $rules = [
            "{$section}_gambar" => 'nullable|image|mimes:jpg,jpeg,png|max:1024',
            "{$section}_konten" => 'required|string',
        ];

        $validated = $request->validate($rules);

        // Sanitasi HTML
        if (isset($validated["{$section}_konten"])) {
            $validated["{$section}_konten"] = Purifier::clean($validated["{$section}_konten"], 'profil_dinas');
        }

        // Handle file uploads
        if ($request->hasFile("{$section}_gambar")) {
            if ($bidang->{"{$section}_gambar"}) {
                Storage::disk('public')->delete($bidang->{"{$section}_gambar"});
            }
            $validated["{$section}_gambar"] = $request->file("{$section}_gambar")->store('bidang-tugas', 'public');
        }

        // Simpan
        $bidang->update($validated);

        return redirect()->back()->with('message', 'Perubahan bidang tugas berhasil disimpan.');
    }
}
