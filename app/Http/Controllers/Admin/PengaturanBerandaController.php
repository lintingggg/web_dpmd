<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\PengaturanBeranda;
use Illuminate\Support\Facades\Storage;

class PengaturanBerandaController extends Controller
{
    public function edit()
    {
        $pengaturan = PengaturanBeranda::first();
        return Inertia::render('Admin/PengaturanBeranda', [
            'pengaturan' => $pengaturan
        ]);
    }

    public function update(Request $request)
    {
        $pengaturan = PengaturanBeranda::first();
        if (!$pengaturan) {
            $pengaturan = new PengaturanBeranda();
        }

        $request->validate([
            'hero_title' => 'nullable|string|max:255',
            'hero_description' => 'nullable|string',
            'hero_image_1' => 'nullable|image|mimes:jpeg,png,jpg|max:1024',
            'hero_image_2' => 'nullable|image|mimes:jpeg,png,jpg|max:1024',
            'hero_image_3' => 'nullable|image|mimes:jpeg,png,jpg|max:1024',
        ]);

        $pengaturan->hero_title = $request->hero_title;
        $pengaturan->hero_description = $request->hero_description;

        // Handle Image 1
        if ($request->hasFile('hero_image_1')) {
            if ($pengaturan->hero_image_1) {
                Storage::disk('public')->delete($pengaturan->hero_image_1);
            }
            $path = $request->file('hero_image_1')->store('hero', 'public');
            $pengaturan->hero_image_1 = $path;
        }

        // Handle Image 2
        if ($request->hasFile('hero_image_2')) {
            if ($pengaturan->hero_image_2) {
                Storage::disk('public')->delete($pengaturan->hero_image_2);
            }
            $path = $request->file('hero_image_2')->store('hero', 'public');
            $pengaturan->hero_image_2 = $path;
        }

        // Handle Image 3
        if ($request->hasFile('hero_image_3')) {
            if ($pengaturan->hero_image_3) {
                Storage::disk('public')->delete($pengaturan->hero_image_3);
            }
            $path = $request->file('hero_image_3')->store('hero', 'public');
            $pengaturan->hero_image_3 = $path;
        }

        $pengaturan->save();

        return redirect()->back()->with('success', 'Pengaturan Beranda berhasil diperbarui.');
    }
}
