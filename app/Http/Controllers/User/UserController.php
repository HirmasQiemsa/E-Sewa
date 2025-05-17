<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Admin;
use App\Models\PetugasFasilitas;
use App\Models\PetugasPembayaran;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    // View Blade
    public function beranda()
    {
        return view('User.Fasilitas.index');
    }

    /**
     * Display user profile page
     */
    public function profile()
    {
        $user = Auth::user();
        return view('User.Profile.edit', compact('user'));
    }

    /**
     * Update user profile information
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
            'no_ktp' => [
                'nullable',
                'numeric',
                'min_digits:16',
                'max_digits:16',  // Memastikan KTP tepat 16 digit
                Rule::unique('users')->ignore($user->id),
            ],
            'alamat' => 'required|string',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        try {
            // Handle photo upload if provided
            if ($request->hasFile('foto')) {
                // Remove old photo if exists
                if ($user->foto && Storage::disk('public')->exists($user->foto)) {
                    Storage::disk('public')->delete($user->foto);
                }

                // Upload new photo
                $filePath = $request->file('foto')->store('users', 'public');
                $validated['foto'] = $filePath;
            }

            // Update user profile
            $user->update($validated);

            return redirect()->route('user.profile')->with('success', 'Informasi profil berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Update user account information (username and email)
     */
    public function updateAccount(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
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

        // Check if username exists in other tables
        $username = $request->username;

        $existsInAdmin = Admin::where('username', $username)
            ->exists();

        $existsInFasilitas = PetugasFasilitas::where('username', $username)
            ->exists();

        $existsInPembayaran = PetugasPembayaran::where('username', $username)
            ->exists();

        if ($existsInAdmin || $existsInFasilitas || $existsInPembayaran) {
            return back()->withErrors(['username' => 'Username sudah digunakan oleh petugas atau admin.'])->withInput();
        }

        try {
            // Update account info
            $user->update($validated);

            return redirect()->route('user.profile')->with('success', 'Informasi akun berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
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

        // Check current password
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Password saat ini tidak sesuai.']);
        }

        try {
            // Update password
            $user->password = Hash::make($request->password);
            $user->save();

            return redirect()->route('user.profile')->with('success', 'Password berhasil diubah.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
