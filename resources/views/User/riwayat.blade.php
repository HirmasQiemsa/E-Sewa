@extends('User.user')
@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Riwayat Booking</h1>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-history mr-1"></i>
                        Semua Riwayat Booking
                    </h3>

                    <div class="card-tools">
                        <div class="input-group input-group-sm" style="width: 150px;">
                            <input type="text" name="table_search" id="history-search" class="form-control float-right"
                                placeholder="Search">
                            <div class="input-group-append">
                                <button type="button" class="btn btn-default" onclick="searchHistory()">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-body table-responsive p-0">
                    <table class="table table-hover text-nowrap">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>ID</th>
                                <th>Tanggal</th>
                                <th>Fasilitas</th>
                                <th>Durasi</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($riwayat as $index => $item)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $item->id }}</td>
                                    <td>{{ $item->jadwal ? date('d-m-Y', strtotime($item->jadwal->tanggal)) : '-' }}</td>
                                    <td>{{ $item->jadwal && $item->jadwal->fasilitas ? $item->jadwal->fasilitas->nama_fasilitas : '-' }}</td>
                                    <td>{{ $item->totalDurasi ?? '-' }} jam</td>
                                    <td>Rp {{ number_format($item->total_bayar, 0, ',', '.') }}</td>
                                    <td>
                                        @if($item->status == 'fee')
                                            <span class="badge badge-warning">DP</span>
                                        @elseif($item->status == 'lunas')
                                            <span class="badge badge-success">Lunas</span>
                                        @elseif($item->status == 'batal')
                                            <span class="badge badge-danger">Batal</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('user.checkout.detail', $item->id) }}" class="btn btn-sm btn-info">
                                            <i class="fas fa-eye"></i> Detail
                                        </a>

                                        @if($item->status == 'fee')
                                            <a href="{{ route('user.checkout.pelunasan', $item->id) }}" class="btn btn-sm btn-success">
                                                <i class="fas fa-money-bill"></i> Lunasi
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center">Belum ada riwayat booking</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-md-4">
                    <div class="info-box bg-success">
                        <span class="info-box-icon"><i class="fas fa-check"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Booking Lunas</span>
                            <span class="info-box-number">
                                {{ $riwayat->where('status', 'lunas')->count() }}
                            </span>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="info-box bg-warning">
                        <span class="info-box-icon"><i class="fas fa-hourglass-half"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Booking DP</span>
                            <span class="info-box-number">
                                {{ $riwayat->where('status', 'fee')->count() }}
                            </span>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="info-box bg-danger">
                        <span class="info-box-icon"><i class="fas fa-times"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Booking Dibatalkan</span>
                            <span class="info-box-number">
                                {{ $riwayat->where('status', 'batal')->count() }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script>
        function searchHistory() {
            const searchText = document.getElementById('history-search').value.toLowerCase();
            const rows = document.querySelectorAll('tbody tr');

            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                if (text.includes(searchText)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }
    </script>
@endsection
