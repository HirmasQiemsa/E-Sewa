<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Checkout;
use App\Models\Fasilitas;
use App\Models\Jadwal;
use App\Models\Pemasukan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BookingController extends Controller
{
    /**
     * Halaman Detail Fasilitas
     */
    public function show(Request $request, $id)
    {
        // 1. Ambil Data Fasilitas
        // Gunakan findOrFail agar jika ID ngawur langsung 404
        $fasilitas = Fasilitas::findOrFail($id);

        // 2. [BARU] CEK STATUS KETERSEDIAAN (VALIDASI BACKEND)
        // Jika status bukan 'aktif', tendang user kembali ke beranda
        if ($fasilitas->ketersediaan !== 'aktif') {
            return redirect()->route('user.fasilitas')
                ->with('error', 'Maaf, fasilitas ini sedang tidak aktif atau dalam perbaikan.');
        }

        // 3. Ambil Jadwal (Hanya load jadwal jika lolos validasi di atas)
        // Load jadwal mulai hari ini ke depan
        $fasilitas->load(['jadwals' => function($q) {
            $q->where('tanggal', '>=', \Carbon\Carbon::today())
              ->where('status', 'tersedia')
              ->orderBy('tanggal')
              ->orderBy('jam_mulai');
        }]);

        // Cek apakah user request tanggal spesifik dari kalender?
        // Jika ada parameter ?date=2025-10-10, kita bisa filter tampilan di view nanti
        $selectedDate = $request->query('date', null);

        return view('User.Fasilitas.detail', compact('fasilitas', 'selectedDate'));
    }


    // NEW METHOD UNTUK BOOKING
    public function detail(Request $request, $id)
{
    $fasilitas = Fasilitas::findOrFail($id);

    if ($fasilitas->ketersediaan !== 'aktif') {
        return redirect()->route('user.fasilitas')->with('error', 'Fasilitas tidak aktif.');
    }

    // Ambil tanggal dari request atau default hari ini
    $date = $request->query('date', now()->format('Y-m-d'));

    // Cek Range Harga untuk Badge
    $minPrice = $fasilitas->harga_sewa; // Default
    $maxPrice = $fasilitas->harga_sewa; // Default

    $jadwals = Jadwal::where('fasilitas_id', $id)->whereDate('tanggal', $date)->get();
    if($jadwals->isNotEmpty()) {
       $minPrice = $jadwals->min('harga_per_slot') ?? $fasilitas->harga_sewa;
       $maxPrice = $jadwals->max('harga_per_slot') ?? $fasilitas->harga_sewa;
    }

    return view('User.checkout', compact('fasilitas', 'date', 'minPrice', 'maxPrice'));
}

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
}
