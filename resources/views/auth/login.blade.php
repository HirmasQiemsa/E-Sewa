<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login | E-Sewa DISPORA</title>

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/icheck-bootstrap@3.0.1/icheck-bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">

    <style>
        /* Custom Background agar gambar full dan responsif */
        body.login-page {
            background: url('{{ asset("img/body-login.png") }}') no-repeat center center fixed;
            background-size: cover;
            /* Overlay gelap agar tulisan terbaca */
            position: relative;
        }

        /* Efek kaca (Glassmorphism) sedikit pada card */
        .login-box .card {
            background: rgba(255, 255, 255, 0.95);
            box-shadow: 0 0 20px rgba(0,0,0,0.2);
            border-radius: 10px;
        }

        .login-logo a b {
            color: #c0392b !important; /* Warna merah Dispora */
        }
    </style>
</head>

<body class="hold-transition login-page">

    <div class="preloader flex-column justify-content-center align-items-center bg-white">
        <img class="animation__shake" src="{{ asset('img/logo.png') }}" alt="LogoDispora" height="120" width="100">
        <h3 class="mt-3 text-dark font-weight-bold">DISPORA SEMARANG</h3>
    </div>

    <div class="login-box">
        <div class="card card-outline card-danger">
            <div class="card-header text-center py-4">
                <img src="{{ asset('img/logo.png') }}" alt="Logo" style="height: 80px; margin-bottom: 10px;">
                <br>
                <a href="{{ url('/') }}" class="h3 font-weight-bold text-dark">E-SEWA FASILITAS</a>
            </div>

            <div class="card-body">
                <p class="login-box-msg text-muted">Silakan login untuk memulai sesi</p>

                <form action="{{ route('login-proses') }}" method="post">
                    @csrf

                    <div class="input-group mb-3">
                        <input type="text" name="username" class="form-control @error('username') is-invalid @enderror"
                               placeholder="Username" value="{{ old('username') }}" required autofocus>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-user"></span>
                            </div>
                        </div>
                        @error('username')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="input-group mb-3">
                        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                               placeholder="Password" required>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-8">
                            <div class="icheck-danger">
                                <input type="checkbox" id="remember">
                                <label for="remember">
                                    Ingat Saya
                                </label>
                            </div>
                        </div>
                        <div class="col-4">
                            <button type="submit" class="btn btn-danger btn-block font-weight-bold">Masuk</button>
                        </div>
                    </div>
                </form>

                <div class="social-auth-links text-center mt-4">
                    <hr>
                    <p class="mb-1">Belum punya akun?</p>
                    <a href="{{ route('register') }}" class="btn btn-outline-danger btn-block">
                        <i class="fas fa-user-plus mr-2"></i> Registrasi Masyarakat
                    </a>
                </div>
            </div>
            </div>
        </div>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @if ($message = Session::get('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: '{{ $message }}',
                timer: 3000,
                showConfirmButton: false
            });
        </script>
    @endif

    @if ($message = Session::get('error'))
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Gagal Login',
                text: '{{ $message }}',
                confirmButtonColor: '#d33',
            });
        </script>
    @endif
</body>
</html>
