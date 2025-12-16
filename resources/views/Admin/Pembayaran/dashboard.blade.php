@extends('Admin.component') {{-- Pastikan layout induknya benar --}}

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Dashboard Keuangan</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item active"><i class="fas fa-database mr-1"></i> Dashboard</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-success">
                        <div class="inner">
                            {{-- PERUBAHAN 1: Akses array $data --}}
                            <h3>Rp {{ number_format($data['total_pemasukan'] ?? 0, 0, ',', '.') }}</h3>
                            <p>Total Pemasukan</p>
                        </div>
                        <div class="icon"><i class="fas fa-money-bill-wave"></i></div>
                        {{-- PERUBAHAN 2: Nama Route Baru --}}
                        <a href="{{ route('admin.keuangan.transaksi') }}" class="small-box-footer">Lihat Detail <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>

                <div class="col-lg-3 col-6">
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3>{{ $data['transaksi_hari_ini'] ?? 0 }}</h3>
                            <p>Transaksi Hari Ini</p>
                        </div>
                        <div class="icon"><i class="fas fa-cash-register"></i></div>
                        <a href="{{ route('admin.keuangan.transaksi') }}" class="small-box-footer">Lihat Detail <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>

                <div class="col-lg-3 col-6">
                    <div class="small-box bg-warning">
                        <div class="inner">
                            <h3>{{ $data['menunggu_verifikasi'] ?? 0 }}</h3>
                            <p>Menunggu Verifikasi</p>
                        </div>
                        <div class="icon"><i class="fas fa-hourglass-half"></i></div>
                        {{-- Link ke halaman verifikasi --}}
                        <a href="{{ route('admin.keuangan.verifikasi.index') }}" class="small-box-footer">Proses Sekarang <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>

                <div class="col-lg-3 col-6">
                    <div class="small-box bg-primary">
                        <div class="inner">
                            {{-- Jika data ini tidak dikirim controller, aman dikasih 0 atau dihapus --}}
                            <h3>{{ $data['total_booking'] ?? '-' }}</h3>
                            <p>Total Booking</p>
                        </div>
                        <div class="icon"><i class="fas fa-calendar-check"></i></div>
                        <a href="#" class="small-box-footer">Info <i class="fas fa-info-circle"></i></a>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fas fa-money-check mr-1"></i> Transaksi Terbaru</h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                            </div>
                        </div>
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
                                    {{-- PERUBAHAN 3: Looping data recent_payments --}}
                                    @forelse($data['recent_payments'] ?? [] as $transaksi)
                                        <tr>
                                            <td>{{ $transaksi->id }}</td>
                                            <td>{{ $transaksi->fasilitas->nama_fasilitas ?? 'Dihapus' }}</td>
                                            <td>Rp {{ number_format($transaksi->jumlah_bayar, 0, ',', '.') }}</td>
                                            <td>{{ ucfirst($transaksi->metode_pembayaran) }}</td>
                                            <td>{{ $transaksi->user->name ?? 'User Hapus' }}</td>
                                            <td>
                                                @if ($transaksi->status == 'lunas')
                                                    <span class="badge badge-success">Lunas</span>
                                                @elseif($transaksi->status == 'pending')
                                                    <span class="badge badge-warning">Verifikasi</span>
                                                @elseif($transaksi->status == 'batal')
                                                    <span class="badge badge-danger">Batal</span>
                                                @else
                                                    <span class="badge badge-info">DP</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center">Belum ada data terbaru</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="card-footer clearfix">
                            <a href="{{ route('admin.keuangan.transaksi') }}" class="btn btn-sm btn-primary float-right">Lihat Semua</a>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Status Pembayaran</h3>
                        </div>
                        <div class="card-body">
                            <div class="chart-container" style="position: relative; height:250px; width:100%">
                                <canvas id="paymentStatusChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var ctx = document.getElementById('paymentStatusChart');
            if (ctx) {
                var data = {
                    labels: ['Lunas', 'DP', 'Pending', 'Batal'],
                    datasets: [{
                        // PERUBAHAN 4: Ambil data dari array status_counts
                        data: [
                            {{ $data['status_counts']['lunas'] ?? 0 }},
                            {{ $data['status_counts']['dp'] ?? 0 }},
                            {{ $data['status_counts']['pending'] ?? 0 }},
                            {{ $data['status_counts']['batal'] ?? 0 }}
                        ],
                        backgroundColor: ['#28a745', '#ffc107', '#17a2b8', '#dc3545'],
                        hoverOffset: 4
                    }]
                };

                new Chart(ctx, {
                    type: 'doughnut',
                    data: data,
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { position: 'bottom' }
                        }
                    }
                });
            }
        });
    </script>
@endsection
