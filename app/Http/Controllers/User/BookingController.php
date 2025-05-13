<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Checkout;
use App\Models\Fasilitas;
use App\Models\Jadwal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    /**
     * Display futsal facilities
     */
    public function futsal()
    {
        $fasilitas = Fasilitas::where('tipe', 'futsal')
                             ->where('ketersediaan', 'aktif')
                             ->whereNull('deleted_at')
                             ->get();

        return view('User.Booking.futsal', compact('fasilitas'));
    }

    /**
     * Display tennis facilities
     */
    public function tenis()
    {
        $fasilitas = Fasilitas::where('tipe', 'tenis')
                             ->where('ketersediaan', 'aktif')
                             ->whereNull('deleted_at')
                             ->get();

        return view('User.Booking.tenis', compact('fasilitas'));
    }

    /**
     * Display volleyball facilities
     */
    public function voli()
    {
        $fasilitas = Fasilitas::where('tipe', 'voli')
                             ->where('ketersediaan', 'aktif')
                             ->whereNull('deleted_at')
                             ->get();

        return view('User.Booking.voli', compact('fasilitas'));
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

        return view('User.riwayat', compact('riwayat'));
    }
}
