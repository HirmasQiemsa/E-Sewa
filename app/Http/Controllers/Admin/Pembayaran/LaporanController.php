<?php

namespace App\Http\Controllers\Admin\Pembayaran;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pemasukan;
use App\Models\Fasilitas;
use App\Models\LaporanPemasukanSewa;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class LaporanController extends Controller
{
    /**
     * Menampilkan halaman laporan keuangan
     */
    public function index(Request $request)
    {
        // Filter laporan berdasarkan bulan dan tahun
        $month = $request->input('month', Carbon::now()->month);
        $year = $request->input('year', Carbon::now()->year);

        // Dapatkan data pemasukan berdasarkan filter
        $pemasukan = Pemasukan::where('status', 'lunas')
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
        $pemasukanBulanan = Pemasukan::where('status', 'lunas')
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

        return view('AdminPembayaran.Laporan.index', compact(
            'month',
            'year',
            'bulan',
            'totalPemasukan',
            'pemasukanByFasilitas',
            'chartData'
        ));
    }

    /**
     * Generate laporan keuangan
     */
    public function generate(Request $request)
    {
        $request->validate([
            'month' => 'required|integer|between:1,12',
            'year' => 'required|integer|between:2020,2030'
        ]);

        $month = $request->month;
        $year = $request->year;

        DB::beginTransaction();
        try {
            // Ambil semua pemasukan pada bulan tersebut
            $pemasukan = Pemasukan::where('status', 'lunas')
                                    ->whereMonth('created_at', $month)
                                    ->whereYear('created_at', $year)
                                    ->get();

            // Hitung total pemasukan
            $totalPemasukan = $pemasukan->sum('jumlah_bayar');

            // Simpan laporan
            $laporan = LaporanPemasukanSewa::updateOrCreate(
                [
                    'bulan' => $month,
                    'tahun' => $year
                ],
                [
                    'total_pemasukan' => $totalPemasukan,
                    'pemasukan_sewa_id' => $pemasukan->first() ? $pemasukan->first()->id : null
                ]
            );

            DB::commit();

            return redirect()->route('petugas_pembayaran.laporan.index', ['month' => $month, 'year' => $year])
                           ->with('success', 'Laporan keuangan berhasil dibuat');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Gagal membuat laporan: ' . $e->getMessage());
        }
    }

    /**
     * Export laporan keuangan
     */
    public function export($type)
    {
        $month = request('month', Carbon::now()->month);
        $year = request('year', Carbon::now()->year);

        // Dapatkan data pemasukan
        $pemasukan = Pemasukan::where('status', 'lunas')
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
            return view('AdminPembayaran.Laporan.export', compact('pemasukan', 'month', 'year', 'bulan'));
        }
    }
}
