<?php

namespace App\Http\Controllers\PetugasFasilitas;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PetugasFasilitas;
use App\Models\Admin;
use App\Models\User;
use App\Models\PetugasPembayaran;
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
     * Memperbarui akun
     */
    public function updateAccount(Request $request)
    {
        $petugas = Auth::guard('petugas_fasilitas')->user();

        $request->validate([
            'username' => [
                'required',
                'alpha_num',
                Rule::unique('petugas_fasilitas')->ignore($petugas->id),
            ],
            'email' => [
                'required',
                'email',
                Rule::unique('petugas_fasilitas')->ignore($petugas->id),
            ],
        ]);

        // Check if username exists in other tables
        $username = $request->username;

        $existsInAdmin = Admin::where('username', $username)
            ->exists();

        $existsInUser = User::where('username', $username)
            ->exists();

        $existsInPembayaran = PetugasPembayaran::where('username', $username)
            ->exists();

        if ($existsInAdmin || $existsInUser || $existsInPembayaran) {
            return back()->withErrors(['username' => 'Username sudah digunakan oleh petugas atau user lain.'])->withInput();
        }

        try {
            // Update account info
            $petugas->username = $request->username;
            $petugas->email = $request->email;
            $petugas->save();

            return redirect()->route('petugas_fasilitas.profile.edit')
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
        $petugas = Auth::guard('petugas_fasilitas')->user();

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

        return redirect()->route('petugas_fasilitas.profile.edit')
            ->with('success', 'Password berhasil diperbarui');
    }

     /**
     * Add a new account (Petugas Fasilitas or Petugas Pembayaran)
     * Only accessible for admin (ID 1)
     */
    public function addAccount(Request $request)
    {
        // Check if current user is admin (ID 1)
        $currentPetugas = Auth::guard('petugas_fasilitas')->user();

        if ($currentPetugas->id != 1) {
            return redirect()->back()->with('error', 'Anda tidak memiliki izin untuk menambahkan petugas baru');
        }

        // Validate the request data
        $request->validate([
            'role' => 'required|in:petugas_fasilitas,petugas_pembayaran',
            'name' => 'required|string|max:255',
            'new_username' => 'required|alpha_num|min:3|max:20|unique:users,username|unique:petugas_fasilitas,username|unique:petugas_pembayarans,username|unique:admins,username',
            'no_hp' => 'required|string|min:5|max:15|unique:petugas_fasilitas,no_hp|unique:petugas_pembayarans,no_hp',
            'alamat' => 'required|string',
            'new_password' => 'required|min:3|confirmed',
        ]);

        try {
            // Create new account based on role
            if ($request->role == 'petugas_fasilitas') {
                PetugasFasilitas::create([
                    'username' => $request->new_username,
                    'name' => $request->name,
                    'no_hp' => $request->no_hp,
                    'alamat' => $request->alamat,
                    'password' => Hash::make($request->new_password),
                    'role' => 'petugas_fasilitas'
                ]);

                return redirect()->route('petugas_fasilitas.profile.edit')
                    ->with('success', 'Akun Petugas Fasilitas baru berhasil ditambahkan');
            } else {
                PetugasPembayaran::create([
                    'username' => $request->new_username,
                    'name' => $request->name,
                    'no_hp' => $request->no_hp,
                    'alamat' => $request->alamat,
                    'password' => Hash::make($request->new_password),
                    'role' => 'petugas_pembayaran'
                ]);

                return redirect()->route('petugas_fasilitas.profile.edit')
                    ->with('success', 'Akun Petugas Pembayaran baru berhasil ditambahkan');
            }
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal menambahkan akun baru: ' . $e->getMessage())
                ->withInput();
        }
    }

}
