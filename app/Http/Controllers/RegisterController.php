<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class RegisterController extends Controller
{
    //Proses POS
    public function register_proses(Request $request)
    {
        $request ->validate([
            'name'=>'required',
            'alamat'=>'required',
            'no_ktp'=>'required|numeric|min_digits:16|unique:users',
            'no_hp'=>'required|numeric|unique:users',
            'email'=>'required',
            'password'=>'required',
        ]);

        $data['name'] = $request->name;
        $data['alamat'] = $request->alamat;
        $data['no_ktp'] = $request->no_ktp;
        $data['no_hp'] = $request->no_hp;
        $data['email'] = $request->email;
        $data['password'] = $request->password;

        User::create($data);

        return redirect()->route('login')->with('success', 'User baru telah berhasil terdaftar');

    }
}
