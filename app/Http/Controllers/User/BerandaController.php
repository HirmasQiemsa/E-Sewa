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

        $selectedDate = $request->query('date', $today);

        /* =====================================================
         * 2. LOGIKA HITUNG SLOT (PURE – TANPA USER)
         * ===================================================== */
        $fasilitas = Fasilitas::where('ketersediaan', 'aktif')
            ->withCount(['jadwals' => function ($query) use ($selectedDate, $today, $currentTime) {
                $query->whereDate('tanggal', $selectedDate)
                      ->where('status', 'tersedia')
                      ->when($selectedDate === $today, function ($q) use ($currentTime) {
                          $q->where('jam_mulai', '>', $currentTime);
                      });
            }])
            ->with(['jadwals' => function ($query) use ($selectedDate, $today, $currentTime) {
                $query->whereDate('tanggal', $selectedDate)
                      ->where('status', 'tersedia')
                      ->when($selectedDate === $today, function ($q) use ($currentTime) {
                          $q->where('jam_mulai', '>', $currentTime);
                      })
                      ->orderBy('jam_mulai');
            }])
            ->get()
            ->map(function ($f) use ($selectedDate, $today) {
                return $this->formatFacilityUi($f, $selectedDate, $today);
            });

        /* =====================================================
        * 3. LOGIKA USER DATA (ALWAYS INITIALIZED)
        * ===================================================== */
        $viewData = [
            'selectedDate'    => $selectedDate,
            'fasilitas'       => $fasilitas,
            'pendingBooking'  => null,
            'userHistoryData' => collect([]),
            'lastPengajuan'   => null,
            'activeTab'       => $request->query('tab', 'booking'),
        ];

        if (Auth::check()) {
            $userId = Auth::id();
            $viewData['pendingBooking'] = Checkout::where('user_id', $userId)
                ->where('status', 'kompensasi')
                ->with('jadwals.fasilitas')
                ->latest()->first();

            $viewData['lastPengajuan'] = PengajuanEvent::where('id_user', $userId)
                ->latest()->first();

            $viewData['userHistoryData'] = $this->getUserHistory($userId, $viewData['activeTab']);
        }

        return view('welcome', $viewData);
    }

    private function formatFacilityUi($f, $selectedDate, $today)
    {
        $f->sisa_slot = $f->jadwals_count;

        if ($selectedDate < $today) {
            $f->status_type = 'locked';
        } elseif ($f->sisa_slot > 0) {
            $f->status_type = 'available';
        } else {
            $f->status_type = 'full';
        }

        $config = [
            'available' => ['border-success', 'Buka', 'fa-check-circle'],
            'full'      => ['border-danger', 'Habis', 'fa-times-circle'],
            'locked'    => ['border-secondary', 'Tutup', 'fa-lock']
        ];

        [$f->box_class, $f->status_text, $f->status_icon] = $config[$f->status_type] ?? $config['locked'];
        return $f;
    }

    private function getUserHistory($userId, $tab)
    {
        if ($tab === 'event') {
            return PengajuanEvent::where('id_user', $userId)
                ->whereIn('status', ['disetujui', 'selesai', 'ditolak'])
                ->latest()->get()->groupBy(fn($item) => $item->tgl_mulai->format('Y-m-d'));
        }

        return Jadwal::whereHas('checkouts', fn($q) => $q->where('user_id', $userId)->whereIn('status', ['lunas', 'selesai']))
            ->with('fasilitas')->latest('tanggal')->get()->groupBy('tanggal');
    }
}
