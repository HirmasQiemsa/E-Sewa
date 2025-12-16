@extends('User.user')

@section('content')
<div class="container py-4">

    <div class="mb-3">
        <a href="{{ route('user.riwayat') }}" class="btn btn-outline-secondary btn-sm">
            <i class="fas fa-arrow-left mr-1"></i> Kembali ke Riwayat
        </a>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Detail Booking #{{ $checkout->id }}</h5>

                    @if($checkout->status == 'kompensasi')
                        <span class="badge badge-warning px-3 py-2">DP Terbayar</span>
                    @elseif($checkout->status == 'pending')
                         <span class="badge badge-info px-3 py-2">Menunggu Verifikasi Admin</span>
                    @elseif($checkout->status == 'lunas')
                        <span class="badge badge-success px-3 py-2">Lunas / Siap Main</span>
                    @else
                        <span class="badge badge-danger px-3 py-2">Dibatalkan</span>
                    @endif
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-4 text-center">
                            <img src="{{ asset('storage/' . $checkout->jadwal->fasilitas->foto) }}"
                                 class="img-fluid rounded shadow-sm"
                                 style="max-height: 150px; object-fit: cover;">
                        </div>
                        <div class="col-md-8">
                            <h4 class="font-weight-bold">{{ $checkout->jadwal->fasilitas->nama_fasilitas }}</h4>
                            <p class="text-muted mb-1"><i class="fas fa-map-marker-alt mr-2"></i> {{ $checkout->jadwal->fasilitas->lokasi }}</p>
                            <p class="text-muted mb-1"><i class="fas fa-calendar-alt mr-2"></i> {{ \Carbon\Carbon::parse($checkout->jadwal->tanggal)->isoFormat('dddd, D MMMM Y') }}</p>
                            <p class="text-muted"><i class="fas fa-clock mr-2"></i> {{ substr($checkout->jadwal->jam_mulai, 0, 5) }} - {{ substr($checkout->jadwal->jam_selesai, 0, 5) }} WIB</p>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="bg-light">
                                <tr>
                                    <th>Deskripsi</th>
                                    <th class="text-right">Biaya</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Sewa Lapangan ({{ $checkout->totalDurasi ?? 1 }} Jam)</td>
                                    <td class="text-right">Rp {{ number_format($checkout->total_bayar, 0, ',', '.') }}</td>
                                </tr>
                                <tr class="font-weight-bold bg-light">
                                    <td>Total Tagihan</td>
                                    <td class="text-right">Rp {{ number_format($checkout->total_bayar, 0, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <td class="text-primary font-weight-bold">Kekurangan Yang Harus Dibayar (50%)</td>
                                    <td class="text-right text-primary font-weight-bold">
                                        Rp {{ number_format($checkout->total_bayar * 0.5, 0, ',', '.') }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">

            @if($checkout->status == 'kompensasi')
            <div class="card shadow-sm border-warning mb-3">
                <div class="card-header bg-warning text-dark">
                    <h6 class="mb-0 font-weight-bold"><i class="fas fa-wallet mr-2"></i> Instruksi Pembayaran</h6>
                </div>
                <div class="card-body">
                    <p class="small text-muted mb-2">Silakan transfer pelunasan sebesar:</p>
                    <h3 class="font-weight-bold text-center mb-3">Rp {{ number_format($checkout->total_bayar * 0.5, 0, ',', '.') }}</h3>

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

            @elseif($checkout->status == 'pending')
            <div class="card shadow-sm border-info mb-3">
                <div class="card-body text-center py-5">
                    <i class="fas fa-clock fa-4x text-info mb-3"></i>
                    <h5>Pembayaran Sedang Diverifikasi</h5>
                    <p class="text-muted small">Mohon tunggu, admin kami sedang mengecek bukti pembayaran Anda.</p>
                    @if($checkout->bukti_bayar)
                         <a href="{{ asset('storage/'.$checkout->bukti_bayar) }}" target="_blank" class="btn btn-sm btn-outline-info mt-2">Lihat Bukti Anda</a>
                    @endif
                </div>
            </div>

            @elseif($checkout->status == 'lunas')
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

<script>
    // Script untuk Custom File Input (Bootstrap 4) agar nama file muncul
    document.addEventListener('DOMContentLoaded', function () {
        const fileInput = document.getElementById('bukti_bayar');
        if(fileInput){
            fileInput.addEventListener('change', function(e){
                var fileName = e.target.files[0].name;
                var nextSibling = e.target.nextElementSibling;
                nextSibling.innerText = fileName;
            })
        }
    });
</script>
@endsection
