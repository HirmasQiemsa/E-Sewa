<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\FasilitasController;
use App\Http\Controllers\Admin\ProfileAdminController;
use App\Http\Controllers\Admin\RiwayatController;
use App\Http\Controllers\Admin\JadwalController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\LogoutController;
use App\Http\Controllers\BerandaController;
use App\Http\Controllers\User\CheckoutController;
use App\Http\Controllers\User\BookingController;
use App\Http\Controllers\User\UserController;


// as Guest //
Route::get('/laravel', function () {
    return view('welcome');
});
Route::get('/', [BerandaController::class, 'index']);
Route::get('/register', function () {
    return view('auth.register');
})->name('register');
Route::post('/register/proses', [RegisterController::class,'register_proses'])->name('register-proses');
Route::post('/login/proses', [LoginController::class,'login_proses'])->name('login-proses');
Route::get('/login', [LoginController::class,'login'])->name('login');
Route::post('/logout', [LogoutController::class,'logout'])->name('logout');


// PETUGAS FASILITAS //
Route::middleware(['auth:petugas_fasilitas', 'petugas_fasilitas'])->name('petugas_fasilitas.')->group(function () {
    //
});


// PETUGAS FASILITAS //
Route::middleware(['auth:petugas_pembayarans', 'petugas_pembayarans'])->name('petugas_pembayarans.')->group(function () {
    //
});


// ADMIN //
Route::middleware(['auth:admin', 'admin'])->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard'); //
    // Fasilitas (CRUD + restore)
    Route::get('/petugas/fasilitas', [FasilitasController::class, 'index'])->name('fasilitas.index');
    Route::get('/fasilitas/tambah', [FasilitasController::class, 'tambah'])->name('fasilitas.tambah');
    Route::post('/fasilitas/tambah', [FasilitasController::class, 'store'])->name('fasilitas.store');
    Route::post('/fasilitas/gunakan', [FasilitasController::class, 'store_gunakan'])->name('fasilitas.storeG');
    Route::get('/fasilitas/{id}/gunakan', [FasilitasController::class, 'gunakan'])->name('fasilitas.gunakan');
    Route::get('/fasilitas/{id}/edit', [FasilitasController::class, 'edit'])->name('fasilitas.edit');
    Route::put('/fasilitas/{id}', [FasilitasController::class, 'update'])->name('fasilitas.update');
    Route::post('/fasilitas/{id}/restore', [FasilitasController::class, 'restore'])->name('fasilitas.restore');
    Route::delete('/fasilitas/{id}', [FasilitasController::class,'delete'])->name('fasilitas.delete');
    // Kelola Jadwal
    Route::prefix('jadwal')->name('jadwal.')->group(function () {
        Route::get('/', [JadwalController::class, 'index'])->name('index');
        Route::post('/generate', [JadwalController::class, 'generate'])->name('generate');
        Route::get('/tambah', [JadwalController::class, 'create'])->name('create');
        Route::post('/store', [JadwalController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [JadwalController::class, 'edit'])->name('edit');
        Route::put('/{id}', [JadwalController::class, 'update'])->name('update');
        Route::delete('/{id}', [JadwalController::class, 'destroy'])->name('destroy');
    });
    // Riwayat
    Route::get('/riwayat-general', [RiwayatController::class, 'index'])->name('riwayat.index');
    // Profile
    Route::get('/admin/profile', [ProfileAdminController::class, 'edit'])->name('profile.edit');
});

// USER //
Route::middleware(['auth:web', 'user:user'])->name('user.')->group(function () {
    // Fasilitas
    Route::get('/fasilitas', [UserController::class, 'beranda'])->name('fasilitas');
    // Profile
    Route::get('/profile', [UserController::class, 'profile'])->name('profile');
    Route::put('/profile/update', [UserController::class, 'updateProfile'])->name('update');
    Route::put('/profile/update-account', [UserController::class, 'updateAccount'])->name('update-account');
    Route::put('/profile/update-password', [UserController::class, 'updatePassword'])->name('update-password');
    // Riwayat dan Bukti Pembayaran
    Route::get('/riwayat', [BookingController::class, 'riwayat'])->name('riwayat');
    Route::get('/riwayat/receipt/{id}', [BookingController::class, 'downloadReceipt'])->name('riwayat.receipt');
    // Booking
    Route::get('/lapangan-tenis', [BookingController::class, 'tenis'])->name('tenis');
    Route::get('/lapangan-voli', [BookingController::class, 'voli'])->name('voli');
    Route::get('/lapangan-futsal', [BookingController::class, 'futsal'])->name('futsal');
    // Checkout
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout');
    Route::post('/checkout/store', [CheckoutController::class, 'store'])->name('checkout.store');
    Route::get('/checkout/pelunasan/{id}', [CheckoutController::class, 'pelunasan'])->name('checkout.pelunasan');
    Route::post('/checkout/pelunasan/{id}', [CheckoutController::class, 'prosesLunasi'])->name('checkout.proses-lunasi');
    Route::get('/checkout/detail/{id}', [CheckoutController::class, 'detail'])->name('checkout.detail');
    Route::put('/checkout/cancel/{id}', [CheckoutController::class, 'cancel'])->name('checkout.cancel');
    // API Route untuk cek jadwal
    Route::get('/api/check-jadwal/{fasilitasId}/{tanggal}', [CheckoutController::class, 'checkJadwal']);
});
// routes/web.php


// ------------------------------------------------DUMB ROUTE (below)---------------------------------------------------- //

