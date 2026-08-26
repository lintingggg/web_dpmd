<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Agenda;
use App\Http\Requests\StoreAgendaRequest;
use App\Http\Requests\UpdateAgendaRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;

class AgendaController extends Controller
{
    public function index(Request $request)
    {
        // Authorization: Hanya superadmin yang dapat melihat (dicek via Policy)
        Gate::authorize('viewAny', Agenda::class);

        // Fetch data menggunakan Scope untuk logic pencarian/filter
        $agenda = Agenda::query()
            ->filter($request->only(['search', 'status']))
            ->latest('tanggal')
            ->paginate(10)
            ->withQueryString();

        return Inertia::render('Admin/Agenda', [
            'agenda' => $agenda,
            'filters' => $request->only(['search', 'status']),
        ]);
    }

    public function store(StoreAgendaRequest $request)
    {
        // Authorization
        Gate::authorize('create', Agenda::class);

        // Validation sudah ter-handle di StoreAgendaRequest
        $agenda = Agenda::create($request->validated());
        
        \App\Services\ActivityLogger::log('Membuat Agenda', "Membuat agenda baru: {$agenda->judul}", $agenda, null, $agenda->toArray());

        return redirect()->back()->with('message', 'Agenda berhasil ditambahkan');
    }

    public function update(UpdateAgendaRequest $request, Agenda $agenda)
    {
        // Authorization
        Gate::authorize('update', $agenda);

        // Validation sudah ter-handle di UpdateAgendaRequest
        $oldValues = $agenda->toArray();
        $agenda->update($request->validated());
        
        \App\Services\ActivityLogger::log('Mengubah Agenda', "Mengubah data agenda: {$agenda->judul}", $agenda, $oldValues, $agenda->fresh()->toArray());

        return redirect()->back()->with('message', 'Agenda berhasil diperbarui');
    }

    public function destroy(Agenda $agenda)
    {
        // Authorization
        Gate::authorize('delete', $agenda);

        $oldValues = $agenda->toArray();
        $agenda->delete();
        
        \App\Services\ActivityLogger::log('Menghapus Agenda', "Menghapus agenda: {$agenda->judul}", $agenda, $oldValues, null);
        
        return redirect()->back()->with('message', 'Agenda berhasil dihapus');
    }
}
