<?php

namespace App\Http\Controllers\PetugasFasilitas;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Jadwal;
use App\Models\Fasilitas;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class JadwalController extends Controller
{
    /**
     * Menampilkan daftar jadwal
     */
    public function index(Request $request)
    {
        // Filter berdasarkan tanggal jika ada
        $tanggal = $request->input('tanggal', Carbon::today()->format('Y-m-d'));
        $fasilitas_id = $request->input('fasilitas_id');

        // Query dasar
        $query = Jadwal::with(['fasilitas', 'checkouts.user'])
                     ->orderBy('tanggal', 'asc')
                     ->orderBy('jam_mulai', 'asc');

        // Terapkan filter
        if ($tanggal) {
            $query->where('tanggal', $tanggal);
        }

        if ($fasilitas_id) {
            $query->where('fasilitas_id', $fasilitas_id);
        }

        // Dapatkan data dan kirim ke view
        $jadwals = $query->paginate(15);
        $fasilitas = Fasilitas::where('ketersediaan', 'aktif')->get();

        return view('PetugasFasilitas.Jadwal.index', compact('jadwals', 'fasilitas', 'tanggal', 'fasilitas_id'));
    }

    /**
     * Menampilkan form buat jadwal
     */
    public function create()
    {
        $fasilitas = Fasilitas::where('ketersediaan', 'aktif')->get();
        return view('PetugasFasilitas.Jadwal.create', compact('fasilitas'));
    }

    /**
     * Menyimpan jadwal baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'fasilitas_id' => 'required|exists:fasilitas,id',
            'tanggal' => 'required|date|date_format:Y-m-d|after_or_equal:today',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
        ]);

        // Cek apakah jadwal bertabrakan
        $existingJadwal = Jadwal::where('fasilitas_id', $request->fasilitas_id)
            ->where('tanggal', $request->tanggal)
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

        // Buat jadwal baru
        Jadwal::create([
            'fasilitas_id' => $request->fasilitas_id,
            'tanggal' => $request->tanggal,
            'jam_mulai' => $request->jam_mulai,
            'jam_selesai' => $request->jam_selesai,
            'status' => 'tersedia'
        ]);

        return redirect()->route('petugas_fasilitas.jadwal.index')
            ->with('success', 'Jadwal berhasil ditambahkan');
    }

    /**
     * Menampilkan detail jadwal
     */
    public function show($id)
    {
        $jadwal = Jadwal::with(['fasilitas', 'checkouts.user'])->findOrFail($id);
        return view('PetugasFasilitas.Jadwal.show', compact('jadwal'));
    }

    /**
     * Menampilkan form edit jadwal
     */
    public function edit($id)
    {
        $jadwal = Jadwal::findOrFail($id);
        $fasilitas = Fasilitas::where('ketersediaan', 'aktif')->get();

        return view('PetugasFasilitas.Jadwal.edit', compact('jadwal', 'fasilitas'));
    }

    /**
     * Menyimpan perubahan jadwal
     */
    public function update(Request $request, $id)
    {
        $jadwal = Jadwal::findOrFail($id);

        // Jika jadwal sudah terbooking, batasi field yang dapat diubah
        if ($jadwal->status == 'terbooking') {
            $request->validate([
                'status' => 'required|in:terbooking,selesai,batal'
            ]);

            $jadwal->update([
                'status' => $request->status
            ]);

            return redirect()->route('petugas_fasilitas.jadwal.index')
                ->with('success', 'Status jadwal berhasil diperbarui');
        }

        // Jika jadwal tersedia, semua field dapat diubah
        $request->validate([
            'fasilitas_id' => 'required|exists:fasilitas,id',
            'tanggal' => 'required|date|date_format:Y-m-d',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
            'status' => 'required|in:tersedia,terbooking,selesai,batal'
        ]);

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

        return redirect()->route('petugas_fasilitas.jadwal.index')
            ->with('success', 'Jadwal berhasil diperbarui');
    }

    /**
     * Menghapus jadwal
     */
    public function destroy($id)
    {
        $jadwal = Jadwal::findOrFail($id);

        // Cek apakah jadwal sudah terbooking
        if ($jadwal->status == 'terbooking') {
            return redirect()->back()->with('error', 'Jadwal yang sudah terbooking tidak dapat dihapus');
        }

        $jadwal->delete();

        return redirect()->route('petugas_fasilitas.jadwal.index')
            ->with('success', 'Jadwal berhasil dihapus');
    }
}
