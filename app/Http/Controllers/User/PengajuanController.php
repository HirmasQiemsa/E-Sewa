<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PengajuanEvent;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PengajuanController extends Controller
{
    public function index(Request $request)
    {
        // Tampilkan halaman pengajuan event
        return view('user.pengajuan');
    }

    public function store(Request $request)
    {
        // 1. Validasi Input
        $request->validate([
            'nama_event'      => 'required|string|max:255',
            'nama_panitia'    => 'required|string|max:255',
            'id_fasilitas'    => 'required|exists:fasilitas,id',
            'tgl_mulai'       => 'required|date|after_or_equal:today',
            'tgl_selesai'     => 'required|date|after_or_equal:tgl_mulai',
            'deskripsi_event' => 'required|string',
            'file_surat'      => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        try {
            // 2. Cek apakah user punya pengajuan yang masih PENDING atau APPROVED (Belum Selesai)
            $existing = PengajuanEvent::where('id_user', Auth::id())
                ->whereIn('status', ['pending', 'approved'])
                ->first();

            if ($existing) {
                if ($existing->status === 'pending') {
                    return back()->with('error', 'Anda masih memiliki pengajuan yang menunggu konfirmasi.');
                }
                if ($existing->isOngoing()) {
                    return back()->with('error', 'Anda masih memiliki event yang sedang berjalan.');
                }
            }

            // 3. Handle File Upload
            $pathSurat = null;
            if ($request->hasFile('file_surat')) {
                $file = $request->file('file_surat');
                $filename = time() . '_' . $file->getClientOriginalName();
                $pathSurat = $file->storeAs('surat_event', $filename, 'public');
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

            // 5. Log Aktivitas (Opsional sesuai parent controller kamu)
            if (method_exists($this, 'recordLog')) {
                $this->recordLog($pengajuan, 'CREATE_PENGAJUAN', 'User mengajukan event: ' . $request->nama_event);
            }

            return back()->with('success', 'Pengajuan Event berhasil dikirim! Silakan tunggu konfirmasi admin.');

        } catch (\Exception $e) {
            // Jika gagal, hapus file yang sempat terupload (clean up)
            if (isset($pathSurat)) {
                Storage::disk('public')->delete($pathSurat);
            }
            return back()->with('error', 'Gagal mengajukan event: ' . $e->getMessage())->withInput();
        }
    }
}
