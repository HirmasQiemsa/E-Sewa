<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Admin;
use App\Models\PetugasFasilitas;
use App\Models\PetugasPembayaran;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    //Proses POS
    public function register_proses(Request $request)
{
    $request ->validate([
        'name'=>'required|string',
        'username' => 'required|alpha_num|unique:users',
        'alamat'=>'required|string',
        'no_hp'=>'required|numeric|min_digits:10|unique:users',
        'email'=>'nullable|email|unique:users', // UBAH: dari 'required' menjadi 'nullable'
        'password'=>'required|string|min:6',
    ]);

    // Cek apakah username sudah dipakai oleh role lain
    $username = $request->username;

    $existsInAdmin = Admin::where('username', $username)->exists();
    $existsInFasilitas = PetugasFasilitas::where('username', $username)->exists();
    $existsInPembayaran = PetugasPembayaran::where('username', $username)->exists();

    if ($existsInAdmin || $existsInFasilitas || $existsInPembayaran) {
        return back()->withErrors(['username' => 'Username sudah digunakan'])->withInput();
    }

    $data['name'] = $request->name;
    $data['username'] = $request->username;
    $data['alamat'] = $request->alamat;
    $data['no_hp'] = $request->no_hp;
    $data['email'] = $request->email; // Bisa null
    $data['password'] = Hash::make($request->password);

    User::create($data);

    return redirect()->route('login')->with('success', 'User baru telah berhasil terdaftar');
}
}
