<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class ProfileUserController extends Controller
{
    public function edit()
    {
        $data = User::get();
        return view('User.Profile.edit',compact('data'));
    }
}
