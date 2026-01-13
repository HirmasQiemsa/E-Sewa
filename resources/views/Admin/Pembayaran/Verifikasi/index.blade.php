@extends('Admin.component')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Verifikasi Pembayaran</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Verifikasi</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">

        {{-- CARD FILTER --}}
        <div class="card card-outline card-primary {{ request('status') || request('search') ? '' : 'collapsed-card' }}">
            <div class="card-header">
                <h3 class="card-title">Filter Data</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-plus"></i></button>
                </div>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.keuangan.verifikasi.index') }}" method="GET">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Status Pembayaran</label>
                                <select name="status" class="form-control">
                                    <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>Semua Status</option>
                                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Menunggu Verifikasi (Pending)</option>
                                    <option value="kompensasi" {{ request('status') == 'kompensasi' ? 'selected' : '' }}>DP / Kompensasi</option>
                                    <option value="lunas" {{ request('status') == 'lunas' ? 'selected' : '' }}>Lunas</option>
                                    <option value="ditolak" {{ request('status') == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Cari (Kode / Nama User)</label>
                                <input type="text" name="search" class="form-control"
                                       value="{{ request('search') }}"
                                       placeholder="Masukan kode transaksi atau nama penyewa...">
                            </div>
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
                            <div class="form-group w-100">
                                <button type="submit" class="btn btn-primary btn-block"><i class="fas fa-search"></i> Cari</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        {{-- CARD TABEL --}}
        <div class="card">
            <div class="card-header border-0">
                <h3 class="card-title">
                    <i class="fas fa-list-alt mr-1"></i>
                    Daftar Transaksi
                    @if(request('status') == 'pending') <span class="badge badge-warning ml-2">Perlu Diproses</span> @endif
                </h3>
            </div>
            <div class="card-body table-responsive p-0">
                <table class="table table-hover text-nowrap table-striped">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Tanggal & Waktu</th>
                            <th>Kode Invoice</th>
                            <th>Penyewa</th>
                            <th>Fasilitas</th>
                            <th>Total Tagihan</th>
                            <th>Status Pembayaran</th>
                            <th class="text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($checkouts as $item)
                            <tr>
                                <td>{{ $loop->iteration + $checkouts->firstItem() - 1 }}</td>
                                <td>
                                    {{ \Carbon\Carbon::parse($item->created_at)->translatedFormat('d M Y') }}
                                    <br>
                                    <small class="text-muted"><i class="far fa-clock"></i> {{ \Carbon\Carbon::parse($item->created_at)->format('H:i') }} WIB</small>
                                </td>
                                <td>
                                    <span class="badge badge-light border">{{ $item->kode_transaksi ?? 'TRX-'.$item->id }}</span>
                                </td>
                                <td>
                                    <div class="user-block">
                                        <span class="username ml-0" style="font-size: 1rem;">
                                            {{ $item->user->name ?? 'User Terhapus' }}
                                        </span>
                                    </div>
                                </td>
                                <td>
                                    {{-- Safe Access untuk Relasi Jadwal -> Fasilitas --}}
                                    @if($item->jadwals->isNotEmpty())
                                        <i class="fas fa-building text-secondary mr-1"></i>
                                        {{ $item->jadwals->first()->fasilitas->nama_fasilitas ?? 'Fasilitas Terhapus' }}
                                    @else
                                        <span class="text-danger font-italic">Jadwal Hilang</span>
                                    @endif
                                </td>
                                <td class="font-weight-bold text-success">
                                    {{-- Menggunakan total_bayar sesuai struktur DB --}}
                                    Rp {{ number_format($item->total_bayar, 0, ',', '.') }}
                                </td>
                                <td>
                                    @if($item->status == 'pending')
                                        <span class="badge badge-warning p-2"><i class="fas fa-clock mr-1"></i> Menunggu Verifikasi</span>
                                    @elseif($item->status == 'lunas')
                                        <span class="badge badge-success p-2"><i class="fas fa-check-circle mr-1"></i> Lunas</span>
                                    @elseif($item->status == 'kompensasi')
                                        <span class="badge badge-info p-2"><i class="fas fa-money-bill-wave mr-1"></i> DP / Belum Lunas</span>
                                    @elseif($item->status == 'ditolak')
                                        <span class="badge badge-danger p-2"><i class="fas fa-times-circle mr-1"></i> Ditolak</span>
                                    @elseif($item->status == 'batal')
                                        <span class="badge badge-secondary p-2">Batal</span>
                                    @else
                                        <span class="badge badge-light border">{{ ucfirst($item->status) }}</span>
                                    @endif
                                </td>
                                <td class="text-right">
                                    <a href="{{ route('admin.keuangan.verifikasi.show', $item->id) }}" class="btn btn-sm btn-primary shadow-sm">
                                        <i class="fas fa-search-dollar mr-1"></i> Periksa
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-5">
                                    <div class="text-muted">
                                        <i class="fas fa-inbox fa-3x mb-3"></i><br>
                                        Tidak ada data pembayaran yang ditemukan.
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="card-footer clearfix">
                <div class="float-right">
                    {{ $checkouts->links('pagination::bootstrap-4') }}
                </div>
                <div class="float-left pt-2 text-muted">
                    Menampilkan {{ $checkouts->firstItem() }} sampai {{ $checkouts->lastItem() }} dari {{ $checkouts->total() }} data
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
