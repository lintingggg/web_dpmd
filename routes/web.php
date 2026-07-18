<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\PengumumanController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('HalamanUtama');
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});



Route::middleware(['auth', 'verified'])->prefix('admin')->group(function () {
    Route::get('/pengumuman', [PengumumanController::class, 'index'])->name('admin.pengumuman.index');
    Route::post('/pengumuman', [PengumumanController::class, 'store'])->name('admin.pengumuman.store');
    Route::post('/pengumuman/{pengumuman}', [PengumumanController::class, 'update'])->name('admin.pengumuman.update');
    Route::delete('/pengumuman/{pengumuman}', [PengumumanController::class, 'destroy'])->name('admin.pengumuman.destroy');
});

Route::get('/admin/kontak-medsos', function () {
    return Inertia::render('Admin/KontakMedsos');
})->middleware(['auth', 'verified'])->name('admin.kontak-medsos');

Route::get('/admin/profil-dinas/{section?}', function ($section = 'sambutan') {
    return Inertia::render('Admin/ProfilDinas', ['section' => $section]);
})->middleware(['auth', 'verified'])->name('admin.profil-dinas');

Route::get('/admin/bidang-tugas/{bidang?}', function ($bidang = 'pemdes') {
    return Inertia::render('Admin/BidangTugas', ['bidang' => $bidang]);
})->middleware(['auth', 'verified'])->name('admin.bidang-tugas');

Route::get('/admin/publikasi-dokumen', function () {
    return Inertia::render('Admin/PublikasiDokumen');
})->middleware(['auth', 'verified'])->name('admin.publikasi-dokumen');

Route::get('/admin/berita', function () {
    return Inertia::render('Admin/Berita');
})->middleware(['auth', 'verified'])->name('admin.berita');

Route::get('/admin/galeri', function () {
    return Inertia::render('Admin/Galeri');
})->middleware(['auth', 'verified'])->name('admin.galeri');

Route::prefix('bidang-tugas')->group(function () {
    Route::get('/sekretariat', function () {
        return Inertia::render('BidangTugas/Sekretariat');
    });
    Route::get('/pemerintahan-desa', function () {
        return Inertia::render('BidangTugas/BidangPemerintahanDesa');
    });
    Route::get('/pemberdayaan-desa', function () {
        return Inertia::render('BidangTugas/BidangPemberdayaanDesa');
    });
    Route::get('/pemberdayaan-lembaga-kemasyarakatan', function () {
        return Inertia::render('BidangTugas/BidangPemberdayaanLembagaKemasyarakatan');
    });
});

Route::get('/kode-etik-pelayanan', function () {
    return Inertia::render('ProfilDinas/KodeEtikPelayanan');
});

Route::get('/maklumat-pelayanan', function () {
    return Inertia::render('ProfilDinas/MaklumatPelayanan');
});

Route::get('/struktur-organisasi', function () {
    return Inertia::render('ProfilDinas/StrukturOrganisasi');
});

Route::get('/dokumen-dan-peraturan', function () {
    return Inertia::render('DokumenDanPeraturan');
});

Route::get('/berita', function () {
    return Inertia::render('Berita');
});

Route::get('/berita-detail', function () {
    return Inertia::render('BeritaDetail');
});

require __DIR__.'/auth.php';
