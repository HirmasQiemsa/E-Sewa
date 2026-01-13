<?php

namespace App\Http\Controllers\Admin\Super;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Checkout;
use App\Models\ActivityLog;
use App\Models\Pemasukan;
use Barryvdh\DomPDF\Facade\Pdf; // Jika nanti mau export PDF

class LaporanController extends Controller
{
    // === 1. LAPORAN TRANSAKSI GLOBAL ===
    public function transaksi(Request $request)
    {
        $query = Checkout::with(['user', 'jadwals.fasilitas'])->latest();

        // Filter Tanggal
        if ($request->filled(['start_date', 'end_date'])) {
            $query->whereBetween('created_at', [$request->start_date, $request->end_date]);
        }

        // Filter Status
        if ($request->filled('status') && $request->status != 'all') {
            $query->where('status', $request->status);
        }

        $transaksi = $query->paginate(15)->withQueryString();

        // Hitung Total Pemasukan (Hanya dari Checkout Lunas & DP)
        $totalPemasukan = Pemasukan::whereIn('checkout_id', $transaksi->pluck('id'))
            ->where('status', 'lunas') // <-- Cukup ini saja (Sama persis dengan Dashboard)
            ->sum('jumlah_bayar');

        return view('Admin.Super.Laporan.transaksi', compact('transaksi', 'totalPemasukan'));
    }

    // === 2. LOG AKTIVITAS (AUDIT TRAIL) ===
    public function log(Request $request)
    {
        // Eager load 'admin' karena log mencatat admin_id
        $query = ActivityLog::with('admin')->latest();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('description', 'like', "%$search%")
                ->orWhereHas('admin', function($q) use ($search) {
                    $q->where('name', 'like', "%$search%");
                });
        }

        $logs = $query->paginate(50)->withQueryString();

        return view('Admin.Super.Laporan.log', compact('logs'));
    }
}
