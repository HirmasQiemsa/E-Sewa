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
     * Halaman Detail Fasilitas
     */
    public function show(Request $request, $id)
    {
        // 1. Ambil Data Fasilitas
        // Gunakan findOrFail agar jika ID ngawur langsung 404
        $fasilitas = Fasilitas::findOrFail($id);

        // 2. [BARU] CEK STATUS KETERSEDIAAN (VALIDASI BACKEND)
        // Jika status bukan 'aktif', tendang user kembali ke beranda
        if ($fasilitas->ketersediaan !== 'aktif') {
            return redirect()->route('user.fasilitas')
                ->with('error', 'Maaf, fasilitas ini sedang tidak aktif atau dalam perbaikan.');
        }

        // 3. Ambil Jadwal (Hanya load jadwal jika lolos validasi di atas)
        // Load jadwal mulai hari ini ke depan
        $fasilitas->load(['jadwals' => function($q) {
            $q->where('tanggal', '>=', \Carbon\Carbon::today())
              ->where('status', 'tersedia')
              ->orderBy('tanggal')
              ->orderBy('jam_mulai');
        }]);

        // Cek apakah user request tanggal spesifik dari kalender?
        // Jika ada parameter ?date=2025-10-10, kita bisa filter tampilan di view nanti
        $selectedDate = $request->query('date', null);

        return view('User.Fasilitas.detail', compact('fasilitas', 'selectedDate'));
    }


}
