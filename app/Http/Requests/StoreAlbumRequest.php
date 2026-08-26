<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAlbumRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nama' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'is_published' => 'boolean',
            'cover' => 'nullable|file|image|mimes:jpg,jpeg,png,webp|max:1024',
        ];
    }
}
