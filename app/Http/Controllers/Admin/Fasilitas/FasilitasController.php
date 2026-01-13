<?php

namespace App\Http\Controllers\Admin\Fasilitas;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Models\Fasilitas;
use App\Models\ActivityLog;
use App\Models\Jadwal;
use App\Models\Admin; // Untuk dropdown admin pembayaran

class FasilitasController extends Controller
{
    /**
     * Tampilkan daftar fasilitas
     */
    public function index()
    {
        $user = Auth::guard('admin')->user();

        // 1. QUERY UTAMA
        // Jika Super Admin atau Admin Fasilitas -> LIHAT SEMUA (Tanpa filter ID)
        // Kita gunakan with(['adminFasilitas']) agar bisa cek pemiliknya di blade
        $fasilitas = Fasilitas::withTrashed()
                        ->with(['adminFasilitas', 'adminPembayaran']) // Load data pembuat
                        ->latest()
                        ->get();

        // 2. Hitung Statistik (Logic tetap sama)
        $countAktif = $fasilitas->where('ketersediaan', 'aktif')->count();
        $countNonaktif = $fasilitas->where('ketersediaan', 'nonaktif')->count();
        $countMaintenance = $fasilitas->where('ketersediaan', 'maintenance')->count();
        $countTrash = $fasilitas->whereNotNull('deleted_at')->count();

        // 3. Kirim ke View
        return view('Admin.Fasilitas.Data.index', compact(
            'fasilitas',
            'countAktif', 'countNonaktif', 'countMaintenance', 'countTrash'
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

            $fasilitas = Fasilitas::create([
                'nama_fasilitas'      => $request->nama_fasilitas,
                'deskripsi'           => $request->deskripsi,
                'kategori'            => $request->kategori,
                'tipe'                => $request->tipe,
                'lokasi'              => $request->lokasi,
                'harga_sewa'          => $request->harga_sewa,
                'foto'                => $path,
                'ketersediaan'        => 'nonaktif',
                // 'status_approval'     => 'pending',
                'admin_fasilitas_id'  => Auth::id(), // ID Admin Login
                'admin_pembayaran_id' => $request->admin_pembayaran_id,
            ]);

            $this->recordLog($fasilitas, 'CREATE', 'Menambahkan fasilitas baru: ' . $fasilitas->nama_fasilitas);

            return redirect()->route('admin.fasilitas.data.index')
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

        // Cek kepemilikan (PENTING: Jangan sampai Admin A mengedit punya Admin B lewat URL)
        if ($fasilitas->admin_fasilitas_id != Auth::id() && Auth::user()->role !== 'super_admin') {
            abort(403, 'Anda tidak memiliki hak akses untuk mengedit fasilitas ini.');
        }

        $adminPembayaran = Admin::where('role', 'admin_pembayaran')->get();

        return view('Admin.Fasilitas.Data.edit', compact('fasilitas', 'adminPembayaran'));
    }

    /**
     * Update Fasilitas
     */
    public function update(Request $request, $id)
    {
        $fasilitas = Fasilitas::withTrashed()->findOrFail($id);

        // 1. OTORISASI (Cek Hak Akses)
        if ($fasilitas->admin_fasilitas_id != Auth::id() && Auth::user()->role !== 'super_admin') {
            abort(403);
        }

        // 2. SIAPKAN SEMUA ATURAN VALIDASI (VALIDATE ALL)
        // Kita tampung dulu di array $rules agar bisa dimodifikasi dinamis
        $rules = [
            'nama_fasilitas'      => 'required|string|max:255',
            'deskripsi'           => 'nullable|string',
            'tipe'                => 'required|string',
            'lokasi'              => 'required|string',
            'harga_sewa'          => 'required|numeric|min:0',
            'ketersediaan'        => 'required|in:aktif,nonaktif,maintenance',
            'admin_pembayaran_id' => 'required|exists:admins,id', // <--- Wajib validasi ini
            'kategori_select'     => 'required|string',
            'foto'                => 'nullable|image|max:2048',   // Nullable karena ini update (bisa tidak ganti foto)
        ];

        // 3. LOGIKA TAMBAHAN KATEGORI
        // Jika user pilih 'lainnya', maka 'kategori_baru' WAJIB diisi
        if ($request->kategori_select == 'lainnya') {
            $rules['kategori_baru'] = 'required|string|max:50';
        }

        // 4. JALANKAN VALIDASI (EKSEKUSI)
        // Ini akan memvalidasi SEMUA field di atas sekaligus.
        // Jika ada satu saja yang salah, Laravel otomatis stop dan redirect back with error.
        $request->validate($rules);

        // 5. TENTUKAN KATEGORI FINAL
        $kategoriFinal = ($request->kategori_select == 'lainnya')
                            ? $request->kategori_baru
                            : $request->kategori_select;

        try {
            // 6. LOGIKA UPLOAD FOTO
            // Simpan path lama dulu untuk dihapus nanti jika sukses upload baru
            $pathFoto = $fasilitas->foto;

            if ($request->hasFile('foto')) {
                // Hapus foto lama fisik jika ada
                if ($fasilitas->foto && Storage::disk('public')->exists($fasilitas->foto)) {
                    Storage::disk('public')->delete($fasilitas->foto);
                }
                // Upload foto baru
                $pathFoto = $request->file('foto')->store('fasilitas', 'public');
            }

            // 7. UPDATE DATABASE
            $fasilitas->update([
                'nama_fasilitas'      => $request->nama_fasilitas,
                'deskripsi'           => $request->deskripsi,
                'kategori'            => $kategoriFinal, // Pakai hasil logika poin 5
                'tipe'                => $request->tipe,
                'lokasi'              => $request->lokasi,
                'harga_sewa'          => $request->harga_sewa,
                'ketersediaan'        => $request->ketersediaan,
                'admin_pembayaran_id' => $request->admin_pembayaran_id,
                'foto'                => $pathFoto, // Update path foto (baik berubah atau tetap)
            ]);

            // 8. LOGIC CANCEL JADWAL (Jika Nonaktif)
            if ($request->ketersediaan !== 'aktif') {
                Jadwal::where('fasilitas_id', $id)
                    ->where('status', 'tersedia')
                    ->update(['status' => 'batal']);
            }

            // 9. CATAT LOG
            $this->recordLog($fasilitas, 'UPDATE', 'Mengubah data fasilitas: ' . $fasilitas->nama_fasilitas);

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

            $this->recordLog($fasilitas, 'DELETE', 'Menonaktifkan fasilitas: ' . $fasilitas->nama_fasilitas);

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
        // Cari data yang sudah dihapus (withTrashed)
        $fasilitas = Fasilitas::withTrashed()->findOrFail($id);

        // Pulihkan data
        $fasilitas->restore();

        // Catat Log (Penting untuk audit trail)
        $this->recordLog($fasilitas, 'RESTORE', 'Memulihkan fasilitas: ' . $fasilitas->nama_fasilitas);

        return back()->with('success', 'Fasilitas berhasil dipulihkan.');
    }

    /**
     * Toggle Status Ketersediaan Fasilitas
     * Siklus: nonaktif -> aktif -> maintenance -> nonaktif
     */
    public function toggleStatus($id)
    {
        $fasilitas = Fasilitas::findOrFail($id);

        // Validasi Kepemilikan
        if (Auth::user()->role != 'super_admin' && $fasilitas->admin_fasilitas_id != Auth::id()) {
            return back()->with('error', 'Akses ditolak.');
        }

        // Logic Cyclic: Nonaktif -> Aktif -> Maintenance -> Nonaktif
        $current = $fasilitas->ketersediaan;
        $nextStatus = 'nonaktif'; // Default fallback

        if ($current == 'nonaktif') {
            $nextStatus = 'aktif';
        } elseif ($current == 'aktif') {
            $nextStatus = 'maintenance';
        } elseif ($current == 'maintenance') {
            $nextStatus = 'nonaktif';
        }

        // Update Status di Database
        $fasilitas->update(['ketersediaan' => $nextStatus]);

        // [PERBAIKAN] Tambahkan pencatatan log aktivitas disini
        $this->recordLog(
            $fasilitas,
            'UPDATE',
            'Mengubah status fasilitas: ' . $fasilitas->nama_fasilitas . ' menjadi ' . strtoupper($nextStatus)
        );

        return back()->with('success', 'Status fasilitas berubah menjadi: ' . ucfirst($nextStatus));
    }
}
