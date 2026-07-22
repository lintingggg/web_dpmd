<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PublikasiDokumen;
use Inertia\Inertia;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class PublikasiDokumenController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = PublikasiDokumen::query();

        if ($request->filled('kategori') && in_array($request->kategori, PublikasiDokumen::CATEGORIES)) {
            $query->where('kategori', $request->kategori);
        } else {
            // Default kategori
            $query->where('kategori', PublikasiDokumen::CATEGORY_PERENCANAAN);
        }

        if ($request->filled('search')) {
            $query->where('judul', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('tahun')) {
            $query->where('tahun', $request->tahun);
        }

        if ($request->filled('status') && in_array($request->status, ['draft', 'publish'])) {
            $query->where('is_published', $request->status === 'publish');
        }

        $dokumen = $query->latest()->paginate(5)->withQueryString();

        return Inertia::render('Admin/PublikasiDokumen', [
            'dokumen' => $dokumen,
            'filters' => $request->only(['kategori', 'search', 'tahun', 'status']),
            'categories' => PublikasiDokumen::CATEGORY_LABELS,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:500',
            'kategori' => 'required|string|in:' . implode(',', PublikasiDokumen::CATEGORIES),
            'tahun' => 'required|integer|min:2000|max:2099',
            'deskripsi' => 'nullable|string|max:1000',
            'is_published' => 'boolean',
            'file_dokumen' => 'nullable|file|mimes:pdf|max:20480', // 20MB
        ]);

        DB::beginTransaction();
        try {
            if ($request->hasFile('file_dokumen')) {
                $path = $request->file('file_dokumen')->store('dokumen', 'public');
                $validated['file_dokumen'] = $path;
            }
            
            PublikasiDokumen::create($validated);
            
            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            if (isset($path)) Storage::disk('public')->delete($path);
            throw $e;
        }

        return redirect()->back()->with('message', 'Dokumen publikasi berhasil ditambahkan');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PublikasiDokumen $dokumen)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:500',
            'kategori' => 'required|string|in:' . implode(',', PublikasiDokumen::CATEGORIES),
            'tahun' => 'required|integer|min:2000|max:2099',
            'deskripsi' => 'nullable|string|max:1000',
            'is_published' => 'boolean',
            'file_dokumen' => 'nullable|file|mimes:pdf|max:20480', // 20MB
        ]);

        DB::beginTransaction();
        try {
            if ($request->hasFile('file_dokumen')) {
                // Hapus file lama jika ada
                if ($dokumen->file_dokumen && Storage::disk('public')->exists($dokumen->file_dokumen)) {
                    Storage::disk('public')->delete($dokumen->file_dokumen);
                }
                
                // Simpan file baru
                $path = $request->file('file_dokumen')->store('dokumen', 'public');
                $validated['file_dokumen'] = $path;
            }

            $dokumen->update($validated);
            
            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            if (isset($path)) Storage::disk('public')->delete($path);
            throw $e;
        }

        return redirect()->back()->with('message', 'Dokumen publikasi berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PublikasiDokumen $dokumen)
    {
        DB::beginTransaction();
        try {
            if ($dokumen->file_dokumen && Storage::disk('public')->exists($dokumen->file_dokumen)) {
                Storage::disk('public')->delete($dokumen->file_dokumen);
            }
            
            $dokumen->delete();
            
            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }

        return redirect()->back()->with('message', 'Dokumen publikasi berhasil dihapus');
    }
}
