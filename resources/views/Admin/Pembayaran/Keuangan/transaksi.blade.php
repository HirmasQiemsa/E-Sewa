@extends('Admin.component')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Laporan Transaksi Keuangan</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Transaksi</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">

        {{-- FILTER CARD --}}
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-filter mr-1"></i> Filter Data</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                </div>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.keuangan.transaksi') }}" method="GET">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Mulai Tanggal</label>
                                <input type="date" name="start_date" class="form-control" value="{{ $startDate }}">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Sampai Tanggal</label>
                                <input type="date" name="end_date" class="form-control" value="{{ $endDate }}">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Fasilitas</label>
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
                                <label>Cari</label>
                                <div class="input-group">
                                    <input type="text" name="search" class="form-control" placeholder="User / Nominal..." value="{{ $search }}">
                                    <div class="input-group-append">
                                        <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        {{-- TABLE CARD --}}
        <div class="card">
            <div class="card-header">
                <h3 class="card-title mt-1">Daftar Pemasukan </h3>
                <div class="card-tools">
                    <a href="{{ route('admin.keuangan.export_transaksi', request()->all()) }}" class="btn btn-success btn-sm">
                        <i class="fas fa-file-csv mr-1"></i> Export CSV
                    </a>
                </div>
            </div>
            <div class="card-body table-responsive p-0">
                <table class="table table-hover text-nowrap">
                    <thead>
                        <tr>
                            <th style="width: 5%">No</th>
                            <th>Tanggal Masuk</th>
                            <th>Penyewa</th>
                            <th>Fasilitas</th>
                            <th>Metode</th>
                            <th class="text-right">Jumlah (Rp)</th>
                            <th class="text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pemasukan as $item)
                            <tr>
                                <td>{{ $loop->iteration + $pemasukan->firstItem() - 1 }}</td>
                                <td>
                                    {{ \Carbon\Carbon::parse($item->created_at)->format('d/m/Y') }}
                                    <span class="text-muted ml-1">{{ \Carbon\Carbon::parse($item->created_at)->format('H:i') }}</span>
                                </td>
                                <td>{{ $item->checkout->user->name ?? 'N/A' }}</td>
                                <td>{{ $item->fasilitas->nama_fasilitas ?? '-' }}</td>
                                <td><span class="badge badge-light border">{{ ucfirst($item->metode_pembayaran) }}</span></td>
                                <td class="text-right font-weight-bold text-success">
                                    {{ number_format($item->jumlah_bayar, 0, ',', '.') }}
                                </td>
                                <td class="text-center">
                                    <span class="badge badge-success">Lunas</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">Data tidak ditemukan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                    <tfoot>
                        <tr class="bg-light">
                            <td colspan="5" class="text-right font-weight-bold">TOTAL PEMASUKAN:</td>
                            <td class="text-right font-weight-bold text-primary" style="font-size: 1.1em;">
                                Rp {{ number_format($totalPemasukan, 0, ',', '.') }}
                            </td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <div class="card-footer clearfix">
                {{ $pemasukan->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </div>
</section>
@endsection
