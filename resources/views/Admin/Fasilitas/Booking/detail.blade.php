@extends('Admin.component')
@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Detail Booking #{{ $booking->id }}</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.fasilitas.booking.index') }}">Kelola Booking</a></li>
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
            <!-- Notifikasi -->
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
                <!-- Info Booking -->
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header bg-primary">
                            <h3 class="card-title">
                                <i class="fas fa-info-circle mr-1"></i> Informasi Booking
                            </h3>
                            <div class="card-tools">
                                <span class="badge
                                    @if($booking->status == 'kompensasi') badge-warning
                                    @elseif($booking->status == 'lunas') badge-success
                                    @elseif($booking->status == 'batal') badge-danger
                                    @elseif($booking->status == 'selesai') badge-info
                                    @endif">
                                    {{ ucfirst($booking->status) }}
                                </span>
                            </div>
                        </div>
                        <div class="card-body">
                            <p><strong><i class="fas fa-calendar mr-1"></i> Tanggal Booking:</strong></p>
                            <p class="text-muted ml-3">
                                {{ $booking->jadwals->isNotEmpty() ? date('d F Y', strtotime($booking->jadwals->first()->tanggal)) : '-' }}
                            </p>

                            <p><strong><i class="fas fa-clock mr-1"></i> Waktu Pemesanan:</strong></p>
                            <p class="text-muted ml-3">{{ date('d F Y H:i', strtotime($booking->created_at)) }}</p>

                            <p><strong><i class="fas fa-money-bill mr-1"></i> Total Biaya:</strong></p>
                            <p class="text-muted ml-3">Rp {{ number_format($booking->total_bayar, 0, ',', '.') }}</p>

                            @if($booking->riwayat)
                                <p><strong><i class="fas fa-comment mr-1"></i> Catatan:</strong></p>
                                <p class="text-muted ml-3">{{ $booking->riwayat->feedback }}</p>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Info Pemesan -->
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header bg-success">
                            <h3 class="card-title">
                                <i class="fas fa-user mr-1"></i> Informasi Pemesan
                            </h3>
                        </div>
                        <div class="card-body">
                            @if($booking->user)
                                <p><strong><i class="fas fa-user mr-1"></i> Nama:</strong></p>
                                <p class="text-muted ml-3">{{ $booking->user->name }}</p>

                                <p><strong><i class="fas fa-phone mr-1"></i> No. HP:</strong></p>
                                <p class="text-muted ml-3">{{ $booking->user->no_hp }}</p>

                                <p><strong><i class="fas fa-envelope mr-1"></i> Email:</strong></p>
                                <p class="text-muted ml-3">{{ $booking->user->email }}</p>

                                <p><strong><i class="fas fa-map-marker-alt mr-1"></i> Alamat:</strong></p>
                                <p class="text-muted ml-3">{{ $booking->user->alamat }}</p>
                            @else
                                <p class="text-center text-muted">Data pemesan tidak tersedia</p>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Info Fasilitas -->
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header bg-warning">
                            <h3 class="card-title">
                                <i class="fas fa-building mr-1"></i> Informasi Fasilitas
                            </h3>
                        </div>
                        <div class="card-body">
                            @if($booking->jadwals->isNotEmpty() && $booking->jadwals->first()->fasilitas)
                                @php $fasilitas = $booking->jadwals->first()->fasilitas; @endphp
                                <p><strong><i class="fas fa-tag mr-1"></i> Nama Fasilitas:</strong></p>
                                <p class="text-muted ml-3">{{ $fasilitas->nama_fasilitas }}</p>

                                <p><strong><i class="fas fa-info mr-1"></i> Tipe:</strong></p>
                                <p class="text-muted ml-3">{{ $fasilitas->tipe ?? '-' }}</p>

                                <p><strong><i class="fas fa-map-pin mr-1"></i> Lokasi:</strong></p>
                                <p class="text-muted ml-3">{{ $fasilitas->lokasi }}</p>

                                <p><strong><i class="fas fa-dollar-sign mr-1"></i> Harga per Jam:</strong></p>
                                <p class="text-muted ml-3">Rp {{ number_format($fasilitas->harga_sewa, 0, ',', '.') }}</p>
                            @else
                                <p class="text-center text-muted">Data fasilitas tidak tersedia</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Jadwal Detail -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header bg-info">
                            <h3 class="card-title">
                                <i class="fas fa-calendar-alt mr-1"></i> Detail Jadwal
                            </h3>
                        </div>
                        <div class="card-body table-responsive p-0">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Tanggal</th>
                                        <th>Jam</th>
                                        <th>Durasi</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($booking->jadwals as $index => $jadwal)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ date('d/m/Y', strtotime($jadwal->tanggal)) }}</td>
                                            <td>{{ substr($jadwal->jam_mulai, 0, 5) }} - {{ substr($jadwal->jam_selesai, 0, 5) }}</td>
                                            <td>
                                                @php
                                                    $mulaiParts = explode(':', $jadwal->jam_mulai);
                                                    $selesaiParts = explode(':', $jadwal->jam_selesai);
                                                    $mulaiJam = (int)$mulaiParts[0];
                                                    $selesaiJam = (int)$selesaiParts[0];
                                                    $durasi = $selesaiJam - $mulaiJam;
                                                    echo $durasi . " jam";
                                                @endphp
                                            </td>
                                            <td>
                                                @if($jadwal->status == 'tersedia')
                                                    <span class="badge badge-success">Tersedia</span>
                                                @elseif($jadwal->status == 'terbooking')
                                                    <span class="badge badge-warning">Terbooking</span>
                                                @elseif($jadwal->status == 'selesai')
                                                    <span class="badge badge-info">Selesai</span>
                                                @elseif($jadwal->status == 'batal')
                                                    <span class="badge badge-danger">Batal</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center">Tidak ada data jadwal</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Riwayat Pembayaran -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header bg-success">
                            <h3 class="card-title">
                                <i class="fas fa-money-check mr-1"></i> Riwayat Pembayaran
                            </h3>
                        </div>
                        <div class="card-body table-responsive p-0">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Tanggal</th>
                                        <th>Jumlah</th>
                                        <th>Metode</th>
                                        <th>Keterangan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($booking->pembayaran as $p)
                                        <tr>
                                            <td>{{ date('d/m/Y H:i', strtotime($p->tanggal_bayar)) }}</td>
                                            <td>Rp {{ number_format($p->jumlah_bayar, 0, ',', '.') }}</td>
                                            <td>{{ ucfirst($p->metode_pembayaran) }}</td>
                                            <td>{{ $p->status }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center">Tidak ada data pembayaran</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tombol Aksi -->
            <div class="row mt-3">
                <div class="col-12">
                    <a href="{{ route('admin.fasilitas.booking.index') }}" class="btn btn-secondary">Kembali</a>

                    @if($booking->status != 'lunas' && $booking->status != 'batal')
                        <form action="{{ route('admin.fasilitas.booking.cancel', $booking->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin batalkan booking ini?');">
                            @csrf
                            @method('PUT')
                            <button type="submit" class="btn btn-danger float-right">
                                <i class="fas fa-times"></i> Batalkan Paksa
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </section>

    <!-- Modal Ubah Status -->
    <div class="modal fade" id="statusModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form action="{{ route('petugas_fasilitas.booking.update-status', $booking->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title">Ubah Status Booking #{{ $booking->id }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Status Saat Ini:</label>
                            <input type="text" class="form-control" value="{{ ucfirst($booking->status) }}" readonly>
                        </div>
                        <div class="form-group">
                            <label>Status Baru:</label>
                            <select name="status" class="form-control" required>
                                <option value="fee" {{ $booking->status == 'kompensasi' ? 'selected' : '' }}>DP</option>
                                <option value="lunas" {{ $booking->status == 'lunas' ? 'selected' : '' }}>Lunas</option>
                                <option value="selesai" {{ $booking->status == 'selesai' ? 'selected' : '' }}>Selesai</option>
                                <option value="batal" {{ $booking->status == 'batal' ? 'selected' : '' }}>Batal</option>
                            </select>
                            <small class="form-text text-muted">
                                Perubahan ke status "Lunas" tidak akan memproses pembayaran baru.<br>
                                Gunakan untuk perubahan status administratif saja (misalnya event komunitas).
                            </small>
                        </div>
                        <div class="form-group">
                            <label>Catatan (Opsional):</label>
                            <textarea name="catatan" class="form-control" rows="3" placeholder="Catatan perubahan status..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Pembatalan -->
    <div class="modal fade" id="cancelModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form action="{{ route('petugas_fasilitas.booking.cancel', $booking->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-header bg-danger">
                        <h5 class="modal-title">Batalkan Booking #{{ $booking->id }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle mr-2"></i>
                            Perhatian! Tindakan ini akan membatalkan booking dan mengubah status jadwal menjadi tersedia kembali.
                        </div>

                        <div class="form-group">
                            <label>Alasan Pembatalan:</label>
                            <textarea name="alasan_batal" class="form-control" rows="3" required placeholder="Masukkan alasan pembatalan..."></textarea>
                        </div>

                        <div class="form-group">
                            <label>Pengembalian DP:</label>
                            <div class="custom-control custom-radio">
                                <input type="radio" id="refund-yes" name="refund_dp" value="1" class="custom-control-input">
                                <label class="custom-control-label" for="refund-yes">DP Dikembalikan</label>
                            </div>
                            <div class="custom-control custom-radio mt-2">
                                <input type="radio" id="refund-no" name="refund_dp" value="0" class="custom-control-input" checked>
                                <label class="custom-control-label" for="refund-no">DP Tidak Dikembalikan</label>
                            </div>
                            <small class="form-text text-muted mt-2">
                                Pilih apakah DP yang telah dibayarkan oleh pemesan akan dikembalikan atau tidak.
                                <br>Catatan: Pembatalan oleh petugas dengan alasan teknis sebaiknya mengembalikan DP.
                            </small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-danger">Batalkan Booking</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
