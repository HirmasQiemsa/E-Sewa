@extends('Admin.component')
@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6"><h1 class="m-0">Dashboard Kepala Dinas</h1></div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12 col-sm-6 col-md-4">
                <div class="info-box mb-3">
                    <span class="info-box-icon bg-success elevation-1"><i class="fas fa-money-bill-wave"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Total Pendapatan</span>
                        <span class="info-box-number">Rp {{ number_format($data['total_pendapatan'], 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>

            <div class="col-12 col-sm-6 col-md-4">
                <div class="info-box mb-3">
                    <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-users"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Masyarakat Terdaftar</span>
                        <span class="info-box-number">{{ $data['total_user'] }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header border-transparent">
                <h3 class="card-title">Log Aktivitas Terbaru (Segera)</h3>
            </div>
            <div class="card-body">
                <p class="text-muted">Fitur grafik/log aktivitas akan ditambahkan di sini.</p>
            </div>
        </div>
    </div>
</section>
@endsection
