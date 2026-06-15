@extends('layouts.landing')

@section('content')
    {{-- Header Section --}}
    <div class="content-header bg-black shadow-sm" style="padding-top: 110px; padding-bottom: 10px;">
        <div class="container bg-white p-4 rounded-lg shadow-sm">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h1 class="font-weight-bold text-dark" style="font-size: 1.8rem;">
                        <i class="fas fa-calendar-check text-danger mr-2"></i> Detail Booking
                    </h1>
                    <p class="text-muted mb-0">
                        Informasi lengkap transaksi booking Anda
                    </p>
                </div>
                <div class="col-md-6 text-md-right mt-3 mt-md-0">
                    <a href="{{ route('user.riwayat') }}" class="btn btn-outline-secondary rounded-pill px-4">
                        <i class="fas fa-arrow-left mr-2"></i> Kembali
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- Content --}}
    <div class="content bg-light border-top" style="min-height: 80vh; padding-top: 20px;">
        <div class="container py-4">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    {{-- Status Badge --}}
                    <div class="text-center mb-4">
                        @php
                            $badges = [
                                'pending' => 'warning',
                                'lunas' => 'success',
                                'batal' => 'danger'
                            ];
                            $badgeClass = $badges[$checkout->status] ?? 'secondary';
                        @endphp
                        <span class="badge badge-{{ $badgeClass }} px-5 py-3 rounded-pill text-uppercase" style="font-size: 1.2rem;">
                            {{ $checkout->status }}
                        </span>
                    </div>

                    {{-- Detail Card --}}
                    <div class="card border-0 shadow-lg rounded-lg">
                        <div class="card-header bg-gradient-danger text-white py-3">
                            <h5 class="mb-0 font-weight-bold">
                                <i class="fas fa-info-circle mr-2"></i> Informasi Booking
                            </h5>
                        </div>
                        <div class="card-body p-4">
                            <table class="table table-borderless">
                                <tbody>
                                    <tr>
                                        <td class="label-detail">ID Booking</td>
                                        <td class="value-detail font-weight-bold text-danger">
                                            BK-{{ str_pad($checkout->id, 5, '0', STR_PAD_LEFT) }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="label-detail">Fasilitas</td>
                                        <td class="value-detail">
                                            <i class="{{ $checkout->jadwalUtama->fasilitas->icon }} mr-2"></i>
                                            {{ $checkout->jadwalUtama->fasilitas->nama_fasilitas }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="label-detail">Tanggal Main</td>
                                        <td class="value-detail">
                                            <i class="far fa-calendar-alt text-danger mr-2"></i>
                                            {{ \Carbon\Carbon::parse($checkout->jadwalUtama->tanggal)->translatedFormat('l, d F Y') }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="label-detail">Jam Sewa</td>
                                        <td class="value-detail">
                                            @foreach($checkout->jadwals as $jadwal)
                                                <span class="badge badge-light border mr-1 mb-1">
                                                    <i class="far fa-clock mr-1"></i>
                                                    {{ substr($jadwal->jam_mulai, 0, 5) }} - {{ substr($jadwal->jam_selesai, 0, 5) }}
                                                </span>
                                            @endforeach
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="label-detail">Total Bayar</td>
                                        <td class="value-detail">
                                            <span class="text-success font-weight-bold" style="font-size: 1.3rem;">
                                                Rp {{ number_format($checkout->total_bayar, 0, ',', '.') }}
                                            </span>
                                        </td>
                                    </tr>
                                    @if($checkout->status == 'lunas')
                                    <tr>
                                        <td class="label-detail">Sudah Dibayar</td>
                                        <td class="value-detail">
                                            <span class="text-success font-weight-bold">
                                                Rp {{ number_format($checkout->pemasukans->where('status', 'lunas')->sum('jumlah_bayar'), 0, ',', '.') }}
                                            </span>
                                        </td>
                                    </tr>
                                    @endif
                                    @if($checkout->status == 'pending' && $checkout->sisa_tagihan > 0)
                                    <tr>
                                        <td class="label-detail">Sisa Tagihan</td>
                                        <td class="value-detail">
                                            <span class="text-warning font-weight-bold">
                                                Rp {{ number_format($checkout->sisa_tagihan, 0, ',', '.') }}
                                            </span>
                                        </td>
                                    </tr>
                                    @endif
                                    <tr>
                                        <td class="label-detail">Waktu Transaksi</td>
                                        <td class="value-detail text-muted">
                                            <i class="far fa-clock mr-1"></i>
                                            {{ $checkout->created_at->translatedFormat('d F Y, H:i') }} WIB
                                        </td>
                                    </tr>
                                </tbody>
                            </table>

                            {{-- Riwayat Pembayaran --}}
                            @if($checkout->pemasukans->count() > 0)
                            <hr class="my-4">
                            <h6 class="font-weight-bold mb-3">
                                <i class="fas fa-money-bill-wave text-success mr-2"></i> Riwayat Pembayaran
                            </h6>
                            <div class="table-responsive">
                                <table class="table table-sm table-hover">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>Tanggal</th>
                                            <th>Jumlah</th>
                                            <th>Metode</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($checkout->pemasukans as $pemasukan)
                                        <tr>
                                            <td>{{ $pemasukan->created_at->translatedFormat('d/m/Y H:i') }}</td>
                                            <td class="font-weight-bold">Rp {{ number_format($pemasukan->jumlah_bayar, 0, ',', '.') }}</td>
                                            <td>{{ $pemasukan->metode_pembayaran ?? '-' }}</td>
                                            <td>
                                                <span class="badge badge-{{ $pemasukan->status == 'lunas' ? 'success' : 'warning' }}">
                                                    {{ $pemasukan->status }}
                                                </span>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('css')
    <style>
        .label-detail {
            font-weight: 600;
            color: #6c757d;
            width: 35%;
            padding: 12px 8px;
        }
        .value-detail {
            font-weight: 500;
            color: #343a40;
            padding: 12px 8px;
        }
        .bg-gradient-danger {
            background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
        }
    </style>
@endpush
