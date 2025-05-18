<?php

namespace App\Http\Controllers\PetugasFasilitas;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Checkout;
use App\Models\Jadwal;
use Carbon\Carbon;

class AktivitasController extends Controller
{
    /**
     * Menampilkan log aktivitas
     */
    public function index(Request $request)
    {
        // Filter berdasarkan tanggal
        $startDate = $request->input('start_date', Carbon::today()->subDays(30)->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::today()->format('Y-m-d'));
        $type = $request->input('type', 'all');

        // Base query - menggunakan checkout sebagai log aktivitas
        $query = Checkout::with(['jadwals.fasilitas', 'user'])
                        ->orderBy('created_at', 'desc');

        // Apply date filters
        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [$startDate.' 00:00:00', $endDate.' 23:59:59']);
        }

        // Apply type filter
        if ($type !== 'all') {
            $query->where('status', $type);
        }

        // Get activities
        $activities = $query->paginate(20);

        // Format activities for view
        $formattedActivities = $activities->map(function ($checkout) {
            return [
                'id' => $checkout->id,
                'created_at' => $checkout->created_at,
                'user' => $checkout->user,
                'fasilitas' => $checkout->jadwals->first()->fasilitas ?? null,
                'description' => $this->getActivityDescription($checkout),
                'status' => $this->getActivityStatus($checkout),
                'status_color' => $this->getActivityStatusColor($checkout)
            ];
        });

        return view('PetugasFasilitas.Aktivitas.index', [
            'activities' => $activities,
            'formattedActivities' => $formattedActivities,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'type' => $type
        ]);
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
            case 'selesai':
                return 'Menyelesaikan penggunaan fasilitas';
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
            case 'selesai':
                return 'Selesai';
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
            case 'selesai':
                return 'info';
            default:
                return 'secondary';
        }
    }
}
