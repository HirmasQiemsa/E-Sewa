<?php

namespace App\Http\Controllers\PetugasPembayaran;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PemasukanSewa;
use App\Models\Checkout;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Menampilkan dashboard petugas pembayaran
     */
    public function index()
    {
        // Data tanggal hari ini
        $today = Carbon::today()->format('Y-m-d');

        // Total pemasukan (sum dari jumlah_bayar di tabel pemasukan_sewas)
        $totalPemasukan = PemasukanSewa::sum('jumlah_bayar');

        // Transaksi hari ini
        $transaksiHariIni = PemasukanSewa::whereDate('created_at', $today)->count();

        // Menunggu verifikasi (status pending)
        $menungguVerifikasi = PemasukanSewa::where('status', 'pending')->count();

        // Total booking aktif
        $totalBooking = Checkout::whereIn('status', ['fee', 'lunas'])->count();

        // Status pembayaran untuk chart
        $pembayaranLunas = PemasukanSewa::where('status', 'lunas')->count();
        $pembayaranDP = PemasukanSewa::where('status', 'fee')->count();
        $pembayaranPending = PemasukanSewa::where('status', 'pending')->count();

        // Transaksi hari ini dengan relasi
        $transaksiToday = PemasukanSewa::with(['checkout.user', 'fasilitas'])
                          ->whereDate('created_at', $today)
                          ->orderBy('created_at', 'desc')
                          ->take(5)
                          ->get();

        // Aktivitas pembayaran terbaru
        $recentPayments = PemasukanSewa::with(['checkout.user', 'fasilitas'])
                         ->orderBy('created_at', 'desc')
                         ->take(10)
                         ->get();

        // Kembalikan data ke view
        return view('PetugasPembayaran.dashboard', compact(
            'totalPemasukan',
            'transaksiHariIni',
            'menungguVerifikasi',
            'totalBooking',
            'pembayaranLunas',
            'pembayaranDP',
            'pembayaranPending',
            'transaksiToday',
            'recentPayments'
        ));
    }
}
