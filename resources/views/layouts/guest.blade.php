<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name'))</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('lte/plugins/fontawesome-free/css/all.min.css') }}">
    <!-- icheck bootstrap -->
    <link rel="stylesheet" href="{{ asset('lte/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('lte/dist/css/adminlte.min.css') }}">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /* Background Styling untuk Auth Pages */
        .auth-background {
            min-height: 100vh;
            background: linear-gradient(135deg,
                rgba(9, 9, 9, 0.9) 0%,
                rgba(80, 80, 80, 0.9) 100%),
                url('{{ asset("img/sports-facilities-bg.jpg") }}') center/cover no-repeat;

            /* Fallback background tanpa gambar */
            background: linear-gradient(135deg,
                rgba(220, 53, 69, 0.9) 0%);

            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Alternative background patterns jika tidak ada gambar */
        .auth-background::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-image:
                radial-gradient(circle at 20% 80%, rgba(255,255,255,0.1) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(255,255,255,0.1) 0%, transparent 50%),
                radial-gradient(circle at 40% 40%, rgba(255,255,255,0.05) 0%, transparent 50%);
            z-index: 1;
        }

        /* Container untuk konten auth */
        .auth-container {
            position: relative;
            z-index: 2;
            width: 100%;
            max-width: 480px;
            margin: 0 auto;
            padding: 20px;
        }

        /* Card styling untuk form */
        .auth-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            overflow: hidden;
        }

        /* Header card dengan logo */
        .auth-header {
            background: linear-gradient(45deg, #dc3545, #343434);
            color: white;
            text-align: center;
            padding: 30px 20px;
            position: relative;
        }

        .auth-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="pattern" x="0" y="0" width="20" height="20" patternUnits="userSpaceOnUse"><circle cx="10" cy="10" r="1" fill="rgba(255,255,255,0.1)"/></pattern></defs><rect width="100" height="100" fill="url(%23pattern)"/></svg>');
            opacity: 0.3;
        }

        .auth-logo {
            width: 80px;
            height: 80px;
            margin: 0 auto 15px;
            display: block;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.2);
            padding: 10px;
            position: relative;
            z-index: 1;
        }

        .auth-title {
            font-size: 1.5rem;
            font-weight: bold;
            margin: 0;
            position: relative;
            z-index: 1;
        }

        .auth-subtitle {
            font-size: 0.9rem;
            margin: 5px 0 0;
            opacity: 0.9;
            position: relative;
            z-index: 1;
        }

        /* Body card untuk form */
        .auth-body {
            padding: 30px;
        }

        /* Form enhancements */
        .form-group {
            margin-bottom: 20px;
        }

        .form-control {
            border-radius: 8px;
            border: 2px solid #e9ecef;
            padding: 12px 15px;
            font-size: 14px;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: #007bff;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        }

        .btn-auth {
            border-radius: 8px;
            padding: 12px 30px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: all 0.3s ease;
        }

        .btn-auth:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }

        /* Footer dengan link */
        .auth-footer {
            text-align: center;
            padding: 20px 30px;
            background: rgba(248, 249, 250, 0.8);
            border-top: 1px solid rgba(0, 0, 0, 0.05);
        }

        .auth-link {
            color: #007bff;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s ease;
        }

        .auth-link:hover {
            color: #0056b3;
            text-decoration: none;
        }

        /* Preloader enhancement */
        .preloader {
            background: linear-gradient(135deg,
                rgba(220, 53, 69, 0.95) 0%,
                rgba(0, 123, 255, 0.95) 100%);
        }

        .preloader h2 {
            color: white;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
        }

        /* Sports icons decoration */
        .sports-decoration {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            pointer-events: none;
            overflow: hidden;
            z-index: 1;
        }

        .sports-icon {
            position: absolute;
            color: rgba(255, 255, 255, 0.1);
            font-size: 3rem;
            animation: float 6s ease-in-out infinite;
        }

        .sports-icon:nth-child(1) {
            top: 10%;
            left: 10%;
            animation-delay: 0s;
        }

        .sports-icon:nth-child(2) {
            top: 20%;
            right: 10%;
            animation-delay: 2s;
        }

        .sports-icon:nth-child(3) {
            bottom: 20%;
            left: 15%;
            animation-delay: 4s;
        }

        .sports-icon:nth-child(4) {
            bottom: 15%;
            right: 20%;
            animation-delay: 1s;
        }

        @keyframes float {
            0%, 100% {
                transform: translateY(0px);
            }
            50% {
                transform: translateY(-20px);
            }
        }

        /* Responsive design */
        @media (max-width: 768px) {
            .auth-container {
                padding: 10px;
                max-width: 100%;
            }

            .auth-header {
                padding: 20px 15px;
            }

            .auth-body {
                padding: 20px;
            }

            .auth-footer {
                padding: 15px 20px;
            }

            .sports-icon {
                font-size: 2rem;
            }
        }

        /* Loading animation untuk button */
        .btn-loading {
            position: relative;
            pointer-events: none;
        }

        .btn-loading::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 20px;
            height: 20px;
            margin: -10px 0 0 -10px;
            border: 2px solid transparent;
            border-top: 2px solid #fff;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
</head>

<body class="auth-background">
    <!-- Preloader -->
    <div class="preloader flex-column justify-content-center align-items-center">
        <img class="animation__shake" src="{{ asset('img/logo.png') }}" alt="AdminLTELogo" height="180"
            width="150">
        <h2 class="mt-4"><b>DISPORA SEMARANG</b></h2>
    </div>

    <!-- Sports Icons Decoration -->
    <div class="sports-decoration">
        <i class="fas fa-futbol sports-icon"></i>
        <i class="fas fa-volleyball-ball sports-icon"></i>
        <i class="fas fa-table-tennis sports-icon"></i>
        <i class="fas fa-basketball-ball sports-icon"></i>
    </div>

    <!-- Main Content Container -->
    <div class="auth-container">
        <div class="auth-card">
            <!-- Header dengan Logo -->
            <div class="auth-header">
                <img src="{{ asset('img/logo.png') }}" alt="DISPORA Logo" class="auth-logo">
                <h1 class="auth-title">DISPORA SEMARANG</h1>
                <p class="auth-subtitle">Sistem Penyewaan Fasilitas Olahraga</p>
            </div>

            <!-- Form Content -->
            <div class="auth-body">
                {{ $slot }}
            </div>

            <!-- Footer -->
            <div class="auth-footer">
                <p class="mb-0">
                    <small class="text-muted">© {{ date('Y') }} DISPORA Kota Semarang. Semua hak dilindungi.</small>
                </p>
            </div>
        </div>
    </div>

    <!-- jQuery -->
    <script src="{{ asset('lte/plugins/jquery/jquery.min.js') }}"></script>
    <!-- Bootstrap 4 -->
    <script src="{{ asset('lte/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <!-- AdminLTE App -->
    <script src="{{ asset('lte/dist/js/adminlte.min.js') }}"></script>
    <!-- SweetAlert2 -->
    <script src="{{ asset('lte/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Form Enhancement Script -->
    <script>
        $(document).ready(function() {
            // Form submission enhancement
            $('form').on('submit', function() {
                const submitBtn = $(this).find('button[type="submit"]');
                submitBtn.addClass('btn-loading');
                submitBtn.prop('disabled', true);
            });

            // Form validation enhancement
            $('.form-control').on('blur', function() {
                if ($(this).val().trim() === '' && $(this).prop('required')) {
                    $(this).addClass('is-invalid');
                } else {
                    $(this).removeClass('is-invalid').addClass('is-valid');
                }
            });

            // Remove validation classes on focus
            $('.form-control').on('focus', function() {
                $(this).removeClass('is-invalid is-valid');
            });
        });
    </script>

    @if ($message = Session::get('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: '{{ $message }}',
                confirmButtonColor: '#007bff'
            });
        </script>
    @endif

    @if ($message = Session::get('failed'))
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: '{{ $message }}',
                confirmButtonColor: '#dc3545'
            });
        </script>
    @endif

    @if ($errors->any())
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Terjadi Kesalahan!',
                html: '@foreach ($errors->all() as $error)<p>{{ $error }}</p>@endforeach',
                confirmButtonColor: '#dc3545'
            });
        </script>
    @endif
</body>

</html>
