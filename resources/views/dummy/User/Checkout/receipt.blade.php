@extends('layouts.print')
@section('title', 'Bukti Pembayaran #' . $checkout->id)
@section('content')
<div class="receipt-container">
    <div class="receipt-header">
        <div class="logo">
            <img src="{{ asset('img/logo.png') }}" alt="DISPORA Logo">
        </div>
        <div class="receipt-title">
            <h1>E-SEWA FASILITAS DISPORA SEMARANG</h1>
            <p>Jl. Pamularsih Raya No. 20, Semarang Barat, Jawa Tengah</p>
            <p>Telp: (024) 76060606 | Email: dispora@semarangkota.go.id</p>
        </div>
    </div>

    <div class="receipt-divider"></div>

    <div class="receipt-info">
        <h2>BUKTI PEMBAYARAN</h2>
        <div class="receipt-number">
            <span>No. Booking:</span>
            <strong>#{{ $checkout->id }}</strong>
        </div>
    </div>

    <div class="receipt-body">
        <div class="customer-info">
            <table>
                <tr>
                    <td>Nama Pemesan</td>
                    <td>: {{ $checkout->user->name }}</td>
                </tr>
                <tr>
                    <td>Tanggal Pemesanan</td>
                    <td>: {{ date('d F Y H:i', strtotime($checkout->created_at)) }}</td>
                </tr>
                <tr>
                    <td>Status Pembayaran</td>
                    <td>: <span class="status-lunas">LUNAS</span></td>
                </tr>
                <tr>
                    <td>Tanggal Pelunasan</td>
                    <td>: {{ date('d F Y H:i', strtotime($lastPayment->tanggal_bayar)) }}</td>
                </tr>
            </table>
        </div>

        <div class="facility-info">
            <h3>Detail Fasilitas</h3>
            <table>
                <tr>
                    <td>Nama Fasilitas</td>
                    <td>: {{ $checkout->jadwal->fasilitas->nama_fasilitas }}</td>
                </tr>
                <tr>
                    <td>Tipe</td>
                    <td>: {{ $checkout->jadwal->fasilitas->tipe }}</td>
                </tr>
                <tr>
                    <td>Lokasi</td>
                    <td>: {{ $checkout->jadwal->fasilitas->lokasi }}</td>
                </tr>
            </table>
        </div>

        <div class="schedule-info">
            <h3>Jadwal Penggunaan</h3>
            <table class="details-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Tanggal</th>
                        <th>Jam</th>
                        <th>Durasi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($checkout->jadwals as $index => $jadwal)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ date('d F Y', strtotime($jadwal->tanggal)) }}</td>
                        <td>{{ substr($jadwal->jam_mulai, 0, 5) }} - {{ substr($jadwal->jam_selesai, 0, 5) }}</td>
                        <td>{{ $jadwal->durasi }} jam</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3" class="text-right">Total Durasi:</td>
                        <td>{{ $totalDurasi }} jam</td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <div class="payment-info">
            <h3>Riwayat Pembayaran</h3>
            <table class="details-table">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Keterangan</th>
                        <th>Metode</th>
                        <th>Jumlah</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($payments as $payment)
                    <tr>
                        <td>{{ date('d/m/Y H:i', strtotime($payment->tanggal_bayar)) }}</td>
                        <td>{{ $payment->status == 'kompensasi' ? 'Pembayaran DP' : 'Pelunasan' }}</td>
                        <td>{{ ucfirst($payment->metode_pembayaran) }}</td>
                        <td class="text-right">Rp {{ number_format($payment->jumlah_bayar, 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3" class="text-right">Total Pembayaran:</td>
                        <td class="text-right">Rp {{ number_format($checkout->total_bayar, 0, ',', '.') }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <div class="notes">
            <h3>Catatan Penting:</h3>
            <ol>
                <li>Bukti pembayaran ini harap dibawa saat menggunakan fasilitas.</li>
                <li>Keterlambatan 15 menit dari jadwal yang ditentukan akan dianggap tidak hadir.</li>
                <li>Penggunaan fasilitas sesuai dengan jadwal yang telah dipesan.</li>
                <li>Apabila terjadi pembatalan dari pihak DISPORA, maka pembayaran akan dikembalikan sepenuhnya.</li>
            </ol>
        </div>
    </div>

    <div class="receipt-footer">
        <div class="receipt-signature">
            <div class="signature-box">
                <p>Semarang, {{ date('d F Y', strtotime(now())) }}</p>
                <p>Petugas</p>
                <div class="signature-line"></div>
                <p>Petugas DISPORA</p>
            </div>
        </div>
        <div class="receipt-validation">
            <p>Dokumen ini dibuat secara digital pada: {{ $generatedDate }}</p>
            <p>Dokumen ini sah tanpa tanda tangan dan stempel</p>
        </div>
    </div>
</div>
@endsection
