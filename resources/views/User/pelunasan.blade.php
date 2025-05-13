@extends('User.user')
@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Pelunasan Pembayaran</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('user.checkout') }}">Checkout</a></li>
                        <li class="breadcrumb-item active">Pelunasan</li>
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
                <!-- Form Pelunasan -->
                <div class="col-md-6">
                    <div class="card card-success">
                        <div class="card-header">
                            <h3 class="card-title">Form Pelunasan</h3>
                        </div>

                        <form action="{{ route('user.checkout.proses-lunasi', $checkout->id) }}" method="POST">
                            @csrf
                            <div class="card-body">
                                <!-- Informasi Booking -->
                                <div class="form-group">
                                    <label>ID Booking</label>
                                    <input type="text" class="form-control" value="{{ $checkout->id }}" readonly>
                                </div>

                                <div class="form-group">
                                    <label>Fasilitas</label>
                                    <input type="text" class="form-control"
                                        value="{{ $checkout->jadwal->fasilitas->nama_fasilitas }} ({{ $checkout->jadwal->fasilitas->tipe }})"
                                        readonly>
                                </div>

                                <div class="form-group">
                                    <label>Tanggal Booking</label>
                                    <input type="text" class="form-control"
                                        value="{{ date('d F Y', strtotime($checkout->jadwal->tanggal)) }}"
                                        readonly>
                                </div>

                                <div class="form-group">
                                    <label>Total Tagihan</label>
                                    <input type="text" class="form-control"
                                        value="Rp {{ number_format($checkout->total_bayar, 0, ',', '.') }}"
                                        readonly>
                                </div>

                                <div class="form-group">
                                    <label>DP Sudah Dibayar (50%)</label>
                                    <input type="text" class="form-control"
                                        value="Rp {{ number_format($checkout->total_bayar * 0.5, 0, ',', '.') }}"
                                        readonly>
                                </div>

                                <div class="form-group">
                                    <label>Sisa Pembayaran</label>
                                    <input type="text" class="form-control"
                                        value="Rp {{ number_format($sisaPembayaran, 0, ',', '.') }}"
                                        readonly>
                                </div>

                                <!-- Metode Pembayaran -->
                                <div class="form-group">
                                    <label>Metode Pembayaran</label>
                                    <select name="metode_pembayaran" class="form-control" required>
                                        <option value="cash">Cash</option>
                                        <option value="transfer">Transfer Bank</option>
                                    </select>
                                </div>

                                <div class="alert alert-info">
                                    <h5><i class="icon fas fa-info"></i> Informasi Pembayaran</h5>
                                    <p>Silakan lakukan pembayaran langsung ke petugas di tempat.</p>
                                    <p>Jika memilih transfer bank, silakan transfer ke rekening:</p>
                                    <p><strong>Bank BCA: 1234567890</strong> a.n. DISPORA Semarang</p>
                                </div>
                            </div>

                            <div class="card-footer">
                                <button type="submit" class="btn btn-success">Konfirmasi Pelunasan</button>
                                <a href="{{ route('user.checkout') }}" class="btn btn-secondary">Kembali</a>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Informasi Jadwal -->
                <div class="col-md-6">
                    <div class="card card-info">
                        <div class="card-header">
                            <h3 class="card-title">Detail Jadwal</h3>
                        </div>

                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Tanggal</th>
                                            <th>Jam</th>
                                            <th>Durasi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($checkout->jadwals as $jadwal)
                                            <tr>
                                                <td>{{ date('d/m/Y', strtotime($jadwal->tanggal)) }}</td>
                                                <td>{{ substr($jadwal->jam_mulai, 0, 5) }} - {{ substr($jadwal->jam_selesai, 0, 5) }}</td>
                                                <td>{{ $jadwal->durasi }} jam</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <div class="mt-4">
                                <h5>Informasi Fasilitas</h5>
                                <p><strong>Nama:</strong> {{ $checkout->jadwal->fasilitas->nama_fasilitas }}</p>
                                <p><strong>Tipe:</strong> {{ $checkout->jadwal->fasilitas->tipe }}</p>
                                <p><strong>Lokasi:</strong> {{ $checkout->jadwal->fasilitas->lokasi }}</p>
                                <p><strong>Harga Per Jam:</strong> Rp {{ number_format($checkout->jadwal->fasilitas->harga_sewa, 0, ',', '.') }}</p>
                            </div>

                            <div class="alert alert-warning mt-4">
                                <h5><i class="icon fas fa-exclamation-triangle"></i> Perhatian!</h5>
                                <p>Silakan lunasi pembayaran sebelum jadwal penggunaan fasilitas.</p>
                                <p>Jika terlambat membayar, booking dapat dibatalkan oleh sistem.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
