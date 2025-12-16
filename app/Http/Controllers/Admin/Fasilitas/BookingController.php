<?php

namespace App\Http\Controllers\Admin\Fasilitas;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Checkout;
use App\Models\Jadwal;
use App\Models\Fasilitas;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BookingController extends Controller
{
    /**
     * Menampilkan daftar booking pada fasilitas yang dikelola admin ini
     */
    public function daftarBooking(Request $request)
    {
        // Ambil ID fasilitas milik admin ini
        $myFasilitasIds = Fasilitas::where('admin_fasilitas_id', Auth::id())->pluck('id');

        $query = Checkout::with(['jadwals.fasilitas', 'user'])
                    ->whereHas('jadwals', function($q) use ($myFasilitasIds) {
                        $q->whereIn('fasilitas_id', $myFasilitasIds);
                    })
                    ->latest();

        // 1. Filter Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('user', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
        }

        // 2. Filter Status
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // 3. Filter Periode (Date Range)
        $startDate = null;
        $endDate = null;
        $filterType = $request->filter_type ?? 'all'; // Default 'all'

        if ($filterType == 'today') {
            $query->whereDate('created_at', now());
        } elseif ($filterType == 'upcoming') {
            // Contoh: Booking untuk jadwal masa depan
            $query->whereHas('jadwals', function($q) {
                $q->whereDate('tanggal', '>=', now());
            });
        } elseif ($filterType == 'custom' && $request->filled(['start_date', 'end_date'])) {
            $startDate = $request->start_date;
            $endDate = $request->end_date;
            $query->whereBetween('created_at', [$startDate, $endDate]);
        }

        $bookings = $query->paginate(10)->withQueryString();

        // KIRIM SEMUA VARIABEL KE VIEW
        return view('Admin.Fasilitas.Booking.index', compact(
            'bookings',
            'filterType',
            'startDate',
            'endDate'
        ))->with([
            'status' => $request->status ?? 'all',
            'search' => $request->search
        ]);
    }

    /**
     * Detail Booking
     */
    public function show($id)
    {
        $booking = Checkout::with(['jadwals.fasilitas', 'user', 'pembayaran'])
                        ->findOrFail($id);

        // Validasi kepemilikan (opsional, untuk keamanan)
        // Cek apakah salah satu jadwal di booking ini milik fasilitas admin ini
        // ...

        return view('Admin.Fasilitas.Booking.detail', compact('booking'));
    }

    /**
     * Batalkan Booking (Force Cancel by Admin)
     */
    public function cancelBooking(Request $request, $id)
    {
        $booking = Checkout::findOrFail($id);

        if ($booking->status == 'lunas') {
            return back()->with('error', 'Booking sudah lunas tidak bisa dibatalkan sembarangan. Hubungi Admin Keuangan.');
        }

        DB::transaction(function() use ($booking) {
            // Ubah status checkout
            $booking->update(['status' => 'batal']);

            // Ubah status jadwal jadi tersedia lagi
            foreach ($booking->jadwals as $jadwal) {
                $jadwal->update(['status' => 'tersedia']);
            }
        });

        return back()->with('success', 'Booking berhasil dibatalkan.');
    }
}
