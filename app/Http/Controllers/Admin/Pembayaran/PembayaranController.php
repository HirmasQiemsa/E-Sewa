<?php

namespace App\Http\Controllers\Admin\Pembayaran;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pemasukan;
use App\Models\Checkout;
use App\Models\Jadwal;
use App\Models\Fasilitas;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PembayaranController extends Controller
{
    /**
     * Menampilkan daftar pembayaran yang perlu diverifikasi
     * Dengan perbaikan untuk menghindari duplikasi dan menampilkan jumlah yang benar
     */
    public function index(Request $request)
    {
        // Filter pembayaran berdasarkan status dan tanggal
        $status = $request->input('status', 'all');
        $filter = $request->input('filter', 'all');
        $search = $request->input('search');

        // Dasar query untuk checkout (bukan Pemasukan langsung)
        // Ini memastikan kita menampilkan 1 booking per baris, bukan per slot jadwal
        $query = Checkout::with([
                    'user',
                    'jadwals' => function($q) {
                        $q->withTrashed(); // Ambil jadwal meskipun sudah dihapus
                    },
                    'jadwals.fasilitas' => function($q) {
                        $q->withTrashed(); // Ambil fasilitas meskipun sudah dihapus
                    },
                    'pembayaran' => function($q) {
                        $q->orderBy('created_at', 'desc');
                    }
                ])
                ->orderBy('created_at', 'desc');

        // Terapkan filter status
        if ($status !== 'all') {
            $query->where('status', $status);
        }

        // Terapkan filter tanggal
        if ($filter === 'today') {
            $query->whereDate('created_at', Carbon::today());
        } elseif ($filter === 'week') {
            $query->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
        } elseif ($filter === 'month') {
            $query->whereMonth('created_at', Carbon::now()->month)
                ->whereYear('created_at', Carbon::now()->year);
        }

        // Terapkan pencarian
        if ($search) {
            $query->where(function($q) use ($search) {
                // Cari berdasarkan nama user atau email
                $q->whereHas('user', function($userQ) use ($search) {
                    $userQ->where('name', 'LIKE', "%{$search}%")
                        ->orWhere('email', 'LIKE', "%{$search}%");
                })
                // Cari berdasarkan nama fasilitas (termasuk yang terhapus)
                ->orWhereHas('jadwals.fasilitas', function($fasQ) use ($search) {
                    $fasQ->where('nama_fasilitas', 'LIKE', "%{$search}%")
                        ->withTrashed();
                })
                // Cari berdasarkan ID atau total bayar
                ->orWhere('id', 'LIKE', "%{$search}%")
                ->orWhere('total_bayar', 'LIKE', "%{$search}%");
            });
        }

        // Paginasi hasil
        $checkouts = $query->paginate(15)->withQueryString();

        // Persiapkan data tambahan untuk setiap checkout
        foreach ($checkouts as $checkout) {
            // Hitung jumlah yang sudah dibayar
            $dibayar = $checkout->pembayaran()
                        ->whereIn('status', ['kompensasi', 'lunas'])
                        ->sum('jumlah_bayar');

            $checkout->total_dibayar = $dibayar;
            $checkout->sisa_pembayaran = $checkout->total_bayar - $dibayar;
            $checkout->persen_dibayar = $checkout->total_bayar > 0 ?
                           round(($dibayar / $checkout->total_bayar) * 100) : 0;
                    // With this code:
                    if ($checkout->status == 'lunas') {
                        $checkout->persen_dibayar = 100; // Force 100% if status is lunas
                    } else {
                        $checkout->persen_dibayar = $checkout->total_bayar > 0 ?
                                                round(($dibayar / $checkout->total_bayar) * 100) : 0;
                    }

            // Tentukan fasilitas dari jadwal pertama
            // Ini aman karena satu checkout selalu untuk fasilitas yang sama
            if ($checkout->jadwals->isNotEmpty()) {
                $checkout->fasilitas = $checkout->jadwals->first()->fasilitas;

                // Jika fasilitas telah dihapus, tambahkan penanda
                if ($checkout->fasilitas && $checkout->fasilitas->deleted_at) {
                    $checkout->fasilitas->nama_display = $checkout->fasilitas->nama_fasilitas . ' (tidak aktif)';
                } else if ($checkout->fasilitas) {
                    $checkout->fasilitas->nama_display = $checkout->fasilitas->nama_fasilitas;
                }
            }

            // Hitung total durasi dari semua jadwal
            $totalDurasi = 0;
            foreach ($checkout->jadwals as $jadwal) {
                $mulaiParts = explode(':', $jadwal->jam_mulai);
                $selesaiParts = explode(':', $jadwal->jam_selesai);
                $mulaiJam = (int)$mulaiParts[0];
                $selesaiJam = (int)$selesaiParts[0];
                $totalDurasi += ($selesaiJam - $mulaiJam);
            }
            $checkout->total_durasi = $totalDurasi;

            // Ambil tanggal booking (dari jadwal pertama)
            if ($checkout->jadwals->isNotEmpty()) {
                $checkout->tanggal_booking = $checkout->jadwals->first()->tanggal;
            } else {
                $checkout->tanggal_booking = null;
            }
        }

        return view('AdminPembayaran.Pembayaran.index', compact('checkouts', 'status', 'filter', 'search'));
    }

    /**
     * Menampilkan detail pembayaran berdasarkan checkout_id
     */
    public function show($id)
    {
        $checkout = Checkout::with([
                'user',
                'jadwals' => function($q) {
                    $q->withTrashed(); // Ambil jadwal meskipun sudah dihapus
                },
                'jadwals.fasilitas' => function($q) {
                    $q->withTrashed(); // Ambil fasilitas meskipun sudah dihapus
                },
                'pembayaran' => function($q) {
                    $q->orderBy('created_at', 'desc');
                }
            ])
            ->findOrFail($id);

        // Hitung jumlah yang sudah dibayar
        $dibayar = $checkout->pembayaran()
                    ->whereIn('status', ['kompensasi', 'lunas'])
                    ->sum('jumlah_bayar');

        $checkout->total_dibayar = $dibayar;
        $checkout->sisa_pembayaran = $checkout->total_bayar - $dibayar;
        $checkout->persen_dibayar = $checkout->total_bayar > 0 ?
                           round(($dibayar / $checkout->total_bayar) * 100) : 0;

// With this code:
if ($checkout->status == 'lunas') {
    $checkout->persen_dibayar = 100; // Force 100% if status is lunas
} else {
    $checkout->persen_dibayar = $checkout->total_bayar > 0 ?
                              round(($dibayar / $checkout->total_bayar) * 100) : 0;
}

        // Tentukan fasilitas dari jadwal pertama
        if ($checkout->jadwals->isNotEmpty() && $checkout->jadwals->first()->fasilitas) {
            $fasilitas = $checkout->jadwals->first()->fasilitas;

            // Jika fasilitas telah dihapus, tambahkan penanda
            if ($fasilitas->deleted_at) {
                $fasilitas->nama_display = $fasilitas->nama_fasilitas . ' (tidak aktif)';
            } else {
                $fasilitas->nama_display = $fasilitas->nama_fasilitas;
            }
        }

        // Hitung total durasi
        $totalDurasi = 0;
        foreach ($checkout->jadwals as $jadwal) {
            $mulaiParts = explode(':', $jadwal->jam_mulai);
            $selesaiParts = explode(':', $jadwal->jam_selesai);
            $mulaiJam = (int)$mulaiParts[0];
            $selesaiJam = (int)$selesaiParts[0];
            $totalDurasi += ($selesaiJam - $mulaiJam);
        }
        $checkout->total_durasi = $totalDurasi;

        return view('AdminPembayaran.Pembayaran.show', compact('checkout'));
    }

    // Sisanya sama seperti sebelumnya...
    /**
     * Verifikasi pembayaran - perbaikan untuk memastikan semua terkait terupdate
     */
    public function verifikasi(Request $request, $id)
    {
        $request->validate([
            'catatan' => 'nullable|string',
            'checkout_id' => 'required|exists:checkouts,id'
        ]);

        DB::beginTransaction();
        try {
            // Ambil data pembayaran
            $pembayaran = Pemasukan::findOrFail($id);
            $checkout = Checkout::findOrFail($request->checkout_id);

            // Update status pembayaran menjadi lunas
            $pembayaran->status = 'lunas';
            $pembayaran->admin_id = Auth::id();

            // Simpan catatan jika ada
            if ($request->filled('catatan')) {
                $pembayaran->keterangan = $request->catatan;
            }

            $pembayaran->save();

            // Update status checkout menjadi lunas
            $checkout->status = 'lunas';
            $checkout->save();

            // Pastikan semua jadwal yang terkait status-nya diupdate menjadi 'terbooking'
            foreach ($checkout->jadwals as $jadwal) {
                if ($jadwal->status != 'selesai') { // Jangan ubah yang sudah selesai
                    $jadwal->status = 'terbooking';
                    $jadwal->save();
                }
            }

            DB::commit();
            return redirect()->route('petugas_pembayaran.pembayaran.index')
                        ->with('success', 'Pembayaran berhasil diverifikasi dan booking diupdate menjadi LUNAS');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Gagal memverifikasi pembayaran: ' . $e->getMessage());
        }
    }

    /**
     * Tolak pembayaran
     */
    public function tolak(Request $request, $id)
    {
        $request->validate([
            'alasan_tolak' => 'required|string',
            'checkout_id' => 'required|exists:checkouts,id'
        ]);

        DB::beginTransaction();
        try {
            // Find the payment record
            $pembayaran = Pemasukan::findOrFail($id);
            $checkout = Checkout::findOrFail($request->checkout_id);

            // Update payment status to 'ditolak'
            $pembayaran->status = 'ditolak';
            $pembayaran->keterangan = $request->alasan_tolak;
            $pembayaran->admin_id = Auth::id();
            $pembayaran->save();

            // Tidak mengubah status checkout - tetap 'kompensasi' karena pelunasan ditolak

            DB::commit();
                return redirect()->route('petugas_pembayaran.pembayaran.index')
                            ->with('success', 'Pembayaran ditolak dengan alasan: ' . $request->alasan_tolak);
            } catch (\Exception $e) {
                DB::rollback();
                return redirect()->back()->with('error', 'Gagal menolak pembayaran: ' . $e->getMessage());
            }
    }

    /**
     * Toggle status pembayaran - diperbarui untuk update semua terkait
     */
    public function toggleStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:fee,lunas,ditolak,pending',
            'checkout_id' => 'required|exists:checkouts,id'
        ]);

        try {
            DB::beginTransaction();

            $pembayaran = Pemasukan::findOrFail($id);
            $checkout = Checkout::findOrFail($request->checkout_id);
            $newStatus = $request->status;

            $pembayaran->status = $newStatus;
            $pembayaran->admin_id = Auth::id();
            $pembayaran->save();

            // Update checkout jika status pembayaran adalah lunas
            if ($newStatus === 'lunas') {
                $checkout->status = 'lunas';
                $checkout->save();

                // Update semua jadwal terkait
                foreach ($checkout->jadwals as $jadwal) {
                    if ($jadwal->status != 'selesai') {
                        $jadwal->status = 'terbooking';
                        $jadwal->save();
                    }
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Status pembayaran berhasil diubah menjadi ' . $newStatus
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Gagal mengubah status: ' . $e->getMessage()
            ], 500);
        }
    }
}
