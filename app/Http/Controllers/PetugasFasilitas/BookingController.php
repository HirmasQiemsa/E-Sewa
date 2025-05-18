<?php

namespace App\Http\Controllers\PetugasFasilitas;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Checkout;
use App\Models\Jadwal;
use App\Models\Fasilitas;
use App\Models\Riwayat;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class BookingController extends Controller
{
    /**
     * Menampilkan booking hari ini
     */
    public function today()
    {
        $today = Carbon::today()->format('Y-m-d');

        // Ambil semua jadwal yang terbooking hari ini
        $bookings = Jadwal::with(['fasilitas', 'checkouts.user'])
                        ->where('tanggal', $today)
                        ->where('status', 'terbooking')
                        ->orderBy('jam_mulai', 'asc')
                        ->paginate(15);

        return view('PetugasFasilitas.Booking.today', compact('bookings'));
    }

    /**
     * Menampilkan booking yang akan datang
     */
    public function upcoming()
    {
        $today = Carbon::today()->format('Y-m-d');

        // Ambil semua jadwal yang terbooking untuk tanggal yang akan datang
        $bookings = Jadwal::with(['fasilitas', 'checkouts.user'])
                        ->where('tanggal', '>', $today)
                        ->where('status', 'terbooking')
                        ->orderBy('tanggal', 'asc')
                        ->orderBy('jam_mulai', 'asc')
                        ->paginate(15);

        return view('PetugasFasilitas.Booking.upcoming', compact('bookings'));
    }

    /**
     * Menampilkan riwayat booking
     */
    public function history(Request $request)
    {
        // Filter berdasarkan bulan dan tahun jika ada
        $month = $request->input('month', Carbon::now()->month);
        $year = $request->input('year', Carbon::now()->year);
        $status = $request->input('status');

        // Query dasar
        $query = Checkout::with(['jadwals.fasilitas', 'user'])
                       ->whereHas('jadwals', function($q) {
                           $q->whereIn('status', ['selesai', 'batal']);
                       })
                       ->orderBy('created_at', 'desc');

        // Terapkan filter bulan dan tahun
        if ($month && $year) {
            $startDate = Carbon::createFromDate($year, $month, 1)->startOfMonth();
            $endDate = Carbon::createFromDate($year, $month, 1)->endOfMonth();

            $query->whereBetween('created_at', [$startDate, $endDate]);
        }

        // Terapkan filter status
        if ($status) {
            $query->where('status', $status);
        }

        // Dapatkan data booking
        $bookings = $query->paginate(15);

        return view('PetugasFasilitas.Booking.history', compact('bookings', 'month', 'year', 'status'));
    }

    /**
     * Menampilkan detail booking
     */
    public function show($id)
    {
        $booking = Checkout::with(['jadwals.fasilitas', 'user', 'pembayaran'])->findOrFail($id);

        return view('PetugasFasilitas.Booking.show', compact('booking'));
    }

    /**
     * Memperbarui status booking
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:fee,lunas,batal,selesai'
        ]);

        DB::beginTransaction();
        try {
            $checkout = Checkout::findOrFail($id);
            $oldStatus = $checkout->status;

            // Update status checkout
            $checkout->status = $request->status;
            $checkout->save();

            // Update status jadwal terkait
            foreach ($checkout->jadwals as $jadwal) {
                if ($request->status == 'batal') {
                    $jadwal->status = 'tersedia';
                } elseif ($request->status == 'selesai') {
                    $jadwal->status = 'selesai';
                } elseif ($request->status == 'lunas' || $request->status == 'fee') {
                    $jadwal->status = 'terbooking';
                }
                $jadwal->save();
            }

            // Catat di riwayat jika selesai atau batal
            if ($request->status == 'selesai' || $request->status == 'batal') {
                Riwayat::updateOrCreate(
                    ['checkout_id' => $checkout->id],
                    [
                        'waktu_selesai' => now(),
                        'feedback' => $request->feedback ?? "Status diubah dari $oldStatus menjadi {$request->status} oleh petugas"
                    ]
                );
            }

            DB::commit();

            return redirect()->back()->with('success', 'Status booking berhasil diperbarui');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal memperbarui status: ' . $e->getMessage());
        }
    }
}
