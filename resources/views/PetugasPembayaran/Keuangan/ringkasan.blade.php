@extends('PetugasPembayaran.component')
@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Ringkasan Keuangan</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('petugas_pembayaran.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Ringkasan Keuangan</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <!-- Filter Card -->
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-calendar-alt mr-1"></i> Filter Periode</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('petugas_pembayaran.keuangan.ringkasan') }}" method="GET">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Bulan:</label>
                                    <select name="month" class="form-control">
                                        @foreach($bulan as $key => $value)
                                            <option value="{{ $key }}" {{ $month == $key ? 'selected' : '' }}>
                                                {{ $value }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Tahun:</label>
                                    <select name="year" class="form-control">
                                        @for($i = date('Y') - 2; $i <= date('Y') + 1; $i++)
                                            <option value="{{ $i }}" {{ $year == $i ? 'selected' : '' }}>
                                                {{ $i }}
                                            </option>
                                        @endfor
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group pt-4 mt-2">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-filter mr-1"></i> Terapkan Filter
                                    </button>
                                    <a href="{{ route('petugas_pembayaran.keuangan.ringkasan.export', [
                                        'type' => 'pdf',
                                        'month' => $month,
                                        'year' => $year
                                    ]) }}" class="btn btn-success">
                                        <i class="fas fa-file-pdf mr-1"></i> Export PDF
                                    </a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Summary Cards -->
            <div class="row">
                <div class="col-md-6">
                    <div class="info-box bg-gradient-info">
                        <span class="info-box-icon"><i class="fas fa-money-bill-wave"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Total Pemasukan</span>
                            <span class="info-box-number">Rp {{ number_format($totalPemasukan, 0, ',', '.') }}</span>
                            <span class="progress-description">
                                Periode: {{ $bulan[$month] }} {{ $year }}
                            </span>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="info-box bg-gradient-success">
                        <span class="info-box-icon"><i class="fas fa-calendar-check"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Jumlah Fasilitas Tersewa</span>
                            <span class="info-box-number">{{ count($pemasukanByFasilitas) }} Fasilitas</span>
                            <span class="progress-description">
                                {{ $pemasukanByFasilitas->sum('count') ?? 0 }} Transaksi Tercatat
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Chart Column -->
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fas fa-chart-bar mr-1"></i> Grafik Pemasukan Bulanan {{ $year }}</h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="chart">
                                <canvas id="incomeChart" style="min-height: 300px; height: 300px; max-height: 300px; max-width: 100%;"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Facility Summary Column -->
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header bg-success">
                            <h3 class="card-title"><i class="fas fa-list mr-1"></i> Pemasukan Per Fasilitas</h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Fasilitas</th>
                                        <th class="text-center">Booking</th>
                                        <th class="text-right">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($pemasukanByFasilitas as $item)
                                        <tr>
                                            <td>{{ $item['nama_fasilitas'] }}</td>
                                            <td class="text-center">{{ $item['count'] }}</td>
                                            <td class="text-right">Rp {{ number_format($item['total'], 0, ',', '.') }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="text-center">Tidak ada data pemasukan</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                                <tfoot>
                                    <tr class="bg-light">
                                        <th>Total</th>
                                        <th class="text-center">{{ $pemasukanByFasilitas->sum('count') ?? 0 }}</th>
                                        <th class="text-right">Rp {{ number_format($totalPemasukan, 0, ',', '.') }}</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Script untuk Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Set current year for the chart by default
            var currentYear = {{ $year ?? 'new Date().getFullYear()' }};

            // Data untuk chart
            var monthNames = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
                             'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
            var chartData = {!! json_encode($chartData) !!};

            // Fetch current year's data if not already loaded
            if (!window.chartInitialized) {
                var yearSelect = document.querySelector('select[name="year"]');
                if (yearSelect && yearSelect.value != currentYear) {
                    yearSelect.value = currentYear;
                    // This would normally trigger a submit, but we'll keep the current page data
                }
                window.chartInitialized = true;
            }

            // Chart configuration
            var ctx = document.getElementById('incomeChart').getContext('2d');
            var chart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: monthNames,
                    datasets: [{
                        label: 'Pemasukan (Rp)',
                        backgroundColor: 'rgba(60,141,188,0.9)',
                        borderColor: 'rgba(60,141,188,1)',
                        pointRadius: false,
                        pointColor: '#3b8bba',
                        pointStrokeColor: 'rgba(60,141,188,1)',
                        pointHighlightFill: '#fff',
                        pointHighlightStroke: 'rgba(60,141,188,1)',
                        data: chartData
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return 'Rp ' + value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                                }
                            }
                        }
                    },
                    plugins: {
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    let value = context.raw || 0;
                                    return 'Rp ' + value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                                }
                            }
                        },
                        legend: {
                            display: false
                        }
                    }
                }
            });
        });
    </script>
@endsection
