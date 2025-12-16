<?php

namespace App\Http\Controllers\Admin\Fasilitas;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Jadwal;
use App\Models\Fasilitas;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class JadwalController extends Controller
{
    /**
     * Halaman Manajemen Jadwal
     */
    public function index(Request $request)
    {
        // Ambil fasilitas milik admin ini
        $myFasilitas = Fasilitas::where('admin_fasilitas_id', Auth::id())->get();
        $fasilitasIds = $myFasilitas->pluck('id');

        $query = Jadwal::with(['fasilitas', 'checkouts.user'])
                    ->whereIn('fasilitas_id', $fasilitasIds);

        // Filter pencarian
        if ($request->filled('fasilitas_id')) {
            $query->where('fasilitas_id', $request->fasilitas_id);
        }
        if ($request->filled('tanggal')) {
            $query->whereDate('tanggal', $request->tanggal);
        }

        $jadwals = $query->orderBy('tanggal', 'desc')
                         ->orderBy('jam_mulai', 'asc')
                         ->paginate(20)->withQueryString();

        return view('Admin.Fasilitas.Jadwal.index', compact('jadwals', 'myFasilitas'));
    }

    /**
     * Generate Jadwal Massal
     */
    public function generate(Request $request)
    {
        // 1. Validasi Input 
        $request->validate([
            'fasilitas_id' => 'required|exists:fasilitas,id',
            'tgl_mulai' => 'required|date|after_or_equal:today',
            'tgl_selesai' => 'required|date|after_or_equal:tgl_mulai',
            'jam_buka' => 'required',
            'jam_tutup' => 'required',
            'durasi' => 'required|integer|min:1',
        ]);

        // 2. Ambil Data Fasilitas
        $fasilitas = Fasilitas::where('id', $request->fasilitas_id)
                        ->where('admin_fasilitas_id', Auth::id())
                        ->firstOrFail();

        // === [LOGIC BARU] CEK STATUS FASILITAS ===
        // Jika fasilitas NONAKTIF atau MAINTENANCE, tolak generate jadwal.
        if ($fasilitas->ketersediaan !== 'aktif') {
            return back()->with('error', 'Gagal Generate: Status fasilitas saat ini sedang ' . ucfirst($fasilitas->ketersediaan) . '. Silakan aktifkan fasilitas terlebih dahulu di menu Data Fasilitas.');
        }

        // === [TAMBAHAN] CEK STATUS APPROVAL (Opsional tapi bagus) ===
        if ($fasilitas->status_approval !== 'approved') {
             return back()->with('error', 'Gagal Generate: Fasilitas belum disetujui oleh Kepala Dinas.');
        }

        // Pastikan fasilitas milik admin ini
        $fasilitas = Fasilitas::where('id', $request->fasilitas_id)
                        ->where('admin_fasilitas_id', Auth::id())
                        ->firstOrFail();

        $start = Carbon::parse($request->tgl_mulai);
        $end   = Carbon::parse($request->tgl_selesai);
        $buka  = (int) substr($request->jam_buka, 0, 2);
        $tutup = (int) substr($request->jam_tutup, 0, 2);
        $durasi = (int) $request->durasi;

        $count = 0;
        $dataInsert = [];

        for ($date = $start; $date->lte($end); $date->addDay()) {
            for ($h = $buka; $h < $tutup; $h += $durasi) {
                $jamMulai = sprintf('%02d:00:00', $h);
                $jamSelesai = sprintf('%02d:00:00', $h + $durasi);

                // Cek duplikat
                $exists = Jadwal::where('fasilitas_id', $fasilitas->id)
                            ->where('tanggal', $date->format('Y-m-d'))
                            ->where('jam_mulai', $jamMulai)
                            ->exists();

                if (!$exists) {
                    $dataInsert[] = [
                        'fasilitas_id' => $fasilitas->id,
                        'tanggal'      => $date->format('Y-m-d'),
                        'jam_mulai'    => $jamMulai,
                        'jam_selesai'  => $jamSelesai,
                        'status'       => 'tersedia',
                        'created_at'   => now(),
                        'updated_at'   => now(),
                    ];
                    $count++;
                }
            }
        }

        if ($count > 0) {
            // Insert batch biar cepat (maks 500 per batch)
            foreach (array_chunk($dataInsert, 500) as $chunk) {
                Jadwal::insert($chunk);
            }
        }

        return back()->with('success', "Berhasil membuat $count slot jadwal.");
    }

    /**
     * Hapus Massal (Bulk Delete)
     */
    public function bulkDestroy(Request $request)
    {
        $ids = $request->ids;
        if (!$ids) return response()->json(['error' => 'Tidak ada data dipilih'], 400);

        // Hanya hapus yang statusnya 'tersedia' atau 'batal'
        // Jangan hapus yang 'terbooking'
        $deleted = Jadwal::whereIn('id', $ids)
                    ->whereIn('status', ['tersedia', 'batal'])
                    ->delete();

        return response()->json(['success' => "$deleted jadwal berhasil dihapus."]);
    }
}
