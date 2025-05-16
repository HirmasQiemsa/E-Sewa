<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Checkout;
use App\Models\Fasilitas;
use App\Models\Jadwal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BookingController extends Controller
{

    /**
     * Display futsal facilities
     */
    public function futsal()
    {
        $fasilitas = Fasilitas::whereRaw('LOWER(nama_fasilitas) = ?', [strtolower('Lapangan Futsal')])
                      ->where('ketersediaan', 'aktif')
                      ->whereNull('deleted_at')
                      ->get();

        return view('User.Fasilitas.futsal', compact('fasilitas'));
    }

    /**
     * Display tennis facilities
     */
    public function tenis()
    {
        $fasilitas = Fasilitas::whereRaw('LOWER(nama_fasilitas) = ?', [strtolower('tenis')])
                      ->where('ketersediaan', 'aktif')
                      ->whereNull('deleted_at')
                      ->get();

        return view('User.Fasilitas.tenis', compact('fasilitas'));
    }

    /**
     * Display volleyball facilities
     */
    public function voli()
    {
        $fasilitas = Fasilitas::whereRaw('LOWER(nama_fasilitas) = ?', [strtolower('voli')])
                      ->where('ketersediaan', 'aktif')
                      ->whereNull('deleted_at')
                      ->get();

        return view('User.Fasilitas.voli', compact('fasilitas'));
    }

    /**
     * Display booking history
     */
    public function riwayat()
    {
        $riwayat = Checkout::with(['jadwal.fasilitas', 'user'])
                          ->where('user_id', Auth::id())
                          ->latest()
                          ->get();

        // Get active facilities
        $fasilitas = Fasilitas::where('ketersediaan', 'aktif')
                             ->whereNull('deleted_at')
                             ->get();

        // Get user's checkouts
        $checkouts = Checkout::with(['jadwal.fasilitas'])
                           ->where('user_id', Auth::id())
                           ->whereIn('status', ['fee', 'lunas']) // Only show active bookings
                           ->latest()
                           ->get();

        // Calculate duration for each booking
        foreach ($riwayat as $item) {
            // Get all jadwals for this checkout
            $jadwals = Jadwal::where('checkout_id', $item->id)->get();

            // Calculate total duration
            $totalDurasi = 0;
            foreach ($jadwals as $jadwal) {
                $mulaiParts = explode(':', $jadwal->jam_mulai);
                $selesaiParts = explode(':', $jadwal->jam_selesai);

                $mulaiJam = (int)$mulaiParts[0];
                $selesaiJam = (int)$selesaiParts[0];

                $totalDurasi += ($selesaiJam - $mulaiJam);
            }

            $item->totalDurasi = $totalDurasi;
        }

        return view('User.Checkout.riwayat', compact('riwayat','fasilitas','checkouts'));
    }
}
