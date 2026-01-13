<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Checkout;
use App\Models\Jadwal;
use Carbon\Carbon;
use App\Models\Fasilitas;
use Illuminate\Support\Facades\Auth;


class BerandaController extends Controller
{
    // View Blade Beranda
    public function beranda(Request $request)
    {
        // 1. Logika Tanggal
        try {
            $reqDate = $request->query('date', Carbon::now()->format('Y-m-d'));
            $selectedDate = Carbon::parse($reqDate)->format('Y-m-d');
        } catch (\Exception $e) {
            $selectedDate = Carbon::now()->format('Y-m-d');
        }

        // 2. Cek Validasi Tanggal (Security)
        $today = Carbon::today()->format('Y-m-d');
        $canViewSchedule = ($selectedDate >= $today);

        if (!$canViewSchedule && Auth::check()) {
            // Cek history jika tanggal masa lalu
            $hasHistory = Jadwal::whereDate('tanggal', $selectedDate)
                ->whereHas('checkouts', function($q) {
                    $q->where('user_id', Auth::id())
                      ->whereIn('status', ['lunas', 'selesai']);
                })->exists();

            if ($hasHistory) $canViewSchedule = true;
        }

        // 3. Ambil Fasilitas (Status Active Only di Query Awal opsional, tapi lebih baik ambil semua utk status)
        // Kita ambil semua dulu, nanti di map kita tentukan statusnya
        $fasilitasQuery = Fasilitas::query();

        // Load Jadwal
        $fasilitasQuery->with(['jadwals' => function ($query) use ($selectedDate) {
             // Hanya load jadwal jika user boleh melihat tanggal ini
             // Dan hanya load jadwal yang statusnya 'tersedia' untuk dihitung slotnya
             $query->whereDate('tanggal', $selectedDate)
                   ->where('status', 'tersedia')
                   ->orderBy('jam_mulai', 'asc');
        }]);

        // 4. Mapping Data untuk View (MERGED VERSION)
        $currentTime = Carbon::now()->format('H:i:s');
        $isToday = ($selectedDate == $today);

        $fasilitas = $fasilitasQuery->get()->map(function ($f) use ($canViewSchedule, $isToday, $currentTime, $selectedDate) {


            /* ======================================
            1. AMBIL STYLE KATEGORI (TETAP)
            ====================================== */
            $catKey = strtolower($f->kategori ?? 'default');
            $style = Fasilitas::CATEGORY_ICONS[$catKey] ?? Fasilitas::CATEGORY_ICONS['default'];

            $f->icon = $style['icon'];

            /* ======================================
            2. STATUS DASAR (TETAP)
            ====================================== */
            $f->is_non_active = ($f->ketersediaan !== 'aktif');
            $f->is_restricted = !$canViewSchedule;

            /* ======================================
            3. FILTER JADWAL VALID (LOGIKA BARU)
            ====================================== */
            $validJadwals = collect();

            if (!$f->is_non_active && !$f->is_restricted) {
                $validJadwals = $f->jadwals->filter(function ($jadwal) use ($isToday, $currentTime) {
                    if ($isToday) {
                        return $jadwal->jam_mulai > $currentTime;
                    }
                    return true;
                });
            }

           /* ======================================
            4. HITUNG SLOT & JAM (FIXED & MATURE)
            ====================================== */

            // DEFAULT
            $f->total_slot = 0;
            $f->jam_mulai = '-';
            $f->jam_selesai = '-';

            // JUMLAH JADWAL ASLI (PENTING!)
            $totalJadwalAsli = Jadwal::where('fasilitas_id', $f->id)
    ->whereDate('tanggal', $selectedDate)
    ->count();


            // SLOT VALID (BELUM LEWAT JAM)
            $totalValidSlot = $validJadwals->count();

            $f->total_slot = $totalValidSlot;

            // HANYA SET JAM JIKA ADA SLOT VALID
            if ($totalValidSlot > 0) {
                $f->jam_mulai = substr($validJadwals->first()->jam_mulai, 0, 5);
                $f->jam_selesai = substr($validJadwals->last()->jam_selesai, 0, 5);
            }


            /* ======================================
            5. LOGIKA STATUS FINAL (ANTI SALAH)
            ====================================== */

            if ($f->is_non_active) {

                // Rusak / Nonaktif
                $f->status_type = 'closed';

            } elseif ($f->is_restricted) {

                // Tanggal lampau
                $f->status_type = 'locked';

            } elseif ($totalJadwalAsli === 0) {

                // ⛔ TIDAK ADA JADWAL SAMA SEKALI
                $f->status_type = 'closed';

            } elseif ($totalValidSlot === 0) {

                // ⚠️ ADA JADWAL, TAPI HABIS / LEWAT JAM
                $f->status_type = 'full';

            } else {

                // ✅ TERSEDIA
                $f->status_type = 'available';
            }

            /* ======================================
6. MAPPING STATUS (FINAL - BENAR)
====================================== */

switch ($f->status_type) {

    case 'closed':
        $f->box_class = 'bg-secondary';
        $f->status_text = 'Tidak Tersedia';
        $f->status_icon = 'fas fa-ban';
        $f->status_desc = 'Fasilitas belum memiliki jadwal ';
        $f->button_state = 'disabled';
        break;

    case 'locked':
        $f->box_class = 'bg-secondary';
        $f->status_text = 'Terkunci';
        $f->status_icon = 'fas fa-lock';
        $f->status_desc = 'Jadwal lampau tidak tersedia';
        $f->button_state = 'disabled';
        break;

    case 'full':
        $f->box_class = $style['color'];
        $f->status_text = 'Jadwal Habis';
        $f->status_icon = 'fas fa-calendar-times';
        $f->status_desc = 'Silakan cek ketersediaan tanggal lain';
        $f->button_state = 'active';
        break;

    case 'available':
    default:
        $f->box_class = $style['color'];
        $f->status_text = 'Tersedia';
        $f->status_icon = 'far fa-clock';
        $f->status_desc = $f->total_slot . ' Slot Tersedia';
        $f->button_state = 'active';
        break;
}
            return $f;
        });


        // 5. Data Tambahan (Widget & History - Kode lama Anda)
        $pendingBooking = null;
        $userHistoryData = [];

        if (Auth::check()) {
            $pendingBooking = Checkout::with(['jadwals', 'jadwals.fasilitas'])
                ->where('user_id', Auth::id())
                ->where('status', 'kompensasi')
                ->latest()
                ->first();

            $rawHistory = Jadwal::whereHas('checkouts', function($q) {
                    $q->where('user_id', Auth::id())
                      ->whereIn('status', ['lunas', 'selesai']);
                })
                ->with('fasilitas')
                ->get()
                ->groupBy('tanggal');

            foreach($rawHistory as $date => $items) {
                $details = $items->map(function($item) {
                    return '• ' . $item->fasilitas->nama_fasilitas . ' (' . substr($item->jam_mulai, 0, 5) . ' - ' . substr($item->jam_selesai, 0, 5) . ')';
                })->toArray();
                $userHistoryData[$date] = implode('<br>', $details);
            }
        }

        return view('User.Fasilitas.beranda', compact(
            'selectedDate', 'fasilitas', 'pendingBooking', 'userHistoryData'
        ));
    }
}
