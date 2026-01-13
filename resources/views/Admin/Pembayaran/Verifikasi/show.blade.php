@extends('Admin.component')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Detail Verifikasi Pelunasan</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.keuangan.verifikasi.index') }}">Verifikasi</a></li>
                    <li class="breadcrumb-item active">Detail</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            {{-- KOLOM KIRI: INFO TRANSAKSI --}}
            <div class="col-md-7">
                <div class="card card-primary card-outline">
                    <div class="card-header">
                        <h3 class="card-title">Informasi Booking</h3>
                    </div>
                    <div class="card-body">
                        <table class="table table-borderless">
                            <tr>
                                <th style="width: 35%">ID Booking</th>
                                {{-- FIX: Use ID if kode_transaksi is null --}}
                                <td>: <span class="badge badge-light border" style="font-size: 1rem">#{{ $checkout->kode_transaksi ?? $checkout->id }}</span></td>
                            </tr>
                            <tr>
                                <th>Penyewa</th>
                                <td>: {{ $checkout->user->name ?? 'User Hilang' }} <br> <small class="text-muted">{{ $checkout->user->email ?? '-' }}</small></td>
                            </tr>
                            <tr>
                                <th>Fasilitas</th>
                                <td>: <b>{{ $checkout->jadwals->first()->fasilitas->nama_fasilitas ?? '-' }}</b></td>
                            </tr>
                            <tr>
                                <th>Tanggal Booking</th>
                                <td>: {{ $checkout->created_at->format('d F Y, H:i') }}</td>
                            </tr>
                            <tr>
                                <th>Status</th>
                                <td>:
                                    @if($checkout->status == 'pending')
                                        <span class="badge badge-warning">Menunggu Verifikasi Pelunasan</span>
                                    @elseif($checkout->status == 'lunas')
                                        <span class="badge badge-success">Lunas</span>
                                    @else
                                        <span class="badge badge-secondary">{{ ucfirst($checkout->status) }}</span>
                                    @endif
                                </td>
                            </tr>
                        </table>

                        <hr>
                        <h5>Rincian Jadwal:</h5>
                        <table class="table table-sm table-bordered mt-2">
                            <thead class="thead-light">
                                <tr>
                                    <th>Tanggal Main</th>
                                    <th>Jam</th>
                                    <th class="text-right">Harga</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($checkout->jadwals as $jadwal)
                                <tr>
                                    <td>{{ \Carbon\Carbon::parse($jadwal->tanggal)->translatedFormat('d F Y') }}</td>
                                    <td>{{ substr($jadwal->jam_mulai, 0, 5) }} - {{ substr($jadwal->jam_selesai, 0, 5) }}</td>
                                    <td class="text-right">Rp {{ number_format($jadwal->fasilitas->harga_sewa ?? 0, 0, ',', '.') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="2" class="text-right">Total Tagihan:</th>
                                    {{-- FIX: Use total_bayar directly from checkout model --}}
                                    <th class="text-right bg-light">Rp {{ number_format($checkout->total_bayar, 0, ',', '.') }}</th>
                                </tr>
                                <tr>
                                    <th colspan="2" class="text-right text-success">Sudah Dibayar (DP):</th>
                                    <th class="text-right text-success">
                                        {{-- Calculate DP (50%) --}}
                                        Rp {{ number_format($checkout->total_bayar * 0.5, 0, ',', '.') }}
                                    </th>
                                </tr>
                                <tr style="font-size: 1.1rem">
                                    <th colspan="2" class="text-right">Sisa Pelunasan:</th>
                                    <th class="text-right bg-warning">
                                        Rp {{ number_format($checkout->total_bayar * 0.5, 0, ',', '.') }}
                                    </th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

            {{-- KOLOM KANAN: BUKTI BAYAR & AKSI --}}
            <div class="col-md-5">
                <div class="card card-warning card-outline">
                    <div class="card-header">
                        <h3 class="card-title">Bukti Transfer Pelunasan</h3>
                    </div>
                    <div class="card-body text-center">
                        {{-- FIX: Use the variable passed from controller --}}
                        @if($buktiBayar)
                            <a href="{{ asset('storage/' . $buktiBayar) }}" target="_blank">
                                <img src="{{ asset('storage/' . $buktiBayar) }}" class="img-fluid rounded border shadow-sm" alt="Bukti Bayar" style="max-height: 400px;">
                            </a>
                            <p class="text-muted mt-2 small"><i class="fas fa-search-plus"></i> Klik gambar untuk memperbesar</p>
                        @else
                            <div class="alert alert-secondary py-4">
                                <i class="fas fa-file-image fa-3x mb-3 text-muted"></i><br>
                                <h5>Belum Ada Bukti</h5>
                                <p class="mb-0 small">User belum mengunggah bukti pelunasan terbaru.</p>
                            </div>
                        @endif

                        <hr>

                        {{-- TOMBOL AKSI --}}
                        @if($checkout->status == 'pending')
                            <div class="btn-group w-100">
                                <button type="button" class="btn btn-success btn-lg" data-toggle="modal" data-target="#modalApprove" {{ !$buktiBayar ? 'disabled' : '' }}>
                                    <i class="fas fa-check-circle mr-1"></i> VALIDASI LUNAS
                                </button>
                                <button type="button" class="btn btn-danger btn-lg" data-toggle="modal" data-target="#modalReject" {{ !$buktiBayar ? 'disabled' : '' }}>
                                    <i class="fas fa-times-circle mr-1"></i> TOLAK
                                </button>
                            </div>
                            @if(!$buktiBayar)
                                <p class="text-danger small mt-2">*Tombol dinonaktifkan karena bukti belum ada.</p>
                            @endif
                        @endif

                        @if($checkout->status == 'lunas')
                            <div class="alert alert-success">
                                <i class="fas fa-check-double"></i> Transaksi Lunas & Terverifikasi.
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- MODAL APPROVE --}}
<div class="modal fade" id="modalApprove" tabindex="-1">
    <div class="modal-dialog">
        <form action="{{ route('admin.keuangan.verifikasi.confirm', $checkout->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title">Validasi Pelunasan</h5>
                    <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <p>Konfirmasi bahwa dana pelunasan sebesar <b>Rp {{ number_format($checkout->total_bayar * 0.5, 0, ',', '.') }}</b> telah diterima?</p>
                    <div class="form-group">
                        <label>Catatan Admin (Opsional)</label>
                        <textarea name="catatan" class="form-control" rows="2" placeholder="Contoh: Dana masuk ke BCA"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">Ya, Validasi</button>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- MODAL REJECT --}}
<div class="modal fade" id="modalReject" tabindex="-1">
    <div class="modal-dialog">
        <form action="{{ route('admin.keuangan.verifikasi.reject', $checkout->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title">Tolak Bukti Bayar</h5>
                    <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <p>Bukti pembayaran ini akan ditolak. User harus mengunggah ulang.</p>
                    <div class="form-group">
                        <label>Alasan Penolakan <span class="text-danger">*</span></label>
                        <textarea name="alasan_tolak" class="form-control" rows="3" required placeholder="Contoh: Foto buram, nominal kurang..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">Tolak</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
