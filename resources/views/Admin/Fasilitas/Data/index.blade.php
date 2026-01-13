@extends('Admin.component')

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Daftar Fasilitas</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Fasilitas</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    {{ session('error') }}
                </div>
            @endif

            <div class="row">
                <div class="col-12">
                    <div class="card collapsed-card">
                        <div class="card-header bg-info">
                            <h3 class="card-title">Filter Status & Statistik</h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3 col-sm-6 col-12">
                                    <div class="info-box bg-success">
                                        <span class="info-box-icon"><i class="fas fa-check-circle"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">Aktif</span>
                                            <span class="info-box-number">{{ $countAktif ?? 0 }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-6 col-12">
                                    <div class="info-box bg-warning">
                                        <span class="info-box-icon"><i class="fas fa-tools"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">Maintenance</span>
                                            <span class="info-box-number">{{ $countMaintenance ?? 0 }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-6 col-12">
                                    <div class="info-box bg-danger">
                                        <span class="info-box-icon"><i class="fas fa-ban"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">Nonaktif</span>
                                            <span class="info-box-number">{{ $countNonaktif ?? 0 }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-6 col-12">
                                    <div class="info-box bg-secondary">
                                        <span class="info-box-icon"><i class="fas fa-trash"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">Terhapus</span>
                                            {{-- PERBAIKAN: Menggunakan variabel yang dikirim controller --}}
                                            <span class="info-box-number">{{ $countTrash ?? 0 }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12 mb-3">
                    <div class="card">
                        <div class="card-header bg-primary">
                            <h3 class="card-title"><b>Data Fasilitas</b></h3>
                            <div class="card-tools">
                                <div class="input-group input-group-sm" style="width: 150px;">
                                    <input type="text" name="table_search" class="form-control float-right"
                                        placeholder="Cari...">
                                    <div class="input-group-append">
                                        <button type="submit" class="btn btn-default"><i
                                                class="fas fa-search"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card-body table-responsive p-0">
                            <table class="table table-hover text-nowrap">
                                <thead>
                                    <tr>
                                        {{-- Tambahkan class 'text-center' di header yang ingin ditengah --}}
                                        <th class="text-center" style="width: 5%">No</th>
                                        <th>Nama Fasilitas</th> {{-- Nama biarkan rata kiri biar rapi --}}
                                        <th class="text-center">Tipe</th>
                                        <th>Lokasi</th> {{-- Lokasi biarkan rata kiri --}}
                                        <th class="text-center">Harga</th>
                                        <th class="text-center">Status</th>
                                        <th class="text-center" style="width: 15%">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($fasilitas as $d)
                                        <tr>
                                            {{-- Pastikan di body (td) juga dikasih text-center --}}
                                            <td class="text-center">{{ $loop->iteration }}</td>

                                            <td>
                                                <span class="font-weight-bold">{{ $d->nama_fasilitas }}</span>
                                                <br>
                                                @if ($d->admin_fasilitas_id != Auth::id())
                                                    <small class="text-muted">
                                                        <i class="fas fa-user-edit mr-1"></i>
                                                        {{ $d->adminFasilitas->name ?? 'Admin Lain' }}
                                                    </small>
                                                @else
                                                    <small class="text-success"><i class="fas fa-user mr-1"></i> Milik
                                                        Anda</small>
                                                @endif
                                            </td>

                                            <td class="text-center">{{ $d->tipe }}</td>

                                            <td>{{ Str::limit($d->lokasi, 30) }}</td>

                                            <td class="text-center">Rp {{ number_format($d->harga_sewa, 0, ',', '.') }}
                                            </td>

                                            {{-- KOLOM STATUS CYCLIC --}}
                                            <td class="text-center">
                                                @if ($d->deleted_at)
                                                    <span class="badge badge-secondary">Terhapus</span>
                                                @else
                                                    @if (Auth::user()->role == 'super_admin' || $d->admin_fasilitas_id == Auth::id())
                                                        <form
                                                            action="{{ route('admin.fasilitas.data.toggle_status', $d->id) }}"
                                                            method="POST" class="d-inline">
                                                            @csrf
                                                            @method('PUT')
                                                            <button type="submit"
                                                                class="btn btn-xs btn-block font-weight-bold shadow-sm border-0
                                                                                    {{ $d->ketersediaan == 'aktif'
                                                                                        ? 'btn-success'
                                                                                        : ($d->ketersediaan == 'maintenance'
                                                                                            ? 'btn-warning'
                                                                                            : 'btn-dark') }}"
                                                                data-toggle="tooltip" title="Klik untuk ubah status">

                                                                @if ($d->ketersediaan == 'aktif')
                                                                    <i class="fas fa-check-circle mr-1"></i> AKTIF
                                                                @elseif($d->ketersediaan == 'maintenance')
                                                                    <i class="fas fa-tools mr-1"></i> MAINTENANCE
                                                                @else
                                                                    <i class="fas fa-ban mr-1"></i> NONAKTIF
                                                                @endif
                                                                <i class="fas fa-sync-alt fa-xs ml-1 opacity-50"></i>
                                                            </button>
                                                        </form>
                                                    @else
                                                        {{-- Read Only View --}}
                                                        @if ($d->ketersediaan == 'aktif')
                                                            <span class="badge badge-success">Aktif</span>
                                                        @elseif($d->ketersediaan == 'maintenance')
                                                            <span class="badge badge-warning">Maintenance</span>
                                                        @else
                                                            <span class="badge badge-dark">Nonaktif</span>
                                                        @endif
                                                    @endif
                                                @endif
                                            </td>

                                            {{-- KOLOM AKSI --}}
                                            <td class="text-center">
                                                <div class="btn-group">
                                                    @if ($d->deleted_at)
                                                        @if (Auth::user()->role == 'super_admin' || $d->admin_fasilitas_id == Auth::id())
                                                            <form
                                                                action="{{ route('admin.fasilitas.data.restore', $d->id) }}"
                                                                method="POST"> {{-- Ubah ke POST --}}
                                                                @csrf
                                                                @method('PUT') {{-- Tambahkan directive ini untuk spoofing ke PUT --}}

                                                                <button type="submit" class="btn btn-sm btn-warning"
                                                                    title="Pulihkan">
                                                                    <i class="fas fa-undo"></i>
                                                                </button>
                                                            </form>
                                                        @else
                                                            <span class="badge badge-secondary"><i
                                                                    class="fas fa-lock"></i></span>
                                                        @endif
                                                    @else
                                                        @if (Auth::user()->role == 'super_admin' || $d->admin_fasilitas_id == Auth::id())
                                                            <a href="{{ route('admin.fasilitas.data.edit', $d->id) }}"
                                                                class="btn btn-sm btn-info" title="Edit Data">
                                                                <i class="fas fa-edit"></i>
                                                            </a>

                                                            <form
                                                                action="{{ route('admin.fasilitas.data.destroy', $d->id) }}"
                                                                method="POST" class="d-inline">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-sm btn-danger"
                                                                    onclick="return confirm('Yakin hapus fasilitas ini?')"
                                                                    title="Hapus">
                                                                    <i class="fas fa-trash"></i>
                                                                </button>
                                                            </form>
                                                        @else
                                                            <button class="btn btn-sm btn-secondary disabled"
                                                                title="Hanya Pembuat yang bisa edit" disabled>
                                                                <i class="fas fa-lock"></i>
                                                            </button>
                                                        @endif
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="card-footer clearfix">
                            {{-- Hanya Admin Fasilitas yang boleh nambah --}}
                            @if (Auth::user()->role == 'admin_fasilitas')
                                <a href="{{ route('admin.fasilitas.data.create') }}" class="btn btn-success">
                                    <i class="fas fa-plus-circle mr-1"></i> Tambah Fasilitas
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            // Inisialisasi tooltip
            $('[data-toggle="tooltip"]').tooltip();
        });
        // Tunggu 3 detik (3000ms), lalu fade out pelan-pelan
        window.setTimeout(function() {
            $(".alert").fadeTo(500, 0).slideUp(500, function() {
                $(this).remove();
            });
        }, 5000);
    </script>
@endsection
