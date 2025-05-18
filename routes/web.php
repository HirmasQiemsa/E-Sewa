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
use App\Http\Controllers\PetugasFasilitas\DashboardController as PetugasFasilitasDashboardController;
use App\Http\Controllers\PetugasFasilitas\FasilitasController as PetugasFasilitasController;
use App\Http\Controllers\PetugasFasilitas\JadwalController as PetugasFasilitasJadwalController;
use App\Http\Controllers\PetugasFasilitas\BookingController as PetugasFasilitasBookingController;
use App\Http\Controllers\PetugasFasilitas\AktivitasController as PetugasFasilitasAktivitasController;
use App\Http\Controllers\PetugasFasilitas\ProfileController as PetugasFasilitasProfileController;
use App\Http\Controllers\PetugasPembayaran\DashboardController as PetugasBayar;


Route::get('/laravel', function () {
    return view('welcome');
});
// as Guest //
Route::get('/', [BerandaController::class, 'index']);
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


// PETUGAS FASILITAS ROUTES //
Route::middleware(['auth:petugas_fasilitas', 'petugas_fasilitas'])->name('petugas_fasilitas.')->prefix('petugas-fasilitas')->group(function () {
    // Dashboard
    Route::get('/dashboard', [PetugasFasilitasDashboardController::class, 'index'])->name('dashboard');

    // Fasilitas Management
    Route::prefix('fasilitas')->name('fasilitas.')->group(function () {
        Route::get('/', [PetugasFasilitasController::class, 'index'])->name('index');
        Route::get('/create', [PetugasFasilitasController::class, 'create'])->name('create');
        Route::post('/store', [PetugasFasilitasController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [PetugasFasilitasController::class, 'edit'])->name('edit');
        Route::put('/{id}', [PetugasFasilitasController::class, 'update'])->name('update');
        Route::delete('/{id}', [PetugasFasilitasController::class, 'destroy'])->name('destroy');
        Route::post('/{id}/restore', [PetugasFasilitasController::class, 'restore'])->name('restore');
        Route::put('/{id}/toggle-status', [PetugasFasilitasController::class, 'toggleStatus'])->name('toggle-status');
    });

    // Jadwal Management
    Route::prefix('jadwal')->name('jadwal.')->group(function () {
        Route::get('/', [PetugasFasilitasJadwalController::class, 'index'])->name('index');
        Route::post('/generate', [PetugasFasilitasJadwalController::class, 'generate'])->name('generate');
        Route::get('/create', [PetugasFasilitasJadwalController::class, 'create'])->name('create');
        Route::post('/store', [PetugasFasilitasJadwalController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [PetugasFasilitasJadwalController::class, 'edit'])->name('edit');
        Route::put('/{id}', [PetugasFasilitasJadwalController::class, 'update'])->name('update');
        Route::delete('/{id}', [PetugasFasilitasJadwalController::class, 'destroy'])->name('destroy');
    });

    // Kelola Booking
    Route::prefix('booking')->name('booking.')->group(function () {
        Route::get('/', [PetugasFasilitasBookingController::class, 'daftarBooking'])->name('daftar');
        Route::get('/{id}/detail', [PetugasFasilitasBookingController::class, 'show'])->name('detail');
        Route::put('/{id}/update-status', [PetugasFasilitasBookingController::class, 'updateStatus'])->name('update-status');
        Route::put('/{id}/cancel', [PetugasFasilitasBookingController::class, 'cancelBooking'])->name('cancel');
    });

    // Activity Log
    Route::get('/activities', [PetugasFasilitasAktivitasController::class, 'index'])->name('activities');

    // Profile Management
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [PetugasFasilitasProfileController::class, 'edit'])->name('edit');
        Route::put('/update', [PetugasFasilitasProfileController::class, 'update'])->name('update');
        Route::put('/update-password', [PetugasFasilitasProfileController::class, 'updatePassword'])->name('update-password');
        Route::put('/update-account', [PetugasFasilitasProfileController::class, 'updateAccount'])->name('update-account');
    });
});


// PETUGAS PEMBAYARAN //
Route::middleware(['auth:petugas_pembayarans', 'petugas_pembayarans'])->name('petugas_pembayaran.')->group(function () {
    //
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

