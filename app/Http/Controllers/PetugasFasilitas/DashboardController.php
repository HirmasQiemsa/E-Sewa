<?php

namespace App\Http\Controllers\PetugasFasilitas;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Fasilitas;
use App\Models\Jadwal;
use App\Models\User;
use App\Models\Checkout;
use App\Models\PetugasFasilitas;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Menampilkan dashboard petugas fasilitas
     */
    public function index()
    {
        // Ambil data tanggal hari ini
        $today = Carbon::today()->format('Y-m-d');

        // Hitung total fasilitas
        $totalFasilitas = Fasilitas::count();

        // Hitung fasilitas berdasarkan status
        $fasilitasAktif = Fasilitas::where('ketersediaan', 'aktif')->count();
        $fasilitasNonaktif = Fasilitas::where('ketersediaan', 'nonaktif')->count();
        $fasilitasMaintenance = Fasilitas::where('ketersediaan', 'maintanace')->count(); // Note: typo in DB schema

        // Hitung booking hari ini
        $bookingHariIni = Jadwal::where('tanggal', $today)
                              ->where('status', 'terbooking')
                              ->count();

        // Hitung total user
        $totalUser = User::count();

        // Ambil jadwal hari ini dengan relasi
        $jadwalsToday = Jadwal::with(['fasilitas', 'checkouts.user'])
                          ->where('tanggal', $today)
                          ->orderBy('jam_mulai', 'asc')
                          ->take(10) // Batasi 10 data saja untuk dashboard
                          ->get();

        // Tambahkan perhitungan durasi untuk setiap jadwal
        foreach ($jadwalsToday as $jadwal) {
            $mulaiParts = explode(':', $jadwal->jam_mulai);
            $selesaiParts = explode(':', $jadwal->jam_selesai);
            $mulaiJam = (int)$mulaiParts[0];
            $selesaiJam = (int)$selesaiParts[0];
            $jadwal->durasi = $selesaiJam - $mulaiJam;
        }

        // Ambil aktivitas terbaru (kita buat query untuk aktivitas booking)
        $recentActivities = $this->getRecentActivities();

        // Kembalikan data ke view
        return view('PetugasFasilitas.dashboard', compact(
            'totalFasilitas',
            'fasilitasAktif',
            'fasilitasNonaktif',
            'fasilitasMaintenance',
            'bookingHariIni',
            'totalUser',
            'jadwalsToday',
            'recentActivities'
        ));
    }

    /**
     * Mendapatkan data aktivitas terbaru
     */
    private function getRecentActivities()
    {
        // Karena tidak ada tabel activities, kita gunakan data checkout sebagai representasi aktivitas
        $checkouts = Checkout::with(['jadwals.fasilitas', 'user'])
                    ->orderBy('created_at', 'desc')
                    ->take(8)
                    ->get();

        // Format data untuk tampilan di UI
        $activities = $checkouts->map(function ($checkout) {
            // Ambil fasilitas dari jadwal pertama terkait checkout ini
            $jadwal = $checkout->jadwals->first();
            $fasilitas = $jadwal ? $jadwal->fasilitas : null;

            return (object)[
                'id' => $checkout->id,
                'created_at' => $checkout->created_at,
                'user' => $checkout->user,
                'fasilitas' => $fasilitas,
                'description' => $this->getActivityDescription($checkout),
                'status' => $this->getActivityStatus($checkout),
                'status_color' => $this->getActivityStatusColor($checkout)
            ];
        });

        return $activities;
    }

    /**
     * Mendapatkan deskripsi aktivitas berdasarkan status checkout
     */
    private function getActivityDescription($checkout)
    {
        switch ($checkout->status) {
            case 'fee':
                return 'Melakukan booking dan membayar DP';
            case 'lunas':
                return 'Melunasi pembayaran booking';
            case 'batal':
                return 'Membatalkan booking';
            default:
                return 'Melakukan aktivitas pada sistem';
        }
    }

    /**
     * Mendapatkan label status aktivitas
     */
    private function getActivityStatus($checkout)
    {
        switch ($checkout->status) {
            case 'fee':
                return 'DP Terbayar';
            case 'lunas':
                return 'Lunas';
            case 'batal':
                return 'Dibatalkan';
            default:
                return 'Undefined';
        }
    }

    /**
     * Mendapatkan warna untuk label status aktivitas
     */
    private function getActivityStatusColor($checkout)
    {
        switch ($checkout->status) {
            case 'fee':
                return 'warning';
            case 'lunas':
                return 'success';
            case 'batal':
                return 'danger';
            default:
                return 'secondary';
        }
    }
}
