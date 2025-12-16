<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Checkout;
use App\Models\Fasilitas;
use App\Models\Jadwal;
use App\Models\Pemasukan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BookingController extends Controller
{
    /**
     * Display dinamis facilities
     */
    public function show($id)
    {
        // Ambil data fasilitas beserta jadwalnya
        $fasilitas = Fasilitas::with(['jadwals' => function($q) {
            $q->where('tanggal', '>=', date('Y-m-d'))
              ->where('status', 'tersedia');
        }])->findOrFail($id);

        // Return ke view detail fasilitas
        return view('User.Fasilitas.detail', compact('fasilitas'));
    }


}
