@extends('Admin.component')

@section('content')
    {{-- Tambahkan Style CSS Khusus Disini --}}
    <style>
        /* Efek Hover untuk Info Box yang bisa diklik */
        .info-box-hover {
            transition: all 0.3s ease-in-out;
            cursor: pointer;
        }

        .info-box-hover:hover {
            transform: translateY(-5px);
            /* Efek melayang sedikit */
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1) !important;
            /* Bayangan lebih dalam */
        }

        /* Hover warna khusus untuk Masyarakat (Info -> Info Tua) */
        .hover-user:hover {
            background-color: #e3f2fd;
            /* Biru sangat muda */
        }

        /* Hover warna khusus untuk Staff (Secondary -> Abu muda) */
        .hover-staff:hover {
            background-color: #cecfd1;
            /* Abu-abu sangat muda */
        }

        /* Hover warna khusus untuk Uang (Success -> Hijau muda) */
        .hover-money:hover {
            background-color: #e8f5e9;
            /* Hijau sangat muda */
        }
    </style>

    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 font-weight-bold text-dark">Dashboard Kepala Dinas</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item active">Dashboard</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">

            {{-- BARIS 1: STATISTIK UTAMA (4 KOLOM SIMETRIS) --}}
            <div class="row">
                {{-- 1. Total Pendapatan (KLIKABLE KE LAPORAN TRANSAKSI) --}}
                <div class="col-12 col-sm-6 col-md-3">
                    <a href="{{ route('admin.super.laporan.transaksi') }}"
                        class="info-box mb-3 shadow-sm text-reset text-decoration-none info-box-hover hover-money">
                        <span class="info-box-icon bg-success elevation-1"><i class="fas fa-money-bill-wave"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Total Pendapatan</span>
                            <span class="info-box-number text-success">
                                Rp {{ number_format($data['total_pendapatan'] ?? 0, 0, ',', '.') }}
                            </span>
                        </div>
                    </a>
                </div>

                {{-- 2. Masyarakat (KLIKABLE + HOVER BIRU MUDA) --}}
                <div class="col-12 col-sm-6 col-md-3">
                    <a href="{{ route('admin.super.masyarakat.index') }}"
                        class="info-box mb-3 shadow-sm text-reset text-decoration-none info-box-hover hover-user">
                        <span class="info-box-icon bg-info elevation-1"><i class="fas fa-users"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Masyarakat</span>
                            <span class="info-box-number">
                                {{ $data['total_user'] ?? 0 }} Orang
                            </span>
                        </div>
                    </a>
                </div>

                {{-- 3. Total Staff (KLIKABLE + HOVER ABU MUDA) --}}
                <div class="col-12 col-sm-6 col-md-3">
                    <a href="{{ route('admin.super.users.index') }}"
                        class="info-box mb-3 shadow-sm text-reset text-decoration-none info-box-hover hover-staff">
                        <span class="info-box-icon bg-secondary elevation-1"><i class="fas fa-id-badge"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Total Staff</span>
                            <span class="info-box-number">
                                {{ $data['total_staff'] ?? 0 }} Admin
                            </span>
                        </div>
                    </a>
                </div>

                {{-- 4. Total Fasilitas (INFO SAJA) --}}
                <div class="col-12 col-sm-6 col-md-3">
                    <div class="info-box mb-3 shadow-sm position-relative">
                        <span class="info-box-icon bg-primary elevation-1"><i class="fas fa-building"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Fasilitas Terdata</span>
                            <span class="info-box-number d-flex justify-content-between align-items-center">
                                <span>{{ $data['fasilitas_count'] ?? 0 }} Unit</span>
                                <span class="badge badge-success" style="font-size: 0.8rem; font-weight: normal;">
                                    <i class="fas fa-check mr-1"></i> {{ $data['fasilitas_aktif'] ?? 0 }} Aktif
                                </span>
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- BARIS 2: GRAFIK PENDAPATAN --}}
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header border-0">
                            <div class="d-flex justify-content-between">
                                <h3 class="card-title font-weight-bold">
                                    <i class="fas fa-chart-line mr-1"></i> Grafik Pendapatan Tahun Ini
                                </h3>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="position-relative mb-4">
                                <canvas id="sales-chart" height="250"></canvas>
                            </div>
                            <div class="d-flex flex-row justify-content-end">
                                <span class="mr-2">
                                    <i class="fas fa-square text-primary"></i> Pemasukan Sewa (Lunas)
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- BARIS 3: LOG AKTIVITAS --}}
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header border-transparent">
                            <h3 class="card-title"><i class="fas fa-history mr-1"></i> Log Aktivitas Sistem Terkini</h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-striped m-0">
                                    <thead>
                                        <tr>
                                            <th style="width: 15%">Waktu</th>
                                            <th style="width: 20%">Aktor (Pelaku)</th>
                                            <th style="width: 10%">Aksi</th>
                                            <th>Deskripsi</th>
                                            <th style="width: 15%">Subject</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($data['recent_logs'] ?? [] as $log)
                                            <tr>
                                                <td><small class="text-muted"><i class="far fa-clock mr-1"></i>
                                                        {{ $log->created_at->diffForHumans() }}</small></td>
                                                <td>
                                                    {{-- CEK APAKAH DILAKUKAN OLEH ADMIN --}}
                                                    @if ($log->admin)
                                                        <span
                                                            class="font-weight-bold text-dark">{{ $log->admin->name }}</span>
                                                        <br><small
                                                            class="text-muted text-uppercase">{{ str_replace('_', ' ', $log->admin->role) }}</small>

                                                        {{-- CEK APAKAH DILAKUKAN OLEH USER (MASYARAKAT) --}}
                                                    @elseif ($log->user)
                                                        <span
                                                            class="font-weight-bold text-primary">{{ $log->user->name }}</span>
                                                        <br><small class="text-muted"><i
                                                                class="fas fa-user-circle mr-1"></i> Masyarakat</small>

                                                        {{-- JIKA TIDAK ADA KEDUANYA (SYSTEM OTOMATIS) --}}
                                                    @else
                                                        <span class="font-italic text-secondary">
                                                            <i class="fas fa-robot mr-1"></i> System
                                                        </span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @php
                                                        $badges = [
                                                            'CREATE' => 'badge-success',
                                                            'UPDATE' => 'badge-info',
                                                            'DELETE' => 'badge-danger',
                                                            'APPROVE' => 'badge-primary',
                                                            'REJECT' => 'badge-secondary',
                                                            'LOCK' => 'badge-warning',
                                                            'UNLOCK' => 'badge-success',
                                                            'LOGIN' => 'badge-light',
                                                        ];
                                                        $badgeClass = $badges[$log->action] ?? 'badge-secondary';
                                                    @endphp
                                                    <span class="badge {{ $badgeClass }}">{{ $log->action }}</span>
                                                </td>
                                                <td>{{ Str::limit($log->description, 80) }}</td>
                                                <td>
                                                    <small class="badge badge-light border">
                                                        {{ class_basename($log->subject_type) }} #{{ $log->subject_id }}
                                                    </small>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="text-center py-4 text-muted">Belum ada aktivitas
                                                    tercatat.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
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

            var ticksStyle = {
                fontColor: '#495057',
                fontStyle: 'bold'
            }

            var mode = 'index'
            var intersect = true

            var $salesChart = $('#sales-chart')

            // Pastikan elemen canvas ada sebelum render
            if ($salesChart.length > 0) {
                var salesChart = new Chart($salesChart, {
                    type: 'bar',
                    data: {
                        // Label Bulan
                        labels: ['JAN', 'FEB', 'MAR', 'APR', 'MEI', 'JUN', 'JUL', 'AGU', 'SEP', 'OKT',
                            'NOV', 'DES'
                        ],
                        datasets: [{
                            backgroundColor: '#007bff',
                            borderColor: '#007bff',
                            // Data dari Controller
                            data: @json($data['chart_values'] ?? [])
                        }]
                    },
                    options: {
                        maintainAspectRatio: false,
                        tooltips: {
                            mode: mode,
                            intersect: intersect,
                            callbacks: {
                                // Format Tooltip menjadi Rupiah
                                label: function(tooltipItem, data) {
                                    var value = tooltipItem.yLabel;
                                    if (value >= 1000000) return 'Rp ' + (value / 1000000) + ' Jt';
                                    if (value >= 1000) return 'Rp ' + (value / 1000) + ' Rb';
                                    return 'Rp ' + value;
                                }
                            }
                        },
                        hover: {
                            mode: mode,
                            intersect: intersect
                        },
                        legend: {
                            display: false
                        },
                        scales: {
                            yAxes: [{
                                display: true,
                                gridLines: {
                                    display: true,
                                    lineWidth: '1px',
                                    color: 'rgba(0, 0, 0, .1)',
                                    zeroLineColor: 'transparent'
                                },
                                ticks: $.extend({
                                    beginAtZero: true,
                                    // Callback Format Sumbu Y (Juta/Ribu)
                                    callback: function(value) {
                                        if (value >= 1000000) return 'Rp ' + (value /
                                            1000000) + ' Jt';
                                        if (value >= 1000) return 'Rp ' + (value /
                                            1000) + ' Rb';
                                        return 'Rp ' + value;
                                    }
                                }, ticksStyle)
                            }],
                            xAxes: [{
                                display: true,
                                gridLines: {
                                    display: false
                                },
                                ticks: ticksStyle
                            }]
                        }
                    }
                })
            }
        })
    </script>
@endpush
