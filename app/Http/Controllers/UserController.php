<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\User;

class UserController extends Controller
{
    //View Blade
    public function beranda()
    {
        return view('user.beranda');
    }
    public function futsal()
    {
        return view('user.futsal');
    }
    public function voli()
    {
        return view('user.voli');
    }
    public function tenis()
    {
        return view('user.tenis');
    }
    public function checkout()
    {
        return view('user.checkout');
    }

}
