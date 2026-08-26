<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class UpdateKontakMedsosRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $section = $this->route('section');

        return match($section) {
            'kontak' => [
                'alamat'        => 'nullable|string|max:500',
                'email'         => 'nullable|email|max:255',
                'telepon'       => ['nullable', 'string', 'min:9', 'max:20', 'regex:/^[\+0-9\s\-\(\)]+$/'],
                'whatsapp'      => ['nullable', 'string', 'min:9', 'max:20', 'regex:/^[\+0-9\s\-\(\)]+$/'],
                'jam_kerja'     => 'nullable|string|max:100',
                'koordinat_map' => ['nullable', 'string', 'regex:/^-?\d+(\.\d+)?,\s?-?\d+(\.\d+)?$/'],
            ],
            'sosmed' => [
                'facebook_url'  => 'nullable|url|max:255',
                'instagram_url' => 'nullable|url|max:255',
                'youtube_url'   => 'nullable|url|max:255',
                'twitter_url'   => 'nullable|url|max:255',
                'tiktok_url'    => 'nullable|url|max:255',
                'show_instagram' => 'boolean',
                'show_tiktok' => 'boolean',
                'show_youtube' => 'boolean',
                'show_facebook' => 'boolean',
                'show_twitter' => 'boolean',
            ],
            'embedding' => [
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
                'show_instagram' => 'boolean',
                'show_tiktok' => 'boolean',
                'show_youtube' => 'boolean',
                'show_facebook' => 'boolean',
                'show_twitter' => 'boolean',
            ],
            default => []
        };
    }

    public function messages(): array
    {
        return [
            'koordinat_map.regex' => 'Format koordinat tidak valid. Contoh yang benar: -7.0270059, 112.7483669',
            'telepon.regex' => 'Format nomor telepon tidak valid. Hanya boleh berisi angka, spasi, tanda tambah (+), tanda hubung (-), atau kurung.',
            'telepon.min' => 'Nomor telepon terlalu pendek (minimal 9 karakter).',
            'whatsapp.regex' => 'Format nomor WhatsApp tidak valid. Hanya boleh berisi angka, spasi, tanda tambah (+), tanda hubung (-), atau kurung.',
            'whatsapp.min' => 'Nomor WhatsApp terlalu pendek (minimal 9 karakter).',
        ];
    }

    protected function passedValidation()
    {
        $section = $this->route('section');
        $validated = $this->validated();

        if ($section === 'sosmed' || $section === 'embedding') {
            // Explicitly cast boolean fields in case they are sent as strings
            $validated['show_instagram'] = filter_var($this->input('show_instagram', true), FILTER_VALIDATE_BOOLEAN);
            $validated['show_tiktok'] = filter_var($this->input('show_tiktok', true), FILTER_VALIDATE_BOOLEAN);
            $validated['show_youtube'] = filter_var($this->input('show_youtube', true), FILTER_VALIDATE_BOOLEAN);
            $validated['show_facebook'] = filter_var($this->input('show_facebook', true), FILTER_VALIDATE_BOOLEAN);
            $validated['show_twitter'] = filter_var($this->input('show_twitter', true), FILTER_VALIDATE_BOOLEAN);

            if ($section === 'embedding') {
                if ($validated['show_facebook'] && empty($validated['facebook_embed_1']) && empty($validated['facebook_embed_2']) && empty($this->input('facebook_url'))) {
                    throw ValidationException::withMessages([
                        'facebook_embed_1' => ['Isi kode embed Facebook atau tambahkan tautan Facebook jika Facebook ditampilkan di Widget.'],
                    ]);
                }

                if ($validated['show_youtube'] && empty($validated['youtube_embed_1']) && empty($validated['youtube_embed_2'])) {
                    throw ValidationException::withMessages([
                        'youtube_embed_1' => ['Isi kode embed YouTube jika YouTube ditampilkan di Widget.'],
                    ]);
                }

                if ($validated['show_twitter'] && empty($validated['twitter_embed_1']) && empty($validated['twitter_embed_2'])) {
                    throw ValidationException::withMessages([
                        'twitter_embed_1' => ['Isi kode embed X (Twitter) jika X (Twitter) ditampilkan di Widget.'],
                    ]);
                }
            }

            // Replace validated data with the casted values
            $this->replace($validated);
        }
    }
}
