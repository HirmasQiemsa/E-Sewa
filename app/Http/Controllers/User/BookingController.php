<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Checkout;
use App\Models\Fasilitas;
use App\Models\Jadwal;
use App\Models\PemasukanSewa;
use Carbon\Carbon;
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
        $fasilitas = Fasilitas::whereRaw('LOWER(nama_fasilitas) LIKE ?', ['%lapangan futsal%'])
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
        $fasilitas = Fasilitas::whereRaw('LOWER(nama_fasilitas) LIKE ?', ['%tenis%'])
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
        $fasilitas = Fasilitas::whereRaw('LOWER(nama_fasilitas) LIKE ?', ['%voli%'])
                      ->where('ketersediaan', 'aktif')
                      ->whereNull('deleted_at')
                      ->get();

        return view('User.Fasilitas.voli', compact('fasilitas'));
    }

   /**
     * Display booking history with pagination
     */
    public function riwayat(Request $request)
    {
        // Base query with relationships
        $query = Checkout::with(['jadwals', 'jadwal.fasilitas', 'pembayaran'])
                       ->where('user_id', Auth::id())
                       ->orderBy('created_at', 'desc');

        // Apply server-side filtering if specified
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        if ($request->filled('month')) {
            $month = $request->month;
            $query->whereHas('jadwal', function($q) use ($month) {
                // Gunakan pendekatan yang kompatibel dengan berbagai database
                if (DB::connection()->getDriverName() === 'sqlite') {
                    // SQLite menggunakan strftime
                    $q->whereRaw("strftime('%Y-%m', tanggal) = ?", [$month]);
                } else {
                    // MySQL dan database lainnya menggunakan DATE_FORMAT
                    $q->whereRaw("DATE_FORMAT(tanggal, '%Y-%m') = ?", [$month]);
                }
            });
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('jadwal.fasilitas', function($q) use ($search) {
                $q->where('nama_fasilitas', 'LIKE', "%{$search}%")
                  ->orWhere('lokasi', 'LIKE', "%{$search}%");
            });
        }

        // Paginate results (10 items per page)
        $checkouts = $query->paginate(5)->withQueryString();

        // Calculate duration for each checkout
        foreach ($checkouts as $checkout) {
            // Get jadwals for this checkout
            $jadwals = $checkout->jadwals;

            // Calculate total duration
            $totalDurasi = 0;
            foreach ($jadwals as $jadwal) {
                $mulaiParts = explode(':', $jadwal->jam_mulai);
                $selesaiParts = explode(':', $jadwal->jam_selesai);

                $mulaiJam = (int)$mulaiParts[0];
                $selesaiJam = (int)$selesaiParts[0];

                $totalDurasi += ($selesaiJam - $mulaiJam);
            }

            $checkout->totalDurasi = $totalDurasi;
        }

        // Get active facilities for reference
        $fasilitas = Fasilitas::where('ketersediaan', 'aktif')
                             ->whereNull('deleted_at')
                             ->get();

        return view('User.Checkout.riwayat', compact('checkouts', 'fasilitas'));
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
