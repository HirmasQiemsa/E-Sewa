@extends('User.component')
@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Profile Pengguna</h1>
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
                <div class="col-md-6">
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fas fa-user mr-2"></i> Biodata Diri</h3>
                        </div>

                        <form action="{{ route('user.profile.update') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="card-body">
                                <div class="text-center mb-4">
                                    <div class="img-circle elevation-2 d-inline-block position-relative"
                                        style="width: 150px; height: 150px; overflow: hidden; border: 3px solid #007bff;">
                                        @if ($user->foto)
                                            <img src="{{ asset('storage/' . $user->foto) }}" alt="Profile Photo"
                                                class="img-fluid" style="object-fit: cover; width: 100%; height: 100%;">
                                        @else
                                            <img src="{{ asset('img/default-user.png') }}" alt="Profile Photo" class="img-fluid"
                                                style="object-fit: cover; width: 100%; height: 100%;">
                                        @endif
                                    </div>
                                    <div class="mt-3">
                                        <div class="custom-file text-left" style="max-width: 80%; margin: 0 auto;">
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
                                    <input type="text" name="name" class="form-control"
                                        value="{{ old('name', $user->name) }}" placeholder="Nama Lengkap" required>
                                    @error('name') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>

                                <div class="form-group">
                                    <label>No. Handphone (WhatsApp)</label>
                                    <input type="number" name="no_hp" class="form-control"
                                        value="{{ old('no_hp', $user->no_hp) }}" placeholder="08..." required>
                                    @error('no_hp') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>

                                <div class="form-group">
                                    <label>No. KTP (NIK)</label>
                                    <div class="input-group">
                                        <input type="password" name="no_ktp" class="form-control" id="no_ktp"
                                            value="{{ old('no_ktp', $user->no_ktp) }}" placeholder="16 digit NIK">
                                        <div class="input-group-append">
                                            <button class="btn btn-outline-secondary" type="button" id="toggleKTP">
                                                <i class="fas fa-eye" id="ktpIcon"></i>
                                            </button>
                                        </div>
                                    </div>
                                    @error('no_ktp') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>

                                <div class="form-group">
                                    <label>Alamat Lengkap</label>
                                    <textarea name="alamat" class="form-control" rows="3" placeholder="Jalan, RT/RW, Kelurahan...">{{ old('alamat', $user->alamat) }}</textarea>
                                    @error('alamat') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>
                            </div>

                            <div class="card-footer bg-white">
                                <button type="submit" class="btn btn-primary btn-block shadow-sm">
                                    <i class="fas fa-save mr-1"></i> Simpan Perubahan Profil
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card card-dark card-outline card-tabs">
                        <div class="card-header p-0 pt-1 border-bottom-0">
                            <ul class="nav nav-tabs" id="custom-tabs-three-tab" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" id="tab-akun" data-toggle="pill" href="#content-akun" role="tab">
                                        <i class="fas fa-user-cog mr-1"></i> Akun Login
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="tab-password" data-toggle="pill" href="#content-password" role="tab">
                                        <i class="fas fa-key mr-1"></i> Ganti Password
                                    </a>
                                </li>
                            </ul>
                        </div>

                        <div class="card-body">
                            <div class="tab-content" id="custom-tabs-three-tabContent">
                                <div class="tab-pane fade show active" id="content-akun" role="tabpanel">
                                    <form action="{{ route('user.profile.account') }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="form-group">
                                            <label>Username</label>
                                            <input type="text" name="username" class="form-control"
                                                value="{{ old('username', $user->username) }}" required>
                                            @error('username') <small class="text-danger">{{ $message }}</small> @enderror
                                        </div>

                                        <div class="form-group">
                                            <label>Email</label>
                                            <input type="email" name="email" class="form-control"
                                                value="{{ old('email', $user->email) }}" required>
                                            @error('email') <small class="text-danger">{{ $message }}</small> @enderror
                                        </div>

                                        <div class="alert alert-light border">
                                            <small><i class="fas fa-info-circle text-info"></i> Username digunakan untuk login. Pastikan unik.</small>
                                        </div>

                                        <button type="submit" class="btn btn-dark btn-block">
                                            Update Akun
                                        </button>
                                    </form>
                                </div>

                                <div class="tab-pane fade" id="content-password" role="tabpanel">
                                    <form action="{{ route('user.profile.password') }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="form-group">
                                            <label>Password Saat Ini</label>
                                            <div class="input-group">
                                                <input type="password" name="current_password" class="form-control" id="current_password" required>
                                                <div class="input-group-append">
                                                    <button class="btn btn-outline-secondary toggle-password" type="button" data-target="current_password">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                </div>
                                            </div>
                                            @error('current_password') <small class="text-danger">{{ $message }}</small> @enderror
                                        </div>

                                        <hr>

                                        <div class="form-group">
                                            <label>Password Baru</label>
                                            <div class="input-group">
                                                <input type="password" name="password" class="form-control" id="password" required>
                                                <div class="input-group-append">
                                                    <button class="btn btn-outline-secondary toggle-password" type="button" data-target="password">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                </div>
                                            </div>
                                            @error('password') <small class="text-danger">{{ $message }}</small> @enderror
                                        </div>

                                        <div class="form-group">
                                            <label>Konfirmasi Password Baru</label>
                                            <input type="password" name="password_confirmation" class="form-control" required>
                                        </div>

                                        <div class="alert alert-warning py-2">
                                            <small><i class="fas fa-lock"></i> Minimal 6 karakter.</small>
                                        </div>

                                        <button type="submit" class="btn btn-danger btn-block">
                                            Ubah Password
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // 1. Logic Nama File & Preview Foto
            const fileInput = document.getElementById('foto');
            if (fileInput) {
                fileInput.addEventListener('change', function() {
                    // Update Label
                    const fileName = this.value.split('\\').pop();
                    const label = this.nextElementSibling;
                    if (label) label.innerHTML = fileName || 'Ganti Foto';

                    // Preview Image
                    if (this.files && this.files[0]) {
                        const reader = new FileReader();
                        // Cari img di dalam card-body terdekat
                        const img = this.closest('.card-body').querySelector('.img-circle img');

                        reader.onload = function(e) {
                            if(img) img.src = e.target.result;
                        }
                        reader.readAsDataURL(this.files[0]);
                    }
                });
            }

            // 2. Toggle Visibility (Mata) - Generic
            // Bisa dipakai untuk KTP maupun Password
            document.querySelectorAll('#toggleKTP, .toggle-password').forEach(btn => {
                btn.addEventListener('click', function() {
                    // Cek apakah tombol ini untuk KTP atau Password
                    let targetInput;

                    if(this.id === 'toggleKTP') {
                        targetInput = document.getElementById('no_ktp');
                    } else {
                        const targetId = this.getAttribute('data-target');
                        targetInput = document.getElementById(targetId);
                    }

                    const icon = this.querySelector('i');

                    if (targetInput.type === 'password') {
                        targetInput.type = 'text';
                        icon.classList.replace('fa-eye', 'fa-eye-slash');
                    } else {
                        targetInput.type = 'password';
                        icon.classList.replace('fa-eye-slash', 'fa-eye');
                    }
                });
            });

            // 3. Tab Persistence (Agar saat reload tetap di tab terakhir)
            $('a[data-toggle="pill"]').on('shown.bs.tab', function (e) {
                localStorage.setItem('activeProfileTab', $(e.target).attr('href'));
            });

            const activeTab = localStorage.getItem('activeProfileTab');
            if(activeTab){
                $('.nav-tabs a[href="' + activeTab + '"]').tab('show');
            }
        });
    </script>
@endsection
