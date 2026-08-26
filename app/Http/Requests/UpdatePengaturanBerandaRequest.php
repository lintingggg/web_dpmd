<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePengaturanBerandaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'hero_title' => 'nullable|string|max:255',
            'hero_description' => 'nullable|string',
            'hero_image_1' => 'nullable|image|mimes:jpeg,png,jpg|max:1024',
            'hero_image_2' => 'nullable|image|mimes:jpeg,png,jpg|max:1024',
            'hero_image_3' => 'nullable|image|mimes:jpeg,png,jpg|max:1024',
        ];
    }
}
