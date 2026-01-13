@extends('User.component')

@section('content')
<div class="container py-4">

    <div class="mb-3">
        <a href="{{ route('user.riwayat') }}" class="btn btn-outline-secondary btn-sm">
            <i class="fas fa-arrow-left mr-1"></i> Kembali ke Riwayat
        </a>
    </div>

    <div class="row">
        {{-- KOLOM KIRI (DETAIL BOOKING) --}}
        <div class="col-md-8">
            <div class="card shadow-sm border-0 mb-4">

                {{-- HEADER --}}
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0 font-weight-bold">Detail Booking #{{ $checkout->id }}</h5>
                </div>

                <div class="card-body">
                    <div class="row mb-4">
                        {{-- FOTO FASILITAS --}}
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

                        {{-- INFO FASILITAS & JADWAL --}}
                        <div class="col-md-8">
                            <h4 class="font-weight-bold">{{ $fasilitas->nama_fasilitas ?? 'Data Fasilitas Tidak Ditemukan' }}</h4>
                            <p class="text-muted mb-1"><i class="fas fa-map-marker-alt mr-2"></i> {{ $fasilitas->lokasi ?? '-' }}</p>

                            @if ($jadwalPertama)
                                <p class="text-muted mb-1"><i class="fas fa-calendar-alt mr-2"></i> {{ \Carbon\Carbon::parse($jadwalPertama->tanggal)->isoFormat('dddd, D MMMM Y') }}</p>
                                <p class="text-muted"><i class="fas fa-clock mr-2"></i> {{ substr($jamMulai, 0, 5) }} - {{ substr($jamSelesai, 0, 5) }} WIB</p>

                                {{-- STATUS BADGE (LOGIKA BARU) --}}
                                <div class="mt-3">
                                    <span class="font-weight-bold mr-2">Status:</span>
                                    @if ($checkout->status == 'batal')
                                        <span class="badge badge-danger px-3 py-2">Dibatalkan</span>
                                    @elseif ($sisaAkhir <= 0 && $uangPending > 0)
                                        <span class="badge badge-info px-3 py-2"><i class="fas fa-hourglass-half mr-1"></i> Menunggu Verifikasi Pelunasan</span>
                                    @elseif ($sisaAkhir <= 0)
                                        <span class="badge badge-success px-3 py-2"><i class="fas fa-check-circle mr-1"></i> Lunas</span>
                                    @else
                                        {{-- Masih ada sisa tagihan --}}
                                        <span class="badge badge-warning px-3 py-2">Belum Lunas (Sisa: Rp {{ number_format($sisaAkhir, 0, ',', '.') }})</span>
                                    @endif
                                </div>
                            @else
                                <p class="text-danger"><i class="fas fa-exclamation-triangle mr-2"></i> Detail jadwal tidak tersedia</p>
                            @endif
                        </div>
                    </div>

                    {{-- TABEL BIAYA (NOTA) --}}
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="bg-light">
                                <tr>
                                    <th>Deskripsi</th>
                                    <th class="text-right">Biaya</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{-- 1. RINCIAN HARGA --}}
                                <tr>
                                    <td class="align-middle">
                                        <span class="font-weight-bold">Sewa Lapangan</span>
                                        <div class="small text-muted mt-1">
                                            <i class="fas fa-clock mr-1"></i> {{ $totalDurasi }} Jam x Rp {{ number_format($hargaPerJam, 0, ',', '.') }}
                                        </div>
                                    </td>
                                    <td class="text-right align-middle">Rp {{ number_format($totalTagihan, 0, ',', '.') }}</td>
                                </tr>

                                {{-- 2. TOTAL TAGIHAN --}}
                                <tr class="bg-light">
                                    <td class="font-weight-bold text-uppercase">Total Tagihan</td>
                                    <td class="text-right font-weight-bold" style="font-size: 1.1em;">Rp {{ number_format($totalTagihan, 0, ',', '.') }}</td>
                                </tr>

                                {{-- 3. SUDAH DIBAYAR --}}
                                @if ($sudahBayar > 0)
                                    <tr>
                                        <td class="text-success">Pembayaran Diterima (Valid)</td>
                                        <td class="text-right text-success font-weight-bold"> Rp {{ number_format($sudahBayar, 0, ',', '.') }}</td>
                                    </tr>
                                @endif

                                {{-- 4. PENDING VERIFIKASI --}}
                                @if ($uangPending > 0)
                                    <tr>
                                        <td class="text-info">Pembayaran Dalam Proses Verifikasi</td>
                                        <td class="text-right text-info font-weight-bold"> Rp {{ number_format($uangPending, 0, ',', '.') }}</td>
                                    </tr>
                                @endif

                                {{-- 5. SISA TAGIHAN AKHIR --}}
                                    {{-- Hanya muncul jika masih ada sisa bayar --}}
                                    @if ($sisaAkhir > 0)
                                        <tr style="border-top: 2px solid #dee2e6;">
                                            <td class="font-weight-bold text-danger border-top-0">Sisa Kekurangan</td>
                                            <td class="text-right font-weight-bold text-danger border-top-0">
                                                Rp {{ number_format($sisaAkhir, 0, ',', '.') }}
                                            </td>
                                        </tr>
                                    @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- KOLOM KANAN (SIDEBAR PEMBAYARAN) --}}
        <div class="col-md-4">
            @if ($sisaAkhir > 0 && $checkout->status != 'batal')
                {{-- KONDISI 1: MASIH ADA SISA TAGIHAN (TAMPILKAN FORM UPLOAD) --}}
                <div class="card shadow-sm border-warning mb-3">
                    <div class="card-header bg-warning text-dark">
                        <h6 class="mb-0 font-weight-bold"><i class="fas fa-wallet mr-2"></i> Instruksi Pembayaran</h6>
                    </div>
                    <div class="card-body">
                        <p class="small text-muted mb-2">Silakan transfer kekurangan sebesar:</p>
                        <h3 class="font-weight-bold text-center mb-3">Rp {{ number_format($sisaAkhir, 0, ',', '.') }}</h3>

                        <div class="alert alert-light border">
                            <strong class="d-block text-dark">Bank Jateng</strong>
                            <span class="h5 d-block mb-0 text-primary">123-456-7890</span>
                            <small>a.n. Dispora Semarang</small>
                        </div>
                        <hr>
                        <form action="{{ route('user.checkout.upload_bukti', $checkout->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="form-group">
                                <label class="small font-weight-bold">Upload Bukti Transfer</label>
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" id="bukti_bayar" name="bukti_bayar" required>
                                    <label class="custom-file-label" for="bukti_bayar">Pilih file...</label>
                                </div>
                                <small class="text-muted">Format: JPG, PNG, PDF. Max: 2MB</small>
                            </div>
                            <button type="submit" class="btn btn-primary btn-block font-weight-bold">
                                <i class="fas fa-upload mr-1"></i> Konfirmasi Pembayaran
                            </button>
                        </form>
                    </div>
                </div>

            @elseif ($sisaAkhir <= 0 && $uangPending > 0)
                {{-- KONDISI 2: SUDAH BAYAR FULL TAPI MENUNGGU VERIFIKASI --}}
                <div class="card shadow-sm border-info mb-3">
                    <div class="card-body text-center py-5">
                        <i class="fas fa-clock fa-4x text-info mb-3"></i>
                        <h5 class="font-weight-bold">Pembayaran Sedang Diverifikasi</h5>
                        <p class="text-muted small mt-2 mb-3">Mohon tunggu, admin kami sedang mengecek bukti pembayaran Anda.</p>
                        @php $buktiTerakhir = $pembayaran->last()->bukti_bayar ?? null; @endphp
                        @if ($buktiTerakhir)
                            <a href="{{ asset('storage/' . $buktiTerakhir) }}" target="_blank" class="btn btn-sm btn-outline-info">
                                <i class="fas fa-image mr-1"></i> Lihat Bukti Terakhir
                            </a>
                        @endif
                    </div>
                </div>

            @elseif ($sisaAkhir <= 0 && $uangPending == 0)
                {{-- KONDISI 3: LUNAS SEMPURNA (CLEAN) --}}
                <div class="card shadow-sm border-success mb-3">
                    <div class="card-body text-center py-5">
                        <i class="fas fa-check-circle fa-4x text-success mb-3"></i>
                        <h5 class="text-success">Pembayaran Lunas!</h5>
                        <p class="text-muted small">Booking Anda telah dikonfirmasi.</p>
                        <button onclick="window.print()" class="btn btn-success btn-block">
                            <i class="fas fa-print mr-1"></i> Cetak Tiket / Bukti
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
