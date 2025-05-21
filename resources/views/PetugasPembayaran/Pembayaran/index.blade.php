@extends('PetugasPembayaran.component')
@section('title', 'Verifikasi Pembayaran')
@section('content')
<!-- Content Header -->
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

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <!-- Filter & Search Box -->
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-filter mr-1"></i>
                    Filter & Pencarian
                </h3>
            </div>
            <div class="card-body">
                <form method="GET" action="{{ route('petugas_pembayaran.pembayaran.index') }}">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Status Booking:</label>
                                <select name="status" class="form-control select2">
                                    <option value="all" {{ $status == 'all' ? 'selected' : '' }}>Semua Status</option>
                                    <option value="fee" {{ $status == 'fee' ? 'selected' : '' }}>DP</option>
                                    <option value="lunas" {{ $status == 'lunas' ? 'selected' : '' }}>Lunas</option>
                                    <option value="batal" {{ $status == 'batal' ? 'selected' : '' }}>Batal</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Filter Waktu:</label>
                                <select name="filter" class="form-control select2">
                                    <option value="all" {{ $filter == 'all' ? 'selected' : '' }}>Semua Waktu</option>
                                    <option value="today" {{ $filter == 'today' ? 'selected' : '' }}>Hari Ini</option>
                                    <option value="week" {{ $filter == 'week' ? 'selected' : '' }}>Minggu Ini</option>
                                    <option value="month" {{ $filter == 'month' ? 'selected' : '' }}>Bulan Ini</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Cari:</label>
                                <div class="input-group">
                                    <input type="text" name="search" class="form-control" placeholder="ID, Nama User, atau Fasilitas" value="{{ $search ?? '' }}">
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

        <!-- Pembayaran Table -->
        <div class="card">
            <div class="card-header bg-primary">
                <h3 class="card-title">
                    <i class="fas fa-money-check-alt mr-1"></i>
                    Daftar Booking dan Pembayaran
                </h3>
                <div class="card-tools">
                    <span class="badge badge-light">{{ $checkouts->total() }} booking ditemukan</span>
                </div>
            </div>
            <div class="card-body table-responsive p-0">
                <table class="table table-hover text-nowrap">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Tanggal Booking</th>
                            <th>User</th>
                            <th>Fasilitas</th>
                            <th>Durasi</th>
                            <th>Pembayaran</th>
                            <th>Status</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($checkouts as $checkout)
                            <tr>
                                <td>
                                    <span class="badge badge-info">#{{ $checkout->id }}</span>
                                </td>
                                <td>
                                    @if($checkout->tanggal_booking)
                                        {{ \Carbon\Carbon::parse($checkout->tanggal_booking)->format('d M Y') }}
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if($checkout->user)
                                        <strong>{{ $checkout->user->name }}</strong><br>
                                        <small class="text-muted">{{ $checkout->user->no_hp }}</small>
                                    @else
                                        <span class="text-muted">User tidak ditemukan</span>
                                    @endif
                                </td>
                                <td>
                                    @if(isset($checkout->fasilitas))
                                        <strong>{{ $checkout->fasilitas->nama_display ?? $checkout->fasilitas->nama_fasilitas }}</strong><br>
                                        <small class="text-muted">{{ $checkout->fasilitas->lokasi ?? '-' }}</small>
                                    @else
                                        <span class="text-muted">Fasilitas tidak tersedia</span>
                                    @endif
                                </td>
                                <td>{{ $checkout->total_durasi ?? 0 }} jam</td>
                                <td>
                                    <!-- Kolom Pembayaran yang digabungkan: Total + Sudah Dibayar + Progress Bar -->
                                    <div>
                                        <div class="d-flex justify-content-between">
                                            <span>Dibayar: {{ number_format($checkout->persen_dibayar, 0) }}%</span>
                                            <span>
                                                <strong>Rp {{ number_format($checkout->total_dibayar, 0, ',', '.') }}</strong> /
                                                <span class="text-muted">Rp {{ number_format($checkout->total_bayar, 0, ',', '.') }}</span>
                                            </span>
                                        </div>
                                        <div class="progress mt-1" style="height: 7px;">
                                            <div class="progress-bar bg-success" role="progressbar"
                                                style="width: {{ $checkout->persen_dibayar }}%;"
                                                aria-valuenow="{{ $checkout->persen_dibayar }}"
                                                aria-valuemin="0"
                                                aria-valuemax="100">
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    @if($checkout->status == 'fee')
                                        <span class="badge badge-warning">DP</span>
                                        @if($checkout->pembayaran->where('status', 'pending')->count() > 0)
                                            <span class="badge badge-info">Menunggu Verifikasi</span>
                                        @endif
                                    @elseif($checkout->status == 'lunas')
                                        <span class="badge badge-success">Lunas</span>
                                    @elseif($checkout->status == 'batal')
                                        <span class="badge badge-danger">Batal</span>
                                    @else
                                        <span class="badge badge-secondary">{{ $checkout->status }}</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="btn-group">
                                        <a href="{{ route('petugas_pembayaran.pembayaran.show', $checkout->id) }}" class="btn btn-sm btn-info" title="Lihat Detail">
                                            <i class="fas fa-eye"></i>
                                        </a>

                                        @if($checkout->status == 'fee' && $checkout->pembayaran->where('status', 'pending')->count() > 0)
                                            <button type="button" class="btn btn-sm btn-success"
                                                    data-toggle="modal"
                                                    data-target="#verifikasiModal{{ $checkout->id }}"
                                                    title="Verifikasi Pembayaran">
                                                <i class="fas fa-check"></i>
                                            </button>

                                            <button type="button" class="btn btn-sm btn-danger"
                                                    data-toggle="modal"
                                                    data-target="#tolakModal{{ $checkout->id }}"
                                                    title="Tolak Pembayaran">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>

                            <!-- Modal Verifikasi -->
                            @if($checkout->status == 'fee' && $checkout->pembayaran->where('status', 'pending')->count() > 0)
                                <div class="modal fade" id="verifikasiModal{{ $checkout->id }}" tabindex="-1" role="dialog" aria-labelledby="verifikasiModalLabel{{ $checkout->id }}" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header bg-success">
                                                <h5 class="modal-title" id="verifikasiModalLabel{{ $checkout->id }}">Verifikasi Pembayaran #{{ $checkout->id }}</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <form action="{{ route('petugas_pembayaran.pembayaran.verifikasi', $checkout->pembayaran->where('status', 'pending')->first()->id) }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="checkout_id" value="{{ $checkout->id }}">
                                                <div class="modal-body">
                                                    <div class="alert alert-info">
                                                        <h6><i class="icon fas fa-info"></i> Informasi Pembayaran</h6>
                                                        <p>Anda akan memverifikasi pelunasan pembayaran untuk booking #{{ $checkout->id }}.</p>
                                                        <ul class="mb-0">
                                                            <li>Total: Rp {{ number_format($checkout->total_bayar, 0, ',', '.') }}</li>
                                                            <li>Sudah Dibayar: Rp {{ number_format($checkout->total_dibayar, 0, ',', '.') }}</li>
                                                            <li>Sisa Pembayaran: Rp {{ number_format($checkout->sisa_pembayaran, 0, ',', '.') }}</li>
                                                        </ul>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="catatan{{ $checkout->id }}">Catatan (opsional):</label>
                                                        <textarea class="form-control" id="catatan{{ $checkout->id }}" name="catatan" rows="3" placeholder="Tambahkan catatan jika diperlukan"></textarea>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                                    <button type="submit" class="btn btn-success">Verifikasi Pembayaran</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                                <!-- Modal Tolak -->
                                <div class="modal fade" id="tolakModal{{ $checkout->id }}" tabindex="-1" role="dialog" aria-labelledby="tolakModalLabel{{ $checkout->id }}" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header bg-danger">
                                                <h5 class="modal-title" id="tolakModalLabel{{ $checkout->id }}">Tolak Pembayaran #{{ $checkout->id }}</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <form action="{{ route('petugas_pembayaran.pembayaran.tolak', $checkout->pembayaran->where('status', 'pending')->first()->id) }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="checkout_id" value="{{ $checkout->id }}">
                                                <div class="modal-body">
                                                    <div class="alert alert-warning">
                                                        <h6><i class="icon fas fa-exclamation-triangle"></i> Perhatian!</h6>
                                                        <p>Anda akan menolak pembayaran untuk booking #{{ $checkout->id }}.</p>
                                                        <p>Status booking akan tetap "DP" dan user harus melakukan pembayaran ulang.</p>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="alasan_tolak{{ $checkout->id }}">Alasan Penolakan: <span class="text-danger">*</span></label>
                                                        <textarea class="form-control" id="alasan_tolak{{ $checkout->id }}" name="alasan_tolak" rows="3" required placeholder="Masukkan alasan penolakan pembayaran"></textarea>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                                    <button type="submit" class="btn btn-danger">Tolak Pembayaran</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-3">
                                    <div class="alert alert-info mb-0">
                                        <i class="fas fa-info-circle mr-2"></i> Tidak ada data pembayaran yang ditemukan
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="card-footer clearfix">
                <div class="float-right">
                    {{ $checkouts->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
    </div>
</section>

<!-- JavaScript for handling dynamic actions -->
<script>
    $(function() {
        // Initialize Select2 elements if available
        if ($.fn.select2) {
            $('.select2').select2()
        }

        // Auto-submit form when select elements change
        $('select[name="status"], select[name="filter"]').change(function() {
            $(this).closest('form').submit();
        });

        // Add loading state to buttons
        $('.btn-success, .btn-danger').click(function() {
            let btn = $(this);
            setTimeout(function() {
                btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i>');
            }, 50);
        });
    });
</script>
@endsection
