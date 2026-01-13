@extends('Admin.component')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6"><h1 class="m-0">Detail Validasi</h1></div>
            <div class="col-sm-6">
                <a href="{{ route('admin.super.approval.index') }}" class="btn btn-secondary float-right">Kembali</a>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            {{-- KOLOM KIRI: Foto Fasilitas --}}
            <div class="col-md-5">
                <div class="card mb-3">
                    <img src="{{ asset('storage/'.$fasilitas->banner) }}" class="card-img-top" alt="Foto Banner" style="height: 300px; object-fit: cover;">
                    <div class="card-body">
                        <h5 class="card-title font-weight-bold">Gambar Utama</h5>
                        <p class="card-text text-muted">Pastikan gambar layak tampil dan sesuai norma.</p>
                    </div>
                </div>
            </div>

            {{-- KOLOM KANAN: Data & Aksi --}}
            <div class="col-md-7">
                <div class="card card-primary card-outline">
                    <div class="card-header">
                        <h3 class="card-title font-weight-bold">{{ $fasilitas->nama_fasilitas }}</h3>
                        <span class="float-right badge badge-warning">Status: Pending</span>
                    </div>
                    <div class="card-body">
                        <dl class="row">
                            <dt class="col-sm-4">Kategori</dt>
                            <dd class="col-sm-8">{{ $fasilitas->kategori }}</dd>

                            <dt class="col-sm-4">Harga Sewa</dt>
                            <dd class="col-sm-8 text-success font-weight-bold">Rp {{ number_format($fasilitas->harga_sewa, 0, ',', '.') }} / Sesi</dd>

                            <dt class="col-sm-4">Lokasi</dt>
                            <dd class="col-sm-8">{{ $fasilitas->lokasi }}</dd>

                            <dt class="col-sm-4">Deskripsi</dt>
                            <dd class="col-sm-8">{{ $fasilitas->deskripsi }}</dd>

                            <dt class="col-sm-4">Admin Pengaju</dt>
                            <dd class="col-sm-8">{{ $fasilitas->adminFasilitas->name ?? '-' }}</dd>

                            <dt class="col-sm-4">Admin Pembayaran</dt>
                            <dd class="col-sm-8">{{ $fasilitas->adminPembayaran->name ?? '-' }}</dd>
                        </dl>

                        <hr>

                        <form action="{{ route('admin.super.approval.action', $fasilitas->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="form-group">
                                <label for="catatan">Catatan / Alasan Penolakan <small class="text-danger">(Wajib diisi jika Reject)</small></label>
                                <textarea name="catatan" id="catatan" class="form-control" rows="3" placeholder="Tulis catatan untuk admin fasilitas..."></textarea>
                            </div>

                            <div class="row mt-4">
                                <div class="col-6">
                                    <button type="submit" name="action" value="reject" class="btn btn-danger btn-block btn-lg" onclick="return confirm('Yakin ingin menolak fasilitas ini?')">
                                        <i class="fas fa-times-circle mr-1"></i> TOLAK (REJECT)
                                    </button>
                                </div>
                                <div class="col-6">
                                    <button type="submit" name="action" value="approve" class="btn btn-success btn-block btn-lg" onclick="return confirm('Yakin ingin menyetujui fasilitas ini? Fasilitas akan langsung tampil di halaman user.')">
                                        <i class="fas fa-check-circle mr-1"></i> SETUJUI (APPROVE)
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
