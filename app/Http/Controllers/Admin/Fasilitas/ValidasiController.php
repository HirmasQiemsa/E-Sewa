<?php

namespace App\Http\Controllers\Admin\Fasilitas;

use App\Http\Controllers\Controller;
use App\Models\PengajuanEvent;
use App\Models\Fasilitas;
use Illuminate\Http\Request;

class ValidasiController extends Controller
{
    /**
     * Menampilkan daftar pengajuan event (List Validasi)
     * Route: admin.fasilitas.validasi.index
     */
    public function index(Request $request)
    {
        // 1. Ambil input filter dari request
        $search = $request->input('search');
        $status = $request->input('status');

        // 2. Query Dasar dengan Eager Loading agar hemat query
        $query = PengajuanEvent::with(['user', 'fasilitas'])
            ->latest(); // Urutkan dari yang terbaru

        // 3. Logika Filter Pencarian (Nama Event, Panitia, atau Nama User)
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nama_event', 'like', "%{$search}%")
                  ->orWhere('nama_panitia', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($subQ) use ($search) {
                      $subQ->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // 4. Logika Filter Status
        if ($status) {
            $query->where('status', $status);
        }

        // 5. Pagination
        $pengajuans = $query->paginate(10)->withQueryString();

        // 6. Return View (Sesuaikan folder view kamu)
        return view('Admin.Fasilitas.Validasi.index', compact('pengajuans'));
    }

    /**
     * Menampilkan detail pengajuan
     * Route: admin.fasilitas.validasi.show
     */
    public function show($id)
    {
        $pengajuan = PengajuanEvent::with(['user', 'fasilitas'])->findOrFail($id);

        return view('Admin.Fasilitas.Validasi.show', compact('pengajuan'));
    }

    /**
     * Menyetujui Pengajuan (Approve)
     * Route: admin.fasilitas.validasi.approve
     */
    public function validasi(Request $request, $id)
    {
        $pengajuan = PengajuanEvent::findOrFail($id);

        // Cek status biar ga double click
        if($pengajuan->status != 'pending') {
            return back()->with('error', 'Pengajuan ini sudah diproses sebelumnya.');
        }

        // Update Status
        $pengajuan->update([
            'status' => 'approved',
            'alasan_admin' => null // Kosongkan alasan jika sebelumnya pernah ditolak lalu diapprove manual
        ]);

        // OPSIONAL: Di sini nanti bisa ditambahkan logika untuk otomatis
        // membuat jadwal di tabel 'jadwals' agar tanggal tersebut terblokir.

        return redirect()->route('admin.fasilitas.validasi.index')
            ->with('success', 'Pengajuan Event berhasil DISETUJUI.');
    }

    /**
     * Menolak Pengajuan (Reject)
     * Route: admin.fasilitas.validasi.reject
     */
    public function tolak(Request $request, $id)
    {
        $pengajuan = PengajuanEvent::findOrFail($id);

        // Validasi Alasan Wajib Diisi
        $request->validate([
            'alasan_admin' => 'required|string|min:5'
        ]);

        if($pengajuan->status != 'pending') {
            return back()->with('error', 'Pengajuan ini sudah diproses sebelumnya.');
        }

        // Update Status & Alasan
        $pengajuan->update([
            'status' => 'rejected',
            'alasan_admin' => $request->alasan_admin
        ]);

        return redirect()->route('admin.fasilitas.validasi.index')
            ->with('success', 'Pengajuan Event berhasil DITOLAK.');
    }
}
