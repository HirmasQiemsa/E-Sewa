<?php

namespace App\Http\Controllers\Admin\Fasilitas;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Checkout;
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
        // 1. Ambil ID fasilitas milik admin yang sedang login
        // Asumsi: Admin Fasilitas hanya boleh lihat booking fasilitas buatannya
        $myFasilitasIds = Fasilitas::where('admin_fasilitas_id', Auth::id())->pluck('id');

        // 2. Query Utama
        $query = Checkout::with(['jadwals.fasilitas', 'user', 'pemasukans']) // Eager load pemasukan juga
                    ->whereHas('jadwals', function($q) use ($myFasilitasIds) {
                        $q->whereIn('fasilitas_id', $myFasilitasIds);
                    })
                    ->latest();

        // 3. Filter Search (Nama User / ID Booking)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('user', function($userQ) use ($search) {
                    $userQ->where('name', 'like', "%{$search}%");
                })
                ->orWhere('id', 'like', "%{$search}%"); // Bisa cari pakai ID #12
            });
        }

        // 4. Filter Status
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // 5. Filter Periode
        $filterType = $request->filter_type ?? 'all';
        $startDate = $request->start_date;
        $endDate = $request->end_date;

        if ($filterType == 'today') {
            $query->whereDate('created_at', now());
        } elseif ($filterType == 'upcoming') {
            // Menampilkan booking yang jadwal mainnya masih akan datang
            $query->whereHas('jadwals', function($q) {
                $q->whereDate('tanggal', '>=', now());
            });
        } elseif ($filterType == 'custom' && $startDate && $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate]);
        }

        $bookings = $query->paginate(10)->withQueryString();

        // Hitung total durasi untuk setiap booking (Opsional, buat display di view)
        foreach ($bookings as $booking) {
            $totalJam = 0;
            foreach ($booking->jadwals as $jadwal) {
                $start = \Carbon\Carbon::parse($jadwal->jam_mulai);
                $end = \Carbon\Carbon::parse($jadwal->jam_selesai);
                $totalJam += $start->diffInHours($end);
            }
            $booking->totalDurasi = $totalJam;
        }

        return view('Admin.Fasilitas.Booking.index', compact(
            'bookings', 'filterType', 'startDate', 'endDate'
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
        // Pastikan hanya membuka booking yang ada fasilitas milik admin ini
        $myFasilitasIds = Fasilitas::where('admin_fasilitas_id', Auth::id())->pluck('id');

        // Ganti 'pembayaran' menjadi 'pemasukans'
        $booking = Checkout::with(['jadwals.fasilitas', 'user', 'pemasukans'])
                        ->whereHas('jadwals', function($q) use ($myFasilitasIds) {
                            $q->whereIn('fasilitas_id', $myFasilitasIds);
                        })
                        ->findOrFail($id);

        return view('Admin.Fasilitas.Booking.detail', compact('booking'));
    }

    /**
     * Update Status Manual (Dari Modal)
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:fee,lunas,selesai,batal',
            'catatan' => 'nullable|string'
        ]);

        $booking = Checkout::findOrFail($id);

        // Mapping value 'fee' dari select option ke 'kompensasi' di database
        $statusDb = ($request->status == 'fee') ? 'kompensasi' : $request->status;

        $booking->update([
            'status' => $statusDb
        ]);

        // Jika dibatalkan via update status, jadwal harus tersedia lagi
        if ($statusDb == 'batal') {
            foreach ($booking->jadwals as $jadwal) {
                $jadwal->update(['status' => 'tersedia']);
            }
        }

        // Opsional: Simpan log catatan admin (jika ada tabel logs)
        // Log::create([...])

        return back()->with('success', 'Status booking berhasil diperbarui.');
    }

    /**
     * Batalkan Booking (Force Cancel)
     */
    public function cancelBooking(Request $request, $id)
    {
        $request->validate([
            'alasan_batal' => 'required|string',
            // 'refund_dp' => 'required|boolean' // Jika mau pakai logika refund
        ]);

        $booking = Checkout::findOrFail($id);

        // Cek keamanan: Jangan cancel yang sudah selesai
        if ($booking->status == 'selesai') {
            return back()->with('error', 'Booking yang sudah selesai tidak dapat dibatalkan.');
        }

        DB::transaction(function() use ($booking) {
            // 1. Ubah status checkout
            $booking->update(['status' => 'batal']);

            // 2. Ubah status jadwal jadi tersedia lagi
            foreach ($booking->jadwals as $jadwal) {
                $jadwal->update(['status' => 'tersedia']);
            }

            // 3. Jika ada logika pengembalian dana, proses di sini
            // ...
        });

        return back()->with('success', 'Booking berhasil dibatalkan paksa.');
    }
}
