@extends('Admin.component')
@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Daftar Fasilitas</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Fasilitas</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    {{ session('error') }}
                </div>
            @endif

            <div class="row">
                <div class="col-12">
                    <div class="card collapsed-card">
                        <div class="card-header bg-info">
                            <h3 class="card-title">Filter Status & Statistik</h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3 col-sm-6 col-12">
                                    <div class="info-box bg-success">
                                        <span class="info-box-icon"><i class="fas fa-check-circle"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">Aktif</span>
                                            <span class="info-box-number">{{ $countAktif ?? 0 }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-6 col-12">
                                    <div class="info-box bg-warning">
                                        <span class="info-box-icon"><i class="fas fa-tools"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">Maintenance</span>
                                            <span class="info-box-number">{{ $countMaintenance ?? 0 }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-6 col-12">
                                    <div class="info-box bg-danger">
                                        <span class="info-box-icon"><i class="fas fa-ban"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">Nonaktif</span>
                                            <span class="info-box-number">{{ $countNonaktif ?? 0 }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-6 col-12">
                                    <div class="info-box bg-secondary">
                                        <span class="info-box-icon"><i class="fas fa-trash"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">Terhapus</span>
                                            {{-- PERBAIKAN: Menggunakan variabel yang dikirim controller --}}
                                            <span class="info-box-number">{{ $countTrash ?? 0 }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12 mb-3">
                    <div class="card">
                        <div class="card-header bg-primary">
                            <h3 class="card-title"><b>Data Fasilitas</b></h3>
                            <div class="card-tools">
                                <div class="input-group input-group-sm" style="width: 150px;">
                                    <input type="text" name="table_search" class="form-control float-right"
                                        placeholder="Cari...">
                                    <div class="input-group-append">
                                        <button type="submit" class="btn btn-default"><i class="fas fa-search"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card-body table-responsive p-0">
                            <table class="table table-hover text-nowrap">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Fasilitas</th>
                                        <th>Tipe</th>
                                        <th>Lokasi</th>
                                        <th>Harga</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {{-- PERBAIKAN: Loop menggunakan $fasilitas --}}
                                    @foreach ($fasilitas as $d)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $d->nama_fasilitas }}</td>
                                            <td>{{ $d->tipe }}</td>
                                            <td>{{ Str::limit($d->lokasi, 30) }}</td>
                                            <td>Rp {{ number_format($d->harga_sewa, 0, ',', '.') }}</td>
                                            <td>
                                                @if($d->deleted_at)
                                                    <span class="badge badge-secondary">Terhapus</span>
                                                @elseif($d->ketersediaan == 'aktif')
                                                    <span class="badge badge-success">Aktif</span>
                                                @elseif($d->ketersediaan == 'nonaktif')
                                                    <span class="badge badge-danger">Nonaktif</span>
                                                @elseif($d->ketersediaan == 'maintenance')
                                                    <span class="badge badge-warning">Maintenance</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="btn-group">
                                                    @if ($d->deleted_at)
                                                        {{-- Restore Button --}}
                                                        <form action="{{ route('admin.fasilitas.data.restore', $d->id) }}" method="POST">
                                                            @csrf
                                                            <button type="submit" class="btn btn-sm btn-warning" title="Pulihkan">
                                                                <i class="fas fa-undo"></i> Restore
                                                            </button>
                                                        </form>
                                                    @else
                                                        {{-- Edit Button --}}
                                                        <a href="{{ route('admin.fasilitas.data.edit', $d->id) }}"
                                                            class="btn btn-sm btn-info" title="Edit">
                                                            <i class="fas fa-edit"></i>
                                                        </a>

                                                        {{-- Delete Button --}}
                                                        <form action="{{ route('admin.fasilitas.data.destroy', $d->id) }}"
                                                            method="POST" class="d-inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-danger"
                                                                onclick="return confirm('Yakin hapus?')" title="Hapus">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </form>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="card-footer clearfix">
                            <a href="{{ route('admin.fasilitas.data.create') }}" class="btn btn-success">
                                <i class="fas fa-plus-circle mr-1"></i> Tambah Fasilitas
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
