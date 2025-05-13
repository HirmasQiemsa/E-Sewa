<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Fasilitas;
use App\Models\Jadwal;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class JadwalController extends Controller
{
    /**
     * Tampilkan halaman manajemen jadwal dengan form generator
     */
    public function index()
    {
        // Ambil daftar fasilitas untuk dropdown
        $fasilitas = Fasilitas::where('ketersediaan', 'aktif')->get();

        // Ambil jadwal yang ada untuk ditampilkan di tabel
        $jadwals = Jadwal::with('fasilitas')
            ->orderBy('tanggal', 'asc')
            ->orderBy('jam_mulai', 'asc')
            ->paginate(15);

        return view('Admin.Jadwal.index', compact('fasilitas', 'jadwals'));
    }

    /**
     * Generate jadwal otomatis berdasarkan parameter dari form
     */
    public function generate(Request $request)
    {
        // Validasi input
        $request->validate([
            'fasilitas_id' => 'required|exists:fasilitas,id',
            'tanggal_mulai' => 'required|date|date_format:Y-m-d|after_or_equal:today',
            'tanggal_selesai' => 'required|date|date_format:Y-m-d|after_or_equal:tanggal_mulai',
            'jam_buka' => 'required|date_format:H:i',
            'jam_tutup' => 'required|date_format:H:i|after:jam_buka',
            'durasi_slot' => 'required|integer|min:1|max:6',
            'hari' => 'required|array',
            'hari.*' => 'integer|min:0|max:6',
        ]);

        try {
            DB::beginTransaction();

            // Simpan data jadwal yang akan dibuat
            $newJadwals = [];
            $jadwalCount = 0;

            // Parameter
            $fasilitasId = $request->fasilitas_id;
            $startDate = Carbon::parse($request->tanggal_mulai);
            $endDate = Carbon::parse($request->tanggal_selesai);
            $jamBuka = Carbon::parse($request->jam_buka);
            $jamTutup = Carbon::parse($request->jam_tutup);
            $durasiSlot = (int) $request->durasi_slot; // dalam jam
            $selectedDays = $request->hari; // array hari dalam seminggu (0=Minggu, 1=Senin, dst)

            // Loop untuk setiap hari dalam rentang tanggal
            for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay()) {
                // Cek apakah hari ini termasuk dalam selectedDays
                if (in_array($date->dayOfWeek, $selectedDays)) {
                    $currentDate = $date->format('Y-m-d');

                    // Loop untuk setiap slot waktu
                    $currentTime = $jamBuka->copy();
                    while ($currentTime->addMinutes($durasiSlot * 60)->lte($jamTutup)) {
                        $slotStart = $currentTime->copy()->subMinutes($durasiSlot * 60)->format('H:i:s');
                        $slotEnd = $currentTime->format('H:i:s');

                        // Cek apakah jadwal dengan parameter yang sama sudah ada
                        $existingJadwal = Jadwal::where('fasilitas_id', $fasilitasId)
                            ->where('tanggal', $currentDate)
                            ->where('jam_mulai', $slotStart)
                            ->where('jam_selesai', $slotEnd)
                            ->exists();

                        // Jika belum ada, tambahkan ke array untuk dibuat
                        if (!$existingJadwal) {
                            $newJadwals[] = [
                                'fasilitas_id' => $fasilitasId,
                                'tanggal' => $currentDate,
                                'jam_mulai' => $slotStart,
                                'jam_selesai' => $slotEnd,
                                'status' => 'tersedia',
                                'created_at' => now(),
                                'updated_at' => now()
                            ];

                            $jadwalCount++;
                        }
                    }
                }
            }

            // Buat jadwal secara batch untuk efisiensi
            if (count($newJadwals) > 0) {
                Jadwal::insert($newJadwals);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'count' => $jadwalCount,
                'message' => "$jadwalCount jadwal berhasil dibuat"
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal generate jadwal: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Gagal membuat jadwal: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Tampilkan form untuk menambah jadwal tunggal
     */
    public function create()
    {
        $fasilitas = Fasilitas::where('ketersediaan', 'aktif')->get();
        return view('Admin.Jadwal.create', compact('fasilitas'));
    }

    /**
     * Simpan jadwal tunggal baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'fasilitas_id' => 'required|exists:fasilitas,id',
            'tanggal' => 'required|date|date_format:Y-m-d|after_or_equal:today',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
        ]);

        try {
            // Cek jadwal yang sama
            $existingJadwal = Jadwal::where('fasilitas_id', $request->fasilitas_id)
                ->where('tanggal', $request->tanggal)
                ->where(function($query) use ($request) {
                    // Cek apakah ada overlap dengan jam mulai dan selesai yang diminta
                    $query->whereBetween('jam_mulai', [$request->jam_mulai, $request->jam_selesai])
                        ->orWhereBetween('jam_selesai', [$request->jam_mulai, $request->jam_selesai])
                        ->orWhere(function($q) use ($request) {
                            $q->where('jam_mulai', '<=', $request->jam_mulai)
                                ->where('jam_selesai', '>=', $request->jam_selesai);
                        });
                })
                ->exists();

            if ($existingJadwal) {
                return redirect()->back()->with('error', 'Jadwal pada waktu tersebut sudah ada!');
            }

            // Buat jadwal baru
            Jadwal::create([
                'fasilitas_id' => $request->fasilitas_id,
                'tanggal' => $request->tanggal,
                'jam_mulai' => $request->jam_mulai,
                'jam_selesai' => $request->jam_selesai,
                'status' => 'tersedia'
            ]);

            return redirect()->route('admin.jadwal.index')->with('success', 'Jadwal berhasil ditambahkan');
        } catch (\Exception $e) {
            Log::error('Gagal menyimpan jadwal: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal menambahkan jadwal: ' . $e->getMessage());
        }
    }

    /**
     * Tampilkan form untuk edit jadwal
     */
    public function edit($id)
    {
        $jadwal = Jadwal::findOrFail($id);
        $fasilitas = Fasilitas::where('ketersediaan', 'aktif')->get();

        return view('Admin.Jadwal.edit', compact('jadwal', 'fasilitas'));
    }

    /**
     * Update jadwal
     */
    public function update(Request $request, $id)
    {
        $jadwal = Jadwal::findOrFail($id);

        // Jika jadwal sudah terbooking, batasi field yang bisa diubah
        if ($jadwal->status == 'terbooking') {
            $request->validate([
                'status' => 'required|in:terbooking,selesai,batal'
            ]);

            $jadwal->update([
                'status' => $request->status
            ]);

            return redirect()->route('admin.jadwal.index')->with('success', 'Status jadwal berhasil diperbarui');
        }

        // Jika jadwal tersedia, semua field bisa diubah
        $request->validate([
            'fasilitas_id' => 'required|exists:fasilitas,id',
            'tanggal' => 'required|date|date_format:Y-m-d',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
            'status' => 'required|in:tersedia,terbooking,selesai,batal'
        ]);

        try {
            // Cek jadwal yang sama (selain jadwal ini)
            $existingJadwal = Jadwal::where('fasilitas_id', $request->fasilitas_id)
                ->where('tanggal', $request->tanggal)
                ->where('id', '!=', $id)
                ->where(function($query) use ($request) {
                    $query->whereBetween('jam_mulai', [$request->jam_mulai, $request->jam_selesai])
                        ->orWhereBetween('jam_selesai', [$request->jam_mulai, $request->jam_selesai])
                        ->orWhere(function($q) use ($request) {
                            $q->where('jam_mulai', '<=', $request->jam_mulai)
                                ->where('jam_selesai', '>=', $request->jam_selesai);
                        });
                })
                ->exists();

            if ($existingJadwal) {
                return redirect()->back()->with('error', 'Jadwal pada waktu tersebut sudah ada!');
            }

            // Update jadwal
            $jadwal->update([
                'fasilitas_id' => $request->fasilitas_id,
                'tanggal' => $request->tanggal,
                'jam_mulai' => $request->jam_mulai,
                'jam_selesai' => $request->jam_selesai,
                'status' => $request->status
            ]);

            return redirect()->route('admin.jadwal.index')->with('success', 'Jadwal berhasil diperbarui');
        } catch (\Exception $e) {
            Log::error('Gagal update jadwal: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal memperbarui jadwal: ' . $e->getMessage());
        }
    }

    /**
     * Hapus jadwal
     */
    public function destroy($id)
    {
        try {
            $jadwal = Jadwal::findOrFail($id);

            // Cek apakah jadwal sudah terbooking
            if ($jadwal->status == 'terbooking') {
                return response()->json([
                    'success' => false,
                    'message' => 'Jadwal sudah terbooking, tidak dapat dihapus.'
                ]);
            }

            $jadwal->delete();

            return response()->json([
                'success' => true,
                'message' => 'Jadwal berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            Log::error('Gagal hapus jadwal: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus jadwal: ' . $e->getMessage()
            ], 500);
        }
    }
}
