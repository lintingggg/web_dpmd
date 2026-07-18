<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\PengumumanController;
use App\Http\Controllers\Admin\BidangTugasController;
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


Route::get('/berita', function () {
    return Inertia::render('Berita');
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
});

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
Route::prefix('publikasi-dokumen')->group(function () {
    Route::get('/dokumen-lainnya', function () {
        return Inertia::render('PublikasiDokumen/DokumenLainnya');
    });
    
    Route::get('/dokumen-perencanaan', function () {
        return Inertia::render('PublikasiDokumen/DokumenPerencanaan'); 
    });

    Route::get('/produk-peraturan', function () {
        return Inertia::render('PublikasiDokumen/ProdukPeraturan'); 
    });

});


require __DIR__.'/auth.php';
