<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Checkout;
use App\Models\Fasilitas;
use App\Models\Jadwal;
use App\Models\Pemasukan;
use App\Models\ActivityLog;
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
        // Dapatkan ID fasilitas dari parameter URL
        $id = request('id');

        // Filter fasilitas berdasarkan ID jika ada
        if ($id) {
            $fasilitas = Fasilitas::where('id', $id)
                                ->where('ketersediaan', 'aktif')
                                ->whereNull('deleted_at')
                                ->get();
        } else {
            // Jika tidak ada ID, tampilkan semua fasilitas aktif
            $fasilitas = Fasilitas::where('ketersediaan', 'aktif')
                                ->whereNull('deleted_at')
                                ->get();
        }

        // Dapatkan booking pengguna
        $checkouts = Checkout::with(['jadwals', 'jadwals.fasilitas'])
                        ->where('user_id', Auth::id())
                        ->whereIn('status', ['kompensasi', 'lunas'])
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

        return view('User.Riwayat.index', compact('fasilitas', 'checkouts'));
    }

    /**
     * Store a new checkout
     */
    public function store(Request $request)
    {
        // 1. Validasi Input (Sudah Oke)
        $request->validate([
            'fasilitas_id' => 'required|exists:fasilitas,id',
            'tanggal_booking' => 'required|date|after_or_equal:today',
            'jadwal_ids' => 'required|array|min:1',
            'jadwal_ids.*' => 'exists:jadwals,id',
            'total_durasi' => 'required|integer|min:1',
            'total_bayar' => 'required|numeric|min:1',
            'dp_value' => 'required|numeric|min:1',
        ]);

        // 2. Cek Ketersediaan (Sudah Oke)
        $jadwals = Jadwal::whereIn('id', $request->jadwal_ids)->get();
        foreach ($jadwals as $jadwal) {
            if ($jadwal->status !== 'tersedia') {
                return back()->with('error', 'Maaf, salah satu jadwal yang dipilih baru saja dibooking orang lain.');
            }
        }

        DB::beginTransaction();
        try {
            // 3. Buat Data Checkout
            $checkout = Checkout::create([
                'user_id' => Auth::id(),
                'jadwals_id' => $request->jadwal_ids[0],
                'total_bayar' => $request->total_bayar,
                'status' => 'kompensasi',
            ]);

            // 4. Update Status Jadwal
            foreach ($jadwals as $jadwal) {
                $checkout->jadwals()->attach($jadwal->id);
                $jadwal->update(['status' => 'terbooking']);
            }

            // 5. Catat Tagihan DP
            Pemasukan::create([
                'checkout_id' => $checkout->id,
                'fasilitas_id' => $request->fasilitas_id,
                'user_id' => Auth::id(),
                'tanggal_bayar' => now(),
                'jumlah_bayar' => $request->dp_value,
                'metode_pembayaran' => 'transfer',
                'status' => 'lunas', // DP awal dianggap lunas (Logic bisnis Anda)
            ]);

            // 6. [PERBAIKAN] Gunakan Helper Log agar konsisten
            $this->recordLog($checkout, 'CREATE', 'User membuat pesanan baru #' . $checkout->id);

            DB::commit();

            return redirect()->route('user.riwayat')
                ->with('success', 'Fasilitas Berhasil Dibooking! <br>Jangan lupa pelunasan ya.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
        }
    }

    /**
     * Show payment completion form
     */
    public function pelunasan($id)
    {
        $checkout = Checkout::with(['jadwals.fasilitas', 'user'])
                          ->where('id', $id)
                          ->where('user_id', Auth::id())
                          ->where('status', 'kompensasi')
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
                        ->where('status', 'kompensasi')
                        ->firstOrFail();

        // Validate request
        $request->validate([
            'metode_pembayaran' => 'required|in:transfer',
        ]);

        // Calculate remaining payment
        $sisaPembayaran = $checkout->total_bayar * 0.5;

        DB::beginTransaction();
        try {
            // Record payment with 'pending' status
            Pemasukan::create([
                'checkout_id' => $checkout->id,
                'fasilitas_id' => $checkout->jadwal->fasilitas_id,
                'tanggal_bayar' => now(),
                'jumlah_bayar' => $sisaPembayaran,
                'metode_pembayaran' => $request->metode_pembayaran,
                'status' => 'pending' // Status pending menunggu verifikasi petugas
            ]);

            DB::commit();
            return redirect()->route('user.riwayat')
                ->with('success', 'Pembayaran telah direkam dan sedang menunggu verifikasi oleh petugas.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Proses Upload Bukti Pembayaran (DP atau Pelunasan)
     */
    public function uploadBukti(Request $request, $id)
    {
        $request->validate([
            'bukti_bayar' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $checkout = Checkout::with('jadwals')->where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        DB::beginTransaction();
        try {
            $path = $request->file('bukti_bayar')->store('bukti_bayar', 'public');

            $jenis = ($checkout->status == 'kompensasi') ? 'DP' : 'Pelunasan';
            // Logic hitung jumlah (Sederhana: 50% dari total)
            $jumlah = $checkout->total_bayar * 0.5;

            // Simpan Record Pembayaran
            Pemasukan::create([
                'checkout_id'       => $checkout->id,
                'fasilitas_id'      => $checkout->jadwals->first()->fasilitas_id ?? null,
                'user_id'           => Auth::id(),
                'tanggal_bayar'     => now(),
                'jumlah_bayar'      => $jumlah,
                'metode_pembayaran' => 'transfer',
                'bukti_bayar'       => $path,
                'keterangan'        => "Pembayaran $jenis via Upload User",
                'status'            => 'pending'
            ]);

            // Update Status Checkout
            $checkout->update(['status' => 'pending']);

            // [PERBAIKAN] Gunakan Helper Log
            $this->recordLog($checkout, 'UPLOAD', 'User mengupload bukti pembayaran untuk #' . $checkout->id);

            DB::commit();

            return redirect()->back()->with('success', 'Transaksi Pelunasan Berhasil!<br>Mohon tunggu validasi.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal upload: ' . $e->getMessage());
        }
    }

    /**
     * Show checkout details
     */
    public function detail($id)
    {
        // 1. Ambil Data Checkout
        $checkout = Checkout::with(['jadwals.fasilitas', 'user'])
            ->where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        // 2. Ambil List Jadwal
        $jadwals = $checkout->jadwals->sortBy('jam_mulai');

        // 3. Logic View Helper
        $jadwalPertama = $jadwals->first();
        $fasilitas     = $jadwalPertama ? $jadwalPertama->fasilitas : null;
        $jamMulai      = $jadwals->min('jam_mulai');
        $jamSelesai    = $jadwals->max('jam_selesai');
        $totalDurasi   = $jadwals->count();

        // 4. Riwayat Pembayaran (Ganti sorting pakai created_at agar lebih stabil)
        $pembayaran = Pemasukan::where('checkout_id', $checkout->id)
            ->orderBy('created_at', 'asc')
            ->get();

        // --- [NEW] LOGIKA KEUANGAN DIPINDAHKAN KESINI ---
        // Hitung total uang yang sudah diverifikasi (LUNAS)
        $sudahBayar = $pembayaran->where('status', 'lunas')->sum('jumlah_bayar');

        // Hitung sisa tagihan (Pastikan tidak minus, minimal 0)
        $sisaTagihan = max(0, $checkout->total_bayar - $sudahBayar);

        // Hitung harga per jam untuk ditampilkan di view
        $hargaPerJam = $totalDurasi > 0 ? $checkout->total_bayar / $totalDurasi : 0;

        // Hitung total uang dengan status pending
        $uangPending = $pembayaran->where('status', 'pending')->sum('jumlah_bayar');

        // Total tagihan asli
        $totalTagihan = $checkout->total_bayar;

        // Sisa akhir yang harus dibayar user (setelah memperhitungkan pending)
        $sisaAkhir = $totalTagihan - ($sudahBayar + $uangPending);

        // ------------------------------------------------

        // 5. Kirim ke View (Tambahkan variabel baru)
        return view('User.Checkout.detail', compact(
            'checkout',
            'jadwals',
            'pembayaran',
            'fasilitas',
            'jadwalPertama',
            'jamMulai',
            'jamSelesai',
            'totalDurasi',
            'sudahBayar',
            'hargaPerJam',
            'uangPending',
            'totalTagihan',
            'sisaAkhir',
            'sisaTagihan'
        ));
    }

    /**
     * Cancel a booking
     */
    public function cancel(Request $request, $id)
    {
        $checkout = Checkout::with('jadwals')
            ->where('id', $id)
            ->where('user_id', Auth::id())
            ->where('status', 'kompensasi')
            ->firstOrFail();

        DB::beginTransaction();
        try {
            // Update Status Checkout
            $checkout->update(['status' => 'batal']);

            // Kembalikan Status Jadwal
            foreach ($checkout->jadwals as $jadwal) {
                $jadwal->update(['status' => 'tersedia']);
            }

            // [PERBAIKAN] Gunakan Helper Log
            $this->recordLog($checkout, 'CANCEL', 'User membatalkan pesanan #' . $checkout->id);

            DB::commit();
            return redirect()->route('user.riwayat')->with('success', 'Booking berhasil dibatalkan.');

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
