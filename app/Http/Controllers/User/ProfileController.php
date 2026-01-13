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
    /**
     * Display user profile page
     */
    public function index()
    {
        $user = Auth::user();
        // Pastikan view-nya ada di resources/views/User/Profile/edit.blade.php
        return view('User.profile', compact('user'));
    }

    /**
     * Update user profile information (Nama, HP, Alamat, Foto)
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'no_hp' => [
                'required',
                'numeric',
                'min_digits:10',
                Rule::unique('users')->ignore($user->id),
            ],
            // Hapus unique check no_ktp jika memang user bisa punya akun tanpa KTP valid (opsional)
            'no_ktp' => [
                'nullable',
                'numeric',
                'digits:16', // Gunakan 'digits' untuk panjang pas 16
                Rule::unique('users')->ignore($user->id),
            ],
            'alamat' => 'required|string',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        try {
            // Handle photo upload
            if ($request->hasFile('foto')) {
                // Hapus foto lama jika bukan default
                if ($user->foto && Storage::disk('public')->exists($user->foto)) {
                    Storage::disk('public')->delete($user->foto);
                }

                // Upload foto baru
                $filePath = $request->file('foto')->store('users', 'public');
                $validated['foto'] = $filePath;
            }

            $user->update($validated);

            return redirect()->route('user.fasilitas')->with('success', 'Informasi profil berhasil diperbarui.');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Update user account information (Username & Email)
     */
    public function updateAccount(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'username' => [
                'required',
                'alpha_num',
                Rule::unique('users')->ignore($user->id),
            ],
            'email' => [
                'required',
                'email',
                Rule::unique('users')->ignore($user->id),
            ],
        ]);

        // Cek duplikasi username di tabel ADMINS (Semua Staff)
        // Karena kita sudah gabung tabel admin_fasilitas & pembayaran ke tabel 'admins'
        if (Admin::where('username', $request->username)->exists()) {
            return back()->withErrors(['username' => 'Username ini tidak tersedia (digunakan oleh sistem).'])->withInput();
        }

        try {
            $user->update([
                'username' => $request->username,
                'email' => $request->email
            ]);

            return redirect()->route('user.index')->with('success', 'Informasi akun berhasil diperbarui.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal update akun: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Update user password
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|min:6|confirmed',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Password saat ini salah.']);
        }

        try {
            $user->update([
                'password' => Hash::make($request->password)
            ]);

            return redirect()->route('user.index')->with('success', 'Password berhasil diubah.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal ubah password: ' . $e->getMessage());
        }
    }
}
