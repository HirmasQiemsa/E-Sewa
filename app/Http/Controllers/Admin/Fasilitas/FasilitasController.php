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
        $adminFasilitas = Admin::where('role', 'admin_fasilitas')->get();

        return view('Admin.Fasilitas.Data.edit', compact('fasilitas', 'adminPembayaran', 'adminFasilitas'));
    }

    /**
     * Update Fasilitas
     */
    public function update(Request $request, $id)
    {
        $fasilitas = Fasilitas::withTrashed()->findOrFail($id);

        // 1. OTORISASI (Cek Hak Akses)
        // Hanya Super Admin ATAU pemilik fasilitas yang boleh edit
        if ($fasilitas->admin_fasilitas_id != Auth::id() && Auth::user()->role !== 'super_admin') {
            abort(403);
        }

        // 2. SIAPKAN RULES VALIDASI
        $rules = [
            'nama_fasilitas'      => 'required|string|max:255',
            'deskripsi'           => 'nullable|string',
            'tipe'                => 'required|string',
            'lokasi'              => 'required|string',
            'harga_sewa'          => 'required|numeric|min:0',
            'ketersediaan'        => 'required|in:aktif,nonaktif,maintenance',
            'admin_pembayaran_id' => 'required|exists:users,id', // Pastikan tabelnya users (bukan admins)
            'kategori_select'     => 'required|string',
            'foto'                => 'nullable|image|max:2048',
        ];

        // [LOGIC BARU] VALIDASI ADMIN FASILITAS (KHUSUS SUPER ADMIN)
        if (Auth::user()->role == 'super_admin') {
            // Jika Super Admin, boleh (dan wajib) ganti admin fasilitas jika inputnya ada
            // 'sometimes' artinya validasi jalan HANYA JIKA input 'admin_fasilitas_id' ada di request
            $rules['admin_fasilitas_id'] = 'sometimes|required|exists:users,id';
        }

        // LOGIKA KATEGORI LAINNYA
        if ($request->kategori_select == 'lainnya') {
            $rules['kategori_baru'] = 'required|string|max:50';
        }

        // EKSEKUSI VALIDASI
        $request->validate($rules);

        // 3. TENTUKAN DATA YANG AKAN DI-UPDATE
        $dataUpdate = [
            'nama_fasilitas'      => $request->nama_fasilitas,
            'deskripsi'           => $request->deskripsi,
            'kategori'            => ($request->kategori_select == 'lainnya') ? $request->kategori_baru : $request->kategori_select,
            'tipe'                => $request->tipe,
            'lokasi'              => $request->lokasi,
            'harga_sewa'          => $request->harga_sewa,
            'ketersediaan'        => $request->ketersediaan,
            'admin_pembayaran_id' => $request->admin_pembayaran_id,
        ];

        // [LOGIC BARU] TAMBAHKAN ADMIN FASILITAS KE DATA UPDATE (KHUSUS SUPER ADMIN)
        if (Auth::user()->role == 'super_admin' && $request->has('admin_fasilitas_id')) {
            $dataUpdate['admin_fasilitas_id'] = $request->admin_fasilitas_id;
        }

        try {
            // 4. LOGIKA FOTO (Tidak Berubah)
            if ($request->hasFile('foto')) {
                if ($fasilitas->foto && Storage::disk('public')->exists($fasilitas->foto)) {
                    Storage::disk('public')->delete($fasilitas->foto);
                }
                $dataUpdate['foto'] = $request->file('foto')->store('fasilitas', 'public');
            }

            // 5. UPDATE DATABASE (Pakai array $dataUpdate yang sudah dinamis)
            $fasilitas->update($dataUpdate);

            // 6. LOGIC CANCEL JADWAL (Jika Nonaktif)
            if ($request->ketersediaan !== 'aktif') {
                Jadwal::where('fasilitas_id', $id)
                    ->where('status', 'tersedia')
                    ->update(['status' => 'batal']);
            }

            // 7. LOG
            $this->recordLog($fasilitas, 'UPDATE', 'Mengubah data fasilitas: ' . $fasilitas->nama_fasilitas);

            return redirect()->route('admin.fasilitas.data.index')->with('success', 'Fasilitas diperbarui.');

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
