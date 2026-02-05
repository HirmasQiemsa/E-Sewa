<?php

namespace App\Http\Controllers\Admin\Fasilitas;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Jadwal;
use App\Models\Fasilitas;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class JadwalController extends Controller
{
    public function index(Request $request)
    {
        $myFasilitas = Fasilitas::where('admin_fasilitas_id', Auth::id())->get();
        $fasilitasIds = $myFasilitas->pluck('id');

        $query = Jadwal::with(['fasilitas', 'checkouts.user'])
                    ->whereIn('fasilitas_id', $fasilitasIds);

        // Filter
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

    public function generate(Request $request)
    {
        $request->validate([
            'fasilitas_id' => 'required|exists:fasilitas,id',
            'tgl_mulai'    => 'required|date|after_or_equal:today',
            'tgl_selesai'  => 'required|date|after_or_equal:tgl_mulai', // Validasi backend juga penting
            'jam_buka'     => 'required',
            'jam_tutup'    => 'required',
            'durasi'       => 'required|integer|min:1',
        ]);

        $fasilitas = Fasilitas::where('id', $request->fasilitas_id)
                        ->where('admin_fasilitas_id', Auth::id())
                        ->firstOrFail();

        if ($fasilitas->ketersediaan !== 'aktif') {
            return back()->with('error', 'Gagal: Fasilitas sedang tidak aktif.');
        }

        $start = Carbon::parse($request->tgl_mulai);
        $end   = Carbon::parse($request->tgl_selesai);
        $buka  = (int) substr($request->jam_buka, 0, 2);
        $tutup = (int) substr($request->jam_tutup, 0, 2);
        $durasi = (int) $request->durasi;

        $count = 0;
        $dataInsert = [];

        for ($date = $start; $date->lte($end); $date->addDay()) {
            for ($h = $buka; $h < $tutup; $h += $durasi) {
                if (($h + $durasi) > $tutup) continue;

                $jamMulai   = sprintf('%02d:00:00', $h);
                $jamSelesai = sprintf('%02d:00:00', $h + $durasi);

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
            foreach (array_chunk($dataInsert, 500) as $chunk) {
                Jadwal::insert($chunk);
            }

            // === RECORD LOG ===
            // Karena insert batch gak return model, kita log subjectnya ke Fasilitasnya aja
            $this->recordLog(
                $fasilitas,
                'generate_jadwal',
                "Mengenerate $count slot jadwal baru dari tanggal " . $request->tgl_mulai . " s/d " . $request->tgl_selesai
            );

            return back()->with('success', "Berhasil membuat $count slot jadwal.");
        }

        return back()->with('warning', "Tidak ada jadwal baru (mungkin sudah ada slotnya).");
    }

    public function store(Request $request)
    {
        $request->validate([
            'fasilitas_id' => 'required|exists:fasilitas,id',
            'tanggal'      => 'required|date|after_or_equal:today', // Validasi backend
            'jam_mulai'    => 'required',
            'jam_selesai'  => 'required|after:jam_mulai',
        ]);

        $exists = Jadwal::where('fasilitas_id', $request->fasilitas_id)
            ->where('tanggal', $request->tanggal)
            ->where(function($q) use ($request) {
                $q->whereBetween('jam_mulai', [$request->jam_mulai, $request->jam_selesai])
                ->orWhereBetween('jam_selesai', [$request->jam_mulai, $request->jam_selesai]);
            })->exists();

        if ($exists) {
            return back()->with('error', 'Bentrok dengan jadwal lain.');
        }

        $jadwal = Jadwal::create([
            'fasilitas_id' => $request->fasilitas_id,
            'tanggal'      => $request->tanggal,
            'jam_mulai'    => $request->jam_mulai,
            'jam_selesai'  => $request->jam_selesai,
            'status'       => 'tersedia'
        ]);

        // === RECORD LOG ===
        $this->recordLog(
            $jadwal,
            'create_jadwal',
            "Menambah jadwal manual pada " . $jadwal->tanggal . " jam " . $jadwal->jam_mulai
        );

        return back()->with('success', 'Berhasil tambah jadwal.');
    }

    /**
     * Aksi Massal (Bulk Action) untuk Nonaktifkan/Aktifkan
     */
    public function bulkAction(Request $request)
    {
        $request->validate([
            'ids'    => 'required|array',
            'ids.*'  => 'exists:jadwals,id',
            'action' => 'required|in:aktifkan,nonaktifkan,hapus', // Tambahkan 'hapus' jika mau
        ]);

        $ids = $request->ids;
        $action = $request->action;
        $sukses = 0;
        $gagal  = 0;

        if ($action == 'hapus') {
            // Hapus Permanen (Kecuali yang terbooking)
            $count = Jadwal::whereIn('id', $ids)->where('status', '!=', 'terbooking')->delete();
            return back()->with('success', "Berhasil menghapus $count jadwal.");
        }

        foreach ($ids as $id) {
            $jadwal = Jadwal::with('fasilitas')->find($id);

            if (!$jadwal) continue;

            // Jangan ubah yang sudah terbooking
            if ($jadwal->status == 'terbooking') {
                $gagal++;
                continue;
            }

            if ($action == 'aktifkan') {
                // LOGIKA UTAMA: Cek Fasilitas Induk
                if ($jadwal->fasilitas->ketersediaan !== 'aktif') {
                    // Jika fasilitas induk NONAKTIF/MAINTENANCE, tolak aktivasi jadwal
                    $gagal++;
                    continue;
                }

                $jadwal->update(['status' => 'tersedia']);
                $sukses++;
            }
            elseif ($action == 'nonaktifkan') {
                // Kalau menonaktifkan bebas saja
                $jadwal->update(['status' => 'nonaktif']);
                $sukses++;
            }
        }

        $msg = "Berhasil memproses $sukses jadwal.";
        if ($gagal > 0) {
            $msg .= " ($gagal jadwal gagal diproses karena fasilitas induk sedang bermasalah/terbooking).";
            return back()->with('warning', $msg);
        }

        return back()->with('success', $msg);
    }
}
