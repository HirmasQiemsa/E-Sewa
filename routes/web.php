<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;


Route::get('/laravel', function () {
    return view('welcome');
});
Route::get('/', function () {
    return view('beranda');
});
Route::get('/login', function () {
    return view('login');
});

// ADMIN
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/beranda', [AdminController::class, 'beranda'])->name('admin.dashboard');
});


//USER
Route::middleware(['auth', 'role:user'])->group(function () {
    Route::get('/user/beranda', [UserController::class, 'beranda'])->name('user.dashboard');
});

//dumb
//Route::middleware(['auth', 'role:admin'])->group(function () {Route::get('/admin/dashboard', function () {return view('admin.dashboard');})->name('admin.dashboard');});
//
