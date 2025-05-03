<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\FasilitasController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\RiwayatController;
use App\Http\Controllers\Admin\UserController;

use App\Http\Controllers\RegisterController;
use App\Http\Controllers\LoginController;

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


// ADMIN //
Route::middleware(['auth:admin', 'admin'])->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard'); //

    // Fasilitas (CRUD + restore)
    Route::get('/fasilitas', [FasilitasController::class, 'index'])->name('fasilitas.index');
    Route::get('/fasilitas/tambah', [FasilitasController::class, 'tambah'])->name('fasilitas.tambah');
    Route::post('/fasilitas/tambah', [FasilitasController::class, 'store'])->name('fasilitas.store');
    Route::get('/fasilitas/{id}/edit', [FasilitasController::class, 'edit'])->name('fasilitas.edit');
    Route::put('/fasilitas/{id}', [FasilitasController::class, 'update'])->name('fasilitas.update');
    Route::post('/fasilitas/{id}/restore', [FasilitasController::class, 'restore'])->name('fasilitas.restore');
    Route::delete('/fasilitas/{id}', [FasilitasController::class,'delete'])->name('fasilitas.delete');

    // User
    Route::get('/users', [UserController::class, 'index'])->name('users.index');

    // Riwayat
    Route::get('/riwayat', [RiwayatController::class, 'index'])->name('riwayat.index');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
});

// USER //
Route::middleware(['auth:web', 'user:user'])->group(function () {
    Route::get('/user/beranda', [UserController::class, 'beranda'])->name('user.beranda');
    Route::get('/user/futsal', [UserController::class, 'futsal'])->name('user.futsal');
    Route::get('/user/tenis', [UserController::class, 'tenis'])->name('user.tenis');
    Route::get('/user/voli', [UserController::class, 'voli'])->name('user.voli');
    Route::get('/user/checkout', [UserController::class, 'checkout'])->name('user.checkout');
    Route::get('/user/riwayat', [UserController::class, 'riwayat'])->name('user.riwayat');
    Route::get('/user/profile', [UserController::class, 'profile'])->name('user.profile');
});



// ------------------------------------------------DUMB ROUTE (below)---------------------------------------------------- //

