<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Fasilitas;

class BerandaController extends Controller
{
    public function index()
    {
        $fasilitas = Fasilitas::all(); // ambil data dari database
        return view('beranda', compact('fasilitas'));
    }
}
