<?php

namespace App\Http\Controllers\PetugasPembayaran;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PemasukanSewa;
use App\Models\Checkout;
use App\Models\Jadwal;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PembayaranController extends Controller
{
    /**
     * Menampilkan daftar pembayaran yang perlu diverifikasi
     */
    public function index(Request $request)
    {
        // Filter pembayaran berdasarkan status dan tanggal
        $status = $request->input('status', 'all');
        $filter = $request->input('filter', 'all');
        $search = $request->input('search');

        // Query dasar dengan relasi
        $query = PemasukanSewa::with(['checkout.user', 'fasilitas'])
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
                $q->whereHas('checkout.user', function($userQ) use ($search) {
                    $userQ->where('name', 'LIKE', "%{$search}%")
                         ->orWhere('email', 'LIKE', "%{$search}%");
                })
                ->orWhereHas('fasilitas', function($fasQ) use ($search) {
                    $fasQ->where('nama_fasilitas', 'LIKE', "%{$search}%");
                })
                ->orWhere('jumlah_bayar', 'LIKE', "%{$search}%")
                ->orWhere('metode_pembayaran', 'LIKE', "%{$search}%");
            });
        }

        // Paginasi hasil
        $pembayaran = $query->paginate(10)->withQueryString();

        return view('PetugasPembayaran.Pembayaran.index', compact('pembayaran', 'status', 'filter', 'search'));
    }

    /**
     * Menampilkan detail pembayaran
     */
    public function show($id)
    {
        $pembayaran = PemasukanSewa::with(['checkout.user', 'fasilitas', 'checkout.jadwals'])
                                 ->findOrFail($id);

        return view('PetugasPembayaran.Pembayaran.show', compact('pembayaran'));
    }

    /**
     * Verifikasi pembayaran
     */
    public function verifikasi(Request $request, $id)
    {
        $request->validate([
            'catatan' => 'nullable|string'
        ]);

        DB::beginTransaction();
        try {
            $pembayaran = PemasukanSewa::findOrFail($id);
            $checkout = $pembayaran->checkout;

            // Update status pembayaran
            $pembayaran->status = $pembayaran->status === 'fee' ? 'fee' : 'lunas';
            $pembayaran->petugas_pembayaran_id = Auth::id();
            $pembayaran->save();

            // Jika ini pelunasan, update status checkout menjadi lunas
            if ($pembayaran->jumlah_bayar == ($checkout->total_bayar * 0.5) && $checkout->status == 'fee') {
                $checkout->status = 'lunas';
                $checkout->save();
            }

            DB::commit();
            return redirect()->route('petugas_pembayaran.pembayaran.index')
                           ->with('success', 'Pembayaran berhasil diverifikasi');
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
            'alasan_tolak' => 'required|string'
        ]);

        DB::beginTransaction();
        try {
            $pembayaran = PemasukanSewa::findOrFail($id);

            // Update status pembayaran
            $pembayaran->status = 'ditolak';
            $pembayaran->keterangan = $request->alasan_tolak;
            $pembayaran->petugas_pembayaran_id = Auth::id();
            $pembayaran->save();

            DB::commit();
            return redirect()->route('petugas_pembayaran.pembayaran.index')
                           ->with('success', 'Pembayaran ditolak dengan alasan: ' . $request->alasan_tolak);
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Gagal menolak pembayaran: ' . $e->getMessage());
        }
    }

    /**
     * Toggle status pembayaran (untuk AJAX)
     */
    public function toggleStatus(Request $request, $id)
    {
        try {
            $pembayaran = PemasukanSewa::findOrFail($id);
            $newStatus = $request->status;

            $pembayaran->status = $newStatus;
            $pembayaran->petugas_pembayaran_id = Auth::id();
            $pembayaran->save();

            // Update checkout jika perlu
            if ($newStatus === 'lunas' && $pembayaran->checkout->status === 'fee') {
                $pembayaran->checkout->status = 'lunas';
                $pembayaran->checkout->save();
            }

            return response()->json([
                'success' => true,
                'message' => 'Status pembayaran berhasil diubah menjadi ' . $newStatus
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengubah status: ' . $e->getMessage()
            ], 500);
        }
    }
}
