@extends('PetugasFasilitas.component')
@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Dashboard Petugas Fasilitas</h1>
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
                            <h3>{{ $totalFasilitas ?? 0 }}</h3>
                            <p>Total Fasilitas</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-basketball-ball"></i>
                        </div>
                        <a href="{{ route('petugas_fasilitas.fasilitas.index') }}" class="small-box-footer">Lihat Detail <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <!-- ./col -->
                <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3>{{ $bookingHariIni ?? 0 }}</h3>
                            <p>Booking Hari Ini</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-calendar-check"></i>
                        </div>
                        <a href="{{ route('petugas_fasilitas.booking.today') }}" class="small-box-footer">Lihat Detail <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <!-- ./col -->
                <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box bg-warning">
                        <div class="inner">
                            <h3>{{ $fasilitasMaintenance ?? 0 }}</h3>
                            <p>Fasilitas Maintenance</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-tools"></i>
                        </div>
                        <a href="{{ route('petugas_fasilitas.fasilitas.maintenance') }}" class="small-box-footer">Lihat Detail <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <!-- ./col -->
                <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box bg-primary">
                        <div class="inner">
                            <h3>{{ $totalUser ?? 0 }}</h3>
                            <p>User Terdaftar</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <a href="#" class="small-box-footer">Info <i class="fas fa-info-circle"></i></a>
                    </div>
                </div>
                <!-- ./col -->
            </div>
            <!-- /.row -->

            <!-- Jadwal Hari Ini -->
            <div class="row">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-calendar-day mr-1"></i>
                                Jadwal Booking Hari Ini
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
                                        <th>Jam</th>
                                        <th>Durasi</th>
                                        <th>User</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($jadwalsToday ?? [] as $jadwal)
                                        <tr>
                                            <td>{{ $jadwal->id }}</td>
                                            <td>{{ $jadwal->fasilitas->nama_fasilitas }}</td>
                                            <td>{{ substr($jadwal->jam_mulai, 0, 5) }} - {{ substr($jadwal->jam_selesai, 0, 5) }}</td>
                                            <td>{{ $jadwal->durasi }} jam</td>
                                            <td>{{ $jadwal->checkouts->first()->user->name ?? 'N/A' }}</td>
                                            <td>
                                                @if($jadwal->status == 'tersedia')
                                                    <span class="badge badge-success">Tersedia</span>
                                                @elseif($jadwal->status == 'terbooking')
                                                    <span class="badge badge-warning">Terbooking</span>
                                                @elseif($jadwal->status == 'selesai')
                                                    <span class="badge badge-info">Selesai</span>
                                                @else
                                                    <span class="badge badge-danger">Batal</span>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('petugas_fasilitas.jadwal.detail', $jadwal->id) }}" class="btn btn-sm btn-info">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center">Tidak ada jadwal booking hari ini</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <!-- /.card-body -->
                        <div class="card-footer clearfix">
                            <a href="{{ route('petugas_fasilitas.booking.today') }}" class="btn btn-sm btn-secondary float-right">Lihat Semua</a>
                        </div>
                    </div>
                    <!-- /.card -->
                </div>

                <div class="col-md-4">
                    <!-- Ringkasan Fasilitas -->
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-chart-pie mr-1"></i>
                                Status Fasilitas
                            </h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="chart-container" style="position: relative; height:250px;">
                                <canvas id="facilitiesChart"></canvas>
                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="description-block">
                                        <h5 class="description-header text-success">{{ $fasilitasAktif ?? 0 }}</h5>
                                        <span class="description-text">AKTIF</span>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="description-block">
                                        <h5 class="description-header text-warning">{{ $fasilitasNonaktif ?? 0 }}</h5>
                                        <span class="description-text">NON-AKTIF</span>
                                    </div>
                                </div>
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
                                Aktivitas Terbaru
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
                                            <th>Kegiatan</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($recentActivities ?? [] as $activity)
                                            <tr>
                                                <td>{{ date('d/m/Y H:i', strtotime($activity->created_at)) }}</td>
                                                <td>{{ $activity->user->name ?? 'System' }}</td>
                                                <td>{{ $activity->fasilitas->nama_fasilitas ?? 'N/A' }}</td>
                                                <td>{{ $activity->description }}</td>
                                                <td>
                                                    <span class="badge badge-{{ $activity->status_color }}">
                                                        {{ $activity->status }}
                                                    </span>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="text-center">Belum ada aktivitas terbaru</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="card-footer clearfix">
                            <a href="{{ route('petugas_fasilitas.activities') }}" class="btn btn-sm btn-secondary float-right">Lihat Semua Aktivitas</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Script untuk chart fasilitas -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Data untuk chart
        var ctx = document.getElementById('facilitiesChart').getContext('2d');
        var facilitiesChart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Aktif', 'Non-Aktif', 'Maintenance'],
                datasets: [{
                    data: [
                        {{ $fasilitasAktif ?? 0 }},
                        {{ $fasilitasNonaktif ?? 0 }},
                        {{ $fasilitasMaintenance ?? 0 }}
                    ],
                    backgroundColor: [
                        '#28a745', // Success/Aktif
                        '#6c757d', // Secondary/Non-aktif
                        '#ffc107'  // Warning/Maintenance
                    ],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                legend: {
                    position: 'bottom',
                    labels: {
                        boxWidth: 12
                    }
                },
                cutoutPercentage: 70
            }
        });
    });
    </script>
@endsection
