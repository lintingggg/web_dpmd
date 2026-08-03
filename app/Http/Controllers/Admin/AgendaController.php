<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Agenda;
use Inertia\Inertia;

class AgendaController extends Controller
{
    public function index(Request $request)
    {
        $query = Agenda::query();

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

        $agenda = $query->latest('tanggal')->paginate(10)->withQueryString();

        return Inertia::render('Admin/Agenda', [
            'agenda' => $agenda,
            'filters' => $request->only(['search', 'status']),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul'          => 'required|string|max:255',
            'deskripsi'      => 'nullable|string',
            'tanggal'        => 'required|date',
            'waktu_mulai'    => 'nullable|date_format:H:i',
            'waktu_selesai'  => 'nullable|date_format:H:i|after_or_equal:waktu_mulai',
            'lokasi'         => 'nullable|string|max:255',
            'is_published'   => 'boolean',
        ]);

        $validated['is_published'] = $request->boolean('is_published', true);

        Agenda::create($validated);

        return redirect()->back()->with('message', 'Agenda berhasil ditambahkan');
    }

    public function update(Request $request, Agenda $agenda)
    {
        $validated = $request->validate([
            'judul'          => 'required|string|max:255',
            'deskripsi'      => 'nullable|string',
            'tanggal'        => 'required|date',
            'waktu_mulai'    => 'nullable|date_format:H:i',
            'waktu_selesai'  => 'nullable|date_format:H:i|after_or_equal:waktu_mulai',
            'lokasi'         => 'nullable|string|max:255',
            'is_published'   => 'boolean',
        ]);

        $validated['is_published'] = $request->boolean('is_published', true);

        $agenda->update($validated);

        return redirect()->back()->with('message', 'Agenda berhasil diperbarui');
    }

    public function destroy(Agenda $agenda)
    {
        $agenda->delete();
        return redirect()->back()->with('message', 'Agenda berhasil dihapus');
    }
}
