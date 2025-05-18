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

    /**
 * Menampilkan riwayat aktivitas booking
 */
public function riwayat(Request $request)
{
    // Filter berdasarkan tanggal
    $startDate = $request->input('start_date', Carbon::today()->subDays(30)->format('Y-m-d'));
    $endDate = $request->input('end_date', Carbon::today()->format('Y-m-d'));
    $type = $request->input('type', 'all');
    $search = $request->input('search', '');

    // Base query - menggunakan checkout sebagai log aktivitas
    $query = Checkout::with(['jadwals.fasilitas', 'user'])
                    ->orderBy('created_at', 'desc');

    // Apply date filters
    if ($startDate && $endDate) {
        $query->whereBetween('created_at', [$startDate.' 00:00:00', $endDate.' 23:59:59']);
    }

    // Apply type filter
    if ($type !== 'all') {
        $query->where('status', $type);
    }

    // Apply search
    if ($search) {
        $query->where(function($q) use ($search) {
            $q->whereHas('user', function($uq) use ($search) {
                $uq->where('name', 'like', "%{$search}%")
                   ->orWhere('email', 'like', "%{$search}%");
            })
            ->orWhereHas('jadwals.fasilitas', function($fq) use ($search) {
                $fq->where('nama_fasilitas', 'like', "%{$search}%")
                   ->orWhere('lokasi', 'like', "%{$search}%");
            });
        });
    }

    // Get activities with pagination
    $activities = $query->paginate(10)->withQueryString();

    // Format activities for view
    $formattedActivities = $activities->map(function ($checkout) {
        return [
            'id' => $checkout->id,
            'created_at' => $checkout->created_at,
            'user' => $checkout->user,
            'fasilitas' => $checkout->jadwals->first()->fasilitas ?? null,
            'description' => $this->getActivityDescription($checkout),
            'status' => $this->getActivityStatus($checkout),
            'status_color' => $this->getActivityStatusColor($checkout)
        ];
    });

    return view('PetugasFasilitas.KelolaBooking.riwayat', [
        'activities' => $activities,
        'formattedActivities' => $formattedActivities,
        'startDate' => $startDate,
        'endDate' => $endDate,
        'type' => $type,
        'search' => $search
    ]);
}

    /**
     * Helper method untuk mendapatkan deskripsi aktivitas
     */
    private function getActivityDescription($checkout)
    {
        switch ($checkout->status) {
            case 'fee':
                return 'Melakukan booking dan membayar DP';
            case 'lunas':
                return 'Melunasi pembayaran booking';
            case 'batal':
                return 'Membatalkan booking';
            case 'selesai':
                return 'Menyelesaikan penggunaan fasilitas';
            default:
                return 'Melakukan aktivitas pada sistem';
        }
    }

    /**
     * Helper method untuk mendapatkan label status
     */
    private function getActivityStatus($checkout)
    {
        switch ($checkout->status) {
            case 'fee':
                return 'DP Terbayar';
            case 'lunas':
                return 'Lunas';
            case 'batal':
                return 'Dibatalkan';
            case 'selesai':
                return 'Selesai';
            default:
                return 'Undefined';
        }
    }

    /**
     * Helper method untuk mendapatkan warna status
     */
    private function getActivityStatusColor($checkout)
    {
        switch ($checkout->status) {
            case 'fee':
                return 'warning';
            case 'lunas':
                return 'success';
            case 'batal':
                return 'danger';
            case 'selesai':
                return 'info';
            default:
                return 'secondary';
        }
    }
}
