@extends('Admin.component')

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Ringkasan Pendapatan</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Ringkasan</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">

            {{-- FILTER PERIODE --}}
            <div class="row mb-3">
                <div class="col-md-12">
                    <div class="card shadow-sm">
                        <div class="card-body py-3">
                            <form action="{{ route('admin.keuangan.ringkasan') }}" method="GET"
                                class="form-inline justify-content-center">
                                <label class="mr-2">Periode:</label>
                                <select name="month" class="form-control mr-2" onchange="this.form.submit()">
                                    @foreach ($bulan as $key => $val)
                                        <option value="{{ $key }}" {{ $month == $key ? 'selected' : '' }}>
                                            {{ $val }}</option>
                                    @endforeach
                                </select>
                                <select name="year" class="form-control mr-2" onchange="this.form.submit()">
                                    @for ($i = date('Y'); $i >= date('Y') - 5; $i--)
                                        <option value="{{ $i }}" {{ $year == $i ? 'selected' : '' }}>
                                            {{ $i }}</option>
                                    @endfor
                                </select>
                                <button type="submit" class="btn btn-primary"><i class="fas fa-sync-alt"></i></button>

                                <div class="ml-auto">
                                    <a href="{{ route('admin.keuangan.export_ringkasan', ['type' => 'csv', 'month' => $month, 'year' => $year]) }}"
                                        class="btn btn-success btn-sm">
                                        <i class="fas fa-file-download mr-1"></i> CSV
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            {{-- INFO BOX TOTAL --}}
            <div class="row">
                <div class="col-12">
                    <div class="small-box bg-success">
                        <div class="inner text-center">
                            <h3>Rp {{ number_format($totalPemasukan, 0, ',', '.') }}</h3>
                            <p>Total Pendapatan Bulan {{ $bulan[$month] }} {{ $year }}</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-wallet"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                {{-- TABEL RINCIAN --}}
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header border-0">
                            <h3 class="card-title">Pendapatan Per Fasilitas</h3>
                        </div>
                        <div class="card-body table-responsive p-0">
                            <table class="table table-striped table-valign-middle">
                                <thead>
                                    <tr>
                                        <th>Fasilitas</th>
                                        <th class="text-center">Transaksi</th>
                                        <th class="text-right">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($pemasukanByFasilitas as $item)
                                        <tr>
                                            <td><i class="fas fa-building text-secondary mr-1"></i>
                                                {{ $item['nama_fasilitas'] }}</td>
                                            <td class="text-center"><span
                                                    class="badge badge-info">{{ $item['count'] }}</span></td>
                                            <td class="text-right text-success font-weight-bold">Rp
                                                {{ number_format($item['total'], 0, ',', '.') }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="text-center text-muted">Belum ada data.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                {{-- GRAFIK --}}
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header border-0">
                            {{-- Judul Dinamis --}}
                            <h3 class="card-title">
                                <i class="fas fa-chart-bar mr-1"></i>
                                Grafik Pendapatan: {{ $bulan[$month] }} {{ $year }}
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="position-relative mb-4">
                                <canvas id="income-chart" height="200"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
    $(function() {
        'use strict'

        var $chartCanvas = $('#income-chart');

        if ($chartCanvas.length > 0) {
            var ctx = $chartCanvas.get(0).getContext('2d');

            var incomeChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: @json($chartLabels), // ['Minggu 1', ...]
                    datasets: [{
                        label: 'Pendapatan (Rp)',
                        backgroundColor: '#28a745', // Hijau Pemasukan
                        borderColor: '#28a745',
                        borderWidth: 1,
                        data: @json($chartData) // [100000, 0, 500000, ...]
                    }]
                },
                options: {
                    maintainAspectRatio: false,
                    responsive: true,
                    legend: {
                        display: false
                    },
                    tooltips: {
                        mode: 'index',
                        intersect: true,
                        callbacks: {
                            label: function(tooltipItem, data) {
                                // Format Rupiah Tooltip (Syntax Chart.js v2)
                                var value = tooltipItem.yLabel;
                                return 'Rp ' + value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                            }
                        }
                    },
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero: true,
                                // Format Sumbu Y (Juta/Ribu)
                                callback: function(value) {
                                    if (value >= 1000000) return 'Rp ' + (value / 1000000) + ' Jt';
                                    if (value >= 1000) return 'Rp ' + (value / 1000) + ' Rb';
                                    return 'Rp ' + value;
                                }
                            },
                            gridLines: {
                                display: true,
                                color: 'rgba(0, 0, 0, .05)'
                            }
                        }],
                        xAxes: [{
                            gridLines: {
                                display: false
                            }
                        }]
                    }
                }
            });
        }
    });
</script>
@endpush
