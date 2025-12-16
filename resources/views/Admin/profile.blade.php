@extends('Admin.component')

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Profile Saya</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item active">Profile</li>
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
                    <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    <i class="fas fa-exclamation-circle mr-2"></i>{{ session('error') }}
                </div>
            @endif

            <div class="row">
                <div class="col-md-6">
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <h3 class="card-title"><b>Informasi Pribadi</b></h3>
                        </div>

                        <form action="{{ route('admin.profile.update') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="card-body box-profile">
                                <div class="text-center mb-4">
                                    <div class="img-circle elevation-2 d-inline-block position-relative"
                                        style="width: 150px; height: 150px; overflow: hidden; border: 3px solid #007bff;">

                                        @if ($user->foto && Storage::disk('public')->exists($user->foto))
                                            <img src="{{ asset('storage/' . $user->foto) }}"
                                                 alt="User profile picture"
                                                 class="img-fluid"
                                                 style="object-fit: cover; width: 100%; height: 100%;">
                                        @else
                                            <img src="{{ asset('img/default-user.png') }}"
                                                 alt="User profile picture"
                                                 class="img-fluid"
                                                 style="object-fit: cover; width: 100%; height: 100%;">
                                        @endif
                                    </div>

                                    <div class="mt-3">
                                        <div class="custom-file text-left" style="max-width: 250px; margin: 0 auto;">
                                            <input type="file" name="foto" class="custom-file-input" id="foto" accept="image/*">
                                            <label class="custom-file-label" for="foto">Ganti Foto</label>
                                        </div>
                                        @error('foto')
                                            <small class="text-danger d-block mt-1">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label>Nama Lengkap</label>
                                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                           value="{{ old('name', $user->name) }}">
                                    @error('name') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>

                                <div class="form-group">
                                    <label>No. Handphone</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                        </div>
                                        <input type="number" name="no_hp" class="form-control @error('no_hp') is-invalid @enderror"
                                               value="{{ old('no_hp', $user->no_hp) }}">
                                    </div>
                                    @error('no_hp') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>

                                <div class="form-group">
                                    <label>Alamat</label>
                                    <textarea name="alamat" class="form-control @error('alamat') is-invalid @enderror"
                                              rows="2">{{ old('alamat', $user->alamat) }}</textarea>
                                    @error('alamat') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>

                                <div class="text-center mt-3">
                                    <span class="badge badge-info p-2" style="font-size: 14px;">
                                        Role: {{ str_replace('_', ' ', strtoupper($user->role)) }}
                                    </span>
                                </div>
                            </div>
                            </div>
                </div>

                <div class="col-md-6">
                    <div class="card card-dark">
                        <div class="card-header">
                            <h3 class="card-title"><b>Keamanan Akun</b></h3>
                        </div>
                        <div class="card-body">

                            <div class="form-group">
                                <label>Email / Username Login</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                    </div>
                                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                                           value="{{ old('email', $user->email) }}"
                                           {{ $user->role !== 'super_admin' ? 'readonly' : '' }}>
                                </div>
                                @if($user->role !== 'super_admin')
                                    <small class="text-muted"><i class="fas fa-lock mr-1"></i>Email hanya dapat diubah oleh Kepala Dinas.</small>
                                @endif
                                @error('email') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>

                            <hr>
                            <h5 class="text-muted mb-3">Ganti Password</h5>
                            <div class="alert alert-light border">
                                <small><i class="fas fa-info-circle mr-1"></i> Kosongkan jika tidak ingin mengubah password.</small>
                            </div>

                            <div class="form-group">
                                <label>Password Baru</label>
                                <div class="input-group">
                                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                                           id="password" placeholder="Minimal 6 karakter">
                                    <div class="input-group-append">
                                        <button class="btn btn-outline-secondary toggle-password" type="button" data-target="password">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                </div>
                                @error('password') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>

                            <div class="form-group">
                                <label>Konfirmasi Password</label>
                                <div class="input-group">
                                    <input type="password" name="password_confirmation" class="form-control"
                                           id="password_confirmation" placeholder="Ulangi password baru">
                                    <div class="input-group-append">
                                        <button class="btn btn-outline-secondary toggle-password" type="button" data-target="password_confirmation">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary float-right">
                                <i class="fas fa-save mr-1"></i> Simpan Perubahan
                            </button>
                        </div>
                        </form> </div>
                </div>
            </div>
        </div>
    </section>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // 1. Image Preview
            const fileInput = document.getElementById('foto');
            if (fileInput) {
                fileInput.addEventListener('change', function() {
                    const fileName = this.value.split('\\').pop();
                    this.nextElementSibling.innerHTML = fileName || 'Pilih Foto';

                    if (this.files && this.files[0]) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            // Cari gambar di dalam card-body terdekat
                            document.querySelector('.img-circle img').src = e.target.result;
                        }
                        reader.readAsDataURL(this.files[0]);
                    }
                });
            }

            // 2. Toggle Password Visibility
            document.querySelectorAll('.toggle-password').forEach(button => {
                button.addEventListener('click', function() {
                    const targetId = this.getAttribute('data-target');
                    const input = document.getElementById(targetId);
                    const icon = this.querySelector('i');

                    if (input.type === 'password') {
                        input.type = 'text';
                        icon.classList.replace('fa-eye', 'fa-eye-slash');
                    } else {
                        input.type = 'password';
                        icon.classList.replace('fa-eye-slash', 'fa-eye');
                    }
                });
            });
        });
    </script>
@endsection
