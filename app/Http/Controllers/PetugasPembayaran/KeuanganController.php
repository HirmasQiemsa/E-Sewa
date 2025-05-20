<?php
namespace App\Http\Controllers\PetugasPembayaran;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PemasukanSewa;
use App\Models\Fasilitas;
use App\Models\LaporanPemasukanSewa;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class KeuanganController extends Controller
{
    /**
     * Menampilkan halaman utama kelola keuangan
     */
    public function index()
    {
        // Redirect ke tab transaksi sebagai default
        return redirect()->route('petugas_pembayaran.keuangan.transaksi');
    }

    /**
     * Menampilkan tab transaksi keuangan (menggunakan data dari PemasukanController)
     */
    public function transaksi(Request $request)
    {
        // Kode dari PemasukanController->index()
        $startDate = $request->input('start_date', Carbon::now()->subDays(30)->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::now()->format('Y-m-d'));
        $fasilitasId = $request->input('fasilitas_id');
        $search = $request->input('search');

        // Query dasar dengan relasi
        $query = PemasukanSewa::with(['checkout.user', 'fasilitas'])
                             ->whereDate('created_at', '>=', $startDate)
                             ->whereDate('created_at', '<=', $endDate)
                             ->where('status', 'lunas')
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

        return view('PetugasPembayaran.Keuangan.transaksi', compact(
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
     * Menampilkan tab ringkasan keuangan (menggunakan data dari LaporanController)
     */
    public function ringkasan(Request $request)
    {
        // Kode dari LaporanController->index()
        $month = $request->input('month', Carbon::now()->month);
        $year = $request->input('year', Carbon::now()->year);

        // Dapatkan data pemasukan berdasarkan filter
        $pemasukan = PemasukanSewa::where('status', 'lunas')
                                 ->whereMonth('created_at', $month)
                                 ->whereYear('created_at', $year)
                                 ->get();

        // Hitung total pemasukan
        $totalPemasukan = $pemasukan->sum('jumlah_bayar');

        // Grup pemasukan berdasarkan fasilitas
        $pemasukanByFasilitas = $pemasukan->groupBy('fasilitas_id')
                                        ->map(function ($items, $key) {
                                            $fasilitas = Fasilitas::find($key);
                                            return [
                                                'nama_fasilitas' => $fasilitas ? $fasilitas->nama_fasilitas : 'Unknown',
                                                'total' => $items->sum('jumlah_bayar'),
                                                'count' => $items->count()
                                            ];
                                        });

        // Hitung statistik per bulan (untuk chart)
        $pemasukanBulanan = PemasukanSewa::where('status', 'lunas')
                                       ->whereYear('created_at', $year)
                                       ->select(DB::raw('MONTH(created_at) as month'), DB::raw('SUM(jumlah_bayar) as total'))
                                       ->groupBy('month')
                                       ->get()
                                       ->pluck('total', 'month')
                                       ->toArray();

        // Daftar bulan untuk tampilan
        $bulan = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];

        // Siapkan data chart
        $chartData = [];
        for ($i = 1; $i <= 12; $i++) {
            $chartData[] = $pemasukanBulanan[$i] ?? 0;
        }

        return view('PetugasPembayaran.Keuangan.ringkasan', compact(
            'month',
            'year',
            'bulan',
            'totalPemasukan',
            'pemasukanByFasilitas',
            'chartData'
        ));
    }

    /**
     * Export data transaksi keuangan ke CSV
     */
    public function exportTransaksi(Request $request)
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

     /**
     * Export data ringkasan keuangan ke PDF
     */
    public function exportRingkasan($type)
    {
        $month = request('month', Carbon::now()->month);
        $year = request('year', Carbon::now()->year);

        // Dapatkan data pemasukan
        $pemasukan = PemasukanSewa::where('status', 'lunas')
                                 ->whereMonth('created_at', $month)
                                 ->whereYear('created_at', $year)
                                 ->with(['checkout.user', 'fasilitas'])
                                 ->get();

        $bulan = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];

        // Filename
        $filename = 'Laporan_Keuangan_' . $bulan[$month] . '_' . $year . '.' . $type;

        if ($type === 'csv') {
            // Export to CSV
            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            ];

            $handle = fopen('php://temp', 'r+');

            // Header kolom
            fputcsv($handle, [
                'ID', 'Tanggal', 'User', 'Fasilitas', 'Jumlah Bayar', 'Metode Pembayaran'
            ]);

            // Data
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

            // Tambahkan total
            fputcsv($handle, ['', '', '', '', 'TOTAL:', $pemasukan->sum('jumlah_bayar')]);

            rewind($handle);
            $contents = stream_get_contents($handle);
            fclose($handle);

            return response($contents, 200, $headers);
        } else {
            // Default export - PDF report view
            return view('PetugasPembayaran.Laporan.export', compact('pemasukan', 'month', 'year', 'bulan'));
        }
    }
}
