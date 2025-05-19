<?php

namespace App\Http\Controllers\PetugasPembayaran;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PemasukanSewa;
use App\Models\Fasilitas;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PemasukanController extends Controller
{
    /**
     * Menampilkan riwayat pemasukan
     */
    public function index(Request $request)
    {
        // Filter berdasarkan tanggal dan fasilitas
        $startDate = $request->input('start_date', Carbon::now()->subDays(30)->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::now()->format('Y-m-d'));
        $fasilitasId = $request->input('fasilitas_id');
        $search = $request->input('search');

        // Query dasar dengan relasi
        $query = PemasukanSewa::with(['checkout.user', 'fasilitas'])
                             ->whereDate('created_at', '>=', $startDate)
                             ->whereDate('created_at', '<=', $endDate)
                             ->where('status', 'lunas') // Hanya pemasukan yang sudah lunas
                             ->orderBy('created_at', 'desc');

        // Filter berdasarkan fasilitas
        if ($fasilitasId) {
            $query->where('fasilitas_id', $fasilitasId);
        }

        // Filter berdasarkan pencarian
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->whereHas('checkout.user', function($userQ) use ($search) {
                    $userQ->where('name', 'LIKE', "%{$search}%");
                })
                ->orWhereHas('fasilitas', function($fasQ) use ($search) {
                    $fasQ->where('nama_fasilitas', 'LIKE', "%{$search}%");
                })
                ->orWhere('jumlah_bayar', 'LIKE', "%{$search}%");
            });
        }

        // Paginasi hasil
        $pemasukan = $query->paginate(15)->withQueryString();

        // Hitung total pemasukan
        $totalPemasukan = $query->sum('jumlah_bayar');

        // Daftar fasilitas untuk filter
        $fasilitas = Fasilitas::where('ketersediaan', 'aktif')->get();

        return view('PetugasPembayaran.Pemasukan.index', compact(
            'pemasukan',
            'fasilitas',
            'startDate',
            'endDate',
            'fasilitasId',
            'search',
            'totalPemasukan'
        ));
    }

    /**
     * Menampilkan detail pemasukan
     */
    public function show($id)
    {
        $pemasukan = PemasukanSewa::with(['checkout.user', 'fasilitas', 'checkout.jadwals'])
                                 ->findOrFail($id);

        return view('PetugasPembayaran.Pemasukan.show', compact('pemasukan'));
    }

    /**
     * Export data pemasukan
     */
    public function export(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->subDays(30)->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::now()->format('Y-m-d'));
        $fasilitasId = $request->input('fasilitas_id');

        // Query data pemasukan
        $query = PemasukanSewa::with(['checkout.user', 'fasilitas'])
                             ->whereDate('created_at', '>=', $startDate)
                             ->whereDate('created_at', '<=', $endDate)
                             ->where('status', 'lunas')
                             ->orderBy('created_at', 'desc');

        // Filter berdasarkan fasilitas
        if ($fasilitasId) {
            $query->where('fasilitas_id', $fasilitasId);
        }

        $pemasukan = $query->get();

        // Judul file ekspor
        $filename = 'Pemasukan_' . $startDate . '_' . $endDate . '.csv';

        // Header kolom
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        // Membuat file CSV
        $handle = fopen('php://temp', 'r+');

        // Menambahkan header kolom
        fputcsv($handle, [
            'ID', 'Tanggal', 'User', 'Fasilitas', 'Jumlah Bayar', 'Metode Pembayaran'
        ]);

        // Menambahkan data
        foreach ($pemasukan as $item) {
            fputcsv($handle, [
                $item->id,
                $item->created_at->format('d/m/Y H:i'),
                $item->checkout->user->name ?? 'N/A',
                $item->fasilitas->nama_fasilitas ?? 'N/A',
                $item->jumlah_bayar,
                ucfirst($item->metode_pembayaran)
            ]);
        }

        // Kembali ke awal file
        rewind($handle);

        // Mengambil semua konten
        $contents = stream_get_contents($handle);

        // Menutup file
        fclose($handle);

        // Mengembalikan file untuk diunduh
        return response($contents, 200, $headers);
    }
}
