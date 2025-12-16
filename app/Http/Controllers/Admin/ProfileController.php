<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    /**
     * Tampilkan Form Edit Profile
     */
    public function edit()
    {
        // Ambil data admin yang sedang login
        /** @var \App\Models\Admin $user */ //
        $user = Auth::guard('admin')->user();

        // Kita pakai satu view saja untuk semua admin agar efisien
        // Pastikan file ini ada di resources/views/Admin/profile.blade.php
        return view('Admin.profile', compact('user'));
    }

    /**
     * Proses Update Profile (Nama, Foto, Password)
     */
    public function update(Request $request)
    {
        /** @var \App\Models\Admin $user */
        $user = Auth::guard('admin')->user();

        // 1. Validasi Input
        $request->validate([
            'name'  => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                Rule::unique('admins')->ignore($user->id), // Email boleh sama kalau punya sendiri
            ],
            'no_hp' => 'nullable|numeric',
            'alamat'=> 'nullable|string',
            'foto'  => 'nullable|image|max:2048', // Max 2MB
            'password' => 'nullable|min:6|confirmed', // Opsional, hanya jika ingin ganti pass
        ]);

        // 2. Update Data Dasar
        $user->name  = $request->name;
        $user->email = $request->email;
        $user->no_hp = $request->no_hp;
        $user->alamat = $request->alamat;

        // 3. Cek apakah ada upload foto baru?
        if ($request->hasFile('foto')) {
            // Hapus foto lama jika ada (dan bukan default)
            if ($user->foto && Storage::disk('public')->exists($user->foto)) {
                Storage::disk('public')->delete($user->foto);
            }

            // Simpan foto baru ke folder 'admins' di storage public
            $path = $request->file('foto')->store('admins', 'public');
            $user->foto = $path;
        }

        // 4. Cek apakah password diisi? (Ganti Password)
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        // 5. Simpan ke Database
        $user->save(); // Karena user diambil dari Auth (Eloquent), bisa langsung save()

        return back()->with('success', 'Profil berhasil diperbarui!');
    }
}
