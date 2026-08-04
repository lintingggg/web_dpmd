<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\ProfilDinas;
use Illuminate\Support\Facades\Storage;
use Mews\Purifier\Facades\Purifier;

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

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(?string $section = null)
    {
        $section = $section ?? 'sambutan';
        abort_unless(in_array($section, self::VALID_SECTIONS), 404);

        $profil = ProfilDinas::firstOrCreate(['id' => 1]);
        
        return Inertia::render('Admin/ProfilDinas', [
            'profil' => $profil,
            'section' => $section
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $section)
    {
        abort_unless(in_array($section, self::VALID_SECTIONS), 404);
        
        $profil = ProfilDinas::firstOrCreate(['id' => 1]);

        $rules = match($section) {
            'sambutan'  => [
                'kadis_nama'    => 'nullable|string|max:255',
                'kadis_nip'     => ['nullable', 'string', 'min:18', 'max:50', 'regex:/^[0-9\s]+$/'],
                'sambutan_teks' => 'nullable|string',
                'kadis_foto'    => 'nullable|image|mimes:jpg,jpeg,png|max:1024',
            ],
            'visi-misi' => [
                'visi_teks' => 'nullable|string',
                'misi_teks' => 'nullable|string',
            ],
            'tupoksi'   => [
                'tupoksi_teks' => 'nullable|string',
                'tupoksi_dokumen' => 'nullable|file|mimes:pdf|max:10240',
            ],
            'struktur'  => [
                'struktur_keterangan' => 'nullable|string',
                'struktur_gambar'     => 'nullable|image|mimes:jpg,jpeg,png|max:1024',
            ],
            'kode-etik' => [
                'kode_etik_teks' => 'nullable|string',
                'kode_etik_dokumen' => 'nullable|file|mimes:pdf|max:10240',
            ],
            'maklumat'  => [
                'maklumat_teks' => 'nullable|string',
                'maklumat_dokumen' => 'nullable|file|mimes:pdf|max:10240',
            ],
            'motto'     => [
                'motto_teks' => 'nullable|string',
            ],
            default => [],
        };

        $messages = [
            'kadis_nip.regex' => 'NIP hanya boleh berisi angka dan spasi.',
            'kadis_nip.min' => 'NIP harus berisi minimal 18 karakter.',
        ];

        $validated = $request->validate($rules, $messages);

        // Sanitasi HTML
        $htmlFields = [
            'sambutan_teks', 'visi_teks', 'misi_teks', 'tupoksi_teks',
            'struktur_keterangan', 'kode_etik_teks', 'maklumat_teks', 'motto_teks'
        ];

        foreach ($htmlFields as $field) {
            if (isset($validated[$field])) {
                $validated[$field] = Purifier::clean($validated[$field], 'profil_dinas');
            }
        }

        // Handle file uploads
        if ($request->hasFile('kadis_foto')) {
            if ($profil->kadis_foto) {
                Storage::disk('public')->delete($profil->kadis_foto);
            }
            $validated['kadis_foto'] = $request->file('kadis_foto')->store('profil-dinas/foto', 'public');
        }

        if ($request->hasFile('struktur_gambar')) {
            if ($profil->struktur_gambar) {
                Storage::disk('public')->delete($profil->struktur_gambar);
            }
            $validated['struktur_gambar'] = $request->file('struktur_gambar')->store('profil-dinas/struktur', 'public');
        }

        if ($request->hasFile('maklumat_dokumen')) {
            if ($profil->maklumat_dokumen) {
                Storage::disk('public')->delete($profil->maklumat_dokumen);
            }
            $validated['maklumat_dokumen'] = $request->file('maklumat_dokumen')->store('profil-dinas/dokumen', 'public');
        }

        if ($request->hasFile('tupoksi_dokumen')) {
            if ($profil->tupoksi_dokumen) {
                Storage::disk('public')->delete($profil->tupoksi_dokumen);
            }
            $validated['tupoksi_dokumen'] = $request->file('tupoksi_dokumen')->store('profil-dinas/dokumen', 'public');
        }

        if ($request->hasFile('kode_etik_dokumen')) {
            if ($profil->kode_etik_dokumen) {
                Storage::disk('public')->delete($profil->kode_etik_dokumen);
            }
            $validated['kode_etik_dokumen'] = $request->file('kode_etik_dokumen')->store('profil-dinas/dokumen', 'public');
        }

        // Simpan
        $profil->update($validated);

        return redirect()->back()->with('message', 'Perubahan profil dinas berhasil disimpan.');
    }
}
