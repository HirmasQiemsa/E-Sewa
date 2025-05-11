<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
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
            // 'no_ktp'=>'nullable|numeric|min_digits:16|unique:users',
            'no_hp'=>'required|numeric|min_digits:10|unique:users',
            'email'=>'required|unique:users',
            'password'=>'required|string|min:3',
        ]);

        $data['name'] = $request->name;
        $data['username'] = $request->username;
        $data['alamat'] = $request->alamat;
        // $data['no_ktp'] = $request->no_ktp;
        $data['no_hp'] = $request->no_hp;
        $data['email'] = $request->email;
        $data['password'] = Hash::make($request->password);

        User::create($data);

        return redirect()->route('login')->with('success', 'User baru telah berhasil terdaftar');

    }
}
