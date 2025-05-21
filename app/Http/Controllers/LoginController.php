<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    // View Blade
    public function login()
    {
        return view('login');
    }

    // Proses POS
    public function login_proses(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        // Logout dari semua guard terlebih dahulu
        Auth::guard('web')->logout();
        Auth::guard('admin')->logout();
        Auth::guard('petugas_fasilitas')->logout();
        Auth::guard('petugas_pembayarans')->logout();

        // Coba login sebagai Petugas Pembayaran
        if (Auth::guard('petugas_pembayarans')->attempt($request->only('username', 'password'))) {
            return redirect()->route('petugas_pembayaran.dashboard')
                            ->with('success', 'Selamat datang, Petugas Pembayaran!');
        }

        // Coba login sebagai Petugas Fasilitas
        if (Auth::guard('petugas_fasilitas')->attempt($request->only('username', 'password'))) {
            return redirect()->route('petugas_fasilitas.dashboard')
                            ->with('success', 'Selamat datang, Petugas Fasilitas!');
        }

        // Coba login sebagai Admin
        if (Auth::guard('admin')->attempt($request->only('username', 'password'))) {
            return redirect()->route('admin.dashboard')
                            ->with('success', 'Selamat datang, Admin!');
        }

        // Coba login sebagai User
        if (Auth::guard('web')->attempt($request->only('username', 'password'))) {
            return redirect()->route('user.fasilitas')
                            ->with('success', 'Selamat datang, User!');
        }

        // Jika gagal
        return back()->with('error', 'Login gagal! Periksa kembali username dan password Anda.');
    }
}
