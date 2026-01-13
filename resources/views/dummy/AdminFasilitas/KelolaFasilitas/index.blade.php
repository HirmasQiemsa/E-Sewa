@extends('AdminFasilitas.component')
@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Daftar Fasilitas</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('petugas_fasilitas.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Fasilitas</li>
                    </ol>
                </div>
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <!-- Notification area -->
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

            <!-- Status Filter and Stats -->
            <div class="row">
                <div class="col-12">
                    <div class="card collapsed-card">
                        <div class="card-header bg-info">
                            <h3 class="card-title">Filter Status</h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
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
                                            <span class="info-box-number">{{ $data->where('deleted_at', '!=', null)->count() }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- List of Facilities -->
            <div class="row">
                <div class="col-12 mb-3">
                    <div class="card">
                        <div class="card-header bg-primary">
                            <h3 class="card-title"><b>Daftar Fasilitas</b></h3>
                            <div class="card-tools">
                                <div class="input-group input-group-sm" style="width: 150px;">
                                    <input type="text" name="table_search" class="form-control float-right" placeholder="Cari...">
                                    <div class="input-group-append">
                                        <button type="submit" class="btn btn-default">
                                            <i class="fas fa-search"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /.card-header -->
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
                                    @foreach ($data as $d)
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
                                                        <form action="{{ route('petugas_fasilitas.fasilitas.restore', ['id' => $d->id]) }}" method="POST">
                                                            @csrf
                                                            <button type="submit" class="btn btn-sm btn-warning" title="Pulihkan Data">
                                                                <i class="fas fa-undo"></i> Restore
                                                            </button>
                                                        </form>
                                                    @else
                                                        <!-- Change Status Button -->
                                                        <button type="button" class="btn btn-sm btn-info dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                                            <i class="fas fa-exchange-alt"></i> Status
                                                        </button>
                                                        <div class="dropdown-menu">
                                                            <form action="{{ route('petugas_fasilitas.fasilitas.toggle-status', ['id' => $d->id]) }}" method="POST">
                                                                @csrf
                                                                @method('PUT')
                                                                <input type="hidden" name="status" value="aktif">
                                                                <button type="submit" class="dropdown-item">Aktif</button>
                                                            </form>
                                                            <form action="{{ route('petugas_fasilitas.fasilitas.toggle-status', ['id' => $d->id]) }}" method="POST">
                                                                @csrf
                                                                @method('PUT')
                                                                <input type="hidden" name="status" value="nonaktif">
                                                                <button type="submit" class="dropdown-item">Nonaktif</button>
                                                            </form>
                                                            <form action="{{ route('petugas_fasilitas.fasilitas.toggle-status', ['id' => $d->id]) }}" method="POST">
                                                                @csrf
                                                                @method('PUT')
                                                                <input type="hidden" name="status" value="maintenance">
                                                                <button type="submit" class="dropdown-item">Maintenance</button>
                                                            </form>
                                                        </div>

                                                        <!-- Edit Button -->
                                                        <a href="{{ route('petugas_fasilitas.fasilitas.edit', ['id' => $d->id]) }}" class="btn btn-sm btn-primary" title="Edit Data">
                                                            <i class="fas fa-pen"></i>
                                                        </a>

                                                        <!-- Delete Button -->
                                                        <button type="button" class="btn btn-sm btn-danger" data-toggle="modal" data-target="#modal-default{{ $d->id }}" title="Hapus Data">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                        <!-- Delete Modal -->
                                        <div class="modal fade" id="modal-default{{ $d->id }}">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h4 class="modal-title">Konfirmasi Penghapusan Fasilitas</h4>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p>Apakah Anda yakin ingin menghapus <b>Fasilitas {{ $d->nama_fasilitas }}?</b></p>
                                                        <p class="text-danger"><small>Fasilitas yang sudah memiliki jadwal aktif tidak dapat dihapus permanen.</small></p>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <form action="{{ route('petugas_fasilitas.fasilitas.destroy', ['id' => $d->id]) }}" method="POST">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                                                            <button type="submit" class="btn btn-danger">Hapus</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <!-- /.card-body -->
                        <div class="card-footer clearfix">
                            <a href="{{ route('petugas_fasilitas.fasilitas.create') }}" class="btn btn-success">
                                <i class="fas fa-plus-circle mr-1"></i> Tambah Fasilitas
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
