@extends('Admin.component')
@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Kelola Booking</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Kelola Booking</li>
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

            <!-- Filter and Search Tools -->
            <div class="card mb-3">
                <div class="card-header bg-light">
                    <h3 class="card-title">
                        <i class="fas fa-filter mr-2"></i>Filter dan Pencarian
                    </h3>
                </div>
                <div class="card-body">
                    <form id="filter-form" action="{{ route('admin.fasilitas.booking.index') }}" method="GET">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Periode:</label>
                                    <select id="filter-type" name="filter_type" class="form-control">
                                        <option value="today" {{ $filterType == 'today' ? 'selected' : '' }}>Hari Ini</option>
                                        <option value="upcoming" {{ $filterType == 'upcoming' ? 'selected' : '' }}>Akan Datang</option>
                                        <option value="custom" {{ $filterType == 'custom' ? 'selected' : '' }}>Periode Tertentu</option>
                                        <option value="all" {{ $filterType == 'all' || !$filterType ? 'selected' : '' }}>Semua Tanggal</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-4 custom-date-range {{ $filterType == 'custom' ? '' : 'd-none' }}">
                                <div class="form-group">
                                    <label>Rentang Tanggal:</label>
                                    <div class="input-group">
                                        <input type="date" id="start-date" name="start_date" class="form-control" value="{{ $startDate ?? '' }}">
                                        <div class="input-group-append input-group-prepend">
                                            <span class="input-group-text">hingga</span>
                                        </div>
                                        <input type="date" id="end-date" name="end_date" class="form-control" value="{{ $endDate ?? '' }}">
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Status:</label>
                                    <select id="status-filter" name="status" class="form-control">
                                        <option value="all" {{ !$status || $status == 'all' ? 'selected' : '' }}>Semua</option>
                                        <option value="fee" {{ $status == 'kompensasi' ? 'selected' : '' }}>DP</option>
                                        <option value="lunas" {{ $status == 'lunas' ? 'selected' : '' }}>Lunas</option>
                                        <option value="batal" {{ $status == 'batal' ? 'selected' : '' }}>Batal</option>
                                        <option value="selesai" {{ $status == 'selesai' ? 'selected' : '' }}>Selesai</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Pencarian:</label>
                                    <div class="input-group">
                                        <input type="text" id="search-input" name="search" class="form-control"
                                            placeholder="Nama, fasilitas..." value="{{ $search ?? '' }}">
                                        <div class="input-group-append">
                                            <button type="submit" class="btn btn-default">
                                                <i class="fas fa-search"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary">Terapkan Filter</button>
                                <a href="{{ route('admin.fasilitas.booking.index') }}" class="btn btn-outline-secondary ml-2">
                                    <i class="fas fa-redo-alt mr-1"></i>Reset
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Daftar Booking -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-calendar-check mr-2"></i>Daftar Booking
                    </h3>
                </div>
                <div class="card-body table-responsive p-0">
                    <table class="table table-hover table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>ID</th>
                                <th>Tanggal</th>
                                <th>Fasilitas</th>
                                <th>Pemesan</th>
                                <th>Durasi</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($bookings as $index => $booking)
                                <tr>
                                    <td>{{ ($bookings->currentPage() - 1) * $bookings->perPage() + $index + 1 }}</td>
                                    <td>
                                        <span class="badge bg-secondary">#{{ $booking->id }}</span>
                                    </td>
                                    <td>
                                        @if($booking->jadwals->isNotEmpty())
                                            {{ date('d-m-Y', strtotime($booking->jadwals->first()->tanggal)) }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>
                                        @if($booking->jadwals->isNotEmpty() && $booking->jadwals->first()->fasilitas)
                                            <strong>{{ $booking->jadwals->first()->fasilitas->nama_fasilitas }}</strong><br>
                                            <small class="text-muted">{{ $booking->jadwals->first()->fasilitas->lokasi }}</small>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>
                                        @if($booking->user)
                                            <strong>{{ $booking->user->name }}</strong><br>
                                            <small class="text-muted">{{ $booking->user->no_hp }}</small>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>{{ $booking->totalDurasi ?? '-' }} jam</td>
                                    <td>Rp {{ number_format($booking->total_bayar, 0, ',', '.') }}</td>
                                    <td>
                                        @if ($booking->status == 'kompensasi')
                                            <span class="badge badge-warning">DP</span>
                                        @elseif($booking->status == 'lunas')
                                            <span class="badge badge-success">Lunas</span>
                                        @elseif($booking->status == 'batal')
                                            <span class="badge badge-danger">Batal</span>
                                        @elseif($booking->status == 'selesai')
                                            <span class="badge badge-info">Selesai</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group">
                                            <a href="{{ route('admin.fasilitas.booking.detail', $booking->id) }}" class="btn btn-sm btn-info" title="Lihat Detail">
                                                <i class="fas fa-eye"></i>
                                            </a>

                                            <button type="button" class="btn btn-sm btn-primary"
                                                    data-toggle="modal" data-target="#statusModal{{ $booking->id }}" title="Ubah Status">
                                                <i class="fas fa-edit"></i>
                                            </button>

                                            @if ($booking->status == 'kompensasi')
                                                <button type="button" class="btn btn-sm btn-danger"
                                                        data-toggle="modal" data-target="#cancelModal{{ $booking->id }}" title="Batalkan Booking">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            @endif
                                        </div>

                                        <!-- Modal Ubah Status -->
                                        <div class="modal fade" id="statusModal{{ $booking->id }}" tabindex="-1" role="dialog" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <form action="{{ route('admin.fasilitas.booking.update-status', $booking->id) }}" method="POST">
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
                                                                    Perubahan ke status "Lunas" tidak akan memproses pembayaran baru. Gunakan untuk perubahan status administratif saja.
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
                                        <div class="modal fade" id="cancelModal{{ $booking->id }}" tabindex="-1" role="dialog" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <form action="{{ route('admin.fasilitas.booking.cancel', $booking->id) }}" method="POST">
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
                                                                    <input type="radio" id="refund-yes{{ $booking->id }}" name="refund_dp" value="1" class="custom-control-input">
                                                                    <label class="custom-control-label" for="refund-yes{{ $booking->id }}">DP Dikembalikan</label>
                                                                </div>
                                                                <div class="custom-control custom-radio mt-2">
                                                                    <input type="radio" id="refund-no{{ $booking->id }}" name="refund_dp" value="0" class="custom-control-input" checked>
                                                                    <label class="custom-control-label" for="refund-no{{ $booking->id }}">DP Tidak Dikembalikan</label>
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
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center py-3">
                                        <div class="alert alert-info mb-0">
                                            <i class="fas fa-info-circle mr-2"></i>Tidak ada data booking
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="card-footer clearfix">
                    <div class="float-left">
                        {{ $bookings->links() }}
                    </div>
                    <div class="float-right">
                        <span>
                            Menampilkan {{ $bookings->firstItem() ?? 0 }}-{{ $bookings->lastItem() ?? 0 }} dari
                            {{ $bookings->total() }} booking
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- JavaScript untuk toggle custom date fields -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Toggle custom date fields
            const filterType = document.getElementById('filter-type');
            const customDateRange = document.querySelector('.custom-date-range');

            filterType.addEventListener('change', function() {
                if (this.value === 'custom') {
                    customDateRange.classList.remove('d-none');
                } else {
                    customDateRange.classList.add('d-none');
                }
            });

            // Auto-submit on filter change
            document.getElementById('filter-type').addEventListener('change', function() {
                if (this.value !== 'custom') {
                    document.getElementById('filter-form').submit();
                }
            });

            // Auto-submit on status change
            document.getElementById('status-filter').addEventListener('change', function() {
                document.getElementById('filter-form').submit();
            });
        });
    </script>
@endsection
