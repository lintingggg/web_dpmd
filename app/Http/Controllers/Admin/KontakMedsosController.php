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
            'telepon'       => ['nullable', 'string', 'min:9', 'max:20', 'regex:/^[\+0-9\s\-\(\)]+$/'],
            'whatsapp'      => ['nullable', 'string', 'min:9', 'max:20', 'regex:/^[\+0-9\s\-\(\)]+$/'],
            'jam_kerja'     => 'nullable|string|max:100',
            'koordinat_map' => ['nullable', 'string', 'regex:/^-?\d+(\.\d+)?,\s?-?\d+(\.\d+)?$/'],
            'facebook_url'  => 'nullable|url|max:255',
            'instagram_url' => 'nullable|url|max:255',
            'youtube_url'   => 'nullable|url|max:255',
            'twitter_url'   => 'nullable|url|max:255',
            'tiktok_url'    => 'nullable|url|max:255',
            
            // Toggles
            'show_instagram' => 'boolean',
            'show_tiktok' => 'boolean',
            'show_youtube' => 'boolean',
            'show_facebook' => 'boolean',
            'show_twitter' => 'boolean',

            // Embeds
            'instagram_embed_1' => 'nullable|string',
            'instagram_embed_2' => 'nullable|string',
            
            'tiktok_embed_1' => 'nullable|string',
            'tiktok_embed_2' => 'nullable|string',
            
            'youtube_embed_1' => 'nullable|string',
            'youtube_embed_2' => 'nullable|string',
            
            'facebook_embed_1' => 'nullable|string',
            'facebook_embed_2' => 'nullable|string',
            
            'twitter_embed_1' => 'nullable|string',
            'twitter_embed_2' => 'nullable|string',
        ], [
            'koordinat_map.regex' => 'Format koordinat tidak valid. Contoh yang benar: -7.0270059, 112.7483669',
            'telepon.regex' => 'Format nomor telepon tidak valid. Hanya boleh berisi angka, spasi, tanda tambah (+), tanda hubung (-), atau kurung.',
            'telepon.min' => 'Nomor telepon terlalu pendek (minimal 9 karakter).',
            'whatsapp.regex' => 'Format nomor WhatsApp tidak valid. Hanya boleh berisi angka, spasi, tanda tambah (+), tanda hubung (-), atau kurung.',
            'whatsapp.min' => 'Nomor WhatsApp terlalu pendek (minimal 9 karakter).',
        ]);

        PengaturanKontak::updateOrCreate(
            ['id' => 1],
            $validated
        );

        return redirect()->back()->with('message', 'Pengaturan kontak & media sosial berhasil diperbarui.');
    }
}
