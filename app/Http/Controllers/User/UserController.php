<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    // View Blade
    public function beranda()
    {
        return view('User.Fasilitas.index');
    }
    public function edit()
    {
        $data = User::get();
        return view('User.Profile.edit',compact('data'));
    }
}
