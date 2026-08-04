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

        $agenda = Agenda::create($validated);
        \App\Services\ActivityLogger::log('Membuat Agenda', "Membuat agenda baru: {$agenda->judul}", $agenda, null, $agenda->toArray());

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

        $oldValues = $agenda->toArray();
        $agenda->update($validated);
        \App\Services\ActivityLogger::log('Mengubah Agenda', "Mengubah data agenda: {$agenda->judul}", $agenda, $oldValues, $agenda->fresh()->toArray());

        return redirect()->back()->with('message', 'Agenda berhasil diperbarui');
    }

    public function destroy(Agenda $agenda)
    {
        $oldValues = $agenda->toArray();
        $agenda->delete();
        \App\Services\ActivityLogger::log('Menghapus Agenda', "Menghapus agenda: {$agenda->judul}", $agenda, $oldValues, null);
        return redirect()->back()->with('message', 'Agenda berhasil dihapus');
    }
}
