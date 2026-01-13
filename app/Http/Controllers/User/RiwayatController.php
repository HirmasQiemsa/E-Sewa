<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Checkout;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PDF; // Pastikan library PDF sudah ada, jika belum bisa dikomen dulu

class RiwayatController extends Controller
{
    /**
     * Display history with pagination
     */
    public function index(Request $request)
    {
        // 1. QUERY UTAMA (Gunakan 'jadwals' jamak)
       $query = Checkout::with(['jadwals.fasilitas', 'user'])
        ->where('user_id', Auth::id());

    // 1. FILTER STATUS (Tetap sama)
    if ($request->filled('status') && $request->status != 'all') {
        $query->where('status', $request->status);
    }

    // 2. FILTER TANGGAL SPESIFIK (Upgrade dari Bulan ke Tanggal)
    // Input dari Flatpickr sekarang dikirim format: YYYY-MM-DD
    if ($request->filled('date')) {
        $query->whereHas('jadwals', function($q) use ($request) {
            $q->whereDate('tanggal', $request->date);
        });
    }

    // 3. PENCARIAN GLOBAL (Search Bar)
    if ($request->filled('search')) {
        $search = $request->search;

        $query->where(function($q) use ($search) {
            // A. Cari ID Booking (#15)
            $q->where('id', 'like', "%$search%")

            // B. Cari Total Biaya (40000)
              ->orWhere('total_bayar', 'like', "%$search%")

            // C. Cari Status (pending, lunas, dll)
              ->orWhere('status', 'like', "%$search%")

            // D. Cari Nama Fasilitas & Lokasi
              ->orWhereHas('jadwals.fasilitas', function($subQ) use ($search) {
                  $subQ->where('nama_fasilitas', 'like', "%$search%")
                       ->orWhere('lokasi', 'like', "%$search%");
              })

            // E. Cari Tanggal (Format: 2026-01-10)
            // Note: Mencari nama hari ("Rabu") sulit dilakukan di database query standar
            // jadi kita fokus pencarian tanggal angka saja.
              ->orWhereHas('jadwals', function($subQ) use ($search) {
                  $subQ->where('tanggal', 'like', "%$search%");
              });
        });
    }

    $checkouts = $query->latest()->paginate(10);

        // --- 2. LOGIKA WARNA KALENDER (FIXED) ---
        // Kita ambil data jadwal untuk mewarnai kalender
        $allBookings = Checkout::with('jadwals') // Gunakan 'jadwals'
            ->where('user_id', Auth::id())
            ->whereHas('jadwals') // Gunakan 'jadwals'
            ->get();

        $calendarData = [];

        foreach ($allBookings as $checkout) {
            // KARENA RELASI MANY-TO-MANY, KITA HARUS LOOPING JADWALNYA
            foreach ($checkout->jadwals as $jadwal) {
                $date = Carbon::parse($jadwal->tanggal)->format('Y-m-d');
                $status = $checkout->status;

                // Inisialisasi jika tanggal belum ada
                if (!isset($calendarData[$date])) {
                    $calendarData[$date] = 'lunas'; // Default hijau
                }

                // PRIORITAS WARNA: Kompensasi (Kuning) > Pending (Biru) > Lunas (Hijau)

                // Jika tanggal ini sudah 'kompensasi' (kuning), jangan ditimpa lagi (prioritas tertinggi)
                if ($calendarData[$date] == 'kompensasi') {
                    continue;
                }

                // Jika checkout ini statusnya 'kompensasi', ubah tanggal jadi kuning
                if ($status == 'kompensasi') {
                    $calendarData[$date] = 'kompensasi';
                }
                // Jika checkout ini 'pending' dan tanggal belum kuning, ubah jadi biru
                elseif ($status == 'pending' && $calendarData[$date] != 'kompensasi') {
                    $calendarData[$date] = 'pending';
                }
            }
        }

        return view('User.Riwayat.index', compact('checkouts', 'calendarData'));
    }

    /**
     * Print PDF
     */
    public function print($id)
    {
        // Gunakan 'jadwals'
        $checkout = Checkout::with(['jadwals.fasilitas', 'user'])
            ->where('user_id', Auth::id())
            ->where('status', 'lunas')
            ->findOrFail($id);

        return view('User.Riwayat.cetak_tiket', compact('checkout'));
    }
}
