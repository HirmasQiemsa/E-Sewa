<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Fasilitas;
use App\Models\Checkout;
use App\Models\User;
use App\Models\Pemasukan;
use App\Models\Jadwal;
use App\Models\ActivityLog;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::guard('admin')->user();
        $data = [];

        switch ($user->role) {
            case 'super_admin':
                // Data Kepala Dinas
                $data['total_user'] = User::count();
                $data['total_pendapatan'] = Pemasukan::where('status', 'lunas')->sum('jumlah_bayar');
                // Tambahan: Log Aktivitas Terbaru (Limit 5)
                $data['recent_activities'] = ActivityLog::with('user')->latest()->take(5)->get();
                return view('Admin.Super.dashboard', compact('data'));

            case 'admin_fasilitas':
                // 1. Data Statistik Utama
                $data['total_fasilitas'] = Fasilitas::count();

                // Hitung jadwal yang terbooking hari ini (Sebagai representasi Booking Hari Ini)
                $data['booking_hari_ini'] = Jadwal::where('status', 'terbooking')
                                                ->whereDate('tanggal', now())
                                                ->count();

                // Fasilitas yang sedang dipakai/terbooking hari ini (Distinct)
                $data['fasilitas_tergunakan'] = Jadwal::where('status', 'terbooking')
                                                ->whereDate('tanggal', now())
                                                ->distinct('fasilitas_id')
                                                ->count('fasilitas_id');

                $data['total_user'] = User::count(); // Info tambahan (User Terdaftar)

                // 2. Data Grafik Status Fasilitas
                $data['fasilitas_aktif'] = Fasilitas::where('ketersediaan', 'aktif')->count();
                $data['fasilitas_nonaktif'] = Fasilitas::where('ketersediaan', 'nonaktif')->count();
                $data['fasilitas_maintenance'] = Fasilitas::where('ketersediaan', 'maintenance')->count();

                // 3. Data Tabel Preview (Jadwal Hari Ini)
                $data['jadwals_today'] = Jadwal::with(['fasilitas', 'checkouts.user']) // Eager load relasi
                                            ->whereDate('tanggal', now())
                                            ->orderBy('jam_mulai', 'asc')
                                            ->take(5)
                                            ->get();

                return view('Admin.Fasilitas.dashboard', compact('data'));

            case 'admin_pembayaran':
                // Data Staff Keuangan
                $data['total_pemasukan'] = Pemasukan::where('status', 'lunas')->sum('jumlah_bayar');
                $data['transaksi_hari_ini'] = Pemasukan::whereDate('created_at', now())->count();
                $data['menunggu_verifikasi'] = Checkout::where('status', 'pending')->count();

                // Data Grafik Donut (Status Pembayaran)
                $data['status_counts'] = [
                    'lunas' => Pemasukan::where('status', 'lunas')->count(),
                    'dp'    => Pemasukan::where('status', 'kompensasi')->count(), // fee/kompensasi
                    'pending' => Pemasukan::where('status', 'pending')->count(),
                    'batal' => Pemasukan::where('status', 'batal')->count(),
                ];

                // Tambahan: List Transaksi Terbaru (Untuk tabel preview)
                $data['recent_payments'] = Pemasukan::with(['user', 'fasilitas'])
                                                ->latest()
                                                ->take(5)->get();

                return view('Admin.Pembayaran.dashboard', compact('data'));

            default:
                abort(403);
        }
    }
}
