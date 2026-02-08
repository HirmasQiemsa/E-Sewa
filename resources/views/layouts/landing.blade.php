<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>E-Sewa Dispora Semarang</title>

    {{-- Global CSS --}}
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet" href="{{ asset('lte/plugins/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('lte/dist/css/adminlte.min.css') }}">
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <link rel="stylesheet" href="{{ asset('lte/plugins/overlayScrollbars/css/OverlayScrollbars.min.css') }}">
    <link rel="stylesheet" href="{{ asset('lte/plugins/daterangepicker/daterangepicker.css') }}">
    <link rel="stylesheet" href="{{ asset('lte/plugins/sweetalert2/sweetalert2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('lte/plugins/toastr/toastr.min.css') }}">
    <link rel="icon" href="{{ asset('img/logo.png') }}" type="image/png">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="https://npmcdn.com/flatpickr/dist/themes/material_blue.css">
    <link rel="stylesheet" href="{{ asset('lte/plugins/bootstrap/css/bootstrap.min.css') }}">



    {{-- CSS CUSTOM --}}
    <style>
        html {
            scroll-padding-top: 90px;
            scroll-behavior: smooth;
        }

        /* 1. UMUM & PRELOADER */
        body {
            overflow-x: hidden;
        }

        .preloader.initial-only {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 9999;
            background: #fff;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            transition: opacity 0.5s, visibility 0.5s;
        }

        .preloader.initial-only.fade-out {
            opacity: 0;
            visibility: hidden;
        }

        .preloader.initial-only.hidden {
            display: none !important;
        }

        .animation__gentle {
            animation: gentle-pulse 2s ease-in-out infinite;
        }

        .fade-in-text {
            animation: fadeIn 1.5s ease forwards;
            opacity: 0;
        }

        @keyframes gentle-pulse {
            0% {
                transform: scale(1);
                opacity: 1;
            }

            50% {
                transform: scale(1.05);
                opacity: 0.8;
            }

            100% {
                transform: scale(1);
                opacity: 1;
            }
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* 2. NAVBAR STYLING */
        /* Base Navbar */
        .navbar-guest {
            background-color: #fff;
            transition: all 0.3s ease-in-out;
        }

        .navbar-auth {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1040;
            background-color: transparent;
            border-bottom: none;
            transition: all 0.4s ease;
            padding: 15px 0;
        }

        /* Scrolled State (Saat digulir) */
        .navbar-auth.scrolled {
            background-color: rgba(0, 0, 0, 0.9);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            padding: 5px 0;
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1);
        }

        /* Logo Perisai */
        .navbar-auth .brand-image {
            width: 45px;
            height: 45px;
            object-fit: contain;
            background: transparent;
            margin-right: 10px;
            transition: all 0.3s;
        }

        .navbar-auth.scrolled .brand-image {
            width: 35px;
            height: 35px;
        }

        /* Teks Brand */
        .brand-text-wrapper {
            line-height: 1.2;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .brand-title {
            font-size: 1.1rem;
            font-weight: 700;
            letter-spacing: 0.5px;
            color: #fff;
            transition: color 0.3s;
        }

        .brand-subtitle {
            font-size: 0.75rem;
            font-weight: 300;
            opacity: 0.9;
            color: rgba(255, 255, 255, 0.8);
            transition: color 0.3s;
        }

        /* Links */
        .navbar-auth .nav-link {
            color: rgba(255, 255, 255, 0.8) !important;
            font-weight: 500;
            position: relative;
            margin: 0 10px;
            transition: color 0.3s;
        }

        .navbar-auth .nav-link:hover,
        .navbar-auth .nav-link.active {
            color: #fff !important;
        }

        .navbar-auth .nav-link::after {
            content: '';
            position: absolute;
            width: 0;
            height: 2px;
            bottom: 0;
            left: 50%;
            background-color: #dc3545;
            transition: 0.3s;
            transform: translateX(-50%);
        }

        .navbar-auth .nav-link:hover::after,
        .navbar-auth .nav-link.active::after {
            width: 100%;
        }

        /* Mobile Adjustments */
        .mobile-brand-scroll {
            text-decoration: none !important;
        }

        /* Profile Dropdown */
        .user-panel-trigger {
            padding: 5px;
            border-radius: 50px;
            transition: background 0.2s;
            display: flex;
            align-items: center;
            cursor: pointer;
        }

        .user-panel-trigger:hover {
            background: rgba(255, 255, 255, 0.15);
        }

        .user-panel-trigger img {
            width: 35px;
            height: 35px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid rgba(255, 255, 255, 0.8);
        }

        .user-name-text {
            display: inline-block;
            max-width: 150px;
            opacity: 1;
            transition: all 0.3s;
            margin-left: 10px;
            color: white;
            font-weight: bold;
        }

        .navbar-auth.scrolled .user-name-text {
            opacity: 0;
            max-width: 0;
            margin-left: 0;
            overflow: hidden;
        }

        /* Dropdown Box */
        .profile-dropdown-menu {
            width: 300px;
            border-radius: 12px;
            border: none;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            margin-top: 10px;
            overflow: hidden;
        }

        .profile-card-header {
            text-align: center;
            padding: 25px 15px;
            background: #fff;
            border-bottom: 1px solid #eee;
            background-image: url('{{ asset('img/pattern-bg.png') }}');
            background-size: cover;
        }

        .profile-card-header img {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid #dfe6e9;
            margin-bottom: 10px;
        }

        /* 3. HALAMAN UTAMA (Hero & Features) */
        .hero-section {
            position: relative;
            background-image: url('{{ asset('img/sports-facilities-bg.jpg') }}');
            background-size: cover;
            background-position: center;
            height: 550px;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            color: white;
            margin-top: -80px;
            /* Tarik ke atas biar di belakang navbar */
            padding-top: 80px;
        }

        .hero-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.6);
            z-index: 1;
        }

        .hero-content {
            position: relative;
            z-index: 2;
            max-width: 800px;
            padding: 20px;
        }

        .hero-title {
            font-size: 3.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
        }

        .hero-subtitle {
            font-size: 1.5rem;
            font-weight: 300;
            margin-bottom: 2rem;
        }

        .btn-hero {
            padding: 12px 40px;
            font-size: 1.2rem;
            font-weight: 600;
            border-radius: 50px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
            transition: all 0.3s;
        }

        .btn-hero:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.4);
        }

        .feature-box {
            padding: 30px 20px;
            text-align: center;
            background: white;
            border-radius: 10px;
            margin-top: -50px;
            position: relative;
            z-index: 3;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        }

        .feature-icon {
            font-size: 2.5rem;
            color: #dc3545;
            margin-bottom: 15px;
        }

        .small-box {
            transition: transform 0.3s ease;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .small-box:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15);
        }

        .step-icon {
            font-size: 2rem;
            color: #dc3545;
            margin-bottom: 10px;
        }

        @stack('css')
    </style>
</head>

<body class="hold-transition layout-top-nav">

    {{-- Preloader --}}
    <div class="preloader initial-only" id="initialPreloader">
        <img class="animation__gentle" src="{{ asset('img/logo.png') }}" alt="LogoDispora" height="120"
            width="100">
        <h3 class="mt-3 text-dark font-weight-bold fade-in-text" style="letter-spacing: 2px;">DISPORA SEMARANG</h3>
    </div>

    <div class="wrapper">
        {{-- Navbar --}}
        @include('layouts.partials.navbar')

        {{-- Content Wrapper --}}
        <div class="content-wrapper" style="min-height: auto; background-color: transparent;">
            @yield('content')
        </div>

        {{-- Footer --}}
        <footer class="main-footer bg-danger border-0">
            <div class="container">
                <div class="row">
                    <div class="col-12 text-center">
                        <strong>Copyright &copy; 2026 <a href="#" class="text-white">E-SEWA DISPORA</a>.</strong>
                        All rights reserved.
                    </div>
                </div>
            </div>
        </footer>
    </div>

    {{-- Global Scripts --}}
    <!-- jQuery -->
    <script src="{{ asset('lte/plugins/jquery/jquery.min.js') }}"></script>

    <!-- Bootstrap 4 -->
    <script src="{{ asset('lte/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

    <!-- AdminLTE -->
    <script src="{{ asset('lte/dist/js/adminlte.min.js') }}"></script>

    <!-- Optional -->
    <script src="{{ asset('lte/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
    <script src="{{ asset('lte/plugins/toastr/toastr.min.js') }}"></script>

    <!-- Flatpickr -->
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/id.js"></script>


    {{-- Custom JS (Langsung disini agar aman) --}}
    <script>
        document.addEventListener('DOMContentLoaded', () => {

            /* =====================================================
               0. SWEETALERT SESSION FLASH
            ===================================================== */
            @if (session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: @json(session('success')),
                    timer: 3000,
                    showConfirmButton: false
                });
            @endif

            @if (session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: @json(session('error')),
                });
            @endif


            /* =====================================================
               1. PRELOADER
            ===================================================== */
            const preloader = document.getElementById('initialPreloader');
            if (preloader) {
                window.addEventListener('load', () => {
                    setTimeout(() => {
                        preloader.classList.add('fade-out');
                        setTimeout(() => preloader.classList.add('hidden'), 500);
                    }, 500);
                });

                // fallback kalau load event gagal
                setTimeout(() => {
                    if (!preloader.classList.contains('hidden')) {
                        preloader.classList.add('fade-out');
                        setTimeout(() => preloader.classList.add('hidden'), 500);
                    }
                }, 3000);
            }

            /* =====================================================
               2. NAVBAR SCROLL EFFECT
            ===================================================== */
            const navbarAuth = document.getElementById('navbarAuth');
            if (navbarAuth) {
                window.addEventListener('scroll', () => {
                    navbarAuth.classList.toggle('scrolled', window.scrollY > 50);
                });
            }

            /* =====================================================
               3. SCROLLSPY (FIX & STABIL)
            ===================================================== */
            const navLinks = document.querySelectorAll(
                '.nav-scroll-spy .nav-link[href^="#"]'
            );

            // section diambil dari link
            const sections = [...navLinks]
                .map(link => document.querySelector(link.getAttribute('href')))
                .filter(Boolean);

            const offset = navbarAuth ? navbarAuth.offsetHeight + 20 : 120;

            function onScrollSpy() {
                let currentId = '';

                sections.forEach(section => {
                    if (window.scrollY >= section.offsetTop - offset) {
                        currentId = section.id;
                    }
                });

                navLinks.forEach(link => {
                    link.classList.toggle(
                        'active',
                        link.getAttribute('href') === `#${currentId}`
                    );
                });
            }

            window.addEventListener('scroll', onScrollSpy);
            onScrollSpy(); // trigger awal
        });
    </script>


    @stack('scripts')
</body>

</html>
