<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\MenuItemController;
use App\Http\Controllers\Admin\OperatingHourController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\PublicController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Authentication Routes (Khusus Guest - Admin Login via /admin/login)
Route::middleware('guest')->group(function () {
    Route::get('/admin/login', [LoginController::class, 'showLoginForm'])->name('admin.login');
    // Jika mengakses /login biasa, alihkan ke /admin/login
    Route::get('/login', fn () => redirect()->route('admin.login'))->name('login');
});

// Login & Logout
Route::post('/login', [AuthController::class, 'login']);
Route::match(['GET', 'POST'], '/logout', [AuthController::class, 'logout'])->name('logout');

// Customer / Public Landing Page
Route::get('/', [PublicController::class, 'index'])->name('home');

// Menu & Category API — dipakai oleh dashboard admin (admin.js) DAN halaman publik (script.js).
Route::get('/api/menus/public', [MenuController::class, 'publicIndex']); // dipakai halaman utama, tanpa login
Route::get('/api/categories/public', [CategoryController::class, 'index']); // dipakai halaman utama, tanpa login

Route::middleware(['auth', 'admin'])->group(function () {
    // Menu API
    Route::get('/api/menus', [MenuController::class, 'index']);
    Route::post('/api/menus', [MenuController::class, 'store']);
    Route::put('/api/menus/{menu}', [MenuController::class, 'update']);
    Route::patch('/api/menus/{menu}/toggle', [MenuController::class, 'toggleStatus']);
    Route::delete('/api/menus/{menu}', [MenuController::class, 'destroy']);

    // Kategori Carousel API
    Route::get('/api/categories', [CategoryController::class, 'index']);
    Route::post('/api/categories', [CategoryController::class, 'store']);
    Route::post('/api/categories/{category}', [CategoryController::class, 'update']);
    Route::put('/api/categories/{category}', [CategoryController::class, 'update']);
    Route::delete('/api/categories/{category}', [CategoryController::class, 'destroy']);

    // Jam Operasional API — dipakai admin.js untuk baca & simpan jadwal ke DB
    Route::get('/api/operating-hours', [OperatingHourController::class, 'index']);
    Route::put('/api/operating-hours', [OperatingHourController::class, 'update']);
});

// Admin Panel Protected Routes (Requires Auth & Admin Role)
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', fn () => redirect()->route('admin.dashboard'));
    Route::get('/dashboard', fn () => view('admin.app'))->name('dashboard');
    Route::get('/menu', fn () => view('admin.app'))->name('menu');
    Route::get('/kategori', fn () => view('admin.app'))->name('kategori');
    Route::get('/jam-operasional', fn () => view('admin.app'))->name('jam-operasional');

    // Categories Management (tetap disimpan jika masih dipakai)
    Route::resource('categories', CategoryController::class);
});