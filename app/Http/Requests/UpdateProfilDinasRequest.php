<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProfilDinasRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Ditangani oleh Gate di Controller
    }

    public function rules(): array
    {
        $section = $this->route('section');

        return match($section) {
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
    }

    public function messages(): array
    {
        return [
            'kadis_nip.regex' => 'NIP hanya boleh berisi angka dan spasi.',
            'kadis_nip.min' => 'NIP harus berisi minimal 18 karakter.',
        ];
    }
}
