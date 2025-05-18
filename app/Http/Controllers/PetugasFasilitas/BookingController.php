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
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    /**
     * Menampilkan daftar booking dengan berbagai filter
     */
    public function daftarBooking(Request $request)
    {
        // Default ke hari ini jika tidak ada filter
        $filterType = $request->input('filter_type', 'today');
        $status = $request->input('status');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $search = $request->input('search');

        // Base query dengan relasi
        $query = Checkout::with(['jadwals', 'jadwals.fasilitas', 'user'])
                       ->orderBy('created_at', 'desc');

        // Terapkan filter tanggal berdasarkan tipe yang dipilih
        if ($filterType == 'today') {
            $today = Carbon::today()->format('Y-m-d');
            $query->whereHas('jadwals', function($q) use ($today) {
                $q->where('tanggal', $today);
            });
        } elseif ($filterType == 'upcoming') {
            $tomorrow = Carbon::tomorrow()->format('Y-m-d');
            $query->whereHas('jadwals', function($q) use ($tomorrow) {
                $q->where('tanggal', '>=', $tomorrow);
            });
        } elseif ($filterType == 'custom' && $startDate && $endDate) {
            $query->whereHas('jadwals', function($q) use ($startDate, $endDate) {
                $q->whereBetween('tanggal', [$startDate, $endDate]);
            });
        }

        // Terapkan filter status
        if ($status && $status != 'all') {
            $query->where('status', $status);
        }

        // Terapkan pencarian jika ada
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->whereHas('user', function($userQ) use ($search) {
                    $userQ->where('name', 'LIKE', "%{$search}%")
                          ->orWhere('email', 'LIKE', "%{$search}%")
                          ->orWhere('no_hp', 'LIKE', "%{$search}%");
                })->orWhereHas('jadwals.fasilitas', function($fasilitasQ) use ($search) {
                    $fasilitasQ->where('nama_fasilitas', 'LIKE', "%{$search}%")
                              ->orWhere('lokasi', 'LIKE', "%{$search}%");
                });
            });
        }

        // Paginasi hasil
        $bookings = $query->paginate(15)->withQueryString();

        // Hitung durasi untuk setiap booking
        foreach ($bookings as $booking) {
            $jadwals = $booking->jadwals;

            // Hitung total durasi
            $totalDurasi = 0;
            foreach ($jadwals as $jadwal) {
                $mulaiParts = explode(':', $jadwal->jam_mulai);
                $selesaiParts = explode(':', $jadwal->jam_selesai);

                $mulaiJam = (int)$mulaiParts[0];
                $selesaiJam = (int)$selesaiParts[0];

                $totalDurasi += ($selesaiJam - $mulaiJam);
            }

            $booking->totalDurasi = $totalDurasi;
        }

        return view('PetugasFasilitas.KelolaBooking.daftar_booking', compact(
            'bookings',
            'filterType',
            'status',
            'startDate',
            'endDate',
            'search'
        ));
    }

    /**
     * Membatalkan booking oleh petugas
     */
    public function cancelBooking(Request $request, $id)
    {
        $request->validate([
            'alasan_batal' => 'required|string',
            'refund_dp' => 'required|boolean'
        ]);

        DB::beginTransaction();
        try {
            $checkout = Checkout::findOrFail($id);

            // Pastikan statusnya masih DP/fee
            if ($checkout->status !== 'fee') {
                return back()->with('error', 'Hanya booking dengan status DP yang dapat dibatalkan');
            }

            // Ambil info petugas
            $petugas = Auth::guard('petugas_fasilitas')->user();

            // Update status checkout
            $checkout->status = 'batal';
            $checkout->save();

            // Update status jadwal - kembalikan ke tersedia
            foreach ($checkout->jadwals as $jadwal) {
                $jadwal->status = 'tersedia';
                $jadwal->save();
            }

            // Catat di riwayat
            Riwayat::updateOrCreate(
                ['checkout_id' => $checkout->id],
                [
                    'waktu_selesai' => now(),
                    'feedback' => "Dibatalkan oleh petugas {$petugas->name}. Alasan: {$request->alasan_batal}. DP " .
                                 ($request->refund_dp ? 'dikembalikan' : 'tidak dikembalikan')
                ]
            );

            DB::commit();
            return redirect()->back()->with('success', 'Booking berhasil dibatalkan');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal membatalkan booking: ' . $e->getMessage());
        }
    }

    /**
     * Mengubah status booking
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:fee,lunas,batal,selesai',
            'catatan' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $checkout = Checkout::findOrFail($id);
            $oldStatus = $checkout->status;
            $petugas = Auth::guard('petugas_fasilitas')->user();

            // Update status checkout
            $checkout->status = $request->status;
            $checkout->save();

            // Update status jadwal
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

            // Catat di riwayat jika status berubah menjadi selesai atau batal
            if ($request->status == 'selesai' || $request->status == 'batal') {
                $catatan = $request->catatan ?? "Status diubah dari $oldStatus menjadi {$request->status} oleh petugas {$petugas->name}";

                Riwayat::updateOrCreate(
                    ['checkout_id' => $checkout->id],
                    [
                        'waktu_selesai' => now(),
                        'feedback' => $catatan
                    ]
                );
            }

            DB::commit();
            return redirect()->back()->with('success', "Status booking berhasil diubah menjadi {$request->status}");
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal mengubah status booking: ' . $e->getMessage());
        }
    }

    /**
     * Menampilkan detail booking
     */
    public function show($id)
    {
        $booking = Checkout::with(['jadwals.fasilitas', 'user', 'pembayaran', 'riwayat'])
                        ->findOrFail($id);

        return view('PetugasFasilitas.KelolaBooking.detail', compact('booking'));
    }
}
