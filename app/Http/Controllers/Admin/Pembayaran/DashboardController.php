<?php

namespace App\Http\Controllers\Admin\Pembayaran;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pemasukan;
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
        $totalPemasukan = Pemasukan::where('status', 'lunas')->sum('jumlah_bayar');

        // Transaksi hari ini
        $transaksiHariIni = Pemasukan::whereDate('created_at', $today)->count();

        // PERBAIKAN PENTING: Hitung menunggu verifikasi dengan benar
        // Ini adalah jumlah checkout dengan status fee yang punya pembayaran pending
        $menungguVerifikasi = Checkout::where('status', 'kompensasi')
            ->whereHas('pembayaran', function($query) {
                $query->where('status', 'pending');
            })
            ->count();

        // Total booking aktif (fee atau lunas)
        $totalBooking = Checkout::whereIn('status', ['kompensasi', 'lunas'])->count();

        // Status pembayaran untuk chart dengan tambahan batal
        $pembayaranLunas = Pemasukan::where('status', 'lunas')->count();
        $pembayaranDP = Checkout::where('status', 'kompensasi')
            ->whereDoesntHave('pembayaran', function($query) {
                $query->where('status', 'pending');
            })
            ->count();
        $pembayaranPending = $menungguVerifikasi; // Menggunakan hasil perhitungan yang sudah diperbaiki
        $pembayaranBatal = Checkout::where('status', 'batal')->count(); // DITAMBAHKAN

        // Transaksi hari ini - tetap ditampilkan tapi tanpa aksi
        $transaksiToday = Pemasukan::with(['checkout.user', 'fasilitas'])
                        ->whereDate('created_at', $today)
                        ->orderBy('created_at', 'desc')
                        ->take(5)
                        ->get();

        // Pembayaran menunggu verifikasi
        $recentPayments = Pemasukan::with(['checkout.user', 'fasilitas'])
                        ->where('status', 'pending')
                        ->orderBy('created_at', 'desc')
                        ->take(5)
                        ->get();

        return view('AdminPembayaran.dashboard', compact(
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
