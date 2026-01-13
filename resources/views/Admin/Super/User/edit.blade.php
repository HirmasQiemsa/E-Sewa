@extends('Admin.component')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6"><h1 class="m-0">Edit Data Staff</h1></div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-8">
                <div class="card card-warning">
                    <div class="card-header"><h3 class="card-title">Edit Biodata: {{ $user->name }}</h3></div>
                    <form action="{{ route('admin.super.users.update', $user->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="card-body">
                            <div class="form-group">
                                <label>Nama Lengkap</label>
                                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $user->name) }}" required>
                                @error('name') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>

                            {{-- TAMBAHAN: Field Username --}}
                            <div class="form-group">
                                <label>Username (Untuk Login)</label>
                                <input type="text" name="username" class="form-control @error('username') is-invalid @enderror" value="{{ old('username', $user->username) }}" required>
                                @error('username') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>

                            <div class="form-group">
                                <label>Email (Opsional)</label>
                                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}">
                                @error('email') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>

                            <div class="form-group">
                                <label>Password (Kosongkan jika tidak ingin mengubah)</label>
                                <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="Isi hanya jika ingin ganti password baru">
                                @error('password') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>

                            <div class="form-group">
                                <label>Jabatan / Role</label>
                                <select name="role" class="form-control @error('role') is-invalid @enderror">
                                    <option value="admin_fasilitas" {{ old('role', $user->role) == 'admin_fasilitas' ? 'selected' : '' }}>Admin Fasilitas</option>
                                    <option value="admin_pembayaran" {{ old('role', $user->role) == 'admin_pembayaran' ? 'selected' : '' }}>Admin Pembayaran</option>
                                </select>
                                @error('role') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>

                            <div class="form-group">
                                <label>Status Akun</label>
                                <div class="custom-control custom-switch">
                                    {{-- Menggunakan old() agar jika validasi gagal, status tidak tereset --}}
                                    <input type="checkbox" class="custom-control-input" id="isActive" name="is_active" value="1" {{ old('is_active', $user->is_active) ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="isActive">Akun Aktif (Bisa Login)</label>
                                </div>
                            </div>

                            <div class="form-group">
                                <label>No. HP</label>
                                <input type="number" name="no_hp" class="form-control" value="{{ old('no_hp', $user->no_hp) }}">
                            </div>
                        </div>
                        <div class="card-footer">
                            <a href="{{ route('admin.super.users.index') }}" class="btn btn-secondary">Kembali</a>
                            <button type="submit" class="btn btn-warning float-right">Update Data</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
