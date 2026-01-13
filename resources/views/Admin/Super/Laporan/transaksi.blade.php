@extends('Admin.component')

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Laporan Transaksi Global</h1>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
    <div class="container-fluid">

        {{-- Filter & Summary Box Combined --}}
        <div class="card shadow-sm">
            <div class="card-header bg-light">
                <h3 class="card-title font-weight-bold text-dark">
                    <i class="fas fa-filter mr-1"></i> Filter & Ringkasan
                </h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.super.laporan.transaksi') }}" method="GET">
                    <div class="row align-items-end">
                        {{-- 1. Filter Tanggal Mulai --}}
                        <div class="col-md-2 col-6">
                            <div class="form-group mb-2">
                                <label class="small text-muted mb-1">Dari Tanggal</label>
                                <input type="date" name="start_date" class="form-control form-control-sm"
                                       value="{{ request('start_date') }}">
                            </div>
                        </div>

                        {{-- 2. Filter Tanggal Selesai --}}
                        <div class="col-md-2 col-6">
                            <div class="form-group mb-2">
                                <label class="small text-muted mb-1">Sampai Tanggal</label>
                                <input type="date" name="end_date" class="form-control form-control-sm"
                                       value="{{ request('end_date') }}">
                            </div>
                        </div>

                        {{-- 3. Filter Status (Sesuai Migration Checkout) --}}
                        <div class="col-md-3">
                            <div class="form-group mb-2">
                                <label class="small text-muted mb-1">Status Transaksi</label>
                                <select name="status" class="form-control form-control-sm">
                                    <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>Semua Status</option>

                                    {{-- FIX: Value disesuaikan dengan Enum Checkout --}}
                                    <option value="lunas" {{ request('status') == 'lunas' ? 'selected' : '' }}>
                                        Lunas (Selesai)
                                    </option>
                                    <option value="kompensasi" {{ request('status') == 'kompensasi' ? 'selected' : '' }}>
                                        DP (Belum Lunas)
                                    </option>
                                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>
                                        Menunggu Verifikasi
                                    </option>
                                    <option value="batal" {{ request('status') == 'batal' ? 'selected' : '' }}>
                                        Dibatalkan
                                    </option>
                                </select>
                            </div>
                        </div>

                        {{-- 4. Tombol Filter --}}
                        <div class="col-md-2">
                            <div class="form-group mb-2">
                                <button type="submit" class="btn btn-primary btn-sm btn-block font-weight-bold">
                                    <i class="fas fa-search mr-1"></i> Terapkan
                                </button>
                            </div>
                        </div>

                        {{-- 5. TOTAL PEMASUKAN (Disatukan disini) --}}
                        <div class="col-md-3 mt-3 mt-md-0">
                            <div class="p-2 rounded shadow-sm d-flex align-items-center justify-content-between"
                                 style="background-color: #e8f5e9; border: 1px solid #c8e6c9;">
                                <div>
                                    <small class="text-success font-weight-bold d-block text-uppercase" style="letter-spacing: 0.5px;">
                                        Total Masuk
                                    </small>
                                    <h5 class="font-weight-bold text-dark m-0">
                                        Rp {{ number_format($totalPemasukan, 0, ',', '.') }}
                                    </h5>
                                </div>
                                <i class="fas fa-coins text-success fa-2x opacity-50"></i>
                            </div>
                        </div>

                    </div>
                </form>
            </div>
        </div>

        {{-- Tabel Data Transaksi --}}
        <div class="card shadow-sm">
            <div class="card-body table-responsive p-0">
                <table class="table table-bordered table-hover text-nowrap">
                    <thead class="bg-light">
                        <tr>
                            <th style="width: 5%">No</th>
                            <th>Kode TRX</th>
                            <th>Penyewa</th>
                            <th>Fasilitas & Jadwal</th>
                            <th>Transaksi</th>
                            <th class="text-center">Status</th>
                            <th>Tgl Transaksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($transaksi as $key => $trx)
                        <tr>
                            <td>{{ $transaksi->firstItem() + $key }}</td>
                            <td class="text-primary font-weight-bold">
                                {{ $trx->kode_transaksi ?? 'TRX-'.$trx->id }}
                            </td>
                            <td>
                                <b>{{ $trx->user->name ?? 'User Terhapus' }}</b><br>
                                <small class="text-muted">{{ $trx->user->no_hp ?? '-' }}</small>
                            </td>
                            <td>
                                {{-- Looping Jadwal --}}
                                @forelse($trx->jadwals as $jadwal)
                                    <span class="font-weight-bold text-dark">
                                        {{ $jadwal->fasilitas->nama_fasilitas ?? '-' }}
                                    </span><br>
                                    <small class="text-primary">
                                        <i class="far fa-calendar-alt mr-1"></i>
                                        {{ \Carbon\Carbon::parse($jadwal->tanggal)->format('d M Y') }}
                                        ({{ substr($jadwal->jam_mulai, 0, 5) }} - {{ substr($jadwal->jam_selesai, 0, 5) }})
                                    </small>
                                    @if(!$loop->last) <hr class="my-1"> @endif
                                @empty
                                    <span class="text-danger small">Data Jadwal Hilang</span>
                                @endforelse
                            </td>
                            <td class="font-weight-bold">
                                Rp {{ number_format($trx->total_bayar, 0, ',', '.') }}
                            </td>
                            <td class="text-center">
                                @if($trx->status == 'lunas')
                                    <span class="badge badge-success px-3">Lunas</span>
                                @elseif($trx->status == 'kompensasi')
                                    <span class="badge badge-warning px-3">DP</span>
                                @elseif($trx->status == 'pending')
                                    <span class="badge badge-info px-3">Verifikasi</span>
                                @elseif($trx->status == 'batal')
                                    <span class="badge badge-danger px-3">Batal</span>
                                @else
                                    <span class="badge badge-secondary">{{ $trx->status }}</span>
                                @endif
                            </td>
                            <td>{{ $trx->created_at->format('d/m/Y H:i') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-4 text-muted">
                                <i class="fas fa-folder-open fa-2x mb-2"></i><br>
                                Tidak ada data transaksi yang ditemukan.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="card-footer bg-white clearfix">
                {{ $transaksi->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </div>
</section>
@endsection
