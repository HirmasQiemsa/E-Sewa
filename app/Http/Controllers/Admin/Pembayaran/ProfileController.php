<?php

namespace App\Http\Controllers\Admin\Pembayaran;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AdminPembayaran;
use App\Models\Admin;
use App\Models\User;
use App\Models\AdminFasilitas;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    /**
     * Menampilkan form edit profil
     */
    public function edit()
    {
        $petugas = Auth::guard('admins')->user();
        return view('AdminPembayaran.Profile.edit', compact('petugas'));
    }

    /**
     * Memperbarui profil
     */
    public function update(Request $request)
    {
        $petugas = Auth::guard('admins')->user();

        $request->validate([
            'name' => 'required|string|max:255',
            'no_hp' => [
                'required',
                'string',
                Rule::unique('admins', 'no_hp')->ignore($petugas->id)
            ],
            'alamat' => 'required|string',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Handle foto upload jika ada
        if ($request->hasFile('foto')) {
            // Hapus foto lama jika ada
            if ($petugas->foto && Storage::disk('public')->exists($petugas->foto)) {
                Storage::disk('public')->delete($petugas->foto);
            }

            // Upload foto baru
            $path = $request->file('foto')->store('petugas', 'public');
            $petugas->foto = $path;
        }

        // Update profil
        $petugas->name = $request->name;
        $petugas->no_hp = $request->no_hp;
        $petugas->alamat = $request->alamat;
        $petugas->save();

        return redirect()->route('petugas_pembayaran.profile.edit')
            ->with('success', 'Profil berhasil diperbarui');
    }

    /**
     * Memperbarui akun
     */
    public function updateAccount(Request $request)
    {
        $petugas = Auth::guard('admins')->user();

        $request->validate([
            'username' => [
                'required',
                'alpha_num',
                Rule::unique('admins')->ignore($petugas->id),
            ],
            'email' => [
                'required',
                'email',
                Rule::unique('admins')->ignore($petugas->id),
            ],
        ]);

        // Check if username exists in other tables
        $username = $request->username;

        $existsInAdmin = Admin::where('username', $username)
            ->exists();

        $existsInUser = User::where('username', $username)
            ->exists();

        $existsInFasilitas = AdminFasilitas::where('username', $username)
            ->exists();

        if ($existsInAdmin || $existsInUser || $existsInFasilitas) {
            return back()->withErrors(['username' => 'Username sudah digunakan oleh petugas atau user lain.'])->withInput();
        }

        try {
            // Update account info
            $petugas->username = $request->username;
            $petugas->email = $request->email;
            $petugas->save();

            return redirect()->route('petugas_pembayaran.profile.edit')
                ->with('success', 'Informasi akun berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Memperbarui password
     */
    public function updatePassword(Request $request)
    {
        $petugas = Auth::guard('admins')->user();

        $request->validate([
            'current_password' => 'required',
            'password' => 'required|string|min:6|confirmed',
        ]);

        // Verifikasi password lama
        if (!Hash::check($request->current_password, $petugas->password)) {
            return redirect()->back()->withErrors(['current_password' => 'Password saat ini salah']);
        }

        // Update password
        $petugas->password = Hash::make($request->password);
        $petugas->save();

        return redirect()->route('petugas_pembayaran.profile.edit')
            ->with('success', 'Password berhasil diperbarui');
    }
}
