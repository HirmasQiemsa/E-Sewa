@extends('User.user')
@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Detail Booking</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <!-- Dynamic breadcrumb based on source -->
                        <li class="breadcrumb-item">
                            <a
                                href="{{ request()->query('source') === 'riwayat' ? route('user.riwayat') : route('user.checkout') }}">
                                {{ request()->query('source') === 'riwayat' ? 'Riwayat' : 'Checkout' }}
                            </a>
                        </li>
                        <li class="breadcrumb-item active">Detail</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <!-- Detail Booking -->
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-info-circle mr-1"></i>
                                Informasi Booking #{{ $checkout->id }}
                            </h3>

                            <div class="card-tools">
                                <span
                                    class="badge
                                    @if ($checkout->status == 'fee') badge-warning
                                    @elseif($checkout->status == 'lunas') badge-success
                                    @elseif($checkout->status == 'batal') badge-danger @endif">
                                    {{ ucfirst($checkout->status) }}
                                </span>
                            </div>
                        </div>

                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <strong><i class="fas fa-calendar mr-1"></i> Tanggal Booking</strong>
                                    <p class="text-muted">
                                        {{ date('d F Y', strtotime($checkout->jadwal->tanggal)) }}
                                    </p>

                                    <strong><i class="fas fa-map-marker-alt mr-1"></i> Fasilitas Dipilih</strong>
                                    <p class="text-muted">
                                        {{ $checkout->jadwal->fasilitas->nama_fasilitas }}
                                        ({{ $checkout->jadwal->fasilitas->tipe }})
                                        <br>
                                        <small>{{ $checkout->jadwal->fasilitas->lokasi }}</small>
                                    </p>

                                    <strong><i class="fas fa-check-circle mr-1"></i> Include</strong>
                                    <p class="text-muted">
                                        {{ $checkout->jadwal->fasilitas->deskripsi }}
                                    </p>

                                    <strong><i class="fas fa-money-bill mr-1"></i> Total Biaya</strong>
                                    <p class="text-muted">
                                        Rp {{ number_format($checkout->total_bayar, 0, ',', '.') }}
                                    </p>
                                </div>

                                <div class="col-md-6">
                                    <strong><i class="fas fa-user mr-1"></i> Nama Pemesan</strong>
                                    <p class="text-muted">{{ $checkout->user->name }}</p>

                                    <strong><i class="fas fa-clock mr-1"></i> Tanggal Pemesanan</strong>
                                    <p class="text-muted">{{ date('d F Y H:i', strtotime($checkout->created_at)) }}</p>

                                    <strong><i class="fas fa-edit mr-1"></i> Status Terakhir</strong>
                                    <p class="text-muted">{{ date('d F Y H:i', strtotime($checkout->updated_at)) }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Jadwal Detail -->
                <div class="col-md-6">
                    <div class="card card-info">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-calendar-alt mr-1"></i>
                                Detail Jadwal
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
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($jadwals as $index => $jadwal)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ date('d/m/Y', strtotime($jadwal->tanggal)) }}</td>
                                            <td>{{ substr($jadwal->jam_mulai, 0, 5) }} -
                                                {{ substr($jadwal->jam_selesai, 0, 5) }}</td>
                                            <td>{{ $jadwal->durasi }} jam</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Riwayat Pembayaran (Diperbarui) -->
                <div class="col-md-6">
                    <div class="card card-success">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-money-check mr-1"></i>
                                Riwayat Pembayaran
                            </h3>
                        </div>

                        <div class="card-body table-responsive p-0">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Tanggal</th>
                                        <th>Jumlah</th>
                                        <th>Metode</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($pembayaran as $p)
                                        <tr>
                                            <td>{{ date('d/m/Y H:i', strtotime($p->tanggal_bayar)) }}</td>
                                            <td>Rp {{ number_format($p->jumlah_bayar, 0, ',', '.') }}</td>
                                            <td>{{ ucfirst($p->metode_pembayaran) }}</td>
                                            <td>
                                                @if ($p->status == 'fee')
                                                    <span class="badge badge-warning">DP</span>
                                                @elseif($p->status == 'lunas')
                                                    <span class="badge badge-success">Lunas</span>
                                                @elseif($p->status == 'pending')
                                                    <span class="badge badge-info">Menunggu Verifikasi</span>
                                                @elseif($p->status == 'ditolak')
                                                    <span class="badge badge-danger">Ditolak</span>
                                                    @if ($p->keterangan)
                                                        <i class="fas fa-info-circle" data-toggle="tooltip"
                                                            title="{{ $p->keterangan }}"></i>
                                                    @endif
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center">Tidak ada data pembayaran</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        @if ($checkout->status == 'fee')
                            @php
                                $pendingPayment = false;
                                foreach ($pembayaran as $p) {
                                    if ($p->status == 'pending') {
                                        $pendingPayment = true;
                                        break;
                                    }
                                }
                            @endphp

                            @if (!$pendingPayment)
                                <div class="card-footer d-flex justify-content-end">
                                    <a href="{{ route('user.checkout.pelunasan', $checkout->id) }}"
                                        class="btn btn-success">
                                        <i class="fas fa-money-bill"></i> Lunasi Pembayaran
                                    </a>
                                </div>
                            @else
                                <div class="card-footer">
                                    <div class="alert alert-info mb-0">
                                        <i class="fas fa-info-circle mr-2"></i> Pembayaran pelunasan sedang dalam proses
                                        verifikasi.
                                    </div>
                                </div>
                            @endif
                        @endif
                    </div>
                </div>

                <!-- Tindakan -->
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <!-- Dynamic back button based on source -->
                                <a href="{{ request()->query('source') === 'riwayat' ? route('user.riwayat') : route('user.checkout') }}"
                                    class="btn btn-secondary">
                                    <i class="fas fa-arrow-left"></i> Kembali ke
                                    {{ request()->query('source') === 'riwayat' ? 'Riwayat' : 'Checkout' }}
                                </a>

                                @if ($checkout->status == 'fee')
                                    <button type="button" class="btn btn-danger"
                                        onclick="confirmCancel({{ $checkout->id }})">
                                        <i class="fas fa-times"></i> Batalkan Booking
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Modal Konfirmasi Batal -->
    <div class="modal fade" id="cancelModal" tabindex="-1" role="dialog" aria-labelledby="cancelModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="cancelModalLabel">Konfirmasi Pembatalan</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin membatalkan booking ini? DP tidak akan dikembalikan.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <form id="cancel-form" action="{{ route('user.checkout.cancel', $checkout->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <button type="submit" class="btn btn-danger">Ya, Batalkan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function confirmCancel(id) {
            $('#cancelModal').modal('show');
        }
    </script>
@endsection
