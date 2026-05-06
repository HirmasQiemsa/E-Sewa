<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Fasilitas;
use Carbon\Carbon;

class BerandaController extends Controller
{
    public function index(Request $request)
    {
        /* ======================================
         * SETUP WAKTU
         * ====================================== */
        $now          = Carbon::now();
        $today        = $now->format('Y-m-d');
        $currentTime  = $now->format('H:i:s');

        $selectedDate = Carbon::parse(
            $request->query('date', $today)
        )->format('Y-m-d');

        /* ======================================
         * AMBIL FASILITAS + HITUNG SLOT VALID
         * ====================================== */
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
            ->get()
            ->map(function ($f) use ($selectedDate, $today) {

                /* ============================
                 * LOGIKA SLOT
                 * ============================ */
                $f->sisa_slot = $f->jadwals_count;

                /* ============================
                 * LOGIKA STATUS
                 * ============================ */
                if ($selectedDate < $today) {
                    $f->status_type = 'locked';
                } elseif ($f->sisa_slot > 0) {
                    $f->status_type = 'available';
                } else {
                    $f->status_type = 'full';
                }

                /* ============================
                 * METADATA UI
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

        return view('welcome', compact(
            'selectedDate',
            'fasilitas'
        ));
    }
}
