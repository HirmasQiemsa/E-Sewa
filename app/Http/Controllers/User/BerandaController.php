<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Checkout;
use App\Models\Jadwal;
use App\Models\Fasilitas;
use App\Models\PengajuanEvent;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class BerandaController extends Controller
{
    public function beranda(Request $request)
    {
        /* =====================================================
         * 1. SETUP WAKTU (GLOBAL – SAMA DENGAN GUEST)
         * ===================================================== */
        $now          = Carbon::now();
        $today        = $now->format('Y-m-d');
        $currentTime  = $now->format('H:i:s');

        $selectedDate = Carbon::parse(
            $request->query('date', $today)
        )->format('Y-m-d');

        /* =====================================================
         * 2. LOGIKA HITUNG SLOT (PURE – TANPA USER)
         * ===================================================== */
        $fasilitas = Fasilitas::where('ketersediaan', 'aktif')
            ->withCount(['jadwals' => function ($query) use (
                $selectedDate,
                $today,
                $currentTime
            ) {
                $query->whereDate('tanggal', $selectedDate)
                      ->where('status', 'tersedia')
                      ->when($selectedDate === $today, function ($q) use ($currentTime) {
                          // SLOT YANG JAM-NYA SUDAH LEWAT TIDAK DIHITUNG
                          $q->where('jam_mulai', '>', $currentTime);
                      });
            }])
            ->with(['jadwals' => function ($query) use (
                $selectedDate,
                $today,
                $currentTime
            ) {
                $query->whereDate('tanggal', $selectedDate)
                      ->where('status', 'tersedia')
                      ->when($selectedDate === $today, function ($q) use ($currentTime) {
                          $q->where('jam_mulai', '>', $currentTime);
                      })
                      ->orderBy('jam_mulai');
            }])
            ->get()
            ->map(function ($f) use ($selectedDate, $today) {

                /* ============================
                 * SLOT RESULT
                 * ============================ */
                $f->sisa_slot = $f->jadwals_count;

                /* ============================
                 * STATUS UI (SAMA DENGAN GUEST)
                 * ============================ */
                if ($selectedDate < $today) {
                    $f->status_type = 'locked';
                } elseif ($f->sisa_slot > 0) {
                    $f->status_type = 'available';
                } else {
                    $f->status_type = 'full';
                }

                /* ============================
                 * UI METADATA
                 * ============================ */
                switch ($f->status_type) {
                    case 'available':
                        $f->box_class   = 'border-success';
                        $f->status_text = 'Buka';
                        $f->status_icon = 'fa-check-circle';
                        break;

                    case 'full':
                        $f->box_class   = 'border-danger';
                        $f->status_text = 'Habis';
                        $f->status_icon = 'fa-times-circle';
                        break;

                    default:
                        $f->box_class   = 'border-secondary';
                        $f->status_text = 'Tutup';
                        $f->status_icon = 'fa-lock';
                        break;
                }

                return $f;
            });

        /* =====================================================
        * 3. LOGIKA KHUSUS USER LOGIN (OPTIMIZED)
        * ===================================================== */
        $userSpecificData = [
            'pendingBooking'  => null,
            'userHistoryData' => collect([]), // Pakai collection kosong agar safe di view
            'lastPengajuan'   => null,
            'activeTab'       => $request->query('tab', 'booking'), // Default ke tab booking
        ];

        if (Auth::check()) {
            $userId = Auth::id();

            // 1. Booking yang belum selesai (Peringatan Kompensasi)
            $userSpecificData['pendingBooking'] = Checkout::with(['jadwals.fasilitas'])
                ->where('user_id', $userId)
                ->where('status', 'kompensasi')
                ->latest()
                ->first();

            // 2. Pengajuan event terakhir (Untuk status banner)
            $userSpecificData['lastPengajuan'] = PengajuanEvent::where('id_user', $userId)
                ->latest()
                ->first();

            // 3. Logika Filter Riwayat Berdasarkan Tab
            if ($userSpecificData['activeTab'] === 'event') {
                // AMBIL RIWAYAT EVENT
                $userSpecificData['userHistoryData'] = PengajuanEvent::where('id_user', $userId)
                    ->whereIn('status', ['disetujui', 'selesai', 'ditolak']) // Sesuaikan status event-mu
                    ->latest()
                    ->get()
                    ->groupBy(function($item) {
                        return $item->tanggal_event->format('Y-m-d'); // Pastikan tgl_event di-cast ke Carbon
                    });
            } else {
                // AMBIL RIWAYAT BOOKING REGULER
                $userSpecificData['userHistoryData'] = Jadwal::whereHas('checkouts', function ($q) use ($userId) {
                        $q->where('user_id', $userId)
                        ->whereIn('status', ['lunas', 'selesai']);
                    })
                    ->with('fasilitas')
                    ->latest('tanggal')
                    ->get()
                    ->groupBy('tanggal');
            }
        }

        /* =====================================================
         * 4. RETURN VIEW
         * ===================================================== */
        return view('welcome', array_merge([
            'selectedDate' => $selectedDate,
            'fasilitas'    => $fasilitas,
        ], $userSpecificData));
    }
}
