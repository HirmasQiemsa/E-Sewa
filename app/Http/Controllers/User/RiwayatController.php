<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Checkout;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\PengajuanEvent;
use PDF; // Pastikan library PDF sudah ada, jika belum bisa dikomen dulu

class RiwayatController extends Controller
{
    /**
     * Display history with pagination
     */
    public function index(Request $request)
    {
        // 1. QUERY UTAMA (Gunakan 'jadwals' jamak)
       $query = Checkout::with(['jadwals.fasilitas', 'user'])
        ->where('user_id', Auth::id());

    // 1. FILTER STATUS (Tetap sama)
    if ($request->filled('status') && $request->status != 'all') {
        $query->where('status', $request->status);
    }

    // 2. FILTER TANGGAL SPESIFIK (Upgrade dari Bulan ke Tanggal)
    // Input dari Flatpickr sekarang dikirim format: YYYY-MM-DD
    if ($request->filled('date')) {
        $query->whereHas('jadwals', function($q) use ($request) {
            $q->whereDate('tanggal', $request->date);
        });
    }

    // 3. PENCARIAN GLOBAL (Search Bar)
    if ($request->filled('search')) {
        $search = $request->search;

        $query->where(function($q) use ($search) {
            // A. Cari ID Booking (#15)
            $q->where('id', 'like', "%$search%")

            // B. Cari Total Biaya (40000)
              ->orWhere('total_bayar', 'like', "%$search%")

            // C. Cari Status (pending, lunas, dll)
              ->orWhere('status', 'like', "%$search%")

            // D. Cari Nama Fasilitas & Lokasi
              ->orWhereHas('jadwals.fasilitas', function($subQ) use ($search) {
                  $subQ->where('nama_fasilitas', 'like', "%$search%")
                       ->orWhere('lokasi', 'like', "%$search%");
              })

            // E. Cari Tanggal (Format: 2026-01-10)
            // Note: Mencari nama hari ("Rabu") sulit dilakukan di database query standar
            // jadi kita fokus pencarian tanggal angka saja.
              ->orWhereHas('jadwals', function($subQ) use ($search) {
                  $subQ->where('tanggal', 'like', "%$search%");
              });
        });
    }

    $checkouts = $query->latest()->paginate(10);

        // --- 2. LOGIKA WARNA KALENDER (FIXED) ---
        // Kita ambil data jadwal untuk mewarnai kalender
        $allBookings = Checkout::with('jadwals') // Gunakan 'jadwals'
            ->where('user_id', Auth::id())
            ->whereHas('jadwals') // Gunakan 'jadwals'
            ->get();

        $calendarData = [];

        foreach ($allBookings as $checkout) {
            // KARENA RELASI MANY-TO-MANY, KITA HARUS LOOPING JADWALNYA
            foreach ($checkout->jadwals as $jadwal) {
                $date = Carbon::parse($jadwal->tanggal)->format('Y-m-d');
                $status = $checkout->status;

                // Inisialisasi jika tanggal belum ada
                if (!isset($calendarData[$date])) {
                    $calendarData[$date] = 'lunas'; // Default hijau
                }

                // PRIORITAS WARNA: Kompensasi (Kuning) > Pending (Biru) > Lunas (Hijau)

                // Jika tanggal ini sudah 'kompensasi' (kuning), jangan ditimpa lagi (prioritas tertinggi)
                if ($calendarData[$date] == 'kompensasi') {
                    continue;
                }

                // Jika checkout ini statusnya 'kompensasi', ubah tanggal jadi kuning
                if ($status == 'kompensasi') {
                    $calendarData[$date] = 'kompensasi';
                }
                // Jika checkout ini 'pending' dan tanggal belum kuning, ubah jadi biru
                elseif ($status == 'pending' && $calendarData[$date] != 'kompensasi') {
                    $calendarData[$date] = 'pending';
                }
            }
        }

        return view('User.Riwayat.index', compact('checkouts', 'calendarData'));
    }

    /**
     * Menampilkan halaman riwayat (Blade View).
     */
    /**
     * 1. Menampilkan Halaman View (Blade)
     * Route: user.riwayat
     */
    public function history()
    {
        return view('User.riwayat');
    }

    /**
     * 2. API List Booking (JSON)
     * Route: user.api.riwayat.booking
     */
    public function getBookingData(Request $request)
    {
        $user = Auth::user();

        // Query Ambil Data
        $query = Checkout::with(['jadwalUtama.fasilitas'])
            ->where('user_id', $user->id)
            ->latest();

        // Logika Filter Tanggal (Range Datepicker)
        if ($request->has('date') && !empty($request->date)) {
            $dates = explode(' to ', $request->date);
            if (count($dates) == 2) {
                $query->whereBetween('created_at', [$dates[0] . ' 00:00:00', $dates[1] . ' 23:59:59']);
            } else {
                $query->whereDate('created_at', $dates[0]);
            }
        }

        // Formatting JSON untuk Tabel
        $data = $query->get()->map(function ($item) {
            return [
                'id' => $item->id,
                'fasilitas' => [
                    'nama_fasilitas' => $item->jadwalUtama->fasilitas->nama_fasilitas ?? 'Fasilitas Tidak Ditemukan'
                ],
                'tanggal_indo' => Carbon::parse($item->created_at)->isoFormat('dddd, D MMMM Y'),
                'status' => $item->status,
            ];
        });

        return response()->json($data);
    }

    /**
     * 3. API List Event (JSON)
     * Route: user.api.riwayat.event
     */
    public function getEventData(Request $request)
    {
        $user = Auth::user();

        $query = PengajuanEvent::with(['fasilitas'])
            ->where('id_user', $user->id)
            ->latest();

        // Logika Filter Tanggal (Berdasarkan Tgl Mulai Event)
        if ($request->has('date') && !empty($request->date)) {
            $dates = explode(' to ', $request->date);
            if (count($dates) == 2) {
                $query->whereBetween('tgl_mulai', [$dates[0], $dates[1]]);
            } else {
                $query->whereDate('tgl_mulai', $dates[0]);
            }
        }

        // Formatting JSON untuk Tabel
        $data = $query->get()->map(function ($item) {
            return [
                'id' => $item->id,
                'nama_event' => $item->nama_event,
                'fasilitas' => [
                    'nama_fasilitas' => $item->fasilitas->nama_fasilitas ?? 'Fasilitas Tidak Ditemukan'
                ],
                'tgl_mulai_indo' => Carbon::parse($item->tgl_mulai)->isoFormat('D MMMM Y'),
                'tgl_selesai_indo' => Carbon::parse($item->tgl_selesai)->isoFormat('D MMMM Y'),
                'status' => $item->status,
            ];
        });

        return response()->json($data);
    }

    public function detailBooking($id)
{
    $checkout = Checkout::with(['jadwalUtama.fasilitas', 'jadwals', 'pemasukans'])
        ->where('user_id', auth()->id())
        ->findOrFail($id);

    return view('User.riwayat_detail_booking', compact('checkout'));
}

public function detailEvent($id)
{
    $event = PengajuanEvent::with('fasilitas')
        ->where('id_user', auth()->id())
        ->findOrFail($id);

    return view('User.riwayat_detail_event', compact('event'));
}
}
