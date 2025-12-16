<?php

namespace App\Http\Controllers;

use App\Models\Fasilitas;
use Illuminate\Http\Request;

class BerandaController extends Controller
{
    public function index()
    {
        // Tampilkan fasilitas di landing page (hanya yang aktif)
        $fasilitas = Fasilitas::where('ketersediaan', 'aktif')->get();

        return view('welcome', compact('fasilitas'));
    }
}
