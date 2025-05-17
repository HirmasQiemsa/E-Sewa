@extends('User.user')
@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Profile Pengguna</h1>
                </div>
            </div>
        </div>
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <!-- Notification area -->
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
                <!-- Profile User Information Form -->
                <div class="col-md-6">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title"><b>Informasi Profile User</b></h3>
                        </div>
                        <!-- form start -->
                        <form action="{{ route('user.update') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="card-body">
                                <div class="text-center mb-4">
                                    <div class="img-circle elevation-2 d-inline-block position-relative"
                                        style="width: 150px; height: 150px; overflow: hidden;">
                                        @if ($user->foto)
                                            <img src="{{ asset('storage/' . $user->foto) }}" alt="Profile Photo"
                                                class="img-fluid" style="object-fit: cover; width: 100%; height: 100%;">
                                        @else
                                            <img src="{{ asset('default-user.png') }}" alt="Profile Photo" class="img-fluid"
                                                style="object-fit: cover; width: 100%; height: 100%;">
                                        @endif
                                    </div>
                                    <div class="mt-2">
                                        <div class="custom-file" style="max-width: 250px; margin: 0 auto;">
                                            <input type="file" name="foto" class="custom-file-input" id="foto"
                                                accept="image/*">
                                            <label class="custom-file-label" for="foto">Pilih Foto</label>
                                        </div>
                                        @error('foto')
                                            <small class="text-danger d-block mt-1">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="name">Nama Lengkap</label>
                                    <input type="text" name="name" class="form-control" id="name"
                                        value="{{ $user->name }}" placeholder="Nama Lengkap">
                                    @error('name')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="no_hp">No. Handphone</label>
                                    <input type="text" name="no_hp" class="form-control" id="no_hp"
                                        value="{{ $user->no_hp }}" placeholder="Nomor Handphone">
                                    @error('no_hp')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="no_ktp">No. KTP</label>
                                    <div class="input-group">
                                        <input type="password" name="no_ktp" class="form-control" id="no_ktp"
                                            value="{{ $user->no_ktp }}" placeholder="Nomor KTP">
                                        <div class="input-group-append">
                                            <button class="btn btn-outline-secondary" type="button" id="toggleKTP">
                                                <i class="fas fa-eye" id="ktpIcon"></i>
                                            </button>
                                        </div>
                                    </div>
                                    @error('no_ktp')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="alamat">Alamat</label>
                                    <textarea name="alamat" class="form-control" id="alamat" rows="3" placeholder="Alamat lengkap">{{ $user->alamat }}</textarea>
                                    @error('alamat')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>

                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary btn-block">Perbarui Informasi Profil</button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Account Change Form (Combined) -->
                <div class="col-md-6">
                    <div class="card card-dark">
                        <div class="card-header">
                            <h3 class="card-title"><b>Pengaturan Akun :</b></h3>
                            <div class="card-tools">
                                <ul class="nav nav-pills ml-auto p-0 ">
                                    <li class="nav-item mx-1">
                                        <a class="nav-link active p-1 px-3" href="#account" data-toggle="tab"><b>Informasi Akun</b></a>
                                    </li>
                                    <li class="nav-item mx-1">
                                        <a class="nav-link p-1" href="#password" data-toggle="tab"><b>Password</b></a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="tab-content p-0">
                                <!-- Account Information Tab -->
                                <div class="tab-pane active" id="account">
                                    <form action="{{ route('user.update-account') }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="form-group">
                                            <label for="username">Username</label>
                                            <input type="text" name="username" class="form-control" id="username"
                                                value="{{ $user->username }}" placeholder="Username">
                                            @error('username')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label for="email">Email</label>
                                            <input type="email" name="email" class="form-control" id="email"
                                                value="{{ $user->email }}" placeholder="Email">
                                            @error('email')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>

                                        <div class="alert alert-info">
                                            <i class="fas fa-info-circle mr-2"></i>
                                            Jika username yang anda buat tidak dapat digunakan, kami sarankan untuk
                                            kombinasikan dengan angka.
                                        </div>

                                        <button type="submit" class="btn btn-dark btn-block mt-4">Perbarui Informasi
                                            Akun</button>
                                    </form>
                                </div>

                                <!-- Password Tab -->
                                <div class="tab-pane" id="password">
                                    <form action="{{ route('user.update-password') }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="form-group">
                                            <label for="current_password">Password Saat Ini</label>
                                            <div class="input-group">
                                                <input type="password" name="current_password" class="form-control"
                                                    id="current_password" placeholder="Password saat ini">
                                                <div class="input-group-append">
                                                    <button class="btn btn-outline-secondary toggle-password"
                                                        type="button" data-target="current_password">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                </div>
                                            </div>
                                            @error('current_password')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label for="password">Password Baru</label>
                                            <div class="input-group">
                                                <input type="password" name="password" class="form-control"
                                                    id="password" placeholder="Password baru">
                                                <div class="input-group-append">
                                                    <button class="btn btn-outline-secondary toggle-password"
                                                        type="button" data-target="password">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                </div>
                                            </div>
                                            @error('password')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label for="password_confirmation">Konfirmasi Password Baru</label>
                                            <div class="input-group">
                                                <input type="password" name="password_confirmation" class="form-control"
                                                    id="password_confirmation" placeholder="Konfirmasi password baru">
                                                <div class="input-group-append">
                                                    <button class="btn btn-outline-secondary toggle-password"
                                                        type="button" data-target="password_confirmation">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="alert alert-warning">
                                            <i class="fas fa-exclamation-circle mr-2"></i>
                                            Password minimal 6 karakter.
                                        </div>

                                        <button type="submit" class="btn btn-dark btn-block mt-4">Ubah
                                            Password</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- /.content -->

    <!-- JavaScript for image preview and toggle visibility -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Custom file input label update
            const fileInput = document.getElementById('foto');
            if (fileInput) {
                fileInput.addEventListener('change', function() {
                    // Update the file label with selected filename
                    const fileName = this.value.split('\\').pop();
                    const label = this.nextElementSibling;
                    if (label) {
                        label.innerHTML = fileName || 'Pilih Foto';
                    }

                    // Preview the selected image
                    if (this.files && this.files[0]) {
                        const reader = new FileReader();
                        const previewContainer = this.closest('.card-body').querySelector('.img-circle');

                        reader.onload = function(e) {
                            const img = previewContainer.querySelector('img');
                            img.src = e.target.result;
                        }

                        reader.readAsDataURL(this.files[0]);
                    }
                });
            }

            // Toggle KTP visibility
            const toggleKTP = document.getElementById('toggleKTP');
            if (toggleKTP) {
                toggleKTP.addEventListener('click', function() {
                    const ktpInput = document.getElementById('no_ktp');
                    const ktpIcon = document.getElementById('ktpIcon');

                    if (ktpInput.type === 'password') {
                        ktpInput.type = 'text';
                        ktpIcon.classList.remove('fa-eye');
                        ktpIcon.classList.add('fa-eye-slash');
                    } else {
                        ktpInput.type = 'password';
                        ktpIcon.classList.remove('fa-eye-slash');
                        ktpIcon.classList.add('fa-eye');
                    }
                });
            }

            // Toggle password fields visibility
            const toggleButtons = document.querySelectorAll('.toggle-password');
            toggleButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const targetId = this.getAttribute('data-target');
                    const inputField = document.getElementById(targetId);
                    const icon = this.querySelector('i');

                    if (inputField.type === 'password') {
                        inputField.type = 'text';
                        icon.classList.remove('fa-eye');
                        icon.classList.add('fa-eye-slash');
                    } else {
                        inputField.type = 'password';
                        icon.classList.remove('fa-eye-slash');
                        icon.classList.add('fa-eye');
                    }
                });
            });

            // Switch tabs based on URL hash if present
            const hash = window.location.hash;
            if (hash && (hash === '#account' || hash === '#password')) {
                $(`a[href="${hash}"]`).tab('show');
            }

            // Update URL hash when tab changes
            $('a[data-toggle="tab"]').on('shown.bs.tab', function(e) {
                const targetHash = $(e.target).attr('href');
                if (history.pushState) {
                    history.pushState(null, null, targetHash);
                } else {
                    location.hash = targetHash;
                }
            });
        });
    </script>
@endsection
