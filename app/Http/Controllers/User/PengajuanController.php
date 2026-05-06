<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PengajuanEvent;
use Illuminate\Support\Facades\Auth;
use App\Models\Fasilitas;
use Illuminate\Support\Facades\Storage;

class PengajuanController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $fasilitas = Fasilitas::where('ketersediaan', 'aktif')->get();
        $lastPengajuan = PengajuanEvent::where('id_user', Auth::id())
            ->latest()
            ->first();

        $pengajuans = PengajuanEvent::with('fasilitas')
            ->where('id_user', Auth::id())
            ->latest()
            ->get();

        return view('user.pengajuan', compact(
            'lastPengajuan',
            'pengajuans',
            'fasilitas',
            'user'
        ));
    }

    public function store(Request $request)
{
    // 1. Validasi Input (Tambahkan webp agar tidak ditolak)
    $request->validate([
        'nama_event'      => 'required|string|max:255',
        'nama_panitia'    => 'required|string|max:255',
        'id_fasilitas'    => 'required|exists:fasilitas,id',
        'tgl_mulai'       => 'required|date|after_or_equal:today',
        'tgl_selesai'     => 'required|date|after_or_equal:tgl_mulai',
        'deskripsi_event' => 'required|string',
        'file_surat'      => 'required|file|mimes:pdf,jpg,jpeg,png,webp|max:5120',
    ], [
        // Custom message biar user makin paham
        'file_surat.mimes' => 'Format file harus PDF, JPG, JPEG, PNG, atau WEBP.',
        'file_surat.max'   => 'Ukuran file maksimal adalah 5MB.',
    ]);

    $pathSurat = null;
    try {
        // 2. Cek apakah user punya pengajuan aktif
        $existing = \App\Models\PengajuanEvent::where('id_user', Auth::id())
            ->whereIn('status', ['pending', 'approved'])
            ->first();

        if ($existing) {
            if ($existing->status === 'pending') {
                return back()->with('error', 'Anda masih memiliki pengajuan yang menunggu konfirmasi.')->withInput();
            }
            if (method_exists($existing, 'isOngoing') && $existing->isOngoing()) {
                return back()->with('error', 'Anda masih memiliki event yang sedang berjalan.')->withInput();
            }
        }

        // 3. Handle File Upload
        if ($request->hasFile('file_surat')) {
            $pathSurat = $request->file('file_surat')->store('surat_event', 'public');
        }

        // 4. Create Data
        $pengajuan = PengajuanEvent::create([
            'id_user'         => Auth::id(),
            'id_fasilitas'    => $request->id_fasilitas,
            'nama_event'      => $request->nama_event,
            'nama_panitia'    => $request->nama_panitia,
            'tgl_mulai'       => $request->tgl_mulai,
            'tgl_selesai'     => $request->tgl_selesai,
            'deskripsi_event' => $request->deskripsi_event,
            'file_surat'      => $pathSurat,
            'status'          => 'pending',
        ]);

        // 5. Log Aktivitas
        if (method_exists($this, 'recordLog')) {
            $this->recordLog($pengajuan, 'CREATE_PENGAJUAN', 'User mengajukan event: ' . $request->nama_event);
        }

        return redirect()->route('user.beranda')->with('success', 'Pengajuan Event berhasil dikirim!');

    } catch (\Exception $e) {
        if ($pathSurat) {
            \Storage::disk('public')->delete($pathSurat);
        }
        return back()->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage())->withInput();
    }
}
}
