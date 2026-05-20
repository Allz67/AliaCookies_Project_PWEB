<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PageController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PreferenceController;

// 1. Halaman Depan (Splash)
Route::get('/', function () {
    return view('Splash');
})->name('Splash');

// 2. Dashboard
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// 3. SEMUA RUTE TERPROTEKSI (Harus Login)
Route::middleware(['auth', 'admin'])->group(function () {

    Route::post('/reset-kunjungan', [DashboardController::class, 'resetKunjungan'])->name('kunjungan.reset');
    
    // Rute POST untuk memproses dan menyimpan cookie preferensi tampilan
    Route::post('/preferensi', [PreferenceController::class, 'store'])->name('preferensi.store');
    // Rute untuk menampilkan halaman form preferensi tampilan
    Route::get('/preferensi', function () {
        return view('preferensi');
    })->name('preferensi.index');
    // Rute Profil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Pengelolaan Stok Utama
    Route::get('/pengelolaan', [ProductController::class, 'index'])->name('pengelolaan');

    // Fitur Arsip
    Route::get('/product/trashed', [ProductController::class, 'trashed'])->name('product.trashed');
    Route::post('/product/{id}/restore', [ProductController::class, 'restore'])->name('product.restore');

    // Resource CRUD Produk
    Route::resource('product', ProductController::class);

    // Halaman lainnya
    Route::get('/user-profile', [PageController::class, 'profile'])->name('profile');
    Route::view('/tentang', 'tentang')->name('tentang');
    Route::view('/kontak', 'kontak')->name('kontak');
});

require __DIR__.'/auth.php';
