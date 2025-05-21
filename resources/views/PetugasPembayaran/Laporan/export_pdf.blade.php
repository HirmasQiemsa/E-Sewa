<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Keuangan {{ $bulan[$month] }} {{ $year }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.5;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }
        .title {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .subtitle {
            font-size: 14px;
            margin-bottom: 5px;
        }
        .period {
            font-size: 12px;
            font-style: italic;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .total-row {
            font-weight: bold;
            background-color: #f9f9f9;
        }
        .footer {
            margin-top: 30px;
            text-align: right;
            font-size: 11px;
        }
        .signature {
            margin-top: 50px;
            float: right;
            width: 200px;
            text-align: center;
        }
        .signature-line {
            margin-top: 40px;
            border-top: 1px solid #000;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="title">LAPORAN KEUANGAN FASILITAS</div>
        <div class="subtitle">DISPORA KOTA SEMARANG</div>
        <div class="period">Periode: {{ $bulan[$month] }} {{ $year }}</div>
    </div>

    <div>
        <h3>Ringkasan Transaksi</h3>
        <table>
            <tr>
                <th>Total Transaksi</th>
                <td>{{ $pemasukan->count() }} transaksi</td>
            </tr>
            <tr>
                <th>Total Pemasukan</th>
                <td>Rp {{ number_format($pemasukan->sum('jumlah_bayar'), 0, ',', '.') }}</td>
            </tr>
            <tr>
                <th>Tanggal Cetak</th>
                <td>{{ date('d F Y H:i') }}</td>
            </tr>
        </table>
    </div>

    <div>
        <h3>Daftar Transaksi</h3>
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Tanggal</th>
                    <th>User</th>
                    <th>Fasilitas</th>
                    <th class="text-right">Jumlah Bayar</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pemasukan as $index => $item)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td>{{ $item->created_at->format('d/m/Y H:i') }}</td>
                        <td>{{ $item->checkout->user->name ?? 'N/A' }}</td>
                        <td>{{ $item->fasilitas->nama_fasilitas ?? 'N/A' }}</td>
                        <td class="text-right">Rp {{ number_format($item->jumlah_bayar, 0, ',', '.') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center">Tidak ada data pemasukan</td>
                    </tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr class="total-row">
                    <th colspan="4" class="text-right">TOTAL</th>
                    <th class="text-right">Rp {{ number_format($pemasukan->sum('jumlah_bayar'), 0, ',', '.') }}</th>
                </tr>
            </tfoot>
        </table>
    </div>

    <div class="signature">
        <p>Semarang, {{ date('d F Y') }}</p>
        <p>Petugas Pembayaran</p>
        <div class="signature-line"></div>
        <p>{{ auth()->user()->name ?? 'Petugas Pembayaran' }}</p>
    </div>

    <div class="footer">
        <p>Dokumen ini dicetak secara otomatis dari sistem E-Sewa Fasilitas DISPORA</p>
    </div>
</body>
</html>
