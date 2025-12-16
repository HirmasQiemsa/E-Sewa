<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Admin;
use App\Models\AdminFasilitas;
use App\Models\AdminPembayaran;
use App\Models\Checkout;
use Carbon\Carbon;
use App\Models\Fasilitas;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class BerandaController extends Controller
{
    // View Blade Beranda
    public function beranda(Request $request)
    {
        // 1. Logika Tanggal (Default Hari Ini)
        try {
            $reqDate = $request->query('date', Carbon::now()->format('Y-m-d'));
            $selectedDate = Carbon::parse($reqDate)->format('Y-m-d');
        } catch (\Exception $e) {
            $selectedDate = Carbon::now()->format('Y-m-d');
        }

        // VALIDASI KEAMANAN: Apakah User Boleh Melihat Tanggal Ini?
        $today = Carbon::today()->format('Y-m-d');
        $canViewSchedule = false;

        // Aturan 1: Tanggal Hari Ini atau Masa Depan
        if ($selectedDate >= $today) {
            $canViewSchedule = true;
        }
        // Aturan 2: Tanggal Masa Lalu TAPI User Punya Booking (Histori)
        else {
            $hasBookingHistory = \App\Models\Jadwal::whereDate('tanggal', $selectedDate)
                ->whereHas('checkouts', function($q) {
                    $q->where('user_id', Auth::id())
                      ->whereIn('status', ['lunas', 'selesai']);
                })->exists();

            if ($hasBookingHistory) {
                $canViewSchedule = true;
            }
        }

        // 2. Ambil Fasilitas & Jadwal
        // Jika tidak boleh lihat ($canViewSchedule = false), kita tidak perlu load 'jadwals'
        $fasilitasQuery = Fasilitas::where('ketersediaan', 'aktif');

        if ($canViewSchedule) {
            $fasilitasQuery->with(['jadwals' => function ($query) use ($selectedDate) {
                $query->whereDate('tanggal', $selectedDate)
                      ->whereIn('status', ['tersedia', 'Tersedia'])
                      ->orderBy('jam_mulai', 'asc');
            }]);
        }

        $fasilitas = $fasilitasQuery->get()->map(function ($f) use ($canViewSchedule) {
            // Jika user tidak boleh lihat jadwal, kosongkan data
            if (!$canViewSchedule) {
                $f->jam_mulai_tersedia = '-';
                $f->jam_tutup_tersedia = '-';
                $f->total_slot = 0;
                $f->is_restricted = true; // Flag untuk UI
                return $f;
            }

            // Logika Hitung Slot (Normal)
            if ($f->relationLoaded('jadwals') && $f->jadwals->isNotEmpty()) {
                $firstSlot = $f->jadwals->first();
                $lastSlot = $f->jadwals->last();
                $f->jam_mulai_tersedia = substr($firstSlot->jam_mulai, 0, 5);
                $f->jam_tutup_tersedia = substr($lastSlot->jam_selesai, 0, 5);
                $f->total_slot = $f->jadwals->count();
            } else {
                $f->jam_mulai_tersedia = '-';
                $f->jam_tutup_tersedia = '-';
                $f->total_slot = 0;
            }
            $f->is_restricted = false;
            return $f;
        });

        return view('User.Fasilitas.beranda', compact( 'selectedDate', 'fasilitas'));
    }
}
