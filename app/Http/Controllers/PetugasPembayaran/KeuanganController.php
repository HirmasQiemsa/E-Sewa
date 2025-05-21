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
     * Menampilkan tab transaksi keuangan
     */
    public function transaksi(Request $request)
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
     * Menampilkan tab ringkasan keuangan
     */
    public function ringkasan(Request $request)
    {
        // Default to current month and year if not specified
        $month = $request->input('month', date('n'));
        $year = $request->input('year', date('Y'));

        // Get income data based on the filter
        $pemasukan = PemasukanSewa::where('status', 'lunas')
                                ->whereMonth('created_at', $month)
                                ->whereYear('created_at', $year)
                                ->get();

        // Calculate total income
        $totalPemasukan = $pemasukan->sum('jumlah_bayar');

        // Group income by facility
        $pemasukanByFasilitas = $pemasukan->groupBy('fasilitas_id')
                                        ->map(function ($items, $key) {
                                            $fasilitas = Fasilitas::find($key);
                                            return [
                                                'nama_fasilitas' => $fasilitas ? $fasilitas->nama_fasilitas : 'Unknown',
                                                'total' => $items->sum('jumlah_bayar'),
                                                'count' => $items->count()
                                            ];
                                        });

        // Calculate monthly statistics for the whole year (for chart)
        // Use DB::connection()->getDriverName() to make the query database-agnostic
        $pemasukanBulanan = [];
        if (DB::connection()->getDriverName() === 'sqlite') {
            // For SQLite
            $monthlyData = PemasukanSewa::where('status', 'lunas')
                ->whereYear('created_at', $year)
                ->selectRaw("strftime('%m', created_at) as month, SUM(jumlah_bayar) as total")
                ->groupBy('month')
                ->get();
        } else {
            // For MySQL and other databases
            $monthlyData = PemasukanSewa::where('status', 'lunas')
                ->whereYear('created_at', $year)
                ->selectRaw("MONTH(created_at) as month, SUM(jumlah_bayar) as total")
                ->groupBy('month')
                ->get();
        }

        // Convert to the format we need
        foreach ($monthlyData as $data) {
            // Convert month to integer (remove leading 0 if it exists)
            $monthNumber = (int)$data->month;
            $pemasukanBulanan[$monthNumber] = $data->total;
        }

        // Prepare chart data for all months
        $chartData = [];
        for ($i = 1; $i <= 12; $i++) {
            $chartData[] = $pemasukanBulanan[$i] ?? 0;
        }

        // List of months for display
        $bulan = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];

        return view('PetugasPembayaran.Keuangan.ringkasan', compact(
            'month',
            'year',
            'bulan',
            'totalPemasukan',
            'pemasukanByFasilitas',
            'chartData'
        ));
    }

    public function exportRingkasan($type)
    {
        $month = request('month', date('n'));
        $year = request('year', date('Y'));

        // Get income data
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
        $filename = 'Laporan_Keuangan_' . $bulan[$month] . '_' . $year;

        // If PDF export is requested
        if ($type === 'pdf') {
            // Return view for PDF rendering
            return view('PetugasPembayaran.Laporan.export_pdf', compact('pemasukan', 'month', 'year', 'bulan'));
        } else {
            // Fall back to CSV if needed
            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="' . $filename . '.csv"',
            ];

            $handle = fopen('php://temp', 'r+');

            // Header columns
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

            // Add total
            fputcsv($handle, ['', '', '', '', 'TOTAL:', $pemasukan->sum('jumlah_bayar')]);

            rewind($handle);
            $contents = stream_get_contents($handle);
            fclose($handle);

            return response($contents, 200, $headers);
        }
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
}

