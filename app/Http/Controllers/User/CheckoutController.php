<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Checkout;
use App\Models\Fasilitas;
use App\Models\Jadwal;
use App\Models\PemasukanSewa;
use App\Models\Riwayat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    /**
     * Display checkout form
     */
    public function index()
    {
        // Get active facilities
        $fasilitas = Fasilitas::where('ketersediaan', 'aktif')
                             ->whereNull('deleted_at')
                             ->get();

        // Get user's checkouts
        $checkouts = Checkout::with(['jadwal.fasilitas'])
                           ->where('user_id', Auth::id())
                           ->whereIn('status', ['fee', 'lunas']) // Only show active bookings
                           ->latest()
                           ->get();

        // Calculate total duration for each checkout
        foreach ($checkouts as $checkout) {
            // Get all jadwals associated with this checkout using pivot relationship
            $jadwals = $checkout->jadwals;

            // Calculate total duration in hours
            $totalDurasi = 0;
            foreach ($jadwals as $jadwal) {
                $mulaiParts = explode(':', $jadwal->jam_mulai);
                $selesaiParts = explode(':', $jadwal->jam_selesai);

                $mulaiJam = (int)$mulaiParts[0];
                $selesaiJam = (int)$selesaiParts[0];

                $totalDurasi += ($selesaiJam - $mulaiJam);
            }

            $checkout->totalDurasi = $totalDurasi;
        }

        return view('User.Checkout.index', compact('fasilitas', 'checkouts'));
    }

    /**
     * Store a new checkout
     */
    public function store(Request $request)
    {
        // Validate input
        $validated = $request->validate([
            'fasilitas_id' => 'required|exists:fasilitas,id',
            'tanggal_booking' => 'required|date|after_or_equal:today',
            'jadwal_ids' => 'required|array',
            'jadwal_ids.*' => 'exists:jadwals,id',
            'total_durasi' => 'required|integer|min:1',
            'total_bayar' => 'required|numeric|min:1',
            'dp_value' => 'required|numeric|min:1',
        ]);

        // Check if jadwals are still available
        $jadwals = Jadwal::whereIn('id', $request->jadwal_ids)->get();

        foreach ($jadwals as $jadwal) {
            if ($jadwal->status !== 'tersedia') {
                return back()->with('error', 'Jadwal yang dipilih sudah dibooking. Silakan pilih jadwal lain.');
            }
        }

        // Begin DB transaction
        DB::beginTransaction();
        try {
            // 1. Create checkout record
            $checkout = Checkout::create([
                'user_id' => Auth::id(),
                'jadwals_id' => $request->jadwal_ids[0], // Use first jadwal ID as reference
                'total_bayar' => $request->total_bayar,
                'status' => 'fee', // DP paid status
            ]);

            // 2. Attach jadwals to checkout using pivot and update jadwal status
            foreach ($jadwals as $jadwal) {
                // Attach jadwal to checkout
                $checkout->jadwals()->attach($jadwal->id);

                // Update jadwal status separately
                $jadwal->update([
                    'status' => 'terbooking'
                ]);
            }

            // 3. Record DP payment
            PemasukanSewa::create([
                'checkout_id' => $checkout->id,
                'fasilitas_id' => $request->fasilitas_id,
                'tanggal_bayar' => now(),
                'jumlah_bayar' => $request->dp_value,
                'metode_pembayaran' => 'transfer', // Default assumption
                'status' => 'fee',
            ]);

            // Commit transaction
            DB::commit();

            return redirect()->route('user.checkout')
                ->with('success', 'Booking berhasil! DP 50% telah dibayar.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Show payment completion form
     */
    public function pelunasan($id)
    {
        $checkout = Checkout::with(['jadwal.fasilitas', 'user'])
                          ->where('id', $id)
                          ->where('user_id', Auth::id())
                          ->where('status', 'fee')
                          ->firstOrFail();

        // Calculate remaining payment (50%)
        $sisaPembayaran = $checkout->total_bayar * 0.5;

        return view('User.Checkout.pelunasan', compact('checkout', 'sisaPembayaran'));
    }

    /**
     * Process payment completion
     */
    public function prosesLunasi(Request $request, $id)
    {
        $checkout = Checkout::where('id', $id)
                        ->where('user_id', Auth::id())
                        ->where('status', 'fee')
                        ->firstOrFail();

        // Validate request
        $request->validate([
            'metode_pembayaran' => 'required|in:transfer',
        ]);

        // Calculate remaining payment
        $sisaPembayaran = $checkout->total_bayar * 0.5;

        DB::beginTransaction();
        try {
            // 1. Update checkout status
            $checkout->update([
                'status' => 'lunas'
            ]);

            // 2. Record payment
            PemasukanSewa::create([
                'checkout_id' => $checkout->id,
                'fasilitas_id' => $checkout->jadwal->fasilitas_id,
                'tanggal_bayar' => now(),
                'jumlah_bayar' => $sisaPembayaran,
                'metode_pembayaran' => $request->metode_pembayaran,
                'status' => 'lunas'
                // Hapus field 'keterangan' yang tidak ada dalam tabel
            ]);

            DB::commit();
            return redirect()->route('user.checkout')
                ->with('success', 'Pembayaran berhasil dilunasi! Terima kasih atas transaksi Anda.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Show checkout details
     */
    public function detail($id)
    {
        $checkout = Checkout::with(['jadwal.fasilitas', 'user'])
                          ->where('id', $id)
                          ->where('user_id', Auth::id())
                          ->firstOrFail();

        // Get all jadwals for this checkout using pivot relationship
        $jadwals = $checkout->jadwals()
                          ->orderBy('jam_mulai', 'asc')
                          ->get();

        // Get payment history
        $pembayaran = PemasukanSewa::where('checkout_id', $checkout->id)
                                 ->orderBy('tanggal_bayar', 'asc')
                                 ->get();

        return view('User.Checkout.detail_checkout', compact('checkout', 'jadwals', 'pembayaran'));
    }

    /**
     * Cancel a booking
     */
    public function cancel(Request $request, $id)
    {
        $checkout = Checkout::where('id', $id)
                          ->where('user_id', Auth::id())
                          ->where('status', 'fee')
                          ->firstOrFail();

        DB::beginTransaction();
        try {
            // 1. Update checkout status
            $checkout->update([
                'status' => 'batal'
            ]);

            // 2. Get all jadwals before detaching to update their status
            $jadwals = $checkout->jadwals;

            // 3. Detach all jadwals from checkout (remove pivot relationships)
            $checkout->jadwals()->detach();

            // 4. Update each jadwal's status back to available
            foreach ($jadwals as $jadwal) {
                $jadwal->update([
                    'status' => 'tersedia'
                ]);
            }

            // 5. Create cancellation record in riwayat
            Riwayat::create([
                'checkout_id' => $checkout->id,
                'waktu_selesai' => now(),
                'feedback' => 'Dibatalkan oleh pengguna'
            ]);

            DB::commit();
            return redirect()->route('user.checkout')
                ->with('success', 'Booking berhasil dibatalkan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Check available jadwals (for API)
     */
    public function checkJadwal($fasilitasId, $tanggal)
    {
        $jadwals = Jadwal::where('fasilitas_id', $fasilitasId)
                        ->where('tanggal', $tanggal)
                        ->orderBy('jam_mulai', 'asc')
                        ->get();

        return response()->json($jadwals);
    }
}
