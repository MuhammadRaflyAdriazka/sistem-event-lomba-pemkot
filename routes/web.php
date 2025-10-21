<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PendaftaranController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\AdminProfileController;

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
        Route::get('/event', [AdminDashboardController::class, 'eventList'])->name('event');
        
        // Create Event
        Route::get('/create', [AdminDashboardController::class, 'create'])->name('create');
        Route::post('/store', [AdminDashboardController::class, 'store'])->name('store');
        
        // Edit, Update, Delete Event
        Route::get('/event/{id}/edit', [AdminDashboardController::class, 'edit'])->name('event.edit');
        Route::patch('/event/{id}', [AdminDashboardController::class, 'update'])->name('event.update');
        Route::delete('/event/{id}', [AdminDashboardController::class, 'destroy'])->name('event.destroy');
        
        // Mark Event as Finished
        Route::patch('/event/{id}/selesai', [AdminDashboardController::class, 'markAsFinished'])->name('event.selesai');
    });

    // Nanti kita bisa tambahkan grup untuk Panitia dan Kepala Dinas di sini
    // Route::prefix('panitia')->middleware('role:panitia')->name('panitia.')->group(function () { ... });
});