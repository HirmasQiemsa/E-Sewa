<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin;

class ProfileController extends Controller
{
    public function index()
    {
        $data = Admin::get();
        return view('Admin.Profile.edit',compact('data'));
    }
}
