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
use App\Models\Admin;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::guard('admin')->user();
        $data = [];

        switch ($user->role) {
            case 'super_admin':
                // 1. Statistik Utama
                $data['total_pendapatan'] = Pemasukan::where('status', 'lunas')
                    ->whereYear('created_at', date('Y'))  // Filter Tahun Ini
                    ->sum('jumlah_bayar');
                $data['total_user'] = User::count();
                $data['total_staff'] = Admin::where('role', '!=', 'super_admin')->count();

                /// 2. [MODIFIKASI] Ganti "Pending Approval" dengan "Total Fasilitas Aktif"
                // Agar dashboard tetap penuh tapi isinya relevan
                // $data['pending_approvals'] = Fasilitas::where('status_approval', 'pending')->latest()->take(5)->get(); // HIDDEN
                // $data['pending_count'] = Fasilitas::where('status_approval', 'pending')->count(); // HIDDEN

                $data['fasilitas_count'] = Fasilitas::count();
                $data['fasilitas_aktif'] = Fasilitas::where('ketersediaan', 'aktif')->count();

                // 3. Log Aktivitas Terbaru (Audit Trail)
                // Pastikan model ActivityLog sudah sesuai relasinya
                $data['recent_logs'] = ActivityLog::with('admin')->latest()->take(7)->get();

                // 4. Data Grafik (Contoh: Pendapatan per Bulan tahun ini)
                // Ini logic sederhana untuk chart
                $pendapatanBulanan = Pemasukan::selectRaw('MONTH(created_at) as bulan, SUM(jumlah_bayar) as total')
                    ->where('status', 'lunas')
                    ->whereYear('created_at', date('Y'))
                    ->groupBy('bulan')
                    ->pluck('total', 'bulan')->toArray();

                $data['chart_values'] = array_values(array_replace(array_fill(1, 12, 0), $pendapatanBulanan));

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
                $adminId = Auth::id();

                // 0. DAPATKAN ID FASILITAS YANG DIPEGANG ADMIN INI
                $myFasilitasIds = Fasilitas::where('admin_pembayaran_id', $adminId)->pluck('id');

                // 0. QUERY BASE CHECKOUT (Filter Checkout berdasarkan Fasilitas Admin)
                // Kita buat base query biar tidak nulis ulang whereHas berkali-kali
                $checkoutQuery = Checkout::whereHas('jadwals.fasilitas', function($q) use ($adminId) {
                    $q->where('admin_pembayaran_id', $adminId);
                });

                // 1. Total Pemasukan (Filter by Fasilitas ID di tabel Pemasukan)
                $data['total_pemasukan'] = Pemasukan::whereIn('fasilitas_id', $myFasilitasIds)
                                            ->whereIn('status', ['lunas'])
                                            ->sum('jumlah_bayar');

                // 2. Transaksi Hari Ini (Filter by Fasilitas ID)
                $data['transaksi_hari_ini'] = Pemasukan::whereIn('fasilitas_id', $myFasilitasIds)
                                            ->whereDate('created_at', now())
                                            ->whereIn('status', ['lunas', 'pending'])
                                            ->count();

                // 3. Menunggu Verifikasi (Filter by Checkout milik Admin)
                // Gunakan 'clone' agar query base tidak berubah
                $data['menunggu_verifikasi'] = (clone $checkoutQuery)->where('status', 'pending')->count();

                // 4. Hitung Rata-rata Nilai Booking
                $data['rata_rata_booking'] = (clone $checkoutQuery)
                                            ->whereNotIn('status', ['batal', 'ditolak'])
                                            ->avg('total_bayar');

                // 5. Data Grafik Donut (Status Pembayaran - Filtered)
                $data['status_counts'] = [
                    'lunas'      => (clone $checkoutQuery)->where('status', 'lunas')->count(),
                    'kompensasi' => (clone $checkoutQuery)->where('status', 'kompensasi')->count(),
                    'pending'    => (clone $checkoutQuery)->where('status', 'pending')->count(),
                    'batal'      => (clone $checkoutQuery)->where('status', 'batal')->count(),
                ];

                // 6. List Transaksi Terbaru (Filtered)
                $data['recent_transactions'] = (clone $checkoutQuery)
                                            ->with([
                                                'user',
                                                'jadwals.fasilitas',
                                                'pemasukans'
                                            ])
                                            ->latest()
                                            ->take(5)
                                            ->get();


            return view('Admin.Pembayaran.dashboard', compact('data'));

        default:
            abort(403);
        }
    }
}
