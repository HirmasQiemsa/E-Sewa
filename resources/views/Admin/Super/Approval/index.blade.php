@extends('Admin.component')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6"><h1 class="m-0">Persetujuan Fasilitas</h1></div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="alert alert-info">
            <i class="fas fa-info-circle mr-1"></i>
            Daftar di bawah adalah fasilitas baru atau perubahan data yang menunggu validasi dari Anda.
        </div>

        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Antrian Pengajuan (Pending)</h3>
            </div>
            <div class="card-body p-0 table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th style="width: 10px">#</th>
                            <th>Nama Fasilitas</th>
                            <th>Kategori</th>
                            <th>Diajukan Oleh</th>
                            <th>Tanggal Pengajuan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($approvals as $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                <b>{{ $item->nama_fasilitas }}</b>
                                <br>
                                <small class="text-muted">Rp {{ number_format($item->harga_sewa, 0, ',', '.') }}</small>
                            </td>
                            <td>{{ $item->kategori }}</td>
                            <td>{{ $item->adminFasilitas->name ?? 'Admin Terhapus' }}</td>
                            <td>{{ $item->updated_at->format('d M Y H:i') }}</td>
                            <td>
                                <a href="{{ route('admin.super.approval.show', $item->id) }}" class="btn btn-sm btn-primary">
                                    <i class="fas fa-search mr-1"></i> Periksa Detail
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <i class="fas fa-check-circle fa-3x mb-3"></i><br>
                                Tidak ada pengajuan yang menunggu persetujuan.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>
@endsection
