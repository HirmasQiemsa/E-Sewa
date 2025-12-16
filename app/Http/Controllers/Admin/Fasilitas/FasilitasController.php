<?php

namespace App\Http\Controllers\Admin\Fasilitas;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Models\Fasilitas;
use App\Models\Jadwal;
use App\Models\Admin; // Untuk dropdown admin pembayaran

class FasilitasController extends Controller
{
    /**
     * Tampilkan daftar fasilitas
     */
    public function index()
    {
        // 1. Ambil data Fasilitas (Milik Admin Login)
        $fasilitas = Fasilitas::withTrashed()
                        ->with(['adminPembayaran'])
                        ->where('admin_fasilitas_id', Auth::id())
                        ->latest()
                        ->get();

        // 2. Hitung Statistik dari Collection (Tanpa query ulang ke DB biar cepat)
        $countAktif = $fasilitas->where('ketersediaan', 'aktif')->count();
        $countNonaktif = $fasilitas->where('ketersediaan', 'nonaktif')->count();
        $countMaintenance = $fasilitas->where('ketersediaan', 'maintenance')->count();

        // Hitung yang terhapus (Soft Deleted)
        // whereNotNull('deleted_at') digunakan untuk mengecek yg sudah dihapus
        $countTrash = $fasilitas->whereNotNull('deleted_at')->count();

        // 3. Kirim ke View
        return view('Admin.Fasilitas.Data.index', compact(
            'fasilitas',
            'countAktif',
            'countNonaktif',
            'countMaintenance',
            'countTrash'
        ));
    }

    /**
     * Form Tambah Fasilitas
     */
    public function create()
    {
        // Ambil daftar Admin Pembayaran (Keuangan)
        $adminPembayaran = Admin::where('role', 'admin_pembayaran')
                                ->where('is_active', true)
                                ->get();

        return view('Admin.Fasilitas.Data.create', compact('adminPembayaran'));
    }

    /**
     * Simpan Fasilitas Baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_fasilitas' => 'required|string|max:255',
            'deskripsi'      => 'nullable|string',
            'kategori'       => 'required|string',
            'tipe'           => 'required|string',
            'lokasi'         => 'required|string',
            'harga_sewa'     => 'required|numeric|min:0',
            'foto'           => 'required|image|max:2048',
            'admin_pembayaran_id' => 'required|exists:admins,id',
        ]);

        try {
            $path = $request->file('foto')->store('fasilitas', 'public');

            Fasilitas::create([
                'nama_fasilitas'      => $request->nama_fasilitas,
                'deskripsi'           => $request->deskripsi,
                'kategori'            => $request->kategori,
                'tipe'                => $request->tipe,
                'lokasi'              => $request->lokasi,
                'harga_sewa'          => $request->harga_sewa,
                'foto'                => $path,
                'ketersediaan'        => 'nonaktif', // Default
                'status_approval'     => 'draft',
                'admin_fasilitas_id'  => Auth::id(), // ID Admin Login
                'admin_pembayaran_id' => $request->admin_pembayaran_id,
            ]);

            return redirect()->route('admin.fasilitas.index')
                ->with('success', 'Fasilitas berhasil ditambahkan.');

        } catch (\Exception $e) {
            return back()->with('error', 'Gagal: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Form Edit Fasilitas
     */
    public function edit($id)
    {
        $fasilitas = Fasilitas::withTrashed()->findOrFail($id);
        $adminPembayaran = Admin::where('role', 'admin_pembayaran')->get();

        return view('Admin.Fasilitas.Data.edit', compact('fasilitas', 'adminPembayaran'));
    }

    /**
     * Update Fasilitas
     */
    public function update(Request $request, $id)
    {
        $fasilitas = Fasilitas::withTrashed()->findOrFail($id);

        // Otorisasi: Hanya pemilik atau super admin
        if ($fasilitas->admin_fasilitas_id != Auth::id() && Auth::user()->role !== 'super_admin') {
            abort(403);
        }

        $request->validate([
            'nama_fasilitas' => 'required|string',
            'kategori'       => 'required',
            'harga_sewa'     => 'required|numeric',
            'foto'           => 'nullable|image|max:2048',
            'ketersediaan'   => 'required|in:aktif,nonaktif,maintenance',
            'admin_pembayaran_id' => 'required|exists:admins,id',
        ]);

        try {
            // Upload foto baru jika ada
            if ($request->hasFile('foto')) {
                if ($fasilitas->foto && Storage::disk('public')->exists($fasilitas->foto)) {
                    Storage::disk('public')->delete($fasilitas->foto);
                }
                $fasilitas->foto = $request->file('foto')->store('fasilitas', 'public');
            }

            // Update data
            $fasilitas->update([
                'nama_fasilitas'      => $request->nama_fasilitas,
                'deskripsi'           => $request->deskripsi,
                'kategori'            => $request->kategori,
                'tipe'                => $request->tipe,
                'lokasi'              => $request->lokasi,
                'harga_sewa'          => $request->harga_sewa,
                'ketersediaan'        => $request->ketersediaan,
                'admin_pembayaran_id' => $request->admin_pembayaran_id,
            ]);

            // Jika status berubah jadi non-aktif/maintenance, batalkan jadwal tersedia
            if ($request->ketersediaan !== 'aktif') {
                Jadwal::where('fasilitas_id', $id)
                    ->where('status', 'tersedia')
                    ->update(['status' => 'batal']);
            }

            return redirect()->route('admin.fasilitas.data.index')
                ->with('success', 'Fasilitas diperbarui.');

        } catch (\Exception $e) {
            return back()->with('error', 'Gagal update: ' . $e->getMessage());
        }
    }

    /**
     * Soft Delete
     */
    public function destroy($id)
    {
        try {
            $fasilitas = Fasilitas::findOrFail($id);

            // Cek jadwal aktif
            $hasBooking = Jadwal::where('fasilitas_id', $id)
                            ->where('status', 'terbooking')
                            ->whereDate('tanggal', '>=', now())
                            ->exists();

            if ($hasBooking) {
                return back()->with('error', 'Gagal hapus: Masih ada jadwal booking aktif.');
            }

            $fasilitas->delete();
            return back()->with('success', 'Fasilitas dinonaktifkan.');

        } catch (\Exception $e) {
            return back()->with('error', 'Gagal hapus: ' . $e->getMessage());
        }
    }

    /**
     * Restore
     */
    public function restore($id)
    {
        $fasilitas = Fasilitas::withTrashed()->findOrFail($id);
        $fasilitas->restore();
        return back()->with('success', 'Fasilitas dipulihkan.');
    }
}
