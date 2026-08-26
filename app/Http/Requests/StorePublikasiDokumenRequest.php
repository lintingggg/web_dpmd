<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\PublikasiDokumen;

class StorePublikasiDokumenRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'judul' => 'required|string|max:500',
            'kategori' => 'required|string|in:' . implode(',', PublikasiDokumen::CATEGORIES),
            'tahun' => 'required|integer|min:2000|max:2099',
            'deskripsi' => 'nullable|string|max:1000',
            'is_published' => 'boolean',
            'file_dokumen' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx|max:5120', // 5MB
        ];
    }
}
