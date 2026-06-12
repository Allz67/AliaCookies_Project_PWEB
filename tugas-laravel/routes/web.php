<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\PreferenceController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\SocialAuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\LokasiController;
use App\Http\Controllers\CheckoutController;

// ========================================================
// 1. RUTE PUBLIK & KUSTOMER (Bisa diakses tanpa login)
// ========================================================
Route::get('/auth/google',          [SocialAuthController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('/auth/google/callback', [SocialAuthController::class, 'handleGoogleCallback'])->name('auth.google.callback');
// Halaman Depan Sementara (Splash) & Auto-Redirect
Route::get('/', function () {
    // 1. Cek apakah ada user yang sedang login (sesinya masih aktif)
    if (Auth::check()) {
        // 2. Jika dia Admin, langsung tendang ke Dashboard
        if (Auth::user()->role == 'admin') {
            return redirect()->route('dashboard');
        }
        // 3. Jika dia Kustomer, arahkan ke Home (Katalog)
        return redirect()->route('home');
    }

    // 4. Jika belum login sama sekali, baru tampilkan Splash Screen
    return view('Splash');
})->name('Splash');

Route::get('/tentang', [PageController::class, 'tentang'])->name('tentang');
Route::get('/kontak', [PageController::class, 'kontak'])->name('kontak');
// Halaman Beranda Customer Storefront
Route::get('/home', [HomeController::class, 'index'])->name('home');

// Halaman Detail Produk
Route::get('/produk/{id}', [HomeController::class, 'show'])->name('product.detail');

Route::post('/midtrans-callback', [CheckoutController::class, 'callback'])->name('midtrans.callback');
Route::post('/checkout/proses-payment', [App\Http\Controllers\CheckoutController::class, 'prosesPayment'])->name('checkout.prosesPayment');

Route::post('/preferensi', [PreferenceController::class, 'store'])->name('preferensi.store');
Route::get('/preferensi', function () {return view('preferensi');})->name('preferensi.index');

// ========================================================
// 2. RUTE UMUM (Hanya Butuh Login - Kustomer & Admin bisa akses)
// ========================================================
Route::middleware(['auth'])->group(function () {

    // Rute Profil Bawaan Breeze
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    // Rute Profil Custom
    Route::get('/user-profile', [PageController::class, 'profile'])->name('profile');
    Route::get('/cart',              [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart',             [CartController::class, 'store'])->name('cart.store');
    Route::patch('/cart/{cart}',     [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/{cart}',    [CartController::class, 'destroy'])->name('cart.destroy');
    Route::delete('/cart',           [CartController::class, 'clear'])->name('cart.clear');
    Route::get('/api/provinsi', [LokasiController::class, 'getProvinsi']);
    Route::get('/api/kota/{provinsi_id}', [LokasiController::class, 'getKota']);
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    // Pastikan baris ini ada di dalam routes/web.php kamu
    Route::post('/checkout/hitung-ongkir', [App\Http\Controllers\CheckoutController::class, 'hitungOngkir'])->name('checkout.hitungOngkir');
    Route::get('/transaksi/{id}', [App\Http\Controllers\CheckoutController::class, 'showDetail'])->name('transaksi.detail');
    // Rute untuk melihat Daftar Transaksi (Halaman Utama List Transaksi)
    Route::get('/transaksi', [CheckoutController::class, 'indexTransaksi'])->name('transaksi.index');
    Route::get('/transaksi/{id}', [CheckoutController::class, 'detailTransaksi'])->name('transaksi.detail');

    // Rute khusus Admin untuk melakukan update nomor resi & status pengiriman
    Route::post('/transaksi/{id}/update-resi', [App\Http\Controllers\CheckoutController::class, 'updateResi'])->name('transaksi.updateResi');

});


// ========================================================
// 3. RUTE TERPROTEKSI (Khusus Admin Saja)
// ========================================================

// Dashboard Admin
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'admin', 'verified']) // Aman, sudah ada penjaga admin
    ->name('dashboard');

// Grup Middleware Auth & Admin
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin/tentang/edit', [PageController::class, 'editTentang'])->name('admin.tentang.edit');
    Route::post('/admin/tentang/update', [PageController::class, 'updateTentang'])->name('admin.tentang.update');
    Route::post('/reset-kunjungan', [DashboardController::class, 'resetKunjungan'])->name('kunjungan.reset');

    // Pengelolaan Stok Utama & Arsip
    Route::get('/pengelolaan', [ProductController::class, 'index'])->name('pengelolaan');
    Route::get('/product/trashed', [ProductController::class, 'trashed'])->name('product.trashed');
    Route::post('/product/{id}/restore', [ProductController::class, 'restore'])->name('product.restore');
    Route::resource('product', ProductController::class);

});

require __DIR__.'/auth.php';
