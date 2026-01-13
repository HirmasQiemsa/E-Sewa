<?php

namespace App\Http\Controllers\Admin\Pembayaran;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ActivityLog;
use App\Models\Checkout;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class VerifikasiController extends Controller
{
    public function index(Request $request)
    {
        // Default tampilkan 'pending' agar admin fokus ke tugas baru
        $status = $request->input('status', 'pending');
        $search = $request->input('search');

        $adminId = Auth::guard('admin')->id();

        // 1. QUERY DASAR & EAGER LOADING
        // Kita langsung chain 'with' dan 'latest'
        $query = Checkout::with([
            'user',
            'jadwals.fasilitas' => function($q) {
                $q->withTrashed(); // Jaga-jaga jika fasilitas dihapus soft-delete
            },
            'pemasukans' => function($q) {
                $q->latest();
            }
        ])->latest();

        // 2. [PENTING] FILTER HAK AKSES ADMIN
        // Hanya tampilkan checkout jika fasilitasnya dipegang oleh admin yang login
        $query->whereHas('jadwals.fasilitas', function($q) use ($adminId) {
            $q->where('admin_pembayaran_id', $adminId);
        });

        // 3. FILTER STATUS
        if ($status !== 'all') {
            // Jika ada status 'kompensasi' (DP), masukan logic OR jika perlu
            if ($status == 'kompensasi') {
                $query->whereIn('status', ['kompensasi', 'dp']);
            } else {
                $query->where('status', $status);
            }
        }

        // 4. FILTER PENCARIAN
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('kode_transaksi', 'LIKE', "%{$search}%") // Cari Kode Invoice
                  ->orWhereHas('user', function($u) use ($search) {
                      $u->where('name', 'LIKE', "%{$search}%"); // Cari Nama User
                  });
            });
        }

        // 5. PAGINATION
        $checkouts = $query->paginate(10)->withQueryString();

        return view('Admin.Pembayaran.Verifikasi.index', compact('checkouts', 'status', 'search'));
    }

    public function show($id)
    {
        // Eager load necessary relationships
        $checkout = Checkout::with(['user', 'jadwals.fasilitas', 'pemasukans'])->findOrFail($id);

        // Get the latest payment proof
        $pemasukanTerbaru = $checkout->pemasukans->last();
        $buktiBayar = $pemasukanTerbaru ? $pemasukanTerbaru->bukti_bayar : null;

        return view('Admin.Pembayaran.Verifikasi.show', compact('checkout', 'buktiBayar'));
    }

    public function verifikasi(Request $request, $id)
    {
        $request->validate(['catatan' => 'nullable|string']);

        DB::beginTransaction();
        try {
            $checkout = Checkout::findOrFail($id);

            // 1. Update Checkout Status -> Lunas (Fully Paid)
            $checkout->update(['status' => 'lunas']);

            // 2. Update Schedules -> Terbooking (Booked)
            foreach ($checkout->jadwals as $jadwal) {
                $jadwal->update(['status' => 'terbooking']);
            }

            // 3. Update the Payment Record (Pemasukan)
            // We verify the latest payment upload
            $pemasukan = $checkout->pemasukans->sortByDesc('created_at')->first();

            if ($pemasukan) {
                $pemasukan->update([
                    'status' => 'lunas', // Mark this specific payment as valid
                    'admin_id' => Auth::id(),
                    'keterangan' => $request->catatan
                ]);
            }

            ActivityLog::create([
                'admin_id'     => Auth::guard('admin')->id(),
                'action'       => 'APPROVE',
                'description'  => 'Admin memverifikasi pelunasan booking #' . $id,
                'subject_type' => Checkout::class, // Or Pemasukan::class
                'subject_id'   => $id
            ]);

            DB::commit();
            return redirect()->route('admin.keuangan.verifikasi.index')->with('success', 'Pembayaran Pelunasan Berhasil Diverifikasi.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal verifikasi: ' . $e->getMessage());
        }
    }

    public function tolak(Request $request, $id)
    {
        $request->validate(['alasan_tolak' => 'required|string']);

        $adminId = Auth::guard('admin')->id();

        // 1. Cari Checkout dengan Validasi Hak Akses Admin
        $checkout = Checkout::where('id', $id)
            ->whereHas('jadwals.fasilitas', function ($q) use ($adminId) {
                $q->where('admin_pembayaran_id', $adminId);
            })
            ->first();

        if (!$checkout) {
            return back()->with('error', 'Akses ditolak atau data tidak ditemukan.');
        }

        DB::beginTransaction();
        try {
            // 2. Ambil Bukti Pembayaran Terakhir
            $pemasukan = $checkout->pemasukans->sortByDesc('created_at')->first();

            if ($pemasukan) {
                // A. Update dulu datanya (Catat siapa yang nolak & alasannya)
                $pemasukan->update([
                    'status'     => 'ditolak',
                    'keterangan' => 'Ditolak: ' . $request->alasan_tolak,
                    'admin_id'   => $adminId
                ]);

                // B. Lakukan Soft Delete (Hapus dari list aktif)
                $pemasukan->delete();
            }

            // 3. Kembalikan Status Checkout ke 'kompensasi'
            // Agar tombol Upload di sisi User muncul kembali
            $checkout->update(['status' => 'kompensasi']);

            // 4. Catat Log
            $this->recordLog($checkout, 'REJECT', 'Admin menolak & menghapus bukti bayar #' . $id . '. Alasan: ' . $request->alasan_tolak);

            DB::commit();
            return redirect()->route('admin.keuangan.verifikasi.index')
                             ->with('success', 'Bukti pembayaran ditolak dan dihapus. User dapat mengupload ulang.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menolak: ' . $e->getMessage());
        }
    }
}
