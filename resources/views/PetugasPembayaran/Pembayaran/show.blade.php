@extends('PetugasPembayaran.component')
@section('title', 'Detail Pembayaran')
@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Detail Pembayaran #{{ $pembayaran->id }}</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('petugas_pembayaran.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('petugas_pembayaran.pembayaran.index') }}">Pembayaran</a></li>
                    <li class="breadcrumb-item active">Detail</li>
                </ol>
            </div>
        </div>
    </div>
</div>
<!-- /.content-header -->

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <!-- Info Pembayaran -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-primary">
                        <h3 class="card-title">
                            <i class="fas fa-money-check-alt mr-1"></i>
                            Informasi Pembayaran
                        </h3>
                        <div class="card-tools">
                            @if($pembayaran->status == 'pending')
                                <span class="badge badge-warning">Menunggu Verifikasi</span>
                            @elseif($pembayaran->status == 'fee')
                                <span class="badge badge-info">DP Terbayar</span>
                            @elseif($pembayaran->status == 'lunas')
                                <span class="badge badge-success">Lunas</span>
                            @elseif($pembayaran->status == 'ditolak')
                                <span class="badge badge-danger">Ditolak</span>
                            @else
                                <span class="badge badge-secondary">{{ $pembayaran->status }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="card-body">
                        <dl class="row">
                            <dt class="col-sm-4">ID Pembayaran</dt>
                            <dd class="col-sm-8">{{ $pembayaran->id }}</dd>

                            <dt class="col-sm-4">Tanggal Bayar</dt>
                            <dd class="col-sm-8">{{ $pembayaran->created_at->format('d F Y H:i') }}</dd>

                            <dt class="col-sm-4">ID Booking</dt>
                            <dd class="col-sm-8">
                                @if($pembayaran->checkout)
                                    <strong>#{{ $pembayaran->checkout->id }}</strong>
                                @else
                                    <span class="text-muted">Data tidak tersedia</span>
                                @endif
                            </dd>

                            <dt class="col-sm-4">Jumlah Bayar</dt>
                            <dd class="col-sm-8">
                                <span class="badge badge-success p-2">
                                    Rp {{ number_format($pembayaran->jumlah_bayar, 0, ',', '.') }}
                                </span>
                            </dd>

                            <dt class="col-sm-4">Metode Pembayaran</dt>
                            <dd class="col-sm-8">{{ ucfirst($pembayaran->metode_pembayaran) }}</dd>

                            <dt class="col-sm-4">Terverifikasi Oleh</dt>
                            <dd class="col-sm-8">
                                @if($pembayaran->petugas_pembayaran_id)
                                    {{ optional($pembayaran->petugasPembayaran)->name ?? 'ID: '.$pembayaran->petugas_pembayaran_id }}
                                @else
                                    <span class="text-warning">Belum diverifikasi</span>
                                @endif
                            </dd>

                            @if($pembayaran->keterangan)
                                <dt class="col-sm-4">Catatan</dt>
                                <dd class="col-sm-8">{{ $pembayaran->keterangan }}</dd>
                            @endif
                        </dl>
                    </div>

                    @if($pembayaran->status == 'pending')
                        <div class="card-footer">
                            <div class="d-flex justify-content-between">
                                <button type="button" class="btn btn-success" onclick="verifikasiModal()">
                                    <i class="fas fa-check mr-1"></i> Verifikasi Pembayaran
                                </button>
                                <button type="button" class="btn btn-danger" onclick="tolakModal()">
                                    <i class="fas fa-times mr-1"></i> Tolak Pembayaran
                                </button>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Info Pengguna -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-info">
                        <h3 class="card-title">
                            <i class="fas fa-user mr-1"></i>
                            Informasi Pengguna
                        </h3>
                    </div>
                    <div class="card-body">
                        @if($pembayaran->checkout && $pembayaran->checkout->user)
                            <?php $user = $pembayaran->checkout->user; ?>
                            <div class="d-flex align-items-center mb-3">
                                <div class="mr-3">
                                    @if($user->foto)
                                        <img src="{{ asset('storage/'.$user->foto) }}" alt="Foto Profil" class="img-circle elevation-2" style="width: 60px; height: 60px; object-fit: cover;">
                                    @else
                                        <img src="{{ asset('default-user.png') }}" alt="Foto Profil" class="img-circle elevation-2" style="width: 60px; height: 60px; object-fit: cover;">
                                    @endif
                                </div>
                                <div>
                                    <h5 class="mb-0">{{ $user->name }}</h5>
                                    <p class="text-muted mb-0">{{ $user->email }}</p>
                                </div>
                            </div>

                            <dl class="row mt-3">
                                <dt class="col-sm-4">Username</dt>
                                <dd class="col-sm-8">{{ $user->username }}</dd>

                                <dt class="col-sm-4">No. HP</dt>
                                <dd class="col-sm-8">{{ $user->no_hp }}</dd>

                                <dt class="col-sm-4">Alamat</dt>
                                <dd class="col-sm-8">{{ $user->alamat }}</dd>
                            </dl>
                        @else
                            <div class="alert alert-warning mb-0">
                                <i class="fas fa-exclamation-triangle mr-2"></i> Data pengguna tidak tersedia
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Info Fasilitas & Jadwal -->
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header bg-success">
                        <h3 class="card-title">
                            <i class="fas fa-calendar-check mr-1"></i>
                            Detail Booking
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h5 class="mb-3">Informasi Fasilitas</h5>
                                @if($pembayaran->fasilitas)
                                    <div class="d-flex mb-3">
                                        <div class="mr-3">
                                            @if($pembayaran->fasilitas->foto)
                                                <img src="{{ asset('storage/'.$pembayaran->fasilitas->foto) }}" alt="Foto Fasilitas" class="img-thumbnail" style="width: 120px; height: 80px; object-fit: cover;">
                                            @else
                                                <div class="bg-light d-flex align-items-center justify-content-center" style="width: 120px; height: 80px;">
                                                    <i class="fas fa-image fa-2x text-muted"></i>
                                                </div>
                                            @endif
                                        </div>
                                        <div>
                                            <h5 class="mb-1">{{ $pembayaran->fasilitas->nama_fasilitas }}</h5>
                                            <p class="mb-0">{{ $pembayaran->fasilitas->tipe }}</p>
                                            <p class="text-muted mb-0">{{ $pembayaran->fasilitas->lokasi }}</p>
                                        </div>
                                    </div>

                                    <dl class="row">
                                        <dt class="col-sm-4">Harga per Jam</dt>
                                        <dd class="col-sm-8">Rp {{ number_format($pembayaran->fasilitas->harga_sewa, 0, ',', '.') }}</dd>

                                        <dt class="col-sm-4">Deskripsi</dt>
                                        <dd class="col-sm-8">{{ $pembayaran->fasilitas->deskripsi }}</dd>
                                    </dl>
                                @else
                                    <div class="alert alert-warning">
                                        <i class="fas fa-exclamation-triangle mr-2"></i> Data fasilitas tidak tersedia
                                    </div>
                                @endif
                            </div>

                            <div class="col-md-6">
                                <h5 class="mb-3">Detail Jadwal</h5>
                                @if($pembayaran->checkout && $pembayaran->checkout->jadwals->count() > 0)
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>Tanggal</th>
                                                    <th>Jam</th>
                                                    <th>Durasi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($pembayaran->checkout->jadwals as $index => $jadwal)
                                                    <?php
                                                        // Hitung durasi
                                                        $mulaiParts = explode(':', $jadwal->jam_mulai);
                                                        $selesaiParts = explode(':', $jadwal->jam_selesai);
                                                        $mulaiJam = (int)$mulaiParts[0];
                                                        $selesaiJam = (int)$selesaiParts[0];
                                                        $durasi = $selesaiJam - $mulaiJam;
                                                    ?>
                                                    <tr>
                                                        <td>{{ $index + 1 }}</td>
                                                        <td>{{ date('d/m/Y', strtotime($jadwal->tanggal)) }}</td>
                                                        <td>{{ substr($jadwal->jam_mulai, 0, 5) }} - {{ substr($jadwal->jam_selesai, 0, 5) }}</td>
                                                        <td>{{ $durasi }} jam</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>

                                    <div class="alert alert-info mt-3">
                                        <div class="d-flex justify-content-between">
                                            <div>
                                                <p class="mb-0"><strong>Total Booking:</strong> {{ $pembayaran->checkout->total_bayar ? 'Rp '.number_format($pembayaran->checkout->total_bayar, 0, ',', '.') : 'Tidak tersedia' }}</p>
                                            </div>
                                            <div>
                                                <p class="mb-0"><strong>Status Booking:</strong>
                                                    @if($pembayaran->checkout->status == 'fee')
                                                        <span class="badge badge-warning">DP</span>
                                                    @elseif($pembayaran->checkout->status == 'lunas')
                                                        <span class="badge badge-success">Lunas</span>
                                                    @elseif($pembayaran->checkout->status == 'batal')
                                                        <span class="badge badge-danger">Batal</span>
                                                    @else
                                                        <span class="badge badge-secondary">{{ $pembayaran->checkout->status }}</span>
                                                    @endif
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <div class="alert alert-warning">
                                        <i class="fas fa-exclamation-triangle mr-2"></i> Tidak ada data jadwal tersedia
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tombol Kembali -->
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <a href="{{ route('petugas_pembayaran.pembayaran.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left mr-1"></i> Kembali ke Daftar Pembayaran
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- /.content -->

<!-- Modal Verifikasi -->
<div class="modal fade" id="verifikasiModal" tabindex="-1" role="dialog" aria-labelledby="verifikasiModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{ route('petugas_pembayaran.pembayaran.verifikasi', $pembayaran->id) }}" method="POST" id="verifikasiForm">
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
            <form action="{{ route('petugas_pembayaran.pembayaran.tolak', $pembayaran->id) }}" method="POST" id="tolakForm">
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
    function verifikasiModal() {
        $('#verifikasiModal').modal('show');
    }

    function tolakModal() {
        $('#tolakModal').modal('show');
    }
</script>
@endsection
