<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Berita;
use Inertia\Inertia;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Mews\Purifier\Facades\Purifier;
use Illuminate\Support\Facades\Auth;

class BeritaController extends Controller
{
    public function index(Request $request)
    {
        $query = Berita::query();

        if ($request->filled('search')) {
            $query->where('judul', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('status')) {
            if ($request->status === 'publish') {
                $query->where('is_published', true);
            } elseif ($request->status === 'draft') {
                $query->where('is_published', false);
            }
        }

        if ($request->filled('tag')) {
            $query->whereJsonContains('tags', $request->tag);
        }

        $berita = $query->latest()->paginate(5)->withQueryString();

        // Get all unique tags for the filter dropdown
        $allTags = Berita::whereNotNull('tags')
            ->pluck('tags')
            ->flatten()
            ->unique()
            ->sort()
            ->values()
            ->toArray();

        return Inertia::render('Admin/Berita', [
            'berita' => $berita,
            'filters' => $request->only(['search', 'status', 'tag']),
            'available_tags' => $allTags,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:500',
            'konten' => 'required|string',
            'thumbnail' => 'nullable|file|image|mimes:jpg,jpeg,png,webp|max:1024', // Max 1MB
            'tags' => 'nullable|array',
            'tags.*' => 'string|max:50',
            'is_published' => 'boolean',
        ]);

        DB::beginTransaction();
        try {
            if ($request->hasFile('thumbnail')) {
                $path = $request->file('thumbnail')->store('berita', 'public');
                $validated['thumbnail'] = $path;
            }

            // Slug deduplication
            $slug = Str::slug($validated['judul']);
            $count = Berita::withTrashed()->where('slug', 'like', $slug . '%')->count();
            $validated['slug'] = $count ? "{$slug}-{$count}" : $slug;

            // Sanitize konten
            $validated['konten'] = Purifier::clean($validated['konten'], 'berita');

            // Set penulis and published_at
            $validated['penulis'] = Auth::user()?->name ?? 'Admin';
            
            $validated['is_published'] = $request->boolean('is_published');
            if ($validated['is_published']) {
                $validated['published_at'] = now();
            }

            Berita::create($validated);
            DB::commit();

            return redirect()->back()->with('message', 'Berita berhasil ditambahkan');
        } catch (\Throwable $e) {
            DB::rollBack();
            if (isset($path)) Storage::disk('public')->delete($path);
            throw $e;
        }
    }

    public function update(Request $request, Berita $berita)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:500',
            'konten' => 'required|string',
            'thumbnail' => 'nullable|file|image|mimes:jpg,jpeg,png,webp|max:1024',
            'tags' => 'nullable|array',
            'tags.*' => 'string|max:50',
            'is_published' => 'boolean',
        ]);

        DB::beginTransaction();
        try {
            if ($request->hasFile('thumbnail')) {
                if ($berita->thumbnail && Storage::disk('public')->exists($berita->thumbnail)) {
                    Storage::disk('public')->delete($berita->thumbnail);
                }
                $path = $request->file('thumbnail')->store('berita', 'public');
                $validated['thumbnail'] = $path;
            } else {
                unset($validated['thumbnail']);
            }

            $validated['konten'] = Purifier::clean($validated['konten'], 'berita');

            $validated['is_published'] = $request->boolean('is_published');
            if ($validated['is_published'] && !$berita->published_at) {
                $validated['published_at'] = now();
            }

            $berita->update($validated);
            DB::commit();

            return redirect()->back()->with('message', 'Berita berhasil diperbarui');
        } catch (\Throwable $e) {
            DB::rollBack();
            if (isset($path)) Storage::disk('public')->delete($path);
            throw $e;
        }
    }

    public function destroy(Berita $berita)
    {
        DB::beginTransaction();
        try {
            $berita->delete(); // Soft delete, thumbnail is NOT deleted
            DB::commit();
            return redirect()->back()->with('message', 'Berita berhasil dihapus');
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
