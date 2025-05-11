<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\FasilitasController;
use App\Http\Controllers\Admin\ProfileAdminController;
use App\Http\Controllers\Admin\RiwayatController;
use App\Http\Controllers\Admin\UsersController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\LogoutController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\User\CheckoutController;

// as Guest //
Route::get('/laravel', function () {
    return view('welcome');
});
Route::get('/', function () {
    return view('beranda');
});
Route::get('/register', function () {
    return view('auth.register');
})->name('register');
Route::post('/register/proses', [RegisterController::class,'register_proses'])->name('register-proses');
Route::post('/login/proses', [LoginController::class,'login_proses'])->name('login-proses');
Route::get('/login', [LoginController::class,'login'])->name('login');
Route::post('/logout', [LogoutController::class,'logout'])->name('logout');


// ADMIN //
Route::middleware(['auth:admin', 'admin'])->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard'); //

    // Fasilitas (CRUD + restore)
    Route::get('/fasilitas', [FasilitasController::class, 'index'])->name('fasilitas.index');
    Route::get('/fasilitas/tambah', [FasilitasController::class, 'tambah'])->name('fasilitas.tambah');
    Route::post('/fasilitas/tambah', [FasilitasController::class, 'store'])->name('fasilitas.store');
    Route::post('/fasilitas/gunakan', [FasilitasController::class, 'store_gunakan'])->name('fasilitas.storeG');
    Route::get('/fasilitas/{id}/gunakan', [FasilitasController::class, 'gunakan'])->name('fasilitas.gunakan');
    Route::get('/fasilitas/{id}/edit', [FasilitasController::class, 'edit'])->name('fasilitas.edit');
    Route::put('/fasilitas/{id}', [FasilitasController::class, 'update'])->name('fasilitas.update');
    Route::post('/fasilitas/{id}/restore', [FasilitasController::class, 'restore'])->name('fasilitas.restore');
    Route::delete('/fasilitas/{id}', [FasilitasController::class,'delete'])->name('fasilitas.delete');

    // User
    Route::get('/kelola/users', [UsersController::class, 'index'])->name('users.index');

    // Riwayat
    Route::get('/riwayat-general', [RiwayatController::class, 'index'])->name('riwayat.index');

    // Profile
    Route::get('/admin/profile', [ProfileAdminController::class, 'edit'])->name('profile.edit');
});

// USER //
Route::middleware(['auth:web', 'user:user'])->name('user.')->group(function () {
    Route::get('/beranda', [UserController::class, 'beranda'])->name('beranda');
    Route::get('/futsal', [UserController::class, 'futsal'])->name('futsal');
    Route::get('/tenis', [UserController::class, 'tenis'])->name('tenis');
    Route::get('/voli', [UserController::class, 'voli'])->name('voli');

    Route::get('/riwayat', [UserController::class, 'riwayat'])->name('riwayat');
    Route::get('/profile', [UserController::class, 'profile'])->name('profile');

    // Checkout
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout');
});



// ------------------------------------------------DUMB ROUTE (below)---------------------------------------------------- //

