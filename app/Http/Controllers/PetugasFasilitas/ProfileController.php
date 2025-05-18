<?php

namespace App\Http\Controllers\PetugasFasilitas;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PetugasFasilitas;
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
        $petugas = Auth::guard('petugas_fasilitas')->user();
        return view('PetugasFasilitas.Profile.edit', compact('petugas'));
    }

    /**
     * Memperbarui profil
     */
    public function update(Request $request)
    {
        $petugas = Auth::guard('petugas_fasilitas')->user();

        $request->validate([
            'name' => 'required|string|max:255',
            'no_hp' => [
                'required',
                'string',
                Rule::unique('petugas_fasilitas', 'no_hp')->ignore($petugas->id)
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

        return redirect()->route('petugas_fasilitas.profile.edit')
            ->with('success', 'Profil berhasil diperbarui');
    }

    /**
     * Memperbarui password
     */
    public function updatePassword(Request $request)
    {
        $petugas = Auth::guard('petugas_fasilitas')->user();

        $request->validate([
            'current_password' => 'required',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Verifikasi password lama
        if (!Hash::check($request->current_password, $petugas->password)) {
            return redirect()->back()->withErrors(['current_password' => 'Password saat ini salah']);
        }

        // Update password
        $petugas->password = Hash::make($request->password);
        $petugas->save();

        return redirect()->route('petugas_fasilitas.profile.edit')
            ->with('success', 'Password berhasil diperbarui');
    }
}
