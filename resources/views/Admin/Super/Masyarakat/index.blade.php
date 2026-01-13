@extends('Admin.component')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6"><h1 class="m-0">Data Masyarakat</h1></div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Masyarakat Terdaftar</h3>
                <div class="card-tools">
                    <form action="{{ route('admin.super.masyarakat.index') }}" method="GET">
                        <div class="input-group input-group-sm" style="width: 250px;">
                            <input type="text" name="search" class="form-control float-right" placeholder="Cari Nama/KTP..." value="{{ request('search') }}">
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
                            <th>Nama Lengkap</th>
                            <th>Kontak (Email/HP)</th>
                            <th>No. KTP (NIK)</th>
                            <th>Status Akun</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                        <tr>
                            <td>
                                <span class="font-weight-bold">{{ $user->name }}</span><br>
                                <small class="text-muted">Join: {{ $user->created_at->format('d M Y') }}</small>
                            </td>
                            <td>
                                <i class="fas fa-envelope mr-1 text-muted"></i> {{ $user->email }}<br>
                                <i class="fas fa-phone mr-1 text-muted"></i> {{ $user->no_hp ?? '-' }}
                            </td>
                            <td>{{ $user->no_ktp ?? '-' }}</td>
                            <td>
                                @if($user->is_locked ?? true)
                                    <span class="badge badge-danger">Dibekukan (Locked)</span>
                                @else
                                    <span class="badge badge-success">Aktif</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('admin.super.masyarakat.show', $user->id) }}" class="btn btn-sm btn-info" title="Detail">
                                    <i class="fas fa-eye"></i>
                                </a>
                                {{-- Tombol Delete (Optional, jika diperlukan) --}}
                                <form action="{{ route('admin.super.masyarakat.destroy', $user->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus data masyarakat ini? History transaksinya mungkin akan error jika tidak soft delete.')">
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
                            <td colspan="5" class="text-center">Tidak ada data masyarakat.</td>
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
