<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Daftar Akun | E-Sewa DISPORA</title>

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/icheck-bootstrap@3.0.1/icheck-bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">

    <style>
        /* Background seragam dengan Login */
        body.register-page {
            background: url('{{ asset("img/body-login.png") }}') no-repeat center center fixed;
            background-size: cover;
            position: relative;
        }

        /* Card effect */
        .register-box .card {
            background: rgba(255, 255, 255, 0.95);
            box-shadow: 0 0 20px rgba(171, 19, 19, 0.2);
            border-radius: 10px;
        }

        /* Lebarkan box registrasi karena inputnya banyak */
        .register-box {
            width: 400px;
        }

        @media (max-width: 576px) {
            .register-box {
                width: 90%;
                margin-top: 20px;
            }
        }
    </style>
</head>

<body class="hold-transition register-page">

    <div class="register-box">
        <div class="card card-outline card-danger">
            <div class="card-header text-center">
                <a href="{{ url('/') }}" class="h2 font-weight-bold text-dark">E-SEWA FASILITAS</a>
            </div>

            <div class="card-body">
                <p class="login-box-msg text-muted">Daftar akun baru untuk mulai booking</p>

                <form action="{{ route('register-proses') }}" method="post">
                    @csrf

                    <div class="input-group mb-3">
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                               placeholder="Nama Lengkap" value="{{ old('name') }}" required autofocus>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-user"></span>
                            </div>
                        </div>
                        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="input-group mb-3">
                        <input type="text" name="username" class="form-control @error('username') is-invalid @enderror"
                               placeholder="Username (Tanpa Spasi)" value="{{ old('username') }}" required>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-at"></span>
                            </div>
                        </div>
                        @error('username') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="input-group mb-3">
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                               placeholder="Email Aktif" value="{{ old('email') }}" required>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-envelope"></span>
                            </div>
                        </div>
                        @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="input-group mb-3">
                        <input type="number" name="no_hp" class="form-control @error('no_hp') is-invalid @enderror"
                               placeholder="Nomor WhatsApp" value="{{ old('no_hp') }}" required>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-phone"></span>
                            </div>
                        </div>
                        @error('no_hp') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="form-group mb-3">
                        <textarea name="alamat" class="form-control @error('alamat') is-invalid @enderror"
                                  rows="2" placeholder="Alamat Lengkap" required>{{ old('alamat') }}</textarea>
                        @error('alamat') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="input-group mb-3">
                        <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror"
                               placeholder="Password (Min. 6 Karakter)" required>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                        @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="input-group mb-3">
                        <input type="password" name="password_confirmation" id="password_confirmation" class="form-control"
                               placeholder="Ulangi Password" required>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-check"></span>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-8">
                            <div class="icheck-danger">
                                <input type="checkbox" id="agreeTerms" name="terms" value="agree" required>
                                <label for="agreeTerms">
                                    Saya setuju <a href="#">ketentuan</a>
                                </label>
                            </div>
                        </div>
                        <div class="col-4">
                            <button type="submit" class="btn btn-danger btn-block font-weight-bold">Daftar</button>
                        </div>
                    </div>
                </form>

                <div class="social-auth-links text-center mt-3">
                    <hr>
                    <p class="mb-0">Sudah punya akun?</p>
                    <a href="{{ route('login') }}" class="text-center font-weight-bold text-danger">Login disini</a>
                </div>
            </div>
            </div>
        </div>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // Validasi Password Match Real-time
        $('#password, #password_confirmation').on('keyup', function () {
            if ($('#password').val() == $('#password_confirmation').val()) {
                $('#password_confirmation').removeClass('is-invalid').addClass('is-valid');
            } else {
                $('#password_confirmation').removeClass('is-valid').addClass('is-invalid');
            }
        });

        // Tampilkan Error dari Session (Controller)
        @if ($message = Session::get('error'))
            Swal.fire({
                icon: 'error',
                title: 'Gagal Daftar',
                text: '{{ $message }}',
                confirmButtonColor: '#d33',
            });
        @endif
    </script>
</body>
</html>
