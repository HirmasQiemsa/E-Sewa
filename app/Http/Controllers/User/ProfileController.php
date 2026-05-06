<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        return view('User.profile', compact('user'));
    }

    /**
     * Update gabungan (Profil, Akun, Password) dalam satu tarikan napas
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        // 1. VALIDASI GABUNGAN
        // Kita set password nullable, jadi kalau kosong dianggap gak mau ganti password
        $validated = $request->validate([
            // --- Bagian Profil ---
            'name'     => 'required|string|max:255',
            'no_hp'    => ['required', 'numeric', 'min_digits:10', Rule::unique('users')->ignore($user->id)],
            'no_ktp'   => ['nullable', 'numeric', 'digits:16', Rule::unique('users')->ignore($user->id)],
            'alamat'   => 'required|string',
            'foto'     => 'nullable|image|mimes:jpeg,png,jpg|max:2048',

            // --- Bagian Akun ---
            'username' => ['required', 'alpha_num', Rule::unique('users')->ignore($user->id)],
            'email'    => ['required', 'email', Rule::unique('users')->ignore($user->id)],

            // --- Bagian Password (Optional) ---
            // 'current_password' cuma wajib kalau 'password' (baru) diisi
            'password' => 'nullable|min:6|confirmed',
            'current_password' => 'required_with:password',
        ]);

        // Cek duplikasi username di tabel Admin (Staff) kayak logic kamu sebelumnya
        if (Admin::where('username', $request->username)->exists()) {
             return back()->withErrors(['username' => 'Username ini tidak tersedia (digunakan oleh sistem).'])->withInput();
        }

        try {
            // === LOGIKA 1: UPDATE FOTO ===
            if ($request->hasFile('foto')) {
                if ($user->foto && Storage::disk('public')->exists($user->foto)) {
                    Storage::disk('public')->delete($user->foto);
                }
                $user->foto = $request->file('foto')->store('users', 'public');
            }

            // === LOGIKA 2: UPDATE DATA DIRI & AKUN ===
            $user->name     = $request->name;
            $user->no_hp    = $request->no_hp;
            $user->no_ktp   = $request->no_ktp;
            $user->alamat   = $request->alamat;
            $user->username = $request->username;
            $user->email    = $request->email;

            // === LOGIKA 3: UPDATE PASSWORD (Cuma kalau diisi) ===
            if ($request->filled('password')) {
                // Cek password lama bener gak
                if (!Hash::check($request->current_password, $user->password)) {
                    return back()->withErrors(['current_password' => 'Password saat ini salah.'])->withInput();
                }
                // Kalau bener, hash password baru
                $user->password = Hash::make($request->password);
            }

            // Simpan semua perubahan
            $user->save();

            return redirect()->route('user.profile.index')->with('success', 'Data profil dan akun berhasil diperbarui!');

        } catch (\Exception $e) {
            // Satu Catch buat nangkep error apapun
            return back()->with('error', 'Gagal update: ' . $e->getMessage())->withInput();
        }
    }
}
