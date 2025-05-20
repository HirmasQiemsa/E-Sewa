@extends('PetugasPembayaran.component')
@section('title', 'Verifikasi Pembayaran')
@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Verifikasi Pembayaran</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('petugas_pembayaran.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Verifikasi Pembayaran</li>
                </ol>
            </div>
        </div>
    </div>
</div>
<!-- /.content-header -->

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <!-- Filter Box -->
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title">Filter Pembayaran</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="card-body">
                <form action="{{ route('petugas_pembayaran.pembayaran.index') }}" method="GET" id="filterForm">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Status Pembayaran</label>
                                <select name="status" class="form-control" onchange="this.form.submit()">
                                    <option value="all" {{ $status == 'all' ? 'selected' : '' }}>Semua Status</option>
                                    <option value="fee" {{ $status == 'fee' ? 'selected' : '' }}>DP Terbayar</option>
                                    <option value="lunas" {{ $status == 'lunas' ? 'selected' : '' }}>Lunas</option>
                                    <option value="pending" {{ $status == 'pending' ? 'selected' : '' }}>Menunggu Verifikasi</option>
                                    <option value="ditolak" {{ $status == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Filter Waktu</label>
                                <select name="filter" class="form-control" onchange="this.form.submit()">
                                    <option value="all" {{ $filter == 'all' ? 'selected' : '' }}>Semua Waktu</option>
                                    <option value="today" {{ $filter == 'today' ? 'selected' : '' }}>Hari Ini</option>
                                    <option value="week" {{ $filter == 'week' ? 'selected' : '' }}>Minggu Ini</option>
                                    <option value="month" {{ $filter == 'month' ? 'selected' : '' }}>Bulan Ini</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Pencarian</label>
                                <div class="input-group">
                                    <input type="text" name="search" class="form-control" placeholder="Cari nama, fasilitas, dll..." value="{{ $search ?? '' }}">
                                    <div class="input-group-append">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-search"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>&nbsp;</label>
                                <a href="{{ route('petugas_pembayaran.pembayaran.index') }}" class="btn btn-default btn-block">
                                    <i class="fas fa-sync-alt"></i> Reset
                                </a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <!-- /.filter box -->

        <!-- Pending Payment Box -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-money-check-alt mr-1"></i>
                    Daftar Pembayaran
                </h3>
                <div class="card-tools">
                    <span class="badge badge-info">{{ $pembayaran->total() }} total data</span>
                </div>
            </div>
            <div class="card-body table-responsive p-0">
                <table class="table table-hover text-nowrap">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Tanggal</th>
                            <th>Pengguna</th>
                            <th>Fasilitas</th>
                            <th>Jumlah</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pembayaran as $p)
                        <tr>
                            <td>{{ $p->id }}</td>
                            <td>{{ $p->created_at->format('d/m/Y H:i') }}</td>
                            <td>
                                @if($p->checkout && $p->checkout->user)
                                    <strong>{{ $p->checkout->user->name }}</strong><br>
                                    <small>{{ $p->checkout->user->no_hp }}</small>
                                @else
                                    <span class="text-muted">Data tidak tersedia</span>
                                @endif
                            </td>
                            <td>
                                @if($p->fasilitas)
                                    {{ $p->fasilitas->nama_fasilitas }}<br>
                                    <small>{{ $p->fasilitas->lokasi }}</small>
                                @else
                                    <span class="text-muted">Data tidak tersedia</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge badge-success">Rp {{ number_format($p->jumlah_bayar, 0, ',', '.') }}</span><br>
                                <small>{{ ucfirst($p->metode_pembayaran) }}</small>
                            </td>
                            <td>
                                @if($p->status == 'pending')
                                    <span class="badge badge-warning">Menunggu Verifikasi</span>
                                @elseif($p->status == 'fee')
                                    <span class="badge badge-info">DP Terbayar</span>
                                @elseif($p->status == 'lunas')
                                    <span class="badge badge-success">Lunas</span>
                                @elseif($p->status == 'ditolak')
                                    <span class="badge badge-danger">Ditolak</span>
                                @else
                                    <span class="badge badge-secondary">{{ $p->status }}</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group">
                                    <a href="{{ route('petugas_pembayaran.pembayaran.show', $p->id) }}" class="btn btn-sm btn-info" title="Lihat Detail">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    @if($p->status == 'pending')
                                    <button type="button" class="btn btn-sm btn-success" title="Verifikasi Pembayaran" onclick="verifikasiModal({{ $p->id }})">
                                        <i class="fas fa-check"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-danger" title="Tolak Pembayaran" onclick="tolakModal({{ $p->id }})">
                                        <i class="fas fa-times"></i>
                                    </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-3">
                                <div class="alert alert-info mb-0">
                                    <i class="fas fa-info-circle mr-2"></i>Tidak ada data pembayaran yang perlu diverifikasi
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="card-footer clearfix">
                <div class="float-right">
                    {{ $pembayaran->links() }}
                </div>
            </div>
        </div>
        <!-- /.pending payment box -->
    </div>
</section>
<!-- /.content -->

<!-- Modal Verifikasi -->
<div class="modal fade" id="verifikasiModal" tabindex="-1" role="dialog" aria-labelledby="verifikasiModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="" method="POST" id="verifikasiForm">
                @csrf
                @method('PUT')
                <div class="modal-header bg-success">
                    <h5 class="modal-title" id="verifikasiModalLabel">Verifikasi Pembayaran</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Anda yakin ingin memverifikasi pembayaran ini?</p>
                    <div class="form-group">
                        <label for="catatan">Catatan (opsional)</label>
                        <textarea name="catatan" id="catatan" class="form-control" rows="3" placeholder="Tambahkan catatan jika diperlukan"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">Verifikasi</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Tolak -->
<div class="modal fade" id="tolakModal" tabindex="-1" role="dialog" aria-labelledby="tolakModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="" method="POST" id="tolakForm">
                @csrf
                @method('PUT')
                <div class="modal-header bg-danger">
                    <h5 class="modal-title" id="tolakModalLabel">Tolak Pembayaran</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="alasan_tolak">Alasan Penolakan <span class="text-danger">*</span></label>
                        <textarea name="alasan_tolak" id="alasan_tolak" class="form-control" rows="3" placeholder="Berikan alasan penolakan" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">Tolak</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function verifikasiModal(id) {
        $('#verifikasiForm').attr('action', `{{ route('petugas_pembayaran.pembayaran.verifikasi', '') }}/${id}`);
        $('#verifikasiModal').modal('show');
    }

    function tolakModal(id) {
        $('#tolakForm').attr('action', `{{ route('petugas_pembayaran.pembayaran.tolak', '') }}/${id}`);
        $('#tolakModal').modal('show');
    }
</script>
@endsection
