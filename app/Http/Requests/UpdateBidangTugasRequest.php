<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBidangTugasRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Ditangani oleh Gate di Controller
    }

    public function rules(): array
    {
        $section = $this->route('section');
        
        return [
            "{$section}_gambar" => 'nullable|image|mimes:jpg,jpeg,png|max:1024',
            "{$section}_konten" => 'required|string',
        ];
    }
}
