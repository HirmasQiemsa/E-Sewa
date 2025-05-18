<?php

namespace App\Http\Controllers\PetugasFasilitas;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Models\Fasilitas;
use App\Models\Jadwal;
use Carbon\Carbon;

class FasilitasController extends Controller
{
    /**
     * Display list of facilities
     */
    public function index()
    {
        // Get all facilities including soft-deleted ones
        $data = Fasilitas::withTrashed()->get();

        // Count statistics for each status
        $countAktif = Fasilitas::where('ketersediaan', 'aktif')->count();
        $countNonaktif = Fasilitas::where('ketersediaan', 'nonaktif')->count();
        $countMaintenance = Fasilitas::where('ketersediaan', 'maintanace')->count();

        return view('PetugasFasilitas.KelolaFasilitas.index', compact(
            'data',
            'countAktif',
            'countNonaktif',
            'countMaintenance'
        ));
    }

    /**
     * Show form to create a new facility
     */
    public function create()
    {
        return view('PetugasFasilitas.KelolaFasilitas.create');
    }

    /**
     * Store a new facility in the database
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_fasilitas' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'tipe' => 'nullable|string|max:255',
            'lokasi' => 'required|string|max:255',
            'harga_sewa' => 'required|integer|min:0',
            'foto' => 'required|image|mimes:jpeg,png,jpg,svg|max:2048',
        ]);

        try {
            // Save photo file to storage
            $path = $request->file('foto')->store('fasilitas', 'public');

            // Create facility record
            Fasilitas::create([
                'nama_fasilitas' => $request->nama_fasilitas,
                'deskripsi' => $request->deskripsi,
                'tipe' => $request->tipe,
                'lokasi' => $request->lokasi,
                'harga_sewa' => $request->harga_sewa,
                'foto' => $path,
                'ketersediaan' => 'aktif',
                'petugas_fasilitas_id' => Auth::guard('petugas_fasilitas')->id(),
            ]);

            return redirect()->route('petugas_fasilitas.fasilitas.index')
                ->with('success', 'Fasilitas berhasil ditambahkan.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal menambahkan fasilitas: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Show form to edit a facility
     */
    public function edit($id)
    {
        $fasilitas = Fasilitas::withTrashed()->findOrFail($id);
        return view('PetugasFasilitas.KelolaFasilitas.edit', compact('fasilitas'));
    }

    /**
     * Update facility data
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_fasilitas' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'tipe' => 'nullable|string|max:255',
            'lokasi' => 'required|string|max:255',
            'harga_sewa' => 'required|integer|min:0',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,svg|max:2048',
            'ketersediaan' => 'required|in:aktif,nonaktif,maintanace',
        ]);

        try {
            $fasilitas = Fasilitas::withTrashed()->findOrFail($id);

            // Update base data
            $fasilitas->nama_fasilitas = $request->nama_fasilitas;
            $fasilitas->deskripsi = $request->deskripsi;
            $fasilitas->tipe = $request->tipe;
            $fasilitas->lokasi = $request->lokasi;
            $fasilitas->harga_sewa = $request->harga_sewa;
            $fasilitas->ketersediaan = $request->ketersediaan;

            // Update petugas who edited
            $fasilitas->petugas_fasilitas_id = Auth::guard('petugas_fasilitas')->id();

            // Handle photo update if provided
            if ($request->hasFile('foto')) {
                // Delete old photo if exists
                if ($fasilitas->foto && Storage::disk('public')->exists($fasilitas->foto)) {
                    Storage::disk('public')->delete($fasilitas->foto);
                }

                // Save new photo
                $fasilitas->foto = $request->file('foto')->store('fasilitas', 'public');
            }

            $fasilitas->save();

            // If status changed to nonactive or maintenance, update any available schedules
            if ($request->ketersediaan !== 'aktif') {
                Jadwal::where('fasilitas_id', $id)
                    ->where('status', 'tersedia')
                    ->update(['status' => 'batal']);
            }

            return redirect()->route('petugas_fasilitas.fasilitas.index')
                ->with('success', 'Fasilitas berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal memperbarui fasilitas: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Soft delete a facility
     */
    public function destroy($id)
    {
        try {
            $fasilitas = Fasilitas::findOrFail($id);

            // Check if facility has active schedules
            $hasActiveSchedules = Jadwal::where('fasilitas_id', $id)
                ->where('status', 'terbooking')
                ->where('tanggal', '>=', Carbon::today()->format('Y-m-d'))
                ->exists();

            if ($hasActiveSchedules) {
                return redirect()->back()
                    ->with('error', 'Fasilitas tidak dapat dihapus karena memiliki jadwal aktif.');
            }

            // Soft delete the facility
            $fasilitas->delete();

            return redirect()->route('petugas_fasilitas.fasilitas.index')
                ->with('success', 'Fasilitas berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal menghapus fasilitas: ' . $e->getMessage());
        }
    }

    /**
     * Restore a soft-deleted facility
     */
    public function restore($id)
    {
        try {
            $fasilitas = Fasilitas::withTrashed()->findOrFail($id);
            $fasilitas->restore();

            return redirect()->route('petugas_fasilitas.fasilitas.index')
                ->with('success', 'Fasilitas berhasil dipulihkan.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal memulihkan fasilitas: ' . $e->getMessage());
        }
    }

    /**
     * Toggle facility status
     */
    public function toggleStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:aktif,nonaktif,maintanace'
        ]);

        try {
            $fasilitas = Fasilitas::findOrFail($id);
            $oldStatus = $fasilitas->ketersediaan;

            // Update status
            $fasilitas->ketersediaan = $request->status;
            $fasilitas->petugas_fasilitas_id = Auth::guard('petugas_fasilitas')->id();
            $fasilitas->save();

            // If status changed to nonactive or maintenance, update any available schedules
            if ($request->status !== 'aktif') {
                Jadwal::where('fasilitas_id', $id)
                    ->where('status', 'tersedia')
                    ->update(['status' => 'batal']);
            }

            return redirect()->back()
                ->with('success', "Status fasilitas berhasil diubah dari $oldStatus menjadi {$request->status}.");
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal mengubah status fasilitas: ' . $e->getMessage());
        }
    }
}
