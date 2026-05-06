@extends('Admin.component')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Validasi Pengajuan Event</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Validasi Event</li>
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
                <form action="{{ route('admin.fasilitas.validasi.index') }}" method="GET">
                    <div class="row">
                        <div class="col-md-10">
                            <div class="form-group">
                                <label>Cari (Nama Event / Panitia / User)</label>
                                <input type="text" name="search" class="form-control"
                                       value="{{ request('search') }}"
                                       placeholder="Masukan nama event atau panitia...">
                            </div>
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
                            <div class="form-group w-100">
                                <button type="submit" class="btn btn-primary btn-block"><i class="fas fa-search"></i> Cari</button>
                            </div>
                        </div>
                    </div>
                    {{-- Opsional: Tambah tombol reset filter jika perlu --}}
                    @if(request('search') || request('status'))
                        <div class="mt-2">
                            <a href="{{ route('admin.fasilitas.validasi.index') }}" class="text-muted"><i class="fas fa-sync-alt mr-1"></i> Reset Filter</a>
                        </div>
                    @endif
                </form>
            </div>
        </div>

        {{-- CARD TABEL --}}
        <div class="card">
            <div class="card-header border-0">
                <h3 class="card-title">
                    <i class="fas fa-calendar-check mr-1"></i>
                    Daftar Pengajuan
                    @if(request('status') == 'pending')
                        <span class="badge badge-warning ml-2">Menunggu Persetujuan</span>
                    @endif
                </h3>
            </div>
            <div class="card-body table-responsive p-0">
                <table class="table table-hover text-nowrap table-striped">
                    <thead>
                        <tr>
                            <th style="width: 5%">No</th>
                            <th>Tanggal Pengajuan</th>
                            <th>Nama Event</th>
                            <th>Panitia / Penyewa</th>
                            <th>Fasilitas & Jadwal</th>
                            <th>Status</th>
                            <th class="text-center" style="width: 10%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pengajuans as $item)
                            <tr>
                                <td>{{ $loop->iteration + $pengajuans->firstItem() - 1 }}</td>
                                <td>
                                    {{ $item->created_at->translatedFormat('d M Y') }}
                                    <br>
                                    <small class="text-muted"><i class="far fa-clock"></i> {{ $item->created_at->format('H:i') }} WIB</small>
                                </td>
                                <td>
                                    <span class="font-weight-bold">{{ $item->nama_event }}</span>
                                </td>
                                <td>
                                    <div class="user-block">
                                        <span class="username ml-0" style="font-size: 1rem;">
                                            {{ $item->nama_panitia }}
                                        </span>
                                        <span class="description ml-0">
                                            User: {{ $item->user->name ?? 'User Terhapus' }}
                                        </span>
                                    </div>
                                </td>
                                <td>
                                    {{-- Info Fasilitas --}}
                                    <i class="fas fa-building text-secondary mr-1"></i>
                                    {{ $item->fasilitas->nama_fasilitas ?? 'Fasilitas Terhapus' }}
                                    <br>

                                    {{-- Info Tanggal Sewa --}}
                                    <small class="text-muted">
                                        <i class="fas fa-calendar-alt mr-1"></i>
                                        {{ $item->tgl_mulai->format('d M') }} s/d {{ $item->tgl_selesai->format('d M Y') }}
                                    </small>
                                </td>
                                <td>
                                    @if($item->status == 'pending')
                                        <span class="badge badge-warning p-2">
                                            <i class="fas fa-hourglass-half mr-1"></i> Pending
                                        </span>
                                    @elseif($item->status == 'approved')
                                        <span class="badge badge-success p-2">
                                            <i class="fas fa-check-circle mr-1"></i> Disetujui
                                        </span>
                                    @elseif($item->status == 'rejected')
                                        <span class="badge badge-danger p-2">
                                            <i class="fas fa-times-circle mr-1"></i> Ditolak
                                        </span>
                                    @else
                                        <span class="badge badge-light border">{{ ucfirst($item->status) }}</span>
                                    @endif
                                </td>
                                <td class="text-right">
                                    <a href="{{ route('admin.fasilitas.validasi.show', $item->id) }}" class="btn btn-sm btn-primary shadow-sm">
                                        <i class="fas fa-eye mr-1"></i> Detail
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5">
                                    <div class="text-muted">
                                        <i class="fas fa-folder-open fa-3x mb-3"></i><br>
                                        Tidak ada data pengajuan event yang ditemukan.
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="card-footer clearfix">
                <div class="float-right">
                    {{ $pengajuans->links('pagination::bootstrap-4') }}
                </div>
                <div class="float-left pt-2 text-muted">
                    Menampilkan {{ $pengajuans->firstItem() }} sampai {{ $pengajuans->lastItem() }} dari {{ $pengajuans->total() }} data
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
