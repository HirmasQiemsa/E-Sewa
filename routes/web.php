<?php

use Illuminate\Support\Facades\Route;

// AUTH & LANDING
use App\Http\Controllers\BerandaController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LogoutController;

// User
use App\Http\Controllers\User\BerandaController as UserBerandaController;
use App\Http\Controllers\User\BookingController as UserBookingController;
use App\Http\Controllers\User\CheckoutController as UserCheckoutController;
use App\Http\Controllers\User\RiwayatController as UserRiwayatController;
use App\Http\Controllers\User\ProfileController as UserProfileController;

// Admin General
use App\Http\Controllers\Admin\DashboardController as SharedDashboard;
use App\Http\Controllers\Admin\ProfileController as SharedProfile;

// Admin Fasilitas
use App\Http\Controllers\Admin\Fasilitas\FasilitasController;
use App\Http\Controllers\Admin\Fasilitas\JadwalController;
use App\Http\Controllers\Admin\Fasilitas\BookingController;

// Admin Pembayaran
use App\Http\Controllers\Admin\Pembayaran\VerifikasiController;
use App\Http\Controllers\Admin\Pembayaran\KeuanganController;

// Super Admin
use App\Http\Controllers\Admin\Super\UserController;
use App\Http\Controllers\Admin\Super\ApprovalController;
use App\Http\Controllers\Admin\Super\UserPublicController;
use App\Http\Controllers\Admin\Super\LaporanController;



/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// ============================================================================
// 1. GUEST ROUTES (Landing Page & Auth)
// ============================================================================

Route::get('/', [BerandaController::class, 'index'])->name('landing');

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'login'])->name('login');
    Route::post('/login/proses', [LoginController::class, 'login_proses'])->name('login-proses');

    Route::get('/register', function () {
        return view('auth.register');
    })->name('register');
    Route::post('/register/proses', [RegisterController::class, 'register_proses'])->name('register-proses');
});

Route::post('/logout', [LogoutController::class, 'logout'])->name('logout');


// ============================================================================
// 2. USER ROUTES (Masyarakat)
// Middleware: Login sebagai 'web' (User) dan Role 'user'
// ============================================================================

Route::middleware(['auth:web', 'role:user'])->name('user.')->group(function () {

    // Beranda User (Setelah Login)
    Route::get('/beranda', [UserBerandaController::class, 'beranda'])->name('fasilitas');

    // Detail Fasilitas & Booking
    Route::get('/fasilitas/{id}/detail', [UserBookingController::class, 'show'])->name('fasilitas.detail');

    // Checkout & Pembayaran
    Route::get('/checkout', [UserCheckoutController::class, 'index'])->name('checkout');
    Route::post('/checkout/store', [UserCheckoutController::class, 'store'])->name('checkout.store');

    // Halaman Detail Transaksi (Invoice) & Upload Bukti
    Route::get('/checkout/detail/{id}', [UserCheckoutController::class, 'detail'])->name('checkout.detail');
    Route::put('/checkout/{id}/upload-bukti', [UserCheckoutController::class, 'uploadBukti'])->name('checkout.upload_bukti');

    // Pembatalan & Cetak
    Route::put('/checkout/cancel/{id}', [UserCheckoutController::class, 'cancel'])->name('checkout.cancel');
    Route::get('/checkout/{id}/print', [UserCheckoutController::class, 'print'])->name('checkout.print');

    // API Helper untuk Cek Jadwal (AJAX)
    Route::get('/api/check-jadwal/{fasilitasId}/{tanggal}', [UserCheckoutController::class, 'checkJadwal']);

    // Riwayat Pemesanan
    Route::get('/riwayat', [UserRiwayatController::class, 'index'])->name('riwayat');
    // Route::get('/riwayat/receipt/{id}', [UserRiwayatController::class, 'downloadReceipt'])->name('riwayat.receipt'); // Diganti checkout.print

    // Profile User
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [UserProfileController::class, 'index'])->name('index');
        Route::put('/update', [UserProfileController::class, 'updateProfile'])->name('update');
        Route::put('/account', [UserProfileController::class, 'updateAccount'])->name('account');
        Route::put('/password', [UserProfileController::class, 'updatePassword'])->name('password');
    });
});


// ============================================================================
// 3. ADMIN ROUTES
// Middleware Utama: Login sebagai 'admin' (Semua role masuk sini)
// ============================================================================

Route::middleware(['auth:admin'])->prefix('admin')->name('admin.')->group(function () {

    // A. SHARED ROUTES (Semua Admin bisa akses ini)
    // ------------------------------------------------------------
    // Route Dashboard menghandle semua role
    Route::get('/dashboard', [SharedDashboard::class, 'index'])->name('dashboard');

    // Route Profile menghandle semua role
    Route::get('/profile', [SharedProfile::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [SharedProfile::class, 'update'])->name('profile.update');


    // B. ROUTES KHUSUS (Dibatasi Middleware Role)
    // ------------------------------------------------------------

    // 1. SUPER ADMIN (Kepala Dinas)
    Route::middleware(['role:super_admin'])->name('super.')->group(function () {
        // A. Manajemen User Staff (Admin Fasilitas & Pembayaran)
        // Menggunakan Resource Controller standar
        // Route yang terbentuk: admin.super.users.index, create, store, edit, update, destroy
        Route::resource('users', UserController::class)->except(['show']);

        // B. Approval Fasilitas
        // Pakai custom route karena butuh aksi spesifik (action approve/reject)
        Route::prefix('approval')->name('approval.')->controller(ApprovalController::class)->group(function () {
            Route::get('/', 'index')->name('index');        // admin.super.approval.index
            Route::get('/{id}', 'show')->name('show');      // admin.super.approval.show
            Route::put('/{id}', 'action')->name('action');  // admin.super.approval.action (Proses Approve/Reject)
        });
        // === [NEW] MANAJEMEN MASYARAKAT ===
        Route::prefix('masyarakat')->name('masyarakat.')->controller(UserPublicController::class)->group(function() {
            Route::get('/', 'index')->name('index');
            Route::get('/{id}', 'show')->name('show');
            Route::put('/{id}/lock', 'lock')->name('lock'); // Untuk lock/unlock akun
            Route::delete('/{id}', 'destroy')->name('destroy');
        });

        // === [NEW] LAPORAN & MONITORING ===
        Route::prefix('laporan')->name('laporan.')->controller(LaporanController::class)->group(function() {
            Route::get('/transaksi', 'transaksi')->name('transaksi');
            Route::get('/log', 'log')->name('log');
        });
    });

    // 2. ADMIN FASILITAS (Staff Prasarana)
    // Note: Tambahkan 'super_admin' jika Kadin boleh akses menu ini juga
    Route::middleware(['role:super_admin,admin_fasilitas'])->prefix('fasilitas')->name('fasilitas.')->group(function () {

        // CRUD Fasilitas
        Route::resource('data', FasilitasController::class);
        Route::put('data/{id}/restore', [FasilitasController::class, 'restore'])->name('data.restore');
        Route::put('/data/{id}/toggle-status', [FasilitasController::class, 'toggleStatus'])->name('data.toggle_status');

        // Jadwal
        Route::prefix('jadwal')->name('jadwal.')->group(function () {
            Route::get('/', [JadwalController::class, 'index'])->name('index');
            Route::post('/generate', [JadwalController::class, 'generate'])->name('generate');
            Route::delete('/bulk-delete', [JadwalController::class, 'bulkDestroy'])->name('bulk_destroy');
        });

        // ROUTE BOOKING
        Route::prefix('booking')->name('booking.')->controller(BookingController::class)->group(function () {
            Route::get('/', 'daftarBooking')->name('index');
            Route::get('/{id}', 'show')->name('detail');
            Route::put('/{id}/cancel', 'cancelBooking')->name('cancel');
            Route::put('/{id}/update-status', 'updateStatus')->name('update-status');
        });
    });

    // 3. ADMIN PEMBAYARAN (Staff Keuangan)
    Route::middleware(['role:super_admin,admin_pembayaran'])->prefix('keuangan')->name('keuangan.')->group(function () {

        // A. VERIFIKASI (Operasional)
        Route::controller(VerifikasiController::class)->prefix('verifikasi')->name('verifikasi.')->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/{id}', 'show')->name('show');
            Route::put('/{id}/confirm', 'verifikasi')->name('confirm');
            Route::put('/{id}/reject', 'tolak')->name('reject');
        });

        // B. KEUANGAN (Laporan & Statistik)
        Route::controller(KeuanganController::class)->group(function () {
            // 1. Route Index & Ringkasan (INI YANG HILANG DAN BIKIN ERROR)
            Route::get('/', 'index')->name('index');
            Route::get('/ringkasan', 'ringkasan')->name('ringkasan'); // <--- INI SOLUSINYA
            Route::get('/export/ringkasan/{type}', 'exportRingkasan')->name('export_ringkasan');

            // 2. Route Transaksi
            Route::get('/transaksi', 'transaksi')->name('transaksi');
            // Ubah nama route export agar spesifik (export_transaksi) karena ada export_ringkasan juga
            Route::get('/export/transaksi', 'exportTransaksi')->name('export_transaksi');
        });
    });

});
