@extends('Admin.component')

@push('css')
    <style>
        /* ATURAN PAGINATION BIAR RAPI (DI LAYAR BIASA) */
        .pagination {
            margin-bottom: 0 !important;
            /* Hilangkan space bawah pagination */
        }

        /* CSS KHUSUS PRINT */
        @media print {

            /* 1. Sembunyikan elemen sistem & navigasi */
            .main-footer,
            .navbar,
            .main-sidebar,
            .content-header,
            .card-header,
            .btn,
            .card-tools,
            .card-footer {
                display: none !important;
            }

            /* 2. Reset layout agar full kertas */
            .content-wrapper,
            .main-footer,
            .content {
                margin-left: 0 !important;
                padding: 0 !important;
                min-height: 0 !important;
            }

            /* 3. Bersihkan tampilan Card */
            .card {
                box-shadow: none !important;
                border: none !important;
                margin-bottom: 0 !important;
            }

            /* 4. TOTAL MASUK (Wajib Tampil Rapi) */
            .total-print-wrapper {
                border: 2px solid #000 !important;
                background-color: #fff !important;
                color: #000 !important;
                margin-bottom: 20px !important;
                padding: 15px !important;
            }

            /* 5. FIX TABLE BORDER */
            .table-bordered td,
            .table-bordered th {
                border: 1px solid #000 !important;
            }

            /* 6. HILANGKAN LINK HREF */
            a[href]:after {
                content: none !important;
            }
        }

        /* Fix ukuran pagination agar tidak terlalu besar */
        .pagination {
            margin-bottom: 0 !important;
        }

        .pagination .page-link {
            padding: 0.375rem 0.75rem;
            font-size: 0.875rem;
        }

        /* Responsif untuk mobile */
        @media (max-width: 767px) {
            .card-footer .row>div {
                text-align: center;
            }

            .pagination {
                justify-content: center;
            }
        }
    </style>
@endpush

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

            {{-- CARD FILTER & TOTAL --}}
            <div class="card shadow-sm">
                {{-- HEADER: Tambahkan 'd-print-none' agar judul filter HILANG saat print --}}
                <div class="card-header bg-light d-print-none">
                    <h3 class="card-title font-weight-bold text-dark">
                        <i class="fas fa-filter mr-1"></i> Filter & Ringkasan
                    </h3>
                </div>

                <div class="card-body">
                    <form action="{{ route('admin.super.laporan.transaksi') }}" method="GET">
                        <div class="row align-items-end">

                            {{-- INPUT FORM (Hilang saat print) --}}
                            <div class="col-md-2 col-6 d-print-none">
                                <div class="form-group mb-2">
                                    <label class="small text-muted mb-1">Dari Tanggal</label>
                                    <input type="date" name="start_date" class="form-control form-control-sm"
                                        value="{{ request('start_date') }}">
                                </div>
                            </div>

                            <div class="col-md-2 col-6 d-print-none">
                                <div class="form-group mb-2">
                                    <label class="small text-muted mb-1">Sampai Tanggal</label>
                                    <input type="date" name="end_date" class="form-control form-control-sm"
                                        value="{{ request('end_date') }}">
                                </div>
                            </div>

                            <div class="col-md-3 d-print-none">
                                <div class="form-group mb-2">
                                    <label class="small text-muted mb-1">Status Transaksi</label>
                                    <select name="status" class="form-control form-control-sm">
                                        <option value="all">Semua Status</option>
                                        <option value="lunas" {{ request('status') == 'lunas' ? 'selected' : '' }}>Lunas
                                        </option>
                                        <option value="kompensasi"
                                            {{ request('status') == 'kompensasi' ? 'selected' : '' }}>DP</option>
                                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>
                                            Pending</option>
                                        <option value="batal" {{ request('status') == 'batal' ? 'selected' : '' }}>Batal
                                        </option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-2 d-print-none">
                                <div class="form-group mb-2">
                                    <button type="submit" class="btn btn-primary btn-sm btn-block font-weight-bold">
                                        <i class="fas fa-search mr-1"></i> Terapkan
                                    </button>
                                </div>
                            </div>

                            {{-- TOTAL MASUK (TETAP MUNCUL) --}}
                            {{-- Saya pisahkan row baru khusus print jika perlu, atau pakai col-print-12 --}}
                            <div class="col-md-3 mt-3 mt-md-0 col-12">
                                <div class="p-2 rounded shadow-sm d-flex align-items-center justify-content-between total-print-wrapper"
                                    style="background-color: #e8f5e9; border: 1px solid #c8e6c9;">
                                    <div>
                                        <small class="text-success font-weight-bold d-block text-uppercase"
                                            style="letter-spacing: 0.5px;">
                                            Total Pendapatan
                                        </small>
                                        <h5 class="font-weight-bold text-dark m-0">
                                            Rp {{ number_format($totalPemasukan, 0, ',', '.') }}
                                        </h5>
                                    </div>
                                    <i class="fas fa-coins text-success fa-2x opacity-50 d-print-none"></i>
                                </div>
                            </div>

                        </div>
                    </form>
                </div>
            </div>

            {{-- TABEL DATA --}}
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
                                        {{ $trx->kode_transaksi ?? 'TRX-' . $trx->id }}
                                    </td>
                                    <td>
                                        <b>{{ $trx->user->name ?? 'User Terhapus' }}</b><br>
                                        <small class="text-muted">{{ $trx->user->no_hp ?? '-' }}</small>
                                    </td>
                                    <td>
                                        @foreach ($trx->jadwals as $jadwal)
                                            <span
                                                class="font-weight-bold text-dark">{{ $jadwal->fasilitas->nama_fasilitas }}</span><br>
                                            <small class="text-primary">
                                                {{ \Carbon\Carbon::parse($jadwal->tanggal)->format('d M Y') }}
                                                ({{ substr($jadwal->jam_mulai, 0, 5) }} -
                                                {{ substr($jadwal->jam_selesai, 0, 5) }})
                                            </small>
                                            @if (!$loop->last)
                                                <hr class="my-1">
                                            @endif
                                        @endforeach
                                    </td>
                                    <td class="font-weight-bold">Rp {{ number_format($trx->total_bayar, 0, ',', '.') }}
                                    </td>
                                    <td class="text-center">
                                        @if ($trx->status == 'lunas')
                                            <span class="badge badge-success px-3">Lunas</span>
                                        @elseif($trx->status == 'kompensasi')
                                            <span class="badge badge-warning px-3">DP</span>
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
                                    <td colspan="7" class="text-center">Tidak ada data.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- CARD FOOTER: PAGINATION & TOMBOL PRINT --}}
                <div class="card-footer bg-white d-print-none py-2 px-3 border-top">
                    <div class="row align-items-center">

                        {{-- KIRI: Info Data --}}
                        <div class="col-md-4 col-12 mb-2 mb-md-0">
                            <small class="text-muted">
                                Menampilkan {{ $transaksi->firstItem() ?? 0 }} - {{ $transaksi->lastItem() ?? 0 }}
                                dari {{ $transaksi->total() }} data
                            </small>
                        </div>

                        {{-- TENGAH: Pagination --}}
                        <div class="col-md-4 col-12 mb-2 mb-md-0 d-flex justify-content-center">
                            {{ $transaksi->links('pagination::bootstrap-4') }}
                        </div>

                        {{-- KANAN: Tombol Print --}}
                        <div class="col-md-4 col-12 d-flex justify-content-md-end justify-content-center">
                            <button onclick="window.print()"
                                class="btn btn-outline-secondary btn-sm font-weight-bold shadow-sm">
                                <i class="fas fa-print mr-1"></i> Cetak Laporan
                            </button>
                        </div>

                    </div>
                </div>


            </div> {{-- Penutup card shadow-sm --}}

            {{-- FOOTER KHUSUS CETAK (TANDA TANGAN) --}}
            <div class="row d-none d-print-flex justify-content-between mt-5 pt-4 px-4">
                <div class="text-center" style="width: 200px;">
                    <p class="mb-5">Mengetahui,</p> {{-- mb-5 memberi jarak untuk tanda tangan --}}
                    <p class="font-weight-bold mb-0"><u>{{ Auth::user()->name }}</u></p>
                    <p class="small text-muted">{{ now()->format('d F Y') }}</p>
                </div>

                {{-- <div class="text-center" style="width: 200px;">
                    <p class="mb-5">Mengetahui,</p>
                    <p class="font-weight-bold mb-0"><u>( ........................... )</u></p>
                    <p class="small text-muted">Kepala Dinas / Manager</p>
                </div> --}}
            </div>

        </div>
    </section>
@endsection
