<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Checkout;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RiwayatController extends Controller
{
    /**
     * Display  history with pagination
     */
    public function riwayat(Request $request)
    {
        $query = Checkout::with(['jadwal.fasilitas', 'user'])
            ->where('user_id', Auth::id())
            ->latest();

        // --- 1. FILTER PENCARIAN (Mencakup ID, Nama Fasilitas, Lokasi) ---
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('id', 'like', "%$search%") // Cari ID Booking
                  ->orWhereHas('jadwal.fasilitas', function($f) use ($search) {
                      $f->where('nama_fasilitas', 'like', "%$search%") // Cari Nama Fasilitas
                        ->orWhere('lokasi', 'like', "%$search%"); // Cari Lokasi
                  });
            });
        }

        // --- 2. FILTER STATUS ---
        if ($request->filled('status') && $request->status != 'all') {
            $query->where('status', $request->status);
        }

        // --- 3. FILTER BULAN ---
        if ($request->filled('month')) {
            $date = Carbon::parse($request->month);
            $query->whereYear('created_at', $date->year)
                  ->whereMonth('created_at', $date->month);
        }

        // Data Tabel (Paginate)
        $checkouts = $query->paginate(10)->withQueryString();

        // --- 4. LOGIKA WARNA KALENDER (SEMUA DATA TANPA PAGINASI) ---
        // Kita ambil semua data user untuk mewarnai kalender
        $allBookings = Checkout::with('jadwal')
            ->where('user_id', Auth::id())
            ->whereHas('jadwal') // Pastikan jadwal tidak null
            ->get();

        $calendarData = [];

        foreach ($allBookings as $b) {
            $date = Carbon::parse($b->jadwal->tanggal)->format('Y-m-d');
            $status = $b->status;

            // Inisialisasi jika tanggal belum ada
            if (!isset($calendarData[$date])) {
                $calendarData[$date] = 'lunas'; // Default paling aman (hijau)
            }

            // LOGIKA PRIORITAS WARNA: Fee (Merah) > Pending (Biru) > Lunas (Hijau)
            // Jika tanggal ini sudah ditandai 'kompensasi', jangan ubah lagi (karena fee prioritas tertinggi warning)
            if ($calendarData[$date] == 'kompensasi') {
                continue;
            }

            // Jika booking ini 'kompensasi', paksa tanggal ini jadi 'kompensasi' (Warning)
            if ($status == 'kompensasi') {
                $calendarData[$date] = 'kompensasi';
            }
            // Jika booking ini 'pending' dan status tgl ini belum 'kompensasi', ubah jadi 'pending' (Info)
            elseif ($status == 'pending' && $calendarData[$date] != 'kompensasi') {
                $calendarData[$date] = 'pending';
            }
            // Sisanya 'lunas' atau 'batal', biarkan (atau handle sesuai kebutuhan)
        }

        return view('User.Riwayat.index', compact('checkouts', 'calendarData'));
    }


    /**
     * Generate and download PDF receipt for a booking
     */
    public function downloadReceipt($id)
    {
        $checkout = Checkout::with(['jadwals', 'jadwal.fasilitas', 'pembayaran', 'user'])
                          ->where('id', $id)
                          ->where('user_id', Auth::id())
                          ->firstOrFail();

        // Check if checkout is paid
        if ($checkout->status != 'lunas') {
            return redirect()->back()->with('error', 'Hanya pemesanan dengan status lunas yang dapat diunduh bukti pembayarannya.');
        }

        // Get payment details
        $payments = $checkout->pembayaran()->orderBy('tanggal_bayar', 'asc')->get();
        $lastPayment = $payments->last();

        // Calculate duration
        $totalDurasi = 0;
        foreach ($checkout->jadwals as $jadwal) {
            $mulaiParts = explode(':', $jadwal->jam_mulai);
            $selesaiParts = explode(':', $jadwal->jam_selesai);

            $mulaiJam = (int)$mulaiParts[0];
            $selesaiJam = (int)$selesaiParts[0];

            $totalDurasi += ($selesaiJam - $mulaiJam);
        }

        // Generate PDF using view
        $data = [
            'checkout' => $checkout,
            'payments' => $payments,
            'lastPayment' => $lastPayment,
            'totalDurasi' => $totalDurasi,
            'generatedDate' => Carbon::now()->format('d F Y H:i')
        ];

        return view('User.Checkout.receipt', $data);
    }
}
