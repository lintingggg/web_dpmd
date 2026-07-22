<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\BidangTugas;

class BidangController extends Controller
{
    public function sekretariat()
    {
        return Inertia::render('BidangTugas/Sekretariat', [
            'bidang' => BidangTugas::first() ?? new BidangTugas(),
        ]);
    }

    public function pemberdayaanDesa()
    {
        return Inertia::render('BidangTugas/BidangPemberdayaanDesa', [
            'bidang' => BidangTugas::first() ?? new BidangTugas(),
        ]);
    }

    public function pemberdayaanLembaga()
    {
        return Inertia::render('BidangTugas/BidangPemberdayaanLembagaKemasyarakatan', [
            'bidang' => BidangTugas::first() ?? new BidangTugas(),
        ]);
    }

    public function pemerintahanDesa()
    {
        return Inertia::render('BidangTugas/BidangPemerintahanDesa', [
            'bidang' => BidangTugas::first() ?? new BidangTugas(),
        ]);
    }
}
