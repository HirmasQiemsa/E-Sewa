@extends('PetugasPembayaran.component')
@section('title', 'Detail Pembayaran')
@section('content')
<!-- Content Header -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Detail Pembayaran #{{ $checkout->id }}</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('petugas_pembayaran.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('petugas_pembayaran.pembayaran.index') }}">Pembayaran</a></li>
                    <li class="breadcrumb-item active">Detail #{{ $checkout->id }}</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <!-- Informasi Booking -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-primary">
                        <h3 class="card-title">
                            <i class="fas fa-info-circle mr-1"></i>
                            Informasi Booking
                        </h3>
                        <div class="card-tools">
                            @if($checkout->status == 'fee')
                                <span class="badge badge-warning px-2 py-1" style="font-size: 0.9rem;">DP</span>
                            @elseif($checkout->status == 'lunas')
                                <span class="badge badge-success px-2 py-1" style="font-size: 0.9rem;">LUNAS</span>
                            @elseif($checkout->status == 'batal')
                                <span class="badge badge-danger px-2 py-1" style="font-size: 0.9rem;">BATAL</span>
                            @else
                                <span class="badge badge-secondary px-2 py-1" style="font-size: 0.9rem;">{{ $checkout->status }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="card-body">
                        <dl class="row">
                            <dt class="col-sm-4">ID Booking</dt>
                            <dd class="col-sm-8">#{{ $checkout->id }}</dd>

                            <dt class="col-sm-4">Nama Pemesan</dt>
                            <dd class="col-sm-8">{{ $checkout->user->name ?? 'Tidak ditemukan' }}</dd>

                            <dt class="col-sm-4">Kontak</dt>
                            <dd class="col-sm-8">
                                {{ $checkout->user->no_hp ?? '-' }}<br>
                                <small class="text-muted">{{ $checkout->user->email ?? '-' }}</small>
                            </dd>

                            <dt class="col-sm-4">Fasilitas</dt>
                            <dd class="col-sm-8">
                                @if($checkout->jadwals->isNotEmpty() && $checkout->jadwals->first()->fasilitas)
                                    @php $fasilitas = $checkout->jadwals->first()->fasilitas; @endphp
                                    {{ $fasilitas->nama_display ?? $fasilitas->nama_fasilitas }}
                                    @if($fasilitas->deleted_at)
                                        <span class="badge badge-secondary">Tidak aktif</span>
                                    @endif
                                    <br>
                                    <small class="text-muted">{{ $fasilitas->lokasi }}</small>
                                @else
                                    <span class="text-muted">Fasilitas tidak ditemukan</span>
                                @endif
                            </dd>

                            <dt class="col-sm-4">Total Durasi</dt>
                            <dd class="col-sm-8">{{ $checkout->total_durasi }} jam</dd>

                            <dt class="col-sm-4">Tanggal Booking</dt>
                            <dd class="col-sm-8">
                                @if($checkout->jadwals->isNotEmpty())
                                    {{ \Carbon\Carbon::parse($checkout->jadwals->first()->tanggal)->format('d F Y') }}
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </dd>

                            <dt class="col-sm-4">Waktu Booking</dt>
                            <dd class="col-sm-8">
                                @php
                                    $waktuBooking = new DateTime($checkout->created_at);
                                    $waktuBooking->setTimezone(new DateTimeZone('Asia/Jakarta'));
                                    echo $waktuBooking->format('d M Y H:i');
                                @endphp
                            </dd>
                        </dl>

                        <div class="alert alert-info mt-3">
                            <h5><i class="icon fas fa-coins"></i> Informasi Pembayaran</h5>
                            <div class="mb-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span>Progress Pembayaran: {{ $checkout->persen_dibayar }}%</span>
                                    <span>
                                        <strong>Rp {{ number_format($checkout->total_dibayar, 0, ',', '.') }}</strong> /
                                        <span class="text-muted">Rp {{ number_format($checkout->total_bayar, 0, ',', '.') }}</span>
                                    </span>
                                </div>
                                <div class="progress mt-2" style="height: 10px;">
                                    <div class="progress-bar bg-success" role="progressbar"
                                        style="width: {{ $checkout->persen_dibayar }}%;"
                                        aria-valuenow="{{ $checkout->persen_dibayar }}"
                                        aria-valuemin="0"
                                        aria-valuemax="100">
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-sm-12">
                                    <p class="mb-1">Sisa Pembayaran:</p>
                                    <h4>
                                    @if($checkout->status == 'lunas')
                                        <span class="badge badge-success px-3 py-2" style="font-size: 1rem;">LUNAS</span>
                                    @elseif($checkout->pembayaran->where('status', 'ditolak')->count() > 0 && $checkout->sisa_pembayaran <= 0)
                                        <span class="text-warning">Pembayaran Ditolak</span>
                                    @elseif($checkout->status == 'batal')
                                        <span class="text-secondary">-</span>
                                    @else
                                        <span class="text-danger">Rp {{ number_format($checkout->sisa_pembayaran, 0, ',', '.') }}</span>
                                    @endif
                                    </h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Jadwal Booking -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-success">
                        <h3 class="card-title">
                            <i class="fas fa-calendar-alt mr-1"></i>
                            Jadwal Booking
                        </h3>
                        <div class="card-tools">
                            <span class="badge badge-light">{{ $checkout->jadwals->count() }} slot waktu</span>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Jam</th>
                                    <th>Durasi</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($checkout->jadwals as $jadwal)
                                    <tr>
                                        <td>{{ \Carbon\Carbon::parse($jadwal->tanggal)->format('d M Y') }}</td>
                                        <td>{{ substr($jadwal->jam_mulai, 0, 5) }} - {{ substr($jadwal->jam_selesai, 0, 5) }}</td>
                                        <td>
                                            @php
                                                $mulaiParts = explode(':', $jadwal->jam_mulai);
                                                $selesaiParts = explode(':', $jadwal->jam_selesai);
                                                $mulaiJam = (int)$mulaiParts[0];
                                                $selesaiJam = (int)$selesaiParts[0];
                                                $durasi = $selesaiJam - $mulaiJam;
                                            @endphp
                                            {{ $durasi }} jam
                                        </td>
                                        <td>
                                            @if($jadwal->deleted_at)
                                                <span class="badge badge-secondary">Dihapus</span>
                                            @elseif($jadwal->status == 'tersedia')
                                                <span class="badge badge-success">Tersedia</span>
                                            @elseif($jadwal->status == 'terbooking')
                                                <span class="badge badge-warning">Terbooking</span>
                                            @elseif($jadwal->status == 'selesai')
                                                <span class="badge badge-info">Selesai</span>
                                            @elseif($jadwal->status == 'batal')
                                                <span class="badge badge-danger">Batal</span>
                                            @else
                                                <span class="badge badge-secondary">{{ $jadwal->status }}</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center">Tidak ada jadwal terkait</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Riwayat Pembayaran -->
                <div class="card mt-4">
                    <div class="card-header bg-info">
                        <h3 class="card-title">
                            <i class="fas fa-history mr-1"></i>
                            Riwayat Pembayaran
                        </h3>
                    </div>
                    <div class="card-body p-0">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Jumlah</th>
                                    <th>Metode</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    // Use tanggal_bayar and sort by it for correct chronological order
                                    $sortedPayments = $checkout->pembayaran->sortBy('tanggal_bayar');
                                @endphp
                                @forelse($sortedPayments as $bayar)
                                    <tr>
                                        <td>
                                            @php
                                                // Konversi waktu UTC ke timezone Asia/Jakarta (WIB)
                                                $waktuBayar = new DateTime($bayar->tanggal_bayar);
                                                $waktuBayar->setTimezone(new DateTimeZone('Asia/Jakarta'));
                                                echo $waktuBayar->format('d/m/Y H:i');
                                            @endphp
                                        </td>
                                        <td>Rp {{ number_format($bayar->jumlah_bayar, 0, ',', '.') }}</td>
                                        <td>{{ ucfirst($bayar->metode_pembayaran) }}</td>
                                        <td>
                                            @if($bayar->status == 'fee')
                                                <span class="badge badge-warning px-2">DP</span>
                                            @elseif($bayar->status == 'lunas')
                                                <span class="badge badge-success px-2">Lunas</span>
                                            @elseif($bayar->status == 'pending')
                                                <span class="badge badge-primary px-2">Menunggu Verifikasi</span>
                                            @elseif($bayar->status == 'ditolak')
                                                <span class="badge badge-danger px-2">Ditolak</span>
                                                @if($bayar->keterangan)
                                                    <br><small class="text-muted">{{ $bayar->keterangan }}</small>
                                                @endif
                                            @else
                                                <span class="badge badge-secondary px-2">{{ $bayar->status }}</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center">Tidak ada riwayat pembayaran</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('petugas_pembayaran.pembayaran.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left mr-1"></i> Kembali
                            </a>

                            @php
                                // Find the pending payment if exists
                                $pendingPayment = $checkout->pembayaran->where('status', 'pending')->first();
                            @endphp

                            @if($checkout->status == 'fee' && $pendingPayment)
                                <div>
                                    <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#tolakModalDetail">
                                        <i class="fas fa-times mr-1"></i> Tolak Pembayaran
                                    </button>
                                    <button type="button" class="btn btn-success ml-2" data-toggle="modal" data-target="#verifikasiModalDetail">
                                        <i class="fas fa-check mr-1"></i> Verifikasi Pembayaran
                                    </button>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Modal Verifikasi -->
@if($checkout->status == 'fee' && $checkout->pembayaran->where('status', 'pending')->count() > 0)
    @php
        $pendingPayment = $checkout->pembayaran->where('status', 'pending')->first();
    @endphp
    <div class="modal fade" id="verifikasiModalDetail" tabindex="-1" role="dialog" aria-labelledby="verifikasiModalDetailLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-success">
                    <h5 class="modal-title" id="verifikasiModalDetailLabel">Verifikasi Pembayaran #{{ $checkout->id }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('petugas_pembayaran.pembayaran.verifikasi', $pendingPayment->id) }}" method="POST">
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
                            <label for="catatanDetail">Catatan (opsional):</label>
                            <textarea class="form-control" id="catatanDetail" name="catatan" rows="3" placeholder="Tambahkan catatan jika diperlukan"></textarea>
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
    <div class="modal fade" id="tolakModalDetail" tabindex="-1" role="dialog" aria-labelledby="tolakModalDetailLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-danger">
                    <h5 class="modal-title" id="tolakModalDetailLabel">Tolak Pembayaran #{{ $checkout->id }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('petugas_pembayaran.pembayaran.tolak', $pendingPayment->id) }}" method="POST">
                    @csrf
                    <input type="hidden" name="checkout_id" value="{{ $checkout->id }}">
                    <div class="modal-body">
                        <div class="alert alert-warning">
                            <h6><i class="icon fas fa-exclamation-triangle"></i> Perhatian!</h6>
                            <p>Anda akan menolak pembayaran untuk booking #{{ $checkout->id }}.</p>
                            <p>Status booking akan tetap "DP" dan user harus melakukan pembayaran ulang.</p>
                        </div>
                        <div class="form-group">
                            <label for="alasan_tolakDetail">Alasan Penolakan: <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="alasan_tolakDetail" name="alasan_tolak" rows="3" required placeholder="Masukkan alasan penolakan pembayaran"></textarea>
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

<script>
    $(function() {
        // Add loading state to form submission
        $('form').submit(function() {
            $(this).find('button[type="submit"]').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Memproses...');
        });

        // Initialize tooltips
        $('[data-toggle="tooltip"]').tooltip();
    });
</script>
@endsection
