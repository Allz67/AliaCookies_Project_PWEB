<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PageController;

// Splash & Auth
Route::get('/',       [AuthController::class, 'splash'])->name('splash');
Route::get('/login',  [AuthController::class, 'login'])->name('login');
Route::post('/login', [AuthController::class, 'doLogin'])->name('do.login');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

// Dashboard
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

// Pengelolaan Stok
Route::get('/pengelolaan', [ProductController::class, 'index'])->name('pengelolaan');

// Halaman Umum
Route::get('/profile', [PageController::class, 'profile'])->name('profile');
Route::view('/tentang', 'tentang')->name('tentang');

Route::get('/hitung/{a}/{b}', fn($a, $b) => $a + $b);
