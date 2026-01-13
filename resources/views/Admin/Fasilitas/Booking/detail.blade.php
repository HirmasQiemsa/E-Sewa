@extends('Admin.component')

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Detail Booking <span class="text-muted">#{{ $booking->id }}</span></h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.fasilitas.booking.index') }}">Booking</a></li>
                        <li class="breadcrumb-item active">Detail</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            {{-- Alert Notifikasi --}}
            @if (session('success'))
                <div class="alert alert-success border-0 shadow-sm"><i class="fas fa-check-circle mr-2"></i>
                    {{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger border-0 shadow-sm"><i class="fas fa-exclamation-circle mr-2"></i>
                    {{ session('error') }}</div>
            @endif
{{-- LOGIKA STATUS CERDAS --}}
            @php
                // Hitung ulang status berdasarkan uang masuk
                $uangValid = $booking->pemasukans->where('status', 'lunas')->sum('jumlah_bayar');
                $uangPending = $booking->pemasukans->where('status', 'pending')->sum('jumlah_bayar');
                $sisaTagihan = $booking->total_bayar - ($uangValid + $uangPending);

                // Tentukan tampilan status
                if ($sisaTagihan <= 0 && $uangPending == 0 && $uangValid >= $booking->total_bayar) {
                    // Kasus 1: Sudah lunas valid semua
                    $statusTampil = 'LUNAS';
                    $classCallout = 'callout-success';
                } elseif ($sisaTagihan <= 0 && $uangPending > 0) {
                    // Kasus 2: Uang sudah masuk (valid + pending) menutupi tagihan, tapi butuh verifikasi
                    $statusTampil = 'MENUNGGU VERIFIKASI PELUNASAN';
                    $classCallout = 'callout-info';
                } elseif ($uangValid > 0 || $uangPending > 0) {
                    // Kasus 3: Masih ada sisa tagihan (DP / Cicilan)
                    $statusTampil = 'BELUM LUNAS (DP)';
                    $classCallout = 'callout-warning';
                } elseif ($booking->status == 'batal') {
                    $statusTampil = 'BATAL';
                    $classCallout = 'callout-danger';
                } else {
                    // Fallback ke status database
                    $statusTampil = strtoupper($booking->status);
                    $classCallout = 'callout-secondary';
                }
            @endphp

            {{-- STATUS BANNER UPDATE --}}
            <div class="callout {{ $classCallout }} shadow-sm mb-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="font-weight-bold mb-1">Status: {{ $statusTampil }}</h5>
                        <p class="mb-0 text-muted">Dibuat pada: {{ \Carbon\Carbon::parse($booking->created_at)->translatedFormat('d F Y, H:i') }} WIB</p>
                    </div>
                    {{-- Tombol Ubah Status Cepat --}}
                    {{-- <button class="btn btn-tool btn-lg text-dark" data-toggle="modal" data-target="#statusModal" title="Ubah Status Manual">
                        <i class="fas fa-edit"></i> Ubah Status
                    </button> --}}
                </div>
            </div>

            <div class="row">
                {{-- KOLOM KIRI: DATA UTAMA --}}
                <div class="col-md-8">
                    {{-- 1. Card Rincian Fasilitas & Jadwal --}}
                    <div class="card card-outline card-primary">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fas fa-calendar-alt mr-2"></i> Rincian Jadwal Sewa</h3>
                        </div>
                        <div class="card-body p-0">
                            <table class="table table-striped">
                                <thead class="bg-light">
                                    <tr>
                                        <th>Tanggal</th>
                                        <th>Fasilitas</th>
                                        <th>Jam Main</th>
                                        <th>Durasi</th>
                                        <th>Harga</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($booking->jadwals as $jadwal)
                                        <tr>
                                            <td class="align-middle font-weight-bold text-primary">
                                                {{ \Carbon\Carbon::parse($jadwal->tanggal)->translatedFormat('d M Y') }}
                                            </td>
                                            <td class="align-middle">
                                                {{ $jadwal->fasilitas->nama_fasilitas ?? 'Terhapus' }}
                                                <br><small class="text-muted"><i class="fas fa-map-marker-alt"></i>
                                                    {{ $jadwal->fasilitas->lokasi ?? '-' }}</small>
                                            </td>
                                            <td class="align-middle">
                                                <span class="badge badge-light border">
                                                    {{ substr($jadwal->jam_mulai, 0, 5) }} -
                                                    {{ substr($jadwal->jam_selesai, 0, 5) }}
                                                </span>
                                            </td>
                                            <td class="align-middle">
                                                @php
                                                    $start = \Carbon\Carbon::parse($jadwal->jam_mulai);
                                                    $end = \Carbon\Carbon::parse($jadwal->jam_selesai);
                                                    echo $start->diffInHours($end) . ' Jam';
                                                @endphp
                                            </td>
                                            <td class="align-middle">Rp
                                                {{ number_format($jadwal->fasilitas->harga_sewa ?? 0, 0, ',', '.') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- 2. Card Data Penyewa --}}
                    <div class="card">
                        <div class="card-header border-0">
                            <h3 class="card-title"><i class="fas fa-user mr-2"></i> Data Penyewa</h3>
                        </div>
                        <div class="card-body pt-0">
                            <div class="row">
                                <div class="col-md-2 text-center">
                                    <img src="{{ asset('img/default-user.png') }}" class="img-circle elevation-1"
                                        style="width: 60px; height: 60px;">
                                </div>
                                <div class="col-md-5">
                                    <dl>
                                        <dt>Nama Lengkap</dt>
                                        <dd>{{ $booking->user->name ?? 'User Terhapus' }}</dd>
                                        <dt>Email</dt>
                                        <dd>{{ $booking->user->email ?? '-' }}</dd>
                                    </dl>
                                </div>
                                <div class="col-md-5">
                                    <dl>
                                        <dt>Nomor WhatsApp</dt>
                                        <dd>
                                            @if ($booking->user && $booking->user->no_hp)
                                                <a href="https://wa.me/{{ preg_replace('/^0/', '62', $booking->user->no_hp) }}"
                                                    target="_blank" class="text-success font-weight-bold">
                                                    <i class="fab fa-whatsapp"></i> {{ $booking->user->no_hp }}
                                                </a>
                                            @else
                                                -
                                            @endif
                                        </dd>
                                        <dt>Alamat</dt>
                                        <dd>{{ $booking->user->alamat ?? '-' }}</dd>
                                    </dl>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- KOLOM KANAN: KEUANGAN --}}
                <div class="col-md-4">
                    {{-- 1. Card Ringkasan Biaya (LOGIKA DIPERBAIKI) --}}
                    <div class="card card-outline card-success">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fas fa-file-invoice-dollar mr-2"></i> Tagihan</h3>
                        </div>
                        <div class="card-body p-3">
                            <div class="d-flex justify-content-between mb-2">
                                <span>Total Tagihan:</span>
                                <span class="font-weight-bold" style="font-size: 1.2rem">Rp
                                    {{ number_format($booking->total_bayar, 0, ',', '.') }}</span>
                            </div>

                            @php
                                // Uang Masuk Valid (Sudah di-ACC Admin)
                                $uangValid = $booking->pemasukans->where('status', 'lunas')->sum('jumlah_bayar');

                                // Uang Pending (Sudah Transfer, Belum di-ACC)
                                // DP pun kalau baru upload harusnya masuk sini dulu kalau sistem butuh verifikasi manual
                                $uangPending = $booking->pemasukans->where('status', 'pending')->sum('jumlah_bayar');

                                // Total yang sudah dibayarkan user (Valid + Pending)
                                $totalMasukUser = $uangValid + $uangPending;

                                // Sisa Tagihan Real (Berdasarkan uang yang sudah masuk dr user)
                                $sisaTagihan = $booking->total_bayar - $totalMasukUser;
                            @endphp

                            <div class="d-flex justify-content-between text-success mb-1">
                                <span>Sudah Masuk (Valid):</span>
                                <span class="font-weight-bold">Rp {{ number_format($uangValid, 0, ',', '.') }}</span>
                            </div>

                            @if ($uangPending > 0)
                                <div class="d-flex justify-content-between text-warning mb-1">
                                    <span>Menunggu Verifikasi:</span>
                                    <span class="font-weight-bold">Rp {{ number_format($uangPending, 0, ',', '.') }}</span>
                                </div>
                            @endif

                            <div class="dropdown-divider"></div>

                            <div
                                class="d-flex justify-content-between {{ $sisaTagihan <= 0 ? 'text-info' : 'text-danger' }}">
                                <span>Sisa Kekurangan:</span>
                                <span class="font-weight-bold">Rp
                                    {{ number_format(max(0, $sisaTagihan), 0, ',', '.') }}</span>
                            </div>

                            @if ($sisaTagihan <= 0 && $uangPending > 0)
                                <small class="text-info d-block text-right mt-1"><i class="fas fa-info-circle"></i> Lunas
                                    (Menunggu Konfirmasi Admin)</small>
                            @elseif($sisaTagihan <= 0)
                                <small class="text-success d-block text-right mt-1"><i class="fas fa-check-circle"></i>
                                    Lunas Sempurna</small>
                            @endif
                        </div>
                    </div>

                    {{-- 2. Card Riwayat & Bukti Bayar --}}
                    <div class="card">
                        <div class="card-header bg-dark">
                            <h3 class="card-title" style="font-size: 1rem"><i class="fas fa-history mr-1"></i> Log
                                Pembayaran</h3>
                        </div>
                        <div class="card-body p-0">
                            <table class="table table-sm table-borderless table-hover">
                                <tbody>
                                    @forelse($booking->pemasukans->sortBy('created_at') as $p)
                                        <tr class="border-bottom">
                                            <td class="pl-3 py-2">
                                                <span class="d-block font-weight-bold text-primary">Rp
                                                    {{ number_format($p->jumlah_bayar, 0, ',', '.') }}</span>
                                                <small
                                                    class="text-muted">{{ \Carbon\Carbon::parse($p->created_at)->format('d/m/y H:i') }}</small>

                                                {{-- Badge Status Pembayaran --}}
                                                <div class="mt-1">
                                                    @if ($p->status == 'lunas')
                                                        <span class="badge badge-success"
                                                            style="font-size: 0.7em">Valid</span>
                                                    @elseif($p->status == 'pending')
                                                        <span class="badge badge-warning" style="font-size: 0.7em">Cek
                                                            Admin</span>
                                                    @elseif($p->status == 'ditolak')
                                                        <span class="badge badge-danger"
                                                            style="font-size: 0.7em">Ditolak</span>
                                                    @endif
                                                    <span class="badge badge-light border"
                                                        style="font-size: 0.7em">{{ ucfirst($p->metode_pembayaran) }}</span>
                                                </div>
                                            </td>
                                            <td class="text-right pr-3 py-2 align-middle">
                                                {{-- TOMBOL LIHAT BUKTI --}}
                                                @if ($p->bukti_bayar)
                                                    <a href="{{ asset('storage/' . $p->bukti_bayar) }}" target="_blank"
                                                        class="btn btn-xs btn-outline-primary" title="Lihat Bukti">
                                                        <i class="fas fa-image"></i> Bukti
                                                    </a>
                                                @else
                                                    <span class="text-muted small font-italic">-</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="2" class="text-center py-3 text-muted small">Belum ada riwayat
                                                pembayaran.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        {{-- Link ke Verifikasi jika ada pending --}}
                        {{-- @if ($booking->pemasukans->where('status', 'pending')->count() > 0)
                            <div class="card-footer p-2">
                                <a href="{{ route('admin.keuangan.verifikasi.show', $booking->id) }}"
                                    class="btn btn-warning btn-block btn-sm">
                                    <i class="fas fa-search"></i> Verifikasi Pembayaran Pending
                                </a>
                            </div>
                        @endif --}}
                    </div>

                    {{-- 3. Aksi Cepat --}}
                    <div class="card">
                        <div class="card-body">
                            {{-- <label class="small text-muted mb-2">Aksi Lanjutan:</label>
                            @if ($booking->status != 'batal')
                                <button type="button" class="btn btn-outline-danger btn-block" data-toggle="modal"
                                    data-target="#cancelModal">
                                    <i class="fas fa-times mr-1"></i> Batalkan Booking
                                </button>
                            @endif --}}
                            <a href="{{ route('admin.fasilitas.booking.index') }}"
                                class="btn btn-primary btn-block mt-2">
                                <i class="fas fa-arrow-left mr-1"></i> Kembali
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
