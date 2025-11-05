<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PendaftaranController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\AdminProfileController;
use App\Http\Controllers\AdminAcaraController;
use App\Http\Controllers\PanitiaController;
use App\Http\Controllers\PanitiaProfileController;

// == HALAMAN PUBLIK (Tidak Perlu Login) ==
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/acara/{acara}', [HomeController::class, 'show'])->name('acara.show');


// == AUTENTIKASI (Login, Register, dll dari Breeze) ==
require __DIR__.'/auth.php';


// == HALAMAN YANG MEMERLUKAN LOGIN ==
Route::middleware(['auth', 'verified'])->group(function () {
    
    // -- Rute Umum untuk Semua Peran --
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/acara', [DashboardController::class, 'acaraSaya'])->name('acara');
    Route::get('/acara/{pendaftaran}/detail-pendaftaran', [DashboardController::class, 'detailPendaftaran'])->name('acara.detailPendaftaran');
    
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // -- Rute Khusus Peserta --
    Route::get('/pendaftaran/{acara}', [PendaftaranController::class, 'show'])->name('pendaftaran.show');
    Route::post('/pendaftaran/{acara}', [PendaftaranController::class, 'store'])->name('pendaftaran.store');

    // -- Rute Khusus Admin Dinas --
    Route::prefix('admin')
         ->name('admin.')
         ->group(function () {
        
        // Dashboard Admin
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        
        // Profile Admin
        Route::get('/profile', [AdminProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [AdminProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [AdminProfileController::class, 'destroy'])->name('profile.destroy');
        
        // Kelola Event
        Route::get('/kelola', [AdminDashboardController::class, 'kelola'])->name('kelola');
        
        // Daftar Event
        Route::get('/event', [AdminAcaraController::class, 'index'])->name('event');
        
        // Create Event
        Route::get('/create', [AdminAcaraController::class, 'create'])->name('create');
        Route::post('/store', [AdminAcaraController::class, 'store'])->name('store');
        
        // Edit, Update, Delete Event
        Route::get('/event/{id}/edit', [AdminAcaraController::class, 'edit'])->name('event.edit');
        Route::patch('/event/{id}', [AdminAcaraController::class, 'update'])->name('event.update');
        Route::delete('/event/{id}', [AdminAcaraController::class, 'destroy'])->name('event.destroy');
        
        // Mark Event as Finished
        Route::patch('/event/{id}/selesai', [AdminAcaraController::class, 'markAsFinished'])->name('event.selesai');
    });

    // -- Rute Khusus Panitia --
    Route::prefix('panitia')
         ->name('panitia.')
         ->group(function () {
        
        // Dashboard Panitia
        Route::get('/dashboard', [PanitiaController::class, 'index'])->name('dashboard');
        
        // Kelola Peserta
        Route::get('/peserta', [PanitiaController::class, 'peserta'])->name('peserta');
        Route::get('/peserta-diterima', [PanitiaController::class, 'pesertaDiterima'])->name('peserta.diterima');
        Route::get('/peserta-ditolak', [PanitiaController::class, 'pesertaDitolak'])->name('peserta.ditolak');
        Route::get('/peserta/{pendaftaran}/detail', [PanitiaController::class, 'detailPeserta'])->name('peserta.detail');
        Route::post('/peserta/{pendaftaran}/terima', [PanitiaController::class, 'terimaPeserta'])->name('peserta.terima');
        Route::post('/peserta/{pendaftaran}/tolak', [PanitiaController::class, 'tolakPeserta'])->name('peserta.tolak');
        Route::post('/peserta/{pendaftaran}/batalkan', [PanitiaController::class, 'batalkanPenerimaan'])->name('peserta.batalkan');
        Route::post('/peserta/{pendaftaran}/batalkan-penolakan', [PanitiaController::class, 'batalkanPenolakan'])->name('peserta.batalkanPenolakan');
        Route::post('/peserta/tolak-massal-kuota-penuh', [PanitiaController::class, 'tolakMassalKuotaPenuh'])->name('peserta.tolakMassalKuotaPenuh');
        Route::get('/file/{filename}', [PanitiaController::class, 'showFile'])->name('file');

        Route::get('/tanpa-seleksi', [PanitiaController::class, 'pesertaTanpaSeleksi'])->name('peserta.tanpaSeleksi');
        Route::get('/peserta-ditolak-tanpa-seleksi', [PanitiaController::class, 'pesertaDitolakTanpaSeleksi'])->name('peserta.ditolakTanpaSeleksi');
        Route::get('/peserta/{pendaftaran}/detail-tanpa-seleksi', [PanitiaController::class, 'detailPesertaTanpaSeleksi'])->name('peserta.detailTanpaSeleksi');
        Route::patch('/peserta/{pendaftaran}/batalkan-tanpa-seleksi', [PanitiaController::class, 'batalkanPenerimaanTanpaSeleksi'])->name('peserta.batalkanTanpaSeleksi');
        Route::post('/peserta/{pendaftaran}/batalkan-penolakan-tanpa-seleksi', [PanitiaController::class, 'batalkanPenolakanTanpaSeleksi'])->name('peserta.batalkanPenolakanTanpaSeleksi');
        
        // Profile Panitia
        Route::get('/profile', [PanitiaProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [PanitiaProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [PanitiaProfileController::class, 'destroy'])->name('profile.destroy');
    });
});