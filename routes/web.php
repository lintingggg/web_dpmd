<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\AgendaController as AdminAgendaController;
use App\Http\Controllers\Admin\BidangTugasController;
use App\Http\Controllers\Admin\BeritaController;
use App\Http\Controllers\Admin\GaleriController;
use App\Http\Controllers\Frontend\BerandaController;
use App\Http\Controllers\Frontend\ProfilController;
use App\Http\Controllers\Frontend\BidangController;
use App\Http\Controllers\Frontend\DokumenController;
use App\Http\Controllers\Frontend\BeritaController as FrontendBeritaController;
use App\Http\Controllers\Frontend\GaleriController as FrontendGaleriController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', [BerandaController::class, 'index']);

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});



Route::middleware(['auth', 'verified'])->prefix('admin')->group(function () {
    Route::get('/agenda', [AdminAgendaController::class, 'index'])->name('admin.agenda.index');
    Route::post('/agenda', [AdminAgendaController::class, 'store'])->name('admin.agenda.store');
    Route::post('/agenda/{agenda}', [AdminAgendaController::class, 'update'])->name('admin.agenda.update');
    Route::delete('/agenda/{agenda}', [AdminAgendaController::class, 'destroy'])->name('admin.agenda.destroy');

    Route::get('/pengaturan-beranda', [\App\Http\Controllers\Admin\PengaturanBerandaController::class, 'edit'])->name('admin.pengaturan-beranda');
    Route::post('/pengaturan-beranda', [\App\Http\Controllers\Admin\PengaturanBerandaController::class, 'update'])->name('admin.pengaturan-beranda.update');
    
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
    
    Route::get('/album', [\App\Http\Controllers\Admin\AlbumController::class, 'index'])->name('admin.album');
    Route::post('/album', [\App\Http\Controllers\Admin\AlbumController::class, 'store'])->name('admin.album.store');
    Route::put('/album/{album}', [\App\Http\Controllers\Admin\AlbumController::class, 'update'])->name('admin.album.update');
    Route::delete('/album/{album}', [\App\Http\Controllers\Admin\AlbumController::class, 'destroy'])->name('admin.album.destroy');

    Route::get('/album/{album}/galeri', [GaleriController::class, 'index'])->name('admin.galeri');
    Route::post('/album/{album}/galeri', [GaleriController::class, 'store'])->name('admin.galeri.store');
    Route::put('/galeri/{galeri}', [GaleriController::class, 'update'])->name('admin.galeri.update');
    Route::delete('/galeri/{galeri}', [GaleriController::class, 'destroy'])->name('admin.galeri.destroy');
});

// Rute Bidang Tugas
Route::prefix('bidang-tugas')->group(function () {
    Route::get('/sekretariat', [BidangController::class, 'sekretariat']);
    Route::get('/pemerintahan-desa', [BidangController::class, 'pemerintahanDesa']);
    Route::get('/pemberdayaan-desa', [BidangController::class, 'pemberdayaanDesa']);
    Route::get('/pemberdayaan-lembaga-kemasyarakatan', [BidangController::class, 'pemberdayaanLembaga']);
});

// Rute Profil Dinas
Route::prefix('profil')->group(function () {
    Route::get('/visi-misi', [ProfilController::class, 'visiMisi']);
    Route::get('/tugas-pokok-fungsi', [ProfilController::class, 'tugasFungsi']);
    Route::get('/struktur-organisasi', [ProfilController::class, 'strukturOrganisasi']);
    Route::get('/sambutan-kepala-dinas', [ProfilController::class, 'sambutanKadis']);
    Route::get('/motto-pelayanan', [ProfilController::class, 'motto']);
    Route::get('/maklumat-pelayanan', [ProfilController::class, 'maklumat']);
    Route::get('/kode-etik-pelayanan', [ProfilController::class, 'kodeEtik']);

});

// Rute Berita
Route::get('/berita', [FrontendBeritaController::class, 'index']);
Route::get('/berita/{slug}', [FrontendBeritaController::class, 'show']);

// Rute Dokumen
Route::get('/dokumen-dan-peraturan', [DokumenController::class, 'index']);
Route::get('/dokumen-dan-peraturan/{id}', [DokumenController::class, 'show']);

// Rute Galeri
Route::get('/galeri', [FrontendGaleriController::class, 'index']);
Route::get('/galeri/{id}', [FrontendGaleriController::class, 'show']);

require __DIR__.'/auth.php';
// Global 404 Fallback
Route::fallback(function () {
    return Inertia::render('ProfilDinas/Error404');
});
