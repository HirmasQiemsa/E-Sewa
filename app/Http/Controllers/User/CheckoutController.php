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
            // Get all jadwals associated with this checkout
            $jadwals = Jadwal::where('checkout_id', $checkout->id)->get();

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

            // 2. Update jadwal status and link to checkout
            foreach ($jadwals as $jadwal) {
                $jadwal->update([
                    'status' => 'terbooking',
                    'checkout_id' => $checkout->id
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

        return view('User.pelunasan', compact('checkout', 'sisaPembayaran'));
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

        // Calculate remaining payment (50%)
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
                'metode_pembayaran' => $request->metode_pembayaran ?? 'cash',
                'status' => 'lunas',
                'keterangan' => 'Pelunasan pembayaran'
            ]);

            DB::commit();
            return redirect()->route('user.checkout')
                ->with('success', 'Pembayaran berhasil dilunasi!');
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

        // Get all jadwals for this checkout
        $jadwals = Jadwal::where('checkout_id', $checkout->id)
                        ->orderBy('jam_mulai', 'asc')
                        ->get();

        // Get payment history
        $pembayaran = PemasukanSewa::where('checkout_id', $checkout->id)
                                 ->orderBy('tanggal_bayar', 'asc')
                                 ->get();

        return view('User.detail_checkout', compact('checkout', 'jadwals', 'pembayaran'));
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

            // 2. Free up jadwals
            Jadwal::where('checkout_id', $checkout->id)
                 ->update([
                     'status' => 'tersedia',
                     'checkout_id' => null
                 ]);

            // 3. Create cancellation record in riwayat
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
