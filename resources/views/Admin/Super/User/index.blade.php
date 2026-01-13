@extends('Admin.component')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Data Staff Admin</h1>
            </div>
            <div class="col-sm-6">
                <a href="{{ route('admin.super.users.create') }}" class="btn btn-primary float-right">
                    <i class="fas fa-plus mr-1"></i> Tambah Staff Baru
                </a>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">List Admin Terdaftar</h3>
                <div class="card-tools">
                    <form action="{{ route('admin.super.users.index') }}" method="GET">
                        <div class="input-group input-group-sm" style="width: 250px;">
                            <input type="text" name="search" class="form-control float-right" placeholder="Cari Nama/Email..." value="{{ request('search') }}">
                            <div class="input-group-append">
                                <button type="submit" class="btn btn-default"><i class="fas fa-search"></i></button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="card-body table-responsive p-0">
                <table class="table table-hover text-nowrap">
                    <thead>
                        <tr>
                            <th>Nama Staff</th>
                            <th>Role / Jabatan</th>
                            <th>Kontak</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $u)
                        <tr>
                            <td>
                                <span class="font-weight-bold">{{ $u->name }}</span><br>
                                <small class="text-muted">{{ $u->email }}</small>
                            </td>
                            <td>
                                @if($u->role == 'admin_fasilitas')
                                    <span class="badge badge-info">Admin Fasilitas</span>
                                @elseif($u->role == 'admin_pembayaran')
                                    <span class="badge badge-success">Admin Pembayaran</span>
                                @else
                                    <span class="badge badge-secondary">{{ $u->role }}</span>
                                @endif
                            </td>
                            <td>{{ $u->no_hp ?? '-' }}</td>
                            <td>
                                @if($u->is_active)
                                    <span class="badge badge-primary">Aktif</span>
                                @else
                                    <span class="badge badge-danger">Non-Aktif</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('admin.super.users.edit', $u->id) }}" class="btn btn-sm btn-warning" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.super.users.destroy', $u->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus staff ini? Log aktivitasnya akan tetap tersimpan.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center">Tidak ada data staff.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="card-footer clearfix">
                {{ $users->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </div>
</section>
@endsection
