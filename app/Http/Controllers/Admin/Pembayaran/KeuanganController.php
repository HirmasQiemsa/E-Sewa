<?php
namespace App\Http\Controllers\Admin\Pembayaran;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pemasukan;
use App\Models\Fasilitas;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class KeuanganController extends Controller
{
    /**
     * Menampilkan tab transaksi keuangan
     */
    public function transaksi(Request $request)
    {
        $adminId = Auth::guard('admin')->id();

        // 1. DAPATKAN ID FASILITAS MILIK ADMIN INI
        // Kita simpan dalam array agar bisa dipakai di filter query dan dropdown
        $myFasilitasIds = Fasilitas::where('admin_pembayaran_id', $adminId)
                                ->pluck('id')
                                ->toArray();

        // Filter Input
        $startDate   = $request->input('start_date', Carbon::now()->subDays(30)->format('Y-m-d'));
        $endDate     = $request->input('end_date', Carbon::now()->format('Y-m-d'));
        $fasilitasId = $request->input('fasilitas_id');
        $search      = $request->input('search');

        // 2. QUERY DASAR (Wajib Filter Fasilitas Milik Admin)
        $query = Pemasukan::with(['checkout.user', 'fasilitas'])
                          ->whereIn('fasilitas_id', $myFasilitasIds) // <--- FILTER UTAMA
                          ->whereDate('created_at', '>=', $startDate)
                          ->whereDate('created_at', '<=', $endDate)
                          ->where('status', 'lunas')
                          ->orderBy('created_at', 'desc');

        // 3. Filter Spesifik Fasilitas (Jika user memilih dari dropdown)
        if ($fasilitasId) {
            // Validasi tambahan: Pastikan ID yang dipilih benar-benar milik admin ini
            if (in_array($fasilitasId, $myFasilitasIds)) {
                $query->where('fasilitas_id', $fasilitasId);
            }
        }

        // 4. Filter Pencarian
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

        // 5. Eksekusi Query
        // Gunakan clone untuk sum agar tidak merusak query pagination
        $totalPemasukan = (clone $query)->sum('jumlah_bayar');
        $pemasukan      = $query->paginate(15)->withQueryString();

        // 6. LIST FASILITAS UNTUK DROPDOWN FILTER
        // Hanya tampilkan fasilitas milik admin ini
        $fasilitas = Fasilitas::where('admin_pembayaran_id', $adminId)
                            ->where('ketersediaan', 'aktif')
                            ->get();

        return view('Admin.Pembayaran.Keuangan.transaksi', compact(
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
        $adminId = Auth::guard('admin')->id();

        // 1. Ambil Fasilitas Milik Admin Ini
        $myFasilitasIds = Fasilitas::where('admin_pembayaran_id', $adminId)->pluck('id');

        // 2. Filter Tanggal (Gunakan input langsung, default ke bulan ini)
        $month = $request->input('month', now()->month);
        $year  = $request->input('year', now()->year);

        // 3. Query Pemasukan (Filter Status, Tanggal, DAN Fasilitas Milik Admin)
        $pemasukan = Pemasukan::whereIn('fasilitas_id', $myFasilitasIds) // <--- PENTING: Filter Fasilitas
                                ->where('status', 'lunas')
                                ->whereMonth('created_at', $month)
                                ->whereYear('created_at', $year)
                                ->get();

        // ... (Sisa logika hitung total & grouping tetap sama) ...
        $totalPemasukan = $pemasukan->sum('jumlah_bayar');

        $pemasukanByFasilitas = $pemasukan->groupBy('fasilitas_id')->map(function ($items, $key) {
            $fasilitas = Fasilitas::find($key);
            return [
                'nama_fasilitas' => $fasilitas ? $fasilitas->nama_fasilitas : 'Unknown',
                'total' => $items->sum('jumlah_bayar'),
                'count' => $items->count()
            ];
        });

        // 4. LOGIKA GRAFIK MINGGUAN
        // Kita paksa array index mulai dari 0 agar cocok dengan Chart.js Labels
        $weeklyStats = [0, 0, 0, 0, 0]; // Index 0-4 (Minggu 1-5)

        foreach ($pemasukan as $trx) {
            $tgl = $trx->created_at->day;
            // Rumus index array: ceil(tgl/7) - 1.
            // Tgl 1-7 -> Minggu 1 -> Index 0
            $weekIndex = ceil($tgl / 7) - 1;

            if ($weekIndex > 4) $weekIndex = 4; // Mentok di Minggu 5

            $weeklyStats[$weekIndex] += $trx->jumlah_bayar;
        }

        $chartLabels = ['Minggu 1', 'Minggu 2', 'Minggu 3', 'Minggu 4', 'Minggu 5'];
        $chartData   = $weeklyStats;

        $bulan = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];

        return view('Admin.Pembayaran.Keuangan.ringkasan', compact(
            'month', 'year', 'bulan', 'totalPemasukan', 'pemasukanByFasilitas', 'chartLabels', 'chartData'
        ));
    }

    public function exportRingkasan($type)
    {
        $month = request('month', date('n'));
        $year = request('year', date('Y'));

        // Get income data
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
        $filename = 'Laporan_Keuangan_' . $bulan[$month] . '_' . $year;

        // If PDF export is requested
        if ($type === 'pdf') {
            // Return view for PDF rendering
            return view('Admin.Pembayaran.Keuangan.export_pdf', compact('pemasukan', 'month', 'year', 'bulan'));
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
        $query = Pemasukan::with(['checkout.user', 'fasilitas'])
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

