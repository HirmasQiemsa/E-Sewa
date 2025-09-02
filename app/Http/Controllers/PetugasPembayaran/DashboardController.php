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

        // Total pemasukan dari transaksi lunas
        $totalPemasukan = PemasukanSewa::where('status', 'lunas')->sum('jumlah_bayar');

        // Transaksi hari ini
        $transaksiHariIni = PemasukanSewa::whereDate('created_at', $today)->count();

        // PERBAIKAN PENTING: Hitung menunggu verifikasi dengan benar
        // Ini adalah jumlah checkout dengan status fee yang punya pembayaran pending
        $menungguVerifikasi = Checkout::where('status', 'fee')
            ->whereHas('pembayaran', function($query) {
                $query->where('status', 'pending');
            })
            ->count();

        // Total booking aktif (fee atau lunas)
        $totalBooking = Checkout::whereIn('status', ['fee', 'lunas'])->count();

        // Status pembayaran untuk chart dengan tambahan batal
        $pembayaranLunas = PemasukanSewa::where('status', 'lunas')->count();
        $pembayaranDP = Checkout::where('status', 'fee')
            ->whereDoesntHave('pembayaran', function($query) {
                $query->where('status', 'pending');
            })
            ->count();
        $pembayaranPending = $menungguVerifikasi; // Menggunakan hasil perhitungan yang sudah diperbaiki
        $pembayaranBatal = Checkout::where('status', 'batal')->count(); // DITAMBAHKAN

        // Transaksi hari ini - tetap ditampilkan tapi tanpa aksi
        $transaksiToday = PemasukanSewa::with(['checkout.user', 'fasilitas'])
                        ->whereDate('created_at', $today)
                        ->orderBy('created_at', 'desc')
                        ->take(5)
                        ->get();

        // Pembayaran menunggu verifikasi
        $recentPayments = PemasukanSewa::with(['checkout.user', 'fasilitas'])
                        ->where('status', 'pending')
                        ->orderBy('created_at', 'desc')
                        ->take(5)
                        ->get();

        return view('PetugasPembayaran.dashboard', compact(
            'totalPemasukan',
            'transaksiHariIni',
            'menungguVerifikasi',
            'totalBooking',
            'pembayaranLunas',
            'pembayaranDP',
            'pembayaranPending',
            'pembayaranBatal', // DITAMBAHKAN untuk diagram
            'transaksiToday',
            'recentPayments'
        ));
    }
}
