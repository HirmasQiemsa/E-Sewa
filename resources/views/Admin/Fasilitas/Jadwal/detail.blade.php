@extends('Admin.component')
@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Detail Jadwal</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.jadwal.index') }}">Jadwal</a></li>
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
                <!-- Jadwal Information Card -->
                <div class="col-md-6">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Informasi Jadwal</h3>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered">
                                <tr>
                                    <th>ID Jadwal</th>
                                    <td>{{ $jadwal->id }}</td>
                                </tr>
                                <tr>
                                    <th>Fasilitas</th>
                                    <td>{{ $jadwal->fasilitas->nama_fasilitas }}</td>
                                </tr>
                                <tr>
                                    <th>Lokasi</th>
                                    <td>{{ $jadwal->fasilitas->lokasi }}</td>
                                </tr>
                                <tr>
                                    <th>Tanggal</th>
                                    <td>{{ date('d F Y', strtotime($jadwal->tanggal)) }}</td>
                                </tr>
                                <tr>
                                    <th>Waktu</th>
                                    <td>{{ substr($jadwal->jam_mulai, 0, 5) }} - {{ substr($jadwal->jam_selesai, 0, 5) }} ({{ $jadwal->durasi }} jam)</td>
                                </tr>
                                <tr>
                                    <th>Status</th>
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
                                <tr>
                                    <th>Harga</th>
                                    <td>{{ $jadwal->fasilitas->formatted_price }} / jam</td>
                                </tr>
                            </table>

                            <div class="mt-3">
                                @if($jadwal->status == 'tersedia')
                                    <a href="{{ route('admin.jadwal.edit', $jadwal->id) }}" class="btn btn-info">
                                        <i class="fas fa-edit"></i> Edit Jadwal
                                    </a>

                                    <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#deleteModal">
                                        <i class="fas fa-trash"></i> Hapus Jadwal
                                    </button>
                                @else
                                    <a href="{{ route('admin.jadwal.edit', $jadwal->id) }}" class="btn btn-info">
                                        <i class="fas fa-edit"></i> Update Status
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Fasilitas Information -->
                <div class="col-md-6">
                    <div class="card card-success">
                        <div class="card-header">
                            <h3 class="card-title">Informasi Fasilitas</h3>
                        </div>
                        <div class="card-body">
                            <div class="text-center mb-3">
                                <img src="{{ asset('storage/' . $jadwal->fasilitas->foto) }}" alt="{{ $jadwal->fasilitas->nama_fasilitas }}"
                                    class="img-fluid rounded" style="max-height: 200px;">
                            </div>

                            <h5 class="text-center mb-3">{{ $jadwal->fasilitas->nama_fasilitas }}</h5>

                            <p><strong>Tipe:</strong> {{ $jadwal->fasilitas->tipe }}</p>
                            <p><strong>Lokasi:</strong> {{ $jadwal->fasilitas->lokasi }}</p>
                            <p><strong>Deskripsi:</strong> {{ $jadwal->fasilitas->deskripsi }}</p>
                            <p><strong>Status:</strong> {!! $jadwal->fasilitas->status_badge !!}</p>
                        </div>
                    </div>
                </div>

                <!-- Booking Information (if booked) -->
                @if($jadwal->status == 'terbooking' || $jadwal->status == 'selesai')
                    <div class="col-md-12 mt-3">
                        <div class="card card-warning">
                            <div class="card-header">
                                <h3 class="card-title">Informasi Booking</h3>
                            </div>
                            <div class="card-body table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>ID Booking</th>
                                            <th>Pemesan</th>
                                            <th>Tanggal Booking</th>
                                            <th>Status Pembayaran</th>
                                            <th>Total</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($bookings as $booking)
                                            <tr>
                                                <td>{{ $booking->id }}</td>
                                                <td>{{ $booking->user->name }}</td>
                                                <td>{{ date('d F Y H:i', strtotime($booking->created_at)) }}</td>
                                                <td>
                                                    @if($booking->status == 'kompensasi')
                                                        <span class="badge badge-warning">DP</span>
                                                    @elseif($booking->status == 'lunas')
                                                        <span class="badge badge-success">Lunas</span>
                                                    @elseif($booking->status == 'batal')
                                                        <span class="badge badge-danger">Batal</span>
                                                    @endif
                                                </td>
                                                <td>Rp {{ number_format($booking->total_bayar, 0, ',', '.') }}</td>
                                                <td>
                                                    <a href="{{ route('admin.booking.detail', $booking->id) }}" class="btn btn-sm btn-info">
                                                        <i class="fas fa-eye"></i> Detail
                                                    </a>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6" class="text-center">Tidak ada informasi booking</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Back Button -->
                <div class="col-12 mt-3">
                    <a href="{{ route('admin.jadwal.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Kembali ke Daftar Jadwal
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Delete Confirmation Modal -->
    @if($jadwal->status == 'tersedia')
        <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Konfirmasi Hapus</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>Apakah Anda yakin ingin menghapus jadwal ini?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <form action="{{ route('admin.jadwal.destroy', $jadwal->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Hapus Jadwal</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection
