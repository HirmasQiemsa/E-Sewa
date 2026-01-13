<?php

namespace App\Http\Controllers\Admin\Super;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Fasilitas;

class ApprovalController extends Controller
{
    public function index()
    {
        $approvals = Fasilitas::with('adminFasilitas')
                        ->whereIn('status_approval', ['draft', 'pending'])
                        ->latest()
                        ->get();

        return view('Admin.Super.Approval.index', compact('approvals'));
    }

    public function show($id)
    {
        $fasilitas = Fasilitas::with(['adminFasilitas', 'adminPembayaran'])->findOrFail($id);
        return view('Admin.Super.Approval.show', compact('fasilitas'));
    }

    public function action(Request $request, $id)
    {
        $request->validate([
            'action' => 'required|in:approve,reject',
            'catatan' => 'nullable|string'
        ]);

        $fasilitas = Fasilitas::findOrFail($id);

        try {
            if ($request->action == 'approve') {
                $fasilitas->update([
                    'status_approval' => 'approved',
                ]);

                // === [ADD LOG] ===
                $this->recordLog($fasilitas, 'APPROVE', 'Menyetujui fasilitas: ' . $fasilitas->nama_fasilitas);

                $msg = "Fasilitas disetujui.";
            } else {
                $fasilitas->update([
                    'status_approval' => 'rejected',
                    'deskripsi' => $fasilitas->deskripsi . "\n\n[CATATAN SUPER ADMIN]: " . $request->catatan
                ]);

                // === [ADD LOG] ===
                $this->recordLog($fasilitas, 'REJECT', 'Menolak fasilitas: ' . $fasilitas->nama_fasilitas . '. Alasan: ' . $request->catatan);

                $msg = "Fasilitas ditolak.";
            }

            return redirect()->route('admin.super.approval.index')->with('success', $msg);

        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
