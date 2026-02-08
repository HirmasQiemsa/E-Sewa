<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Checkout;
use App\Models\Fasilitas;
use App\Models\Jadwal;
use App\Models\Pemasukan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    public function index()
    {
        $id = request('id');
        $fasilitasQuery = Fasilitas::where('ketersediaan', 'aktif')->whereNull('deleted_at');

        if ($id) {
            $fasilitas = $fasilitasQuery->where('id', $id)->get();
        } else {
            $fasilitas = $fasilitasQuery->get();
        }

        // Ambil history booking user
        $checkouts = Checkout::with(['jadwals', 'jadwals.fasilitas'])
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        // Hitung durasi untuk tampilan history
        foreach ($checkouts as $checkout) {
            $checkout->totalDurasi = $checkout->jadwals->count(); 
        }

        return view('User.Riwayat.index', compact('fasilitas', 'checkouts'));
    }

    /**
     * SATU PINTU: Booking, Lock Jadwal, & Upload Bukti
     */
    public function store(Request $request)
    {
        // 1. Validasi Input Lengkap (Termasuk Bukti Bayar)
        $request->validate([
            'fasilitas_id'    => 'required|exists:fasilitas,id',
            'tanggal_booking' => 'required|date|after_or_equal:today',
            'jadwal_ids'      => 'required|array|min:1',
            'jadwal_ids.*'    => 'exists:jadwals,id',
            'total_bayar'     => 'required|numeric|min:1',
            // Validasi file gambar wajib ada sekarang
            'bukti_bayar'     => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // 2. Cek Ketersediaan Real-time
        $jadwals = Jadwal::whereIn('id', $request->jadwal_ids)->get();
        foreach ($jadwals as $jadwal) {
            if ($jadwal->status !== 'tersedia') {
                return back()->with('error', 'Waduh, telat dikit! Salah satu jam yang dipilih baru aja diambil orang lain.');
            }
        }

        DB::beginTransaction();
        try {
            // 3. Upload Bukti Transfer
            $path = $request->file('bukti_bayar')->store('bukti_bayar', 'public');

            // 4. Buat Data Checkout (Status Pending - Nunggu Admin Cek)
            $checkout = Checkout::create([
                'user_id'     => Auth::id(),
                'jadwals_id'  => $request->jadwal_ids[0], // ID jadwal pertama sebagai referensi
                'total_bayar' => $request->total_bayar,
                'status'      => 'pending',
            ]);

            // 5. Update Status Jadwal jadi 'terbooking'
            foreach ($jadwals as $jadwal) {
                $checkout->jadwals()->attach($jadwal->id);
                $jadwal->update(['status' => 'terbooking']);
            }

            // 6. Catat Pemasukan (Langsung Lunas tapi Pending Validasi)
            Pemasukan::create([
                'checkout_id'       => $checkout->id,
                'fasilitas_id'      => $request->fasilitas_id,
                'user_id'           => Auth::id(),
                'tanggal_bayar'     => now(),
                'jumlah_bayar'      => $request->total_bayar, // Full Payment
                'metode_pembayaran' => 'transfer',
                'bukti_bayar'       => $path,
                'keterangan'        => 'Booking Baru (Menunggu Validasi)',
                'status'            => 'pending'
            ]);

            // 7. Log Aktivitas
            // $this->recordLog($checkout, 'CREATE', 'User booking baru & upload bukti #' . $checkout->id);

            DB::commit();

            return redirect()->route('user.riwayat') // Pastikan route ini benar
                ->with('success', 'Booking Berhasil Dikirim! <br>Admin akan mengecek bukti transfermu secepatnya.');

        } catch (\Exception $e) {
            DB::rollBack();
            // Hapus file jika database gagal biar server gak penuh sampah
            if (isset($path)) \Illuminate\Support\Facades\Storage::disk('public')->delete($path);

            return back()->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
        }
    }

    // Detail & Cancel tetap diperlukan
    public function detail($id)
    {
        $checkout = Checkout::with(['jadwals.fasilitas', 'user', 'pemasukan']) // Eager load pemasukan
            ->where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $jadwals = $checkout->jadwals->sortBy('jam_mulai');
        $fasilitas = $jadwals->first()->fasilitas ?? null;

        // Data simpel untuk view
        return view('User.Checkout.detail', compact('checkout', 'jadwals', 'fasilitas'));
    }

    public function cancel(Request $request, $id)
    {
        // Hanya bisa cancel kalau masih pending
        $checkout = Checkout::with('jadwals')
            ->where('id', $id)
            ->where('user_id', Auth::id())
            ->where('status', 'pending')
            ->firstOrFail();

        DB::beginTransaction();
        try {
            $checkout->update(['status' => 'batal']);
            foreach ($checkout->jadwals as $jadwal) {
                $jadwal->update(['status' => 'tersedia']);
            }
            DB::commit();
            return redirect()->route('user.riwayat')->with('success', 'Booking berhasil dibatalkan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal membatalkan: ' . $e->getMessage());
        }
    }

    // API check jadwal tetap sama
    public function checkJadwal($fasilitasId, $tanggal)
    {
        $jadwals = Jadwal::where('fasilitas_id', $fasilitasId)
            ->where('tanggal', $tanggal)
            ->orderBy('jam_mulai', 'asc')
            ->get();
        return response()->json($jadwals);
    }
}
