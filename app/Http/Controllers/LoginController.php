<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function login()
    {
        return view('login');
    }
    public function login_proses(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        // Logout dari semua guard terlebih dahulu
        Auth::guard('web')->logout();
        Auth::guard('admin')->logout();

        // Coba login sebagai Admin
        if (Auth::guard('admin')->attempt($request->only('username', 'password'))) {
            return redirect()->intended(route('admin.dashboard'))->with('success', 'Selamat datang, Admin!');
        }

        // Coba login sebagai User
        if (Auth::guard('web')->attempt($request->only('username', 'password'))) {
            return redirect()->intended(route('user.beranda'))->with('success', 'Selamat datang, User!');
        }

        // Jika gagal
        return back()->with('error', 'Login gagal! Periksa kembali username dan password Anda.');
    }
}
