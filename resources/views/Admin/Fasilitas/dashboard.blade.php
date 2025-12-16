@extends('Admin.component')

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Dashboard Prasarana</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item active"><i class="fas fa-building mr-1"></i> Dashboard</li>
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
                            <h3>{{ $data['total_fasilitas'] ?? 0 }}</h3>
                            <p>Total Fasilitas</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-basketball-ball"></i>
                        </div>
                        {{-- Link ke CRUD Fasilitas --}}
                        <a href="{{ route('admin.fasilitas.data.index') }}" class="small-box-footer">Lihat Detail <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>

                <div class="col-lg-3 col-6">
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3>{{ $data['booking_hari_ini'] ?? 0 }}</h3>
                            <p>Booking Hari Ini</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-calendar-check"></i>
                        </div>
                        {{-- Link ke Jadwal --}}
                        <a href="{{ route('admin.fasilitas.jadwal.index') }}" class="small-box-footer">Lihat Jadwal <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>

                <div class="col-lg-3 col-6">
                    <div class="small-box bg-warning">
                        <div class="inner">
                            <h3>{{ $data['fasilitas_tergunakan'] ?? 0 }}</h3>
                            <p>Fasilitas Terpakai Hari Ini</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-building"></i>
                        </div>
                        <a href="{{ route('admin.fasilitas.jadwal.index') }}" class="small-box-footer">Cek Jadwal <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>

                <div class="col-lg-3 col-6">
                    <div class="small-box bg-primary">
                        <div class="inner">
                            <h3>{{ $data['total_user'] ?? 0 }}</h3>
                            <p>User Terdaftar</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <a href="#" class="small-box-footer">Info <i class="fas fa-info-circle"></i></a>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-calendar-day mr-1"></i>
                                Jadwal Booking Hari Ini
                            </h3>
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
                                        <th>Jam</th>
                                        <th>Durasi</th>
                                        <th>Penyewa</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($data['jadwals_today'] ?? [] as $jadwal)
                                        <tr>
                                            <td>{{ $jadwal->id }}</td>
                                            <td>{{ $jadwal->fasilitas->nama_fasilitas ?? 'Dihapus' }}</td>
                                            <td>
                                                {{ substr($jadwal->jam_mulai, 0, 5) }} - {{ substr($jadwal->jam_selesai, 0, 5) }}
                                            </td>
                                            <td>{{ $jadwal->durasi }} jam</td>
                                            <td>
                                                {{-- Ambil nama user dari relasi checkout pertama --}}
                                                {{ $jadwal->checkouts->first()->user->name ?? '-' }}
                                            </td>
                                            <td>
                                                @if ($jadwal->status == 'tersedia')
                                                    <span class="badge badge-success">Tersedia</span>
                                                @elseif($jadwal->status == 'terbooking')
                                                    <span class="badge badge-warning">Terbooking</span>
                                                @elseif($jadwal->status == 'selesai')
                                                    <span class="badge badge-info">Selesai</span>
                                                @else
                                                    <span class="badge badge-danger">Batal</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center">Tidak ada jadwal booking hari ini</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="card-footer clearfix">
                            <a href="{{ route('admin.fasilitas.jadwal.index') }}" class="btn btn-sm btn-primary ml-2">
                                <i class="fas fa-list"></i> Kelola Jadwal Lengkap
                            </a>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Status Ketersediaan Fasilitas</h3>
                        </div>
                        <div class="card-body">
                            <div class="chart-container" style="position: relative; height:250px; width:100%">
                                <canvas id="facilityStatusChart"></canvas>
                            </div>

                            <div class="mt-4 text-center">
                                <span class="mr-3"><i class="fas fa-circle text-success"></i> Aktif ({{ $data['fasilitas_aktif'] ?? 0 }})</span>
                                <span class="mr-3"><i class="fas fa-circle text-danger"></i> Non-aktif ({{ $data['fasilitas_nonaktif'] ?? 0 }})</span>
                                <span><i class="fas fa-circle text-warning"></i> Maintenance ({{ $data['fasilitas_maintenance'] ?? 0 }})</span>
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
            var ctx = document.getElementById('facilityStatusChart');
            if (ctx) {
                var data = {
                    labels: ['Aktif', 'Non-aktif', 'Maintenance'],
                    datasets: [{
                        data: [
                            {{ $data['fasilitas_aktif'] ?? 0 }},
                            {{ $data['fasilitas_nonaktif'] ?? 0 }},
                            {{ $data['fasilitas_maintenance'] ?? 0 }}
                        ],
                        backgroundColor: ['#28a745', '#dc3545', '#ffc107'],
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
                            legend: { display: false } // Legend sudah kita buat manual di bawah chart
                        },
                        cutout: '60%'
                    }
                });
            }
        });
    </script>
@endsection
