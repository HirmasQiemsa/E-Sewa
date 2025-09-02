@extends('PetugasPembayaran.component')
@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Dashboard Petugas Pembayaran</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item active"><i class="fas fa-database mr-1"></i> Dashboard</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <!-- Small boxes (Stat box) -->
            <div class="row">
                <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3>Rp {{ number_format($totalPemasukan ?? 0, 0, ',', '.') }}</h3>
                            <p>Total Pemasukan</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-money-bill-wave"></i>
                        </div>
                        <a href="{{ route('petugas_pembayaran.keuangan.transaksi') }}" class="small-box-footer">Lihat Detail <i
                                class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <!-- ./col -->
                <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3>{{ $transaksiHariIni ?? 0 }}</h3>
                            <p>Transaksi Hari Ini</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-cash-register"></i>
                        </div>
                        <a href="{{ route('petugas_pembayaran.pembayaran.index') }}?filter=today" class="small-box-footer">Lihat Detail <i
                                class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <!-- ./col -->
                <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box bg-warning">
                        <div class="inner">
                            <h3>{{ $menungguVerifikasi ?? 0 }}</h3>
                            <p>Menunggu Verifikasi</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-hourglass-half"></i>
                        </div>
                        <a href="{{ route('petugas_pembayaran.pembayaran.index') }}?status=pending" class="small-box-footer">Lihat
                            Detail <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <!-- ./col -->
                <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box bg-primary">
                        <div class="inner">
                            <h3>{{ $totalBooking ?? 0 }}</h3>
                            <p>Total Booking Aktif</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-calendar-check"></i>
                        </div>
                        <a href="{{ route('petugas_pembayaran.pembayaran.index') }}" class="small-box-footer">Lihat Semua <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <!-- ./col -->
            </div>
            <!-- /.row -->

            <!-- Transaksi Hari Ini -->
            <div class="row">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-money-check mr-1"></i>
                                Transaksi Pembayaran Hari Ini
                            </h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body table-responsive p-0">
                            <table class="table table-hover text-nowrap">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Fasilitas</th>
                                        <th>Jumlah</th>
                                        <th>Metode</th>
                                        <th>User</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($transaksiToday ?? [] as $transaksi)
                                        <tr>
                                            <td>{{ $transaksi->id }}</td>
                                            <td>{{ $transaksi->fasilitas->nama_fasilitas }}</td>
                                            <td>Rp {{ number_format($transaksi->jumlah_bayar, 0, ',', '.') }}</td>
                                            <td>{{ ucfirst($transaksi->metode_pembayaran) }}</td>
                                            <td>{{ $transaksi->checkout->user->name ?? 'N/A' }}</td>
                                            <td>
                                                @if ($transaksi->status == 'lunas')
                                                    <span class="badge badge-success">Lunas</span>
                                                @elseif($transaksi->status == 'fee')
                                                    <span class="badge badge-warning">DP</span>
                                                @elseif($transaksi->status == 'pending')
                                                    <span class="badge badge-info">Pending</span>
                                                @elseif($transaksi->status == 'batal')
                                                    <span class="badge badge-danger">Batal</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center">Tidak ada transaksi pembayaran hari ini</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <!-- /.card-body -->
                        <div class="card-footer clearfix">
                            <a href="{{ route('petugas_pembayaran.pembayaran.index') }}" class="btn btn-sm btn-primary ml-2">
                                <i class="fas fa-list"></i> Lihat Semua Transaksi
                            </a>
                        </div>
                    </div>
                    <!-- /.card -->
                </div>

                <!-- Status Pembayaran Chart Card -->
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Status Pembayaran</h3>
                            <div class="card-tools">
                                <!-- Perbaikan tombol collapse -->
                                <button type="button" class="btn btn-tool" data-card-widget="collapse"
                                    data-toggle="tooltip" title="Collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="chart-container">
                                <canvas id="paymentStatusChart"
                                    style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                            </div>

                            
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-history mr-1"></i>
                                Aktivitas Pembayaran Terbaru
                            </h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table m-0">
                                    <thead>
                                        <tr>
                                            <th>Tanggal/Jam</th>
                                            <th>User</th>
                                            <th>Fasilitas</th>
                                            <th>Jumlah</th>
                                            <th>Keterangan</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($recentPayments ?? [] as $payment)
                                            <tr>
                                                <td>{{ date('d/m/Y H:i', strtotime($payment->created_at)) }}</td>
                                                <td>{{ $payment->checkout->user->name ?? 'System' }}</td>
                                                <td>{{ $payment->fasilitas->nama_fasilitas ?? 'N/A' }}</td>
                                                <td>Rp {{ number_format($payment->jumlah_bayar, 0, ',', '.') }}</td>
                                                <td>
                                                    @if($payment->status == 'fee')
                                                        Pembayaran DP
                                                    @elseif($payment->status == 'lunas')
                                                        Pelunasan
                                                    @elseif($payment->status == 'batal')
                                                        Pembayaran Dibatalkan
                                                    @else
                                                        {{ $payment->keterangan ?? '-' }}
                                                    @endif
                                                </td>
                                                <td>
                                                    <span class="badge badge-{{ $payment->status == 'lunas' ? 'success' : ($payment->status == 'fee' ? 'warning' : ($payment->status == 'batal' ? 'danger' : 'info')) }}">
                                                        {{ $payment->status == 'lunas' ? 'Lunas' : ($payment->status == 'fee' ? 'DP' : ($payment->status == 'batal' ? 'Batal' : 'Pending')) }}
                                                    </span>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6" class="text-center">Belum ada aktivitas pembayaran terbaru</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="card-footer clearfix">
                            <a href="{{ route('petugas_pembayaran.keuangan.transaksi') }}"
                                class="btn btn-sm btn-secondary float-right">Lihat Semua Riwayat</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Script untuk chart status pembayaran -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Ensure jQuery and Chart are loaded
        if (window.jQuery && typeof Chart !== 'undefined') {
            var ctx = document.getElementById('paymentStatusChart');

            if (ctx) {
                // Data untuk chart
                var data = {
                    labels: ['Lunas', 'DP', 'Pending', 'Batal'], // Menambahkan labels
                    datasets: [{
                        data: [
                            {{ $pembayaranLunas ?? 0 }},
                            {{ $pembayaranDP ?? 0 }},
                            {{ $pembayaranPending ?? 0 }}, // Menambahkan data pending
                            {{ $pembayaranBatal ?? 0 }}
                        ],
                        backgroundColor: ['#28a745', '#ffc107', '#17a2b8', '#dc3545'], // Menambahkan warna untuk pending
                        hoverOffset: 4
                    }]
                };

                // Opsi chart
                var options = {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                padding: 20,
                                boxWidth: 12
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    var label = context.label || '';
                                    var value = context.raw || 0;
                                    var total = context.dataset.data.reduce((a, b) => a + b, 0);
                                    var percentage = total > 0 ? Math.round((value / total) * 100) : 0;
                                    return label + ': ' + value + ' (' + percentage + '%)';
                                }
                            }
                        }
                    },
                    animation: {
                        animateScale: true,
                        animateRotate: true
                    },
                    cutout: '60%' // Untuk tampilan donut yang lebih baik
                };

                // Inisialisasi chart
                var paymentChart = new Chart(ctx, {
                    type: 'doughnut',
                    data: data,
                    options: options
                });

                // Debug info
                console.log('Chart initialized with data:', data);
            } else {
                console.error('Canvas element not found');
            }
        } else {
            console.error('jQuery or Chart.js not loaded');
        }
    });
</script>
@endsection
