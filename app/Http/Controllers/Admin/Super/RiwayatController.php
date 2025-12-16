<?php

namespace App\Http\Controllers\Admin\Super;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RiwayatController extends Controller
{
    public function index()
    {
        return view('Admin.Riwayat.index');
    }
}
