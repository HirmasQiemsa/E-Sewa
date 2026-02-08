@extends('User.component')

@section('content')
<div class="container py-4">

    {{-- Tombol Kembali --}}
    <div class="mb-3">
        <a href="{{ route('user.riwayat') }}" class="btn btn-outline-secondary btn-sm">
            <i class="fas fa-arrow-left mr-1"></i> Kembali ke Riwayat
        </a>
    </div>

    <div class="row">
        {{-- ================================================= --}}
        {{-- KOLOM KIRI (DETAIL BOOKING & RIWAYAT)             --}}
        {{-- ================================================= --}}
        <div class="col-md-8">
            <div class="card shadow-sm border-0 mb-4">

                {{-- HEADER --}}
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0 font-weight-bold">Detail Booking #{{ $checkout->id }}</h5>
                </div>

                <div class="card-body">
                    <div class="row mb-4">
                        {{-- FOTO FASILITAS (Layout Blade 2) --}}
                        <div class="col-md-4 text-center">
                            <div class="d-flex justify-content-center align-items-center bg-secondary rounded overflow-hidden shadow-sm" style="height: 150px; width: 100%; position: relative;">
                                @if ($fasilitas && $fasilitas->foto)
                                    <img src="{{ asset('storage/' . $fasilitas->foto) }}" alt="Fasilitas" style="width: 100%; height: 100%; object-fit: cover;" onerror="this.style.display='none'; this.nextElementSibling.classList.remove('d-none');">
                                @endif
                                <div class="{{ $fasilitas && $fasilitas->foto ? 'd-none' : '' }} text-white-50">
                                    <i class="fas fa-camera-retro fa-3x mb-2"></i>
                                    <p class="m-0 small">No Image</p>
                                </div>
                            </div>
                        </div>

                        {{-- INFO UTAMA (Gabungan Logic Blade 1 ke Tampilan Blade 2) --}}
                        <div class="col-md-8">
                            <h4 class="font-weight-bold">{{ $fasilitas->nama_fasilitas ?? 'Data Fasilitas Tidak Ditemukan' }}</h4>
                            <p class="text-muted mb-1"><i class="fas fa-map-marker-alt mr-2"></i> {{ $fasilitas->lokasi ?? '-' }}</p>

                            @if ($jadwalPertama)
                                <p class="text-muted mb-1">
                                    <i class="fas fa-calendar-alt mr-2"></i>
                                    {{ date('d F Y', strtotime($jadwalPertama->tanggal)) }}
                                </p>
                                <p class="text-muted">
                                    <i class="fas fa-clock mr-2"></i>
                                    {{ substr($jamMulai, 0, 5) }} - {{ substr($jamSelesai, 0, 5) }} WIB
                                    <span class="badge badge-light ml-2">{{ $totalDurasi }} Jam</span>
                                </p>
                            @else
                                <p class="text-danger"><i class="fas fa-exclamation-triangle mr-2"></i> Detail jadwal tidak tersedia</p>
                            @endif
                        </div>
                    </div>

                    <hr>

                    {{-- TABEL RINCIAN BIAYA (Layout Blade 2 dengan Variable Blade 1) --}}
                    <h6 class="font-weight-bold mb-3">Rincian Biaya</h6>
                    <div class="table-responsive mb-4">
                        <table class="table table-bordered">
                            <thead class="bg-light">
                                <tr>
                                    <th>Deskripsi</th>
                                    <th class="text-right">Biaya</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{-- 1. Total Sewa --}}
                                <tr>
                                    <td>
                                        <span class="font-weight-bold">Sewa Lapangan</span>
                                        <div class="small text-muted">
                                            Harga: Rp {{ number_format($hargaPerJam, 0, ',', '.') }} x {{ $totalDurasi }} Jam
                                        </div>
                                    </td>
                                    <td class="text-right">Rp {{ number_format($totalTagihan, 0, ',', '.') }}</td>
                                </tr>

                                {{-- 2. Sudah Dibayar --}}
                                @if ($sudahBayar > 0)
                                    <tr>
                                        <td class="text-success">Sudah Dibayar (Terverifikasi)</td>
                                        <td class="text-right text-success font-weight-bold">- Rp {{ number_format($sudahBayar, 0, ',', '.') }}</td>
                                    </tr>
                                @endif

                                {{-- 3. Menunggu Verifikasi --}}
                                @if ($uangPending > 0)
                                    <tr>
                                        <td class="text-warning">Sedang Diverifikasi (Pending)</td>
                                        <td class="text-right text-warning font-weight-bold">- Rp {{ number_format($uangPending, 0, ',', '.') }}</td>
                                    </tr>
                                @endif

                                {{-- 4. Sisa Tagihan --}}
                                <tr class="table-active">
                                    <td class="font-weight-bold text-danger">Sisa Tagihan</td>
                                    <td class="text-right font-weight-bold text-danger" style="font-size: 1.1em;">
                                        Rp {{ number_format($sisaTagihan, 0, ',', '.') }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <hr>

                    {{-- RIWAYAT PEMBAYARAN (Logika Blade 1 dipindah kesini) --}}
                    <h6 class="font-weight-bold mb-3">Riwayat Pembayaran</h6>
                    @if($pembayaran->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm table-hover text-center">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Tanggal</th>
                                        <th>Jumlah</th>
                                        <th>Status</th>
                                        <th>Bukti</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($pembayaran as $p)
                                    <tr>
                                        <td class="align-middle">{{ $p->created_at->format('d/m/Y H:i') }}</td>
                                        <td class="align-middle">Rp {{ number_format($p->jumlah_bayar, 0, ',', '.') }}</td>
                                        <td class="align-middle">
                                            @if($p->status == 'lunas')
                                                <span class="badge badge-success">Terverifikasi</span>
                                            @elseif($p->status == 'pending')
                                                <span class="badge badge-warning">Pending</span>
                                            @else
                                                <span class="badge badge-secondary">{{ $p->status }}</span>
                                            @endif
                                        </td>
                                        <td class="align-middle">
                                            @if($p->bukti_bayar)
                                                <a href="{{ asset('storage/' . $p->bukti_bayar) }}" target="_blank" class="btn btn-sm btn-outline-info">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            @else
                                                -
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted small">Belum ada riwayat pembayaran.</p>
                    @endif

                </div>
            </div>
        </div>

        {{-- ================================================= --}}
        {{-- KOLOM KANAN (SIDEBAR AKSI PEMBAYARAN)             --}}
        {{-- ================================================= --}}
        <div class="col-md-4">

            {{-- KONDISI 1: STATUS BATAL --}}
            @if($checkout->status == 'batal')
                <div class="card shadow-sm border-danger mb-3">
                    <div class="card-body text-center text-danger py-4">
                        <i class="fas fa-times-circle fa-3x mb-3"></i>
                        <h5 class="font-weight-bold">Booking Dibatalkan</h5>
                    </div>
                </div>

            {{-- KONDISI 2: MASIH ADA SISA TAGIHAN (Upload Bukti) --}}
            @elseif ($sisaTagihan > 0)
                <div class="card shadow-sm border-warning mb-3">
                    <div class="card-header bg-warning text-dark">
                        <h6 class="mb-0 font-weight-bold"><i class="fas fa-wallet mr-2"></i> Pembayaran</h6>
                    </div>
                    <div class="card-body">
                        <p class="small text-muted mb-2">Silakan transfer kekurangan sebesar:</p>
                        <h3 class="font-weight-bold text-center mb-3 text-dark">Rp {{ number_format($sisaTagihan, 0, ',', '.') }}</h3>

                        {{-- Info Rekening (Bisa dinamis kalau ada datanya) --}}
                        <div class="alert alert-light border">
                            <strong class="d-block text-dark">Bank Jateng</strong>
                            <span class="h5 d-block mb-0 text-primary">123-456-7890</span>
                            <small>a.n. Dispora Semarang</small>
                        </div>

                        <hr>

                        {{-- Form Upload (Logic Blade 1) --}}
                        <form action="{{ route('user.checkout.upload_bukti', $checkout->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group">
                                <label class="small font-weight-bold">Upload Bukti Transfer</label>
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" id="bukti_bayar" name="bukti_bayar" required accept="image/*">
                                    <label class="custom-file-label" for="bukti_bayar">Pilih file...</label>
                                </div>
                                <small class="text-muted">Format: JPG, PNG. Max: 2MB</small>
                            </div>
                            <button type="submit" class="btn btn-primary btn-block font-weight-bold">
                                <i class="fas fa-upload mr-1"></i> Konfirmasi Pembayaran
                            </button>
                        </form>
                    </div>
                </div>

            {{-- KONDISI 3: SISA 0 TAPI ADA PENDING (Sedang Diverifikasi) --}}
            @elseif ($sisaTagihan <= 0 && $uangPending > 0)
                <div class="card shadow-sm border-info mb-3">
                    <div class="card-body text-center py-5">
                        <i class="fas fa-hourglass-half fa-4x text-info mb-3"></i>
                        <h5 class="font-weight-bold">Menunggu Verifikasi</h5>
                        <p class="text-muted small mt-2 mb-3">
                            Pembayaran Anda sudah masuk dan sedang dicek oleh admin. Mohon tunggu sesaat.
                        </p>
                    </div>
                </div>

            {{-- KONDISI 4: LUNAS --}}
            @elseif ($checkout->status == 'lunas' || ($sisaTagihan <= 0 && $uangPending == 0))
                <div class="card shadow-sm border-success mb-3">
                    <div class="card-body text-center py-5">
                        <i class="fas fa-check-circle fa-4x text-success mb-3"></i>
                        <h5 class="text-success font-weight-bold">Lunas!</h5>
                        <p class="text-muted small">Booking Anda telah aktif.</p>
                        <button onclick="window.print()" class="btn btn-outline-success btn-block mt-3">
                            <i class="fas fa-print mr-1"></i> Cetak Bukti
                        </button>
                    </div>
                </div>
            @endif

        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script>
        // Script sederhana untuk menampilkan nama file saat upload
        document.addEventListener('DOMContentLoaded', function() {
            const fileInput = document.getElementById('bukti_bayar');
            if (fileInput) {
                fileInput.addEventListener('change', function(e) {
                    var fileName = e.target.files[0].name;
                    var nextSibling = e.target.nextElementSibling;
                    nextSibling.innerText = fileName;
                })
            }
        });
    </script>
@endpush
