<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateGaleriRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $galeri = $this->route('galeri');

        return [
            'judul' => 'required|string|max:500',
            'deskripsi' => 'nullable|string',
            'tanggal_kegiatan' => 'nullable|date',
            'is_published' => 'boolean',
            'tipe' => 'required|in:foto,video',
            'foto' => $this->tipe === 'video' 
                ? ($this->boolean('is_published') && !$galeri->foto ? 'required|string|max:2000' : 'nullable|string|max:2000')
                : [
                    ($this->boolean('is_published') && !$galeri->foto) ? 'required' : 'nullable',
                    'file', 'mimes:jpg,jpeg,png,webp', 'max:1024'
                ],
        ];
    }
}
