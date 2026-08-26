<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBeritaRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Otorisasi ditangani oleh Gate di Controller
        return true;
    }

    public function rules(): array
    {
        return [
            'judul' => 'required|string|max:500',
            'konten' => 'required|string',
            'thumbnail' => 'nullable|file|image|mimes:jpg,jpeg,png,webp|max:1024',
            'tags' => 'nullable|array',
            'tags.*' => 'string|max:50',
            'is_published' => 'boolean',
        ];
    }

    protected function prepareForValidation()
    {
        if ($this->has('is_published')) {
            $this->merge([
                'is_published' => $this->boolean('is_published'),
            ]);
        }
    }
}
