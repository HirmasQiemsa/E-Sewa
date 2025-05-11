<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\Fasilitas;

class UserController extends Controller
{
    //View Blade
    public function beranda()
    {
        return view('user.beranda');
    }
    public function futsal()
    {
        $fasilitas = Fasilitas::where('nama_fasilitas', 'Lapangan Futsal')->get();
        return view('user.futsal',compact('fasilitas'));
    }
    public function voli()
    {
        return view('user.voli');
    }
    public function tenis()
    {
        return view('user.tenis');
    }
    

}
