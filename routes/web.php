<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\PengumumanController;
use App\Http\Controllers\Admin\BidangTugasController;
use App\Http\Controllers\Admin\BeritaController;
use App\Http\Controllers\Admin\GaleriController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    $pengumumanList = \App\Models\Pengumuman::orderBy('tanggal', 'desc')->take(4)->get();
    return Inertia::render('HalamanUtama', [
        'pengumumanList' => $pengumumanList
    ]);
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

    Route::get('/kontak-medsos', [\App\Http\Controllers\Admin\KontakMedsosController::class, 'edit'])->name('admin.kontak-medsos');
    Route::post('/kontak-medsos', [\App\Http\Controllers\Admin\KontakMedsosController::class, 'update'])->name('admin.kontak-medsos.update');
    Route::get('/profil-dinas/{section?}', [\App\Http\Controllers\Admin\ProfilDinasController::class, 'edit'])->name('admin.profil-dinas');
    Route::post('/profil-dinas/{section}', [\App\Http\Controllers\Admin\ProfilDinasController::class, 'update'])->name('admin.profil-dinas.update');
    
    Route::get('/bidang-tugas/{section?}', [BidangTugasController::class, 'edit'])->name('admin.bidang-tugas');
    Route::post('/bidang-tugas/{section}', [BidangTugasController::class, 'update'])->name('admin.bidang-tugas.update');
    Route::get('/publikasi-dokumen', [\App\Http\Controllers\Admin\PublikasiDokumenController::class, 'index'])->name('admin.publikasi-dokumen');
    Route::post('/publikasi-dokumen', [\App\Http\Controllers\Admin\PublikasiDokumenController::class, 'store'])->name('admin.publikasi-dokumen.store');
    Route::put('/publikasi-dokumen/{dokumen}', [\App\Http\Controllers\Admin\PublikasiDokumenController::class, 'update'])->name('admin.publikasi-dokumen.update');
    Route::delete('/publikasi-dokumen/{dokumen}', [\App\Http\Controllers\Admin\PublikasiDokumenController::class, 'destroy'])->name('admin.publikasi-dokumen.destroy');
    Route::get('/berita', [BeritaController::class, 'index'])->name('admin.berita');
    Route::post('/berita', [BeritaController::class, 'store'])->name('admin.berita.store');
    Route::put('/berita/{berita}', [BeritaController::class, 'update'])->name('admin.berita.update');
    Route::delete('/berita/{berita}', [BeritaController::class, 'destroy'])->name('admin.berita.destroy');
    
    Route::get('/galeri', [GaleriController::class, 'index'])->name('admin.galeri');
    Route::post('/galeri', [GaleriController::class, 'store'])->name('admin.galeri.store');
    Route::put('/galeri/{galeri}', [GaleriController::class, 'update'])->name('admin.galeri.update');
    Route::delete('/galeri/{galeri}', [GaleriController::class, 'destroy'])->name('admin.galeri.destroy');
});

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

Route::get('/motto-pelayanan', function () {
    return Inertia::render('ProfilDinas/MottoPelayanan');
});

Route::get('/sambutan-kepala-dinas', function () {
    return Inertia::render('ProfilDinas/SambutanKepalaDinas');

});

Route::get('/berita-detail', function () {
    return Inertia::render('BeritaDetail');
});

require __DIR__.'/auth.php';