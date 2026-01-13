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

        // 1. TANGKAP NILAI CHECKBOX
        // Jika dicentang = true, jika tidak = false
        $remember = $request->boolean('remember');

        // 2. CEK USER (MASYARAKAT)
        // Tambahkan $remember sebagai parameter kedua
        if (Auth::guard('web')->attempt($credentials, $remember)) {
            $user = Auth::guard('web')->user();

            if ($user->is_locked) {
                Auth::guard('web')->logout();
                return back()->with('error', 'Akun Anda telah dikunci. Silakan hubungi admin.');
            }

            $request->session()->regenerate();
            return redirect()->route('user.fasilitas')->with('success', 'Selamat Datang, ' . $user->name);
        }

        // 3. CEK ADMIN (STAFF)
        // Tambahkan $remember sebagai parameter kedua juga
        if (Auth::guard('admin')->attempt($credentials, $remember)) {
            $admin = Auth::guard('admin')->user();

            if (!$admin->is_active) {
                Auth::guard('admin')->logout();
                return back()->with('error', 'Akun Staff Anda dinonaktifkan.');
            }

            $request->session()->regenerate();
            return redirect()->route('admin.dashboard')->with('success', 'Selamat Datang Admin ' . $admin->name);
        }

        // 4. GAGAL
        return back()->with('error', 'Username atau Password salah.');
    }
}
