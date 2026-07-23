<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\ProfilDinas;
use App\Models\BidangTugas;

class ProfilController extends Controller
{
    public function visiMisi()
    {
        return Inertia::render('ProfilDinas/VisiMisi', [
            'profil' => ProfilDinas::first() ?? new ProfilDinas(),
        ]);
    }

    public function tugasFungsi()
    {
        return Inertia::render('ProfilDinas/TugasPokokFungsi', [
            'profil' => ProfilDinas::first() ?? new ProfilDinas(),
        ]);
    }

    public function strukturOrganisasi()
    {
        return Inertia::render('ProfilDinas/StrukturOrganisasi', [
            'profil' => ProfilDinas::first() ?? new ProfilDinas(),
        ]);
    }

    public function sambutanKadis()
    {
        return Inertia::render('ProfilDinas/SambutanKepalaDinas', [
            'profil' => ProfilDinas::first() ?? new ProfilDinas(),
        ]);
    }

    public function motto()
    {
        return Inertia::render('ProfilDinas/MottoPelayanan', [
            'profil' => ProfilDinas::first() ?? new ProfilDinas(),
        ]);
    }

    public function maklumat()
    {
        return Inertia::render('ProfilDinas/MaklumatPelayanan', [
            'profil' => ProfilDinas::first() ?? new ProfilDinas(),
        ]);
    }

    public function kodeEtik()
    {
        return Inertia::render('ProfilDinas/KodeEtikPelayanan', [
            'profil' => ProfilDinas::first() ?? new ProfilDinas(),
        ]);
    }
}
