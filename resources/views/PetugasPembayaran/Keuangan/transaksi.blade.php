@extends('PetugasPembayaran.component')
@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Transaksi Keuangan</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('petugas_pembayaran.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Transaksi Keuangan</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    {{ session('success') }}
                </div>
            @endif

            <!-- Filter Card -->
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-filter mr-1"></i> Filter Transaksi</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('petugas_pembayaran.keuangan.transaksi') }}" method="GET">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Dari Tanggal:</label>
                                    <input type="date" name="start_date" class="form-control" value="{{ $startDate }}">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Sampai Tanggal:</label>
                                    <input type="date" name="end_date" class="form-control" value="{{ $endDate }}">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Fasilitas:</label>
                                    <select name="fasilitas_id" class="form-control">
                                        <option value="">Semua Fasilitas</option>
                                        @foreach($fasilitas as $f)
                                            <option value="{{ $f->id }}" {{ $fasilitasId == $f->id ? 'selected' : '' }}>
                                                {{ $f->nama_fasilitas }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Cari:</label>
                                    <div class="input-group">
                                        <input type="text" name="search" class="form-control" value="{{ $search }}" placeholder="Cari...">
                                        <div class="input-group-append">
                                            <button type="submit" class="btn btn-primary">
                                                <i class="fas fa-search"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 text-right">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-filter"></i> Terapkan Filter
                                </button>
                                <a href="{{ route('petugas_pembayaran.keuangan.transaksi') }}" class="btn btn-secondary">
                                    <i class="fas fa-redo"></i> Reset
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Summary Card -->
            <div class="card bg-info">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h3 class="text-white">Total Pemasukan: Rp {{ number_format($totalPemasukan, 0, ',', '.') }}</h3>
                            <p class="text-white">Periode: {{ date('d M Y', strtotime($startDate)) }} - {{ date('d M Y', strtotime($endDate)) }}</p>
                        </div>
                        <div class="col-md-6 text-right">
                            <a href="{{ route('petugas_pembayaran.keuangan.transaksi.export', [
                                'start_date' => $startDate,
                                'end_date' => $endDate,
                                'fasilitas_id' => $fasilitasId,
                                'search' => $search
                            ]) }}" class="btn btn-light">
                                <i class="fas fa-download mr-1"></i> Export Data
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Transactions Table -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-money-bill-wave mr-1"></i> Daftar Transaksi</h3>
                </div>
                <div class="card-body table-responsive p-0">
                    <table class="table table-hover text-nowrap">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Tanggal</th>
                                <th>User</th>
                                <th>Fasilitas</th>
                                <th>Jumlah Bayar</th>
                                <th>Metode</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($pemasukan as $item)
                                <tr>
                                    <td>{{ $item->id }}</td>
                                    <td>{{ date('d/m/Y H:i', strtotime($item->tanggal_bayar)) }}</td>
                                    <td>{{ $item->checkout->user->name ?? 'N/A' }}</td>
                                    <td>{{ $item->fasilitas->nama_fasilitas ?? 'N/A' }}</td>
                                    <td>Rp {{ number_format($item->jumlah_bayar, 0, ',', '.') }}</td>
                                    <td>{{ ucfirst($item->metode_pembayaran) }}</td>
                                    <td>
                                        @if($item->status == 'lunas')
                                            <span class="badge badge-success">Lunas</span>
                                        @elseif($item->status == 'fee')
                                            <span class="badge badge-warning">DP</span>
                                        @elseif($item->status == 'pending')
                                            <span class="badge badge-info">Pending</span>
                                        @else
                                            <span class="badge badge-danger">Ditolak</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('petugas_pembayaran.pembayaran.show', $item->id) }}" class="btn btn-xs btn-info">
                                            <i class="fas fa-eye"></i> Detail
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center">Tidak ada data transaksi</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="card-footer clearfix">
                    {{ $pemasukan->links() }}
                </div>
            </div>
        </div>
    </section>
@endsection
