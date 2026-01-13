@extends('Admin.component')

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Tambah Staff Baru</h1>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-8">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Form Biodata Staff</h3>
                        </div>
                        <form action="{{ route('admin.super.users.store') }}" method="POST">
                            @csrf
                            <div class="card-body">
                                <div class="form-group">
                                    <label>Nama Lengkap</label>
                                    <input type="text" name="name"
                                        class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}"
                                        placeholder="Masukkan nama..." required>
                                    @error('name')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                {{-- PERUBAHAN UTAMA: FIELD USERNAME --}}
                                <div class="form-group">
                                    <label>Username (Untuk Login)</label>
                                    <input type="text" name="username"
                                        class="form-control @error('username') is-invalid @enderror"
                                        value="{{ old('username') }}" placeholder="Contoh: staff_lapangan" required>
                                    @error('username')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label>Email </label>
                                    <input type="email" name="email"
                                        class="form-control @error('email') is-invalid @enderror"
                                        value="{{ old('email') }}" placeholder="email@dispora.com">
                                    @error('email')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label>Password</label>
                                    <input type="password" name="password"
                                        class="form-control @error('password') is-invalid @enderror"
                                        placeholder="Min. 6 Karakter" required>
                                    @error('password')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label>Jabatan / Role</label>
                                    <select name="role" class="form-control @error('role') is-invalid @enderror"
                                        required>
                                        <option value="">-- Pilih Jabatan --</option>
                                        <option value="admin_fasilitas"
                                            {{ old('role') == 'admin_fasilitas' ? 'selected' : '' }}>Admin Fasilitas (Kelola
                                            Gedung/Lapangan)</option>
                                        <option value="admin_pembayaran"
                                            {{ old('role') == 'admin_pembayaran' ? 'selected' : '' }}>Admin Pembayaran
                                            (Verifikasi Keuangan)</option>
                                    </select>
                                    @error('role')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label>No. HP (Opsional)</label>
                                    <input type="number" name="no_hp" class="form-control" value="{{ old('no_hp') }}"
                                        placeholder="08...">
                                </div>
                            </div>
                            <div class="card-footer">
                                <a href="{{ route('admin.super.users.index') }}" class="btn btn-secondary">Kembali</a>
                                <button type="submit" class="btn btn-primary float-right">Simpan Staff</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
