<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Pengumuman;
use Inertia\Inertia;
use Illuminate\Support\Facades\Storage;

class PengumumanController extends Controller
{
    public function index(Request $request)
    {
        $query = Pengumuman::query();

        if ($request->filled('search')) {
            $query->where('judul', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('status')) {
            if ($request->status === 'Aktif') {
                $query->where('is_published', true);
            } elseif ($request->status === 'Non-Aktif') {
                $query->where('is_published', false);
            }
        }

        $pengumuman = $query->latest()->paginate(10)->withQueryString();
        
        return Inertia::render('Admin/Pengumuman', [
            'pengumuman' => $pengumuman,
            'filters' => $request->only(['search', 'status']),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'konten' => 'required|string',
            'file_lampiran' => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:5120',
        ]);

        if ($request->hasFile('file_lampiran')) {
            $path = $request->file('file_lampiran')->store('pengumuman', 'public');
            $validated['file_lampiran'] = $path;
        }

        $validated['is_published'] = $request->boolean('is_published', true);

        Pengumuman::create($validated);

        return redirect()->back()->with('message', 'Pengumuman berhasil ditambahkan');
    }

    public function update(Request $request, Pengumuman $pengumuman)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'konten' => 'required|string',
            'file_lampiran' => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:5120',
        ]);

        if ($request->hasFile('file_lampiran')) {
            if ($pengumuman->file_lampiran && Storage::disk('public')->exists($pengumuman->file_lampiran)) {
                Storage::disk('public')->delete($pengumuman->file_lampiran);
            }
            $path = $request->file('file_lampiran')->store('pengumuman', 'public');
            $validated['file_lampiran'] = $path;
        } elseif ($request->boolean('remove_lampiran')) {
            if ($pengumuman->file_lampiran && Storage::disk('public')->exists($pengumuman->file_lampiran)) {
                Storage::disk('public')->delete($pengumuman->file_lampiran);
            }
            $validated['file_lampiran'] = null;
        }

        $validated['is_published'] = $request->boolean('is_published');

        $pengumuman->update($validated);

        return redirect()->back()->with('message', 'Pengumuman berhasil diperbarui');
    }

    public function destroy(Pengumuman $pengumuman)
    {
        if ($pengumuman->file_lampiran && Storage::disk('public')->exists($pengumuman->file_lampiran)) {
            Storage::disk('public')->delete($pengumuman->file_lampiran);
        }
        
        $pengumuman->delete();

        return redirect()->back()->with('message', 'Pengumuman berhasil dihapus');
    }
}
