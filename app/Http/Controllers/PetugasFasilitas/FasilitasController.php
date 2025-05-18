<?php

namespace App\Http\Controllers\PetugasFasilitas;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Fasilitas;
use App\Models\Jadwal;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class FasilitasController extends Controller
{
    /**
     * Menampilkan daftar fasilitas
     */
    public function index()
    {
        // Ambil semua fasilitas termasuk yang soft deleted
        $fasilitas = Fasilitas::withTrashed()->get();

        // Hitung statistik untuk setiap jenis
        $countAktif = Fasilitas::where('ketersediaan', 'aktif')->count();
        $countNonaktif = Fasilitas::where('ketersediaan', 'nonaktif')->count();
        $countMaintenance = Fasilitas::where('ketersediaan', 'maintanace')->count();

        return view('PetugasFasilitas.Fasilitas.index', compact(
            'fasilitas',
            'countAktif',
            'countNonaktif',
            'countMaintenance'
        ));
    }

    /**
     * Menampilkan form tambah fasilitas
     */
    public function create()
    {
        return view('PetugasFasilitas.Fasilitas.create');
    }

    /**
     * Menyimpan fasilitas baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_fasilitas' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'tipe' => 'nullable|string|max:255',
            'lokasi' => 'required|string|max:255',
            'harga_sewa' => 'required|integer|min:0',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,svg|max:2048',
        ]);

        // Upload foto jika ada
        if ($request->hasFile('foto')) {
            $path = $request->file('foto')->store('fasilitas', 'public');
        }

        // Buat record fasilitas baru
        Fasilitas::create([
            'nama_fasilitas' => $request->nama_fasilitas,
            'deskripsi' => $request->deskripsi,
            'tipe' => $request->tipe,
            'lokasi' => $request->lokasi,
            'harga_sewa' => $request->harga_sewa,
            'foto' => $path ?? null,
            'ketersediaan' => 'aktif',
            'petugas_fasilitas_id' => Auth::guard('petugas_fasilitas')->id(),
        ]);

        return redirect()->route('petugas_fasilitas.fasilitas.index')
            ->with('success', 'Fasilitas berhasil ditambahkan');
    }

    /**
     * Menampilkan form edit fasilitas
     */
    public function edit($id)
    {
        $fasilitas = Fasilitas::withTrashed()->findOrFail($id);
        return view('PetugasFasilitas.Fasilitas.edit', compact('fasilitas'));
    }

    /**
     * Menyimpan perubahan fasilitas
     */
    public function update(Request $request, $id)
    {
        $fasilitas = Fasilitas::withTrashed()->findOrFail($id);

        $request->validate([
            'nama_fasilitas' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'tipe' => 'nullable|string|max:255',
            'lokasi' => 'required|string|max:255',
            'harga_sewa' => 'required|integer|min:0',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,svg|max:2048',
            'ketersediaan' => 'required|in:aktif,nonaktif,maintanace',
        ]);

        // Handle foto upload jika ada
        if ($request->hasFile('foto')) {
            // Hapus foto lama jika ada
            if ($fasilitas->foto && Storage::disk('public')->exists($fasilitas->foto)) {
                Storage::disk('public')->delete($fasilitas->foto);
            }

            // Upload foto baru
            $path = $request->file('foto')->store('fasilitas', 'public');
            $fasilitas->foto = $path;
        }

        // Update data fasilitas
        $fasilitas->nama_fasilitas = $request->nama_fasilitas;
        $fasilitas->deskripsi = $request->deskripsi;
        $fasilitas->tipe = $request->tipe;
        $fasilitas->lokasi = $request->lokasi;
        $fasilitas->harga_sewa = $request->harga_sewa;
        $fasilitas->ketersediaan = $request->ketersediaan;
        $fasilitas->save();

        // Jika fasilitas diset menjadi nonaktif, update semua jadwal tersedia menjadi nonaktif
        if ($request->ketersediaan === 'nonaktif' || $request->ketersediaan === 'maintanace') {
            Jadwal::where('fasilitas_id', $id)
                ->where('status', 'tersedia')
                ->update(['status' => 'batal']);
        }

        return redirect()->route('petugas_fasilitas.fasilitas.index')
            ->with('success', 'Fasilitas berhasil diperbarui');
    }

    /**
     * Menghapus fasilitas (soft delete)
     */
    public function destroy($id)
    {
        $fasilitas = Fasilitas::findOrFail($id);

        // Cek apakah fasilitas memiliki jadwal aktif
        $hasActiveSchedules = Jadwal::where('fasilitas_id', $id)
                                  ->where('status', 'terbooking')
                                  ->where('tanggal', '>=', Carbon::today()->format('Y-m-d'))
                                  ->exists();

        if ($hasActiveSchedules) {
            return redirect()->back()->with('error', 'Fasilitas tidak dapat dihapus karena memiliki jadwal aktif');
        }

        // Soft delete fasilitas
        $fasilitas->delete();

        return redirect()->route('petugas_fasilitas.fasilitas.index')
            ->with('success', 'Fasilitas berhasil dihapus');
    }

    /**
     * Restore fasilitas yang sudah dihapus
     */
    public function restore($id)
    {
        $fasilitas = Fasilitas::withTrashed()->findOrFail($id);
        $fasilitas->restore();

        return redirect()->route('petugas_fasilitas.fasilitas.index')
            ->with('success', 'Fasilitas berhasil dipulihkan');
    }

    /**
     * Menampilkan fasilitas dalam maintenance
     */
    public function maintenance()
    {
        $fasilitas = Fasilitas::where('ketersediaan', 'maintanace')->get();

        // Ambil jadwal maintenance untuk setiap fasilitas
        $fasilitasData = $fasilitas->map(function($item) {
            $maintenanceStart = $item->updated_at ?? $item->created_at;

            // Hitung berapa lama dalam maintenance (dalam hari)
            $daysSinceMaintenance = $maintenanceStart->diffInDays(Carbon::now());

            return [
                'fasilitas' => $item,
                'days_in_maintenance' => $daysSinceMaintenance,
                'upcoming_bookings' => Jadwal::where('fasilitas_id', $item->id)
                                        ->where('status', 'terbooking')
                                        ->where('tanggal', '>=', Carbon::today()->format('Y-m-d'))
                                        ->count()
            ];
        });

        return view('PetugasFasilitas.Fasilitas.maintenance', compact('fasilitasData'));
    }

    /**
     * Toggle status fasilitas (aktif/nonaktif/maintenance)
     */
    public function toggleStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:aktif,nonaktif,maintanace'
        ]);

        $fasilitas = Fasilitas::findOrFail($id);
        $oldStatus = $fasilitas->ketersediaan;
        $fasilitas->ketersediaan = $request->status;
        $fasilitas->save();

        // Jika fasilitas diubah menjadi nonaktif/maintenance, update jadwal yang tersedia
        if ($request->status !== 'aktif') {
            Jadwal::where('fasilitas_id', $id)
                ->where('status', 'tersedia')
                ->update(['status' => 'batal']);
        }

        return redirect()->back()->with('success', "Status fasilitas berhasil diubah dari $oldStatus menjadi {$request->status}");
    }

    /**
     * Tampilkan laporan penggunaan fasilitas
     */
    public function report(Request $request)
    {
        // Filter berdasarkan bulan dan tahun jika ada
        $month = $request->input('month', Carbon::now()->month);
        $year = $request->input('year', Carbon::now()->year);

        // List fasilitas untuk dropdown filter
        $allFasilitas = Fasilitas::withTrashed()->get();
        $fasilitasId = $request->input('fasilitas_id');

        // Query dasar untuk jadwal selesai
        $query = Jadwal::with(['fasilitas', 'checkouts.user'])
                    ->where('status', 'selesai');

        // Terapkan filter bulan dan tahun
        if ($month && $year) {
            $query->whereMonth('tanggal', $month)
                  ->whereYear('tanggal', $year);
        }

        // Terapkan filter fasilitas jika dipilih
        if ($fasilitasId) {
            $query->where('fasilitas_id', $fasilitasId);
        }

        // Dapatkan data jadwal
        $jadwals = $query->get();

        // Hitung statistik penggunaan
        $statsByFacility = $jadwals->groupBy('fasilitas_id')
            ->map(function($items, $fasilitasId) {
                $fasilitas = $items->first()->fasilitas;
                $totalHours = $items->sum(function($jadwal) {
                    $start = Carbon::parse($jadwal->jam_mulai);
                    $end = Carbon::parse($jadwal->jam_selesai);
                    return $end->diffInHours($start);
                });

                $totalBookings = $items->count();
                $totalRevenue = $totalHours * $fasilitas->harga_sewa;

                return [
                    'fasilitas' => $fasilitas,
                    'total_hours' => $totalHours,
                    'total_bookings' => $totalBookings,
                    'total_revenue' => $totalRevenue
                ];
            });

        // Hitung total overall
        $totalRevenue = $statsByFacility->sum('total_revenue');
        $totalBookings = $statsByFacility->sum('total_bookings');
        $totalHours = $statsByFacility->sum('total_hours');

        return view('PetugasFasilitas.Fasilitas.report', compact(
            'statsByFacility',
            'totalRevenue',
            'totalBookings',
            'totalHours',
            'allFasilitas',
            'month',
            'year',
            'fasilitasId'
        ));
    }
}
