<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function login()
    {
        // Jika sudah login, langsung lempar ke halaman yang sesuai
        if (Auth::guard('web')->check()) {
            return redirect()->route('user.fasilitas');
        }
        if (Auth::guard('admin')->check()) {
            return redirect()->route('admin.dashboard');
        }

        return view('auth.login');
    }

    public function login_proses(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        $credentials = [
            'username' => $request->username,
            'password' => $request->password,
        ];

        // 1. CEK USER (MASYARAKAT)
        // Kita cek 'is_locked' juga. Jika terkunci, tolak.
        if (Auth::guard('web')->attempt($credentials)) {
            $user = Auth::guard('web')->user();

            if ($user->is_locked) {
                Auth::guard('web')->logout();
                return back()->with('error', 'Akun Anda telah dikunci. Silakan hubungi admin.');
            }

            $request->session()->regenerate();
            return redirect()->route('user.fasilitas')->with('success', 'Selamat Datang, ' . $user->name);
        }

        // 2. CEK ADMIN (STAFF)
        // Kita cek 'is_active' juga. Jika tidak aktif, tolak.
        if (Auth::guard('admin')->attempt($credentials)) {
            $admin = Auth::guard('admin')->user();

            if (!$admin->is_active) {
                Auth::guard('admin')->logout();
                return back()->with('error', 'Akun Staff Anda dinonaktifkan.');
            }

            $request->session()->regenerate();
            return redirect()->route('admin.dashboard')->with('success', 'Selamat Datang Admin ' . $admin->name);
        }

        // 3. GAGAL
        return back()->with('error', 'Username atau Password salah.');
    }
}
