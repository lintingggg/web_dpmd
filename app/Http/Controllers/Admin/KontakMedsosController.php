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
            'tiktok_url'    => 'nullable|url|max:255',
            
            // Toggles
            'show_instagram' => 'boolean',
            'show_tiktok' => 'boolean',
            'show_youtube' => 'boolean',
            'show_facebook' => 'boolean',
            'show_twitter' => 'boolean',

            // Embeds
            'instagram_embed_1' => 'required_if:show_instagram,true|nullable|string',
            'instagram_embed_2' => 'required_if:show_instagram,true|nullable|string',
            
            'tiktok_embed_1' => 'required_if:show_tiktok,true|nullable|string',
            'tiktok_embed_2' => 'required_if:show_tiktok,true|nullable|string',
            
            'youtube_embed_1' => 'required_if:show_youtube,true|nullable|string',
            'youtube_embed_2' => 'required_if:show_youtube,true|nullable|string',
            
            'facebook_embed_1' => 'required_if:show_facebook,true|nullable|string',
            'facebook_embed_2' => 'required_if:show_facebook,true|nullable|string',
            
            'twitter_embed_1' => 'required_if:show_twitter,true|nullable|string',
            'twitter_embed_2' => 'required_if:show_twitter,true|nullable|string',
        ], [
            'koordinat_map.regex' => 'Format koordinat tidak valid. Contoh yang benar: -7.0270059, 112.7483669',
            'instagram_embed_1.required_if' => 'Kode Embed 1 wajib diisi jika Instagram ditampilkan.',
            'instagram_embed_2.required_if' => 'Kode Embed 2 wajib diisi jika Instagram ditampilkan.',
            'tiktok_embed_1.required_if' => 'Kode Embed 1 wajib diisi jika TikTok ditampilkan.',
            'tiktok_embed_2.required_if' => 'Kode Embed 2 wajib diisi jika TikTok ditampilkan.',
            'youtube_embed_1.required_if' => 'Kode Embed 1 wajib diisi jika YouTube ditampilkan.',
            'youtube_embed_2.required_if' => 'Kode Embed 2 wajib diisi jika YouTube ditampilkan.',
            'facebook_embed_1.required_if' => 'Kode Embed 1 wajib diisi jika Facebook ditampilkan.',
            'facebook_embed_2.required_if' => 'Kode Embed 2 wajib diisi jika Facebook ditampilkan.',
            'twitter_embed_1.required_if' => 'Kode Embed 1 wajib diisi jika X (Twitter) ditampilkan.',
            'twitter_embed_2.required_if' => 'Kode Embed 2 wajib diisi jika X (Twitter) ditampilkan.',
        ]);

        PengaturanKontak::updateOrCreate(
            ['id' => 1],
            $validated
        );

        return redirect()->back()->with('message', 'Pengaturan kontak & media sosial berhasil diperbarui.');
    }
}
