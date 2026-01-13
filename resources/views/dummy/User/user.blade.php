<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>E-Sewa Fasilitas Dispora</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('lte/plugins/fontawesome-free/css/all.min.css') }}">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Tempusdominus Bootstrap 4 -->
    <link rel="stylesheet"
        href="{{ asset('lte/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- iCheck -->
    <link rel="stylesheet" href="{{ asset('lte/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <!-- JQVMap -->
    <link rel="stylesheet" href="{{ asset('lte/plugins/jqvmap/jqvmap.min.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('lte/dist/css/adminlte.min.css') }}">
    <!-- overlayScrollbars -->
    <link rel="stylesheet" href="{{ asset('lte/plugins/overlayScrollbars/css/OverlayScrollbars.min.css') }}">
    <!-- Daterange picker -->
    <link rel="stylesheet" href="{{ asset('lte/plugins/daterangepicker/daterangepicker.css') }}">
    <!-- summernote -->
    <link rel="stylesheet" href="{{ asset('lte/plugins/summernote/summernote-bs4.min.css') }}">
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="{{ asset('lte/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') }}">
    <!-- Toastr -->
    <link rel="stylesheet" href="{{ asset('lte/plugins/toastr/toastr.min.css') }}">
    {{-- Flatpickr --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" type="text/css" href="https://npmcdn.com/flatpickr/dist/themes/material_blue.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/plugins/monthSelect/style.css">

    <style>
        /* ============================================================================
           FLATPICK CUSTOM STYLES
           ============================================================================ */
        /* --- STYLE INDIKATOR KALENDER --- */
        .flatpickr-day {
            position: relative;
        }

        /* Titik Indikator */
        .event-dot {
            position: absolute;
            bottom: 2px;
            left: 50%;
            transform: translateX(-50%);
            width: 6px;
            height: 6px;
            border-radius: 50%;
            content: '';
            display: block;
        }

        /* Warna Prioritas */
        .status-fee {
            background-color: #ffc107 !important;
            border-color: #ffc107 !important;
            color: #fff;
        }

        /* Warning */
        .status-fee::after {
            content: '';
            @extend .event-dot;
            background: #fff;
        }

        /* Dot putih biar kontras */

        .status-pending {
            background-color: #17a2b8 !important;
            border-color: #17a2b8 !important;
            color: #fff;
        }

        /* Info */
        .status-lunas {
            background-color: #28a745 !important;
            border-color: #28a745 !important;
            color: #fff;
        }

        /* Success */

        /* Hover effect agar tetap terbaca */
        .flatpickr-day.status-fee:hover,
        .flatpickr-day.status-pending:hover,
        .flatpickr-day.status-lunas:hover {
            opacity: 0.8;
        }

        /* ============================================================================
           USER PROFILE SIDEBAR STYLING (UPDATED)
           ============================================================================ */
        .user-profile {
            padding: 15px 10px;
            margin-bottom: 10px;
            text-align: center;
            border-bottom: 1px solid #dee2e6;
            /* Warna border bawaan AdminLTE light */
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            white-space: nowrap;
            overflow: hidden;
        }

        .user-profile .image {
            display: block;
            width: 80px;
            height: 80px;
            margin: 0 auto 10px auto;
            /* Center image horizontally */
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .user-profile .image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 50%;
            border: 3px solid #e9ecef;
            /* Border halus */
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .user-profile .info {
            opacity: 1;
            transform: translateY(0);
            transition: all 0.3s ease;
        }

        .user-profile .info h6 {
            color: #343a40;
            /* Warna teks gelap untuk sidebar putih */
            margin-bottom: 5px;
            font-weight: 700;
            font-size: 1rem;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .user-profile .info .role-badge {
            display: inline-block;
            padding: 4px 12px;
            background-color: #dc3545;
            /* Merah Dispora */
            color: #fff;
            border-radius: 50px;
            /* Pill shape */
            font-size: 0.75rem;
            font-weight: 600;
            letter-spacing: 0.5px;
            box-shadow: 0 2px 4px rgba(220, 53, 69, 0.3);
        }

        /* --- LOGIKA SAAT SIDEBAR COLLAPSED (MINI) --- */
        .sidebar-mini.sidebar-collapse .user-profile {
            padding: 15px 0;
            border-bottom: 1px solid #dee2e6;
        }

        /* Gambar mengecil dan tetap di tengah */
        .sidebar-mini.sidebar-collapse .user-profile .image {
            width: 40px;
            height: 40px;
            margin: 0 auto;
            /* Force center */
        }

        .sidebar-mini.sidebar-collapse .user-profile .image img {
            border-width: 2px;
        }

        /* Sembunyikan Teks Nama & Role saat collapse */
        .sidebar-mini.sidebar-collapse .user-profile .info {
            opacity: 0;
            height: 0;
            margin: 0;
            overflow: hidden;
            transform: translateY(10px);
        }


        /* ============================================================================
           LOGOUT PROGRESS BAR - TETAP DIPERTAHANKAN UNTUK UX FEEDBACK
           ============================================================================ */
        .logout-progress-bar {
            position: fixed;
            top: 0;
            left: 0;
            width: 0%;
            height: 3px;
            background: linear-gradient(90deg, #dc3545, #ff6b6b);
            background-size: 200% 100%;
            animation: logout-gradient 1s ease-in-out infinite;
            z-index: 10000;
            opacity: 0;
            transition: opacity 0.2s ease, width 0.4s ease;
            box-shadow: 0 0 8px rgba(220, 53, 69, 0.6);
        }

        .logout-progress-bar.active {
            opacity: 1;
        }

        @keyframes logout-gradient {
            0% {
                background-position: 0% 50%;
            }

            50% {
                background-position: 100% 50%;
            }

            100% {
                background-position: 0% 50%;
            }
        }

        /* ============================================================================
           PRELOADER - DISEMBUNYIKAN SECARA DEFAULT (DOKUMENTASI TETAP ADA)
           Uncomment baris display untuk mengaktifkan kembali jika diperlukan
           ============================================================================ */
        .preloader.initial-only {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 9999;
            background-color: rgba(244, 246, 249, 0.95);
            /* display: flex; */
            /* UNCOMMENT untuk mengaktifkan preloader */
            display: none;
            /* DEFAULT: Preloader disembunyikan */
            flex-direction: column;
            justify-content: center;
            align-items: center;
            opacity: 1;
            visibility: visible;
            transition: opacity 0.4s ease, visibility 0.4s ease;
        }

        .preloader.initial-only.fade-out {
            opacity: 0;
            visibility: hidden;
        }

        .preloader.initial-only.hidden {
            display: none;
        }

        /* Animasi logo preloader */
        .animation__gentle {
            animation: gentle-pulse 1.5s ease-in-out infinite;
        }

        @keyframes gentle-pulse {
            0% {
                transform: scale(1);
                opacity: 1;
            }

            50% {
                transform: scale(1.05);
                opacity: 0.9;
            }

            100% {
                transform: scale(1);
                opacity: 1;
            }
        }

        .fade-in-text {
            animation: fadeIn 0.8s ease forwards;
            opacity: 0;
        }

        @keyframes fadeIn {
            0% {
                opacity: 0;
            }

            100% {
                opacity: 1;
            }
        }

        /* ============================================================================
           SMOOTH SIDEBAR ANIMATIONS - IMPLEMENTASI BARU
           ============================================================================ */

        /* Override AdminLTE default transitions untuk sidebar yang lebih smooth */
        .main-sidebar {
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
            will-change: transform;
        }

        /* Animasi saat sidebar collapse/expand */
        .sidebar-collapse .main-sidebar {
            transform: translateX(-250px);
        }

        .sidebar-mini.sidebar-collapse .main-sidebar {
            transform: translateX(0);
        }

        /* Content wrapper smooth transition */
        .content-wrapper {
            transition: margin-left 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
            will-change: margin-left;
        }

        /* ============================================================================
           ANIMASI MENU ITEMS - IMPLEMENTASI BARU
           ============================================================================ */

        /* Base styles untuk semua nav items */
        .nav-sidebar .nav-item {
            opacity: 0;
            transform: translateX(-20px);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* Staggered animation untuk menu items */
        .nav-sidebar .nav-item:nth-child(1) {
            animation: slideInLeft 0.6s ease forwards 0.1s;
        }

        .nav-sidebar .nav-item:nth-child(2) {
            animation: slideInLeft 0.6s ease forwards 0.2s;
        }

        .nav-sidebar .nav-item:nth-child(3) {
            animation: slideInLeft 0.6s ease forwards 0.3s;
        }

        .nav-sidebar .nav-item:nth-child(4) {
            animation: slideInLeft 0.6s ease forwards 0.4s;
        }

        .nav-sidebar .nav-item:nth-child(5) {
            animation: slideInLeft 0.6s ease forwards 0.5s;
        }

        /* Keyframe untuk slide in animation */
        @keyframes slideInLeft {
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        /* Hover effects untuk menu items - DIPERBAIKI */
        .nav-sidebar .nav-link {
            position: relative;
            overflow: hidden;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border-radius: 6px;
            margin: 2px 4px 2px 4px;
            /* margin kiri lebih besar, kanan lebih kecil */
            padding: 8px 12px;
            /* padding yang proporsional */
            max-width: calc(100% - 16px);
            /* pastikan tidak melampaui batas */
            box-sizing: border-box;
        }

        /* Efek hover dengan smooth scale dan background - DIPERBAIKI */
        .nav-sidebar .nav-link:hover {
            transform: translateX(3px) scale(1.01);
            /* gerakan lebih kecil */
            box-shadow: 0 3px 4px rgba(0, 0, 0, 0.12);
            margin-right: 2px;
            /* margin kanan saat hover */
        }

        /* Ripple effect saat klik */
        .nav-sidebar .nav-link::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            transform: translate(-50%, -50%);
            transition: width 0.3s, height 0.3s;
            z-index: 0;
        }

        .nav-sidebar .nav-link:active::before {
            width: 100px;
            height: 100px;
        }

        /* Icon animations */
        .nav-sidebar .nav-icon {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .nav-sidebar .nav-link:hover .nav-icon {
            transform: scale(1.1) rotate(5deg);
        }

        /* Search form animation */
        .form-inline {
            opacity: 0;
            transform: translateY(-10px);
            animation: slideInTop 0.5s ease forwards 0.1s;
        }

        @keyframes slideInTop {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* ============================================================================
           FIX STICKY FOOTER & LAYOUT
           ============================================================================ */

        /* Pastikan HTML dan BODY mengambil tinggi penuh */
        html,
        body {
            height: 100%;
            margin: 0;
        }

        /* Wrapper utama menjadi Flex Container */
        .wrapper {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            /* Pastikan minimal setinggi layar */
        }

        /* Content Wrapper akan mengambil sisa ruang yang ada (Flex Grow) */
        .content-wrapper {
            flex: 1;
            /* Hapus atau reset min-height bawaan AdminLTE jika mengganggu */
            min-height: auto !important;
            display: flex;
            /* Opsional: Agar child content bisa diatur */
            flex-direction: column;
        }

        /* Pastikan Main Content di dalam wrapper juga mengisi ruang */
        .content {
            flex: 1;
        }

        /* Footer tidak boleh menyusut */
        .main-footer {
            flex-shrink: 0;
        }

        /* Optimasi untuk navigasi cepat */
        .fast-navigation {
            transition: none !important;
            animation: none !important;
        }

        /* Carousel dan komponen lain */
        .carousel-overlay {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            padding: 20px;
            background: linear-gradient(to top, rgba(0, 0, 0, 0.8), rgba(0, 0, 0, 0));
            color: white;
        }

        .small-box {
            transition: transform 0.3s ease;
            overflow: hidden;
        }

        .small-box:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .facility-info {
            font-size: 1.2rem;
            margin-bottom: 10px;
            display: block;
        }

        .how-to-book {
            background-color: #f8f9fa;
            border-radius: 10px;
            padding: 20px;
            margin-top: 30px;
        }

        .step-icon {
            font-size: 2rem;
            margin-bottom: 15px;
            color: #007bff;
        }

        /* Footer visibility styles */
        .footer-hidden {
            transform: translateY(100%);
            transition: transform 0.3s ease;
        }

        .footer-visible {
            transform: translateY(0);
            transition: transform 0.3s ease;
        }

        /* ============================================================================
           RESPONSIVE ANIMATIONS
           ============================================================================ */

        /* Disable animations pada perangkat dengan motion sensitivity */
        @media (prefers-reduced-motion: reduce) {
            * {
                animation-duration: 0.01ms !important;
                animation-iteration-count: 1 !important;
                transition-duration: 0.01ms !important;
            }
        }

        /* Mobile optimizations */
        @media (max-width: 768px) {
            .nav-sidebar .nav-link:hover {
                transform: none;
            }

            .main-sidebar {
                transition-duration: 0.2s;
            }
        }
    </style>
</head>

<body class="hold-transition sidebar-mini sidebar-collapse layout-fixed">
    <!-- LOGOUT PROGRESS BAR - TETAP DIPERTAHANKAN -->
    <div id="logoutProgressBar" class="logout-progress-bar"></div>

    <div class="wrapper" id="pageContent">
        <!-- PRELOADER - DISEMBUNYIKAN TAPI DOKUMENTASI TETAP ADA -->
        <!-- Uncomment display: flex di CSS .preloader.initial-only untuk mengaktifkan -->
        <div class="preloader initial-only" id="initialPreloader">
            <img class="animation__gentle" src="{{ asset('img/logo.png') }}" alt="DISPORA Logo" height="180"
                width="150">
            <h2 class="mt-4 fade-in-text"><b>DISPORA SEMARANG</b></h2>
        </div>

        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand navbar-red navbar-light">
            <!-- Left navbar links -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link d-flex align-items-center fast-navigation" data-widget="pushmenu"
                        href="{{ route('user.fasilitas') }}" role="button">
                        <img class="me-2" src="{{ asset('img/logo.png') }}" alt="AdminLTELogo" height="40" width="40">
                        <b style="font-size: 1.5rem; margin-left: 1rem; color: white;"
                            onmouseover="this.style.color='yellow'" onmouseout="this.style.color='white'">E-Sewa
                            Fasilitas DISPORA SEMARANG
                        </b>
                    </a>
                </li>
            </ul>
        </nav>

        <!-- Main Sidebar Container -->
        <aside class="main-sidebar sidebar-light-danger elevation-4">

            <div class="sidebar">

                @auth
                    <div class="user-profile">
                        <div class="image">
                            {{-- Logika Foto: Cek apakah user punya foto, jika tidak pakai default --}}
                            @if(Auth::user()->foto)
                                <img src="{{ asset('storage/' . Auth::user()->foto) }}" alt="{{ Auth::user()->name }}">
                            @else
                                <img src="{{ asset('img/default-user.png') }}" alt="User">
                                {{-- Pastikan ada default-user.png di public/img --}}
                            @endif
                        </div>
                        <div class="info">
                            {{-- Nama User --}}
                            <h6 title="{{ Auth::user()->name }}">{{ Str::limit(Auth::user()->name, 15) }}</h6>

                            {{-- Filter Role: User -> Masyarakat --}}
                            @php
                                $roleName = Auth::user()->role;
                                if ($roleName === 'user') {
                                    $roleDisplay = 'Masyarakat';
                                } elseif ($roleName === 'admin') {
                                    $roleDisplay = 'Admin'; // Atau biarkan default
                                } else {
                                    $roleDisplay = ucfirst($roleName);
                                }
                            @endphp

                            <span class="role-badge">{{ $roleDisplay }}</span>
                        </div>
                    </div>
                @endauth

                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                        data-accordion="false">

                        <li class="nav-item mt-2">
                            <a href="{{ route('user.fasilitas') }}"
                                class="nav-link {{ request()->routeIs('user.fasilitas') ? 'active' : '' }} bg-danger fast-navigation">
                                <i class="nav-icon fas fa-book"></i>
                                <p>Beranda</p>
                            </a>
                        </li>

                        <li class="nav-item mt-2">
                            <a href="{{ route('user.riwayat') }}"
                                class="nav-link {{ request()->routeIs('user.riwayat') ? 'active' : '' }} bg-danger fast-navigation">
                                <i class="nav-icon fas fa-history"></i>
                                <p>Riwayat</p>
                            </a>
                        </li>

                        <li class="nav-item mt-2">
                            <a href="{{ route('user.profile') }}"
                                class="nav-link {{ request()->routeIs('user.profile') ? 'active' : '' }} bg-primary fast-navigation">
                                <i class="nav-icon fas fa-user"></i>
                                <p>User Profile</p>
                            </a>
                        </li>

                        <li class="nav-item mt-2">
                            <form method="POST" action="{{ route('logout') }}" id="logout-form"
                                class="logout-form-with-loading">
                                @csrf
                                <a href="{{ route('logout') }}" class="nav-link bg-primary"
                                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    <i class="nav-icon fas fa-times-circle"></i>
                                    <p>Logout</p>
                                </a>
                            </form>
                        </li>
                    </ul>
                </nav>
            </div>
        </aside>

        <!-- Content Wrapper -->
        <div class="content-wrapper">
            @yield('content')
        </div>

        <footer class="main-footer bg-dark">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <strong>Copyright &copy; 2025 <a href="#">E-SEWA FASILITAS</a>.</strong>
                        All rights reserved.
                    </div>
                </div>
            </div>
        </footer>
    </div>

    <!-- jQuery -->
    <script src="{{ asset('lte/jquery/jquery.min.js') }}"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js"></script>

    <!-- jQuery UI 1.11.4 -->
    <script src="{{ asset('lte/jquery-ui/jquery-ui.min.js') }}"></script>
    <script>
        $.widget.bridge('uibutton', $.ui.button)
    </script>

    <!-- Bootstrap 4 -->
    <script src="{{ asset('lte/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

    <!-- AdminLTE App -->
    <script src="{{ asset('lte/dist/js/adminlte.js') }}"></script>

    <!-- Additional plugins -->
    <script src="{{ asset('lte/chart.js/Chart.min.js') }}"></script>
    <script src="{{ asset('lte/sparklines/sparkline.js') }}"></script>
    <script src="{{ asset('lte/jqvmap/jquery.vmap.min.js') }}"></script>
    <script src="{{ asset('lte/jqvmap/maps/jquery.vmap.usa.js') }}"></script>
    <script src="{{ asset('lte/jquery-knob/jquery.knob.min.js') }}"></script>
    <script src="{{ asset('lte/moment/moment.min.js') }}"></script>
    <script src="{{ asset('lte/daterangepicker/daterangepicker.js') }}"></script>
    <script src="{{ asset('lte/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js') }}"></script>
    <script src="{{ asset('lte/summernote/summernote-bs4.min.js') }}"></script>
    <script src="{{ asset('lte/overlayScrollbars/js/jquery.overlayScrollbars.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://npmcdn.com/flatpickr/dist/l10n/id.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/plugins/monthSelect/index.js"></script>


    <!-- Notification plugins -->
    <script src="{{ asset('lte/plugins/toastr/toastr.min.js') }}"></script>
    <script src="{{ asset('lte/plugins/sweetalert2/sweetalert2.min.js') }}"></script>

    <!-- ENHANCED LOADER dengan Smooth Sidebar Animations -->
    <script>
        class EnhancedLoader {
            constructor() {
                this.logoutProgressBar = document.getElementById('logoutProgressBar');
                this.isLoading = false;
                this.init();
            }

            init() {
                this.handlePreloader();
                this.setupSmoothSidebar();
                this.setupLogoutOnly();
                this.setupFooterControl();
                this.initMenuAnimations();
            }

            // Handle preloader (disembunyikan tapi bisa diaktifkan)
            handlePreloader() {
                const initialPreloader = document.getElementById('initialPreloader');

                // Jika preloader diaktifkan (uncomment display: flex di CSS)
                if (initialPreloader && initialPreloader.style.display !== 'none') {
                    window.addEventListener('load', () => {
                        setTimeout(() => {
                            initialPreloader.classList.add('fade-out');
                            setTimeout(() => {
                                initialPreloader.classList.add('hidden');
                            }, 400);
                        }, 1000);
                    });
                }
            }

            // Setup smooth sidebar animations
            setupSmoothSidebar() {
                // Override AdminLTE PushMenu untuk animasi yang lebih smooth
                if (typeof AdminLTE !== 'undefined' && AdminLTE.PushMenu) {
                    // Custom smooth toggle function
                    const originalToggle = AdminLTE.PushMenu.prototype.toggle;
                    AdminLTE.PushMenu.prototype.toggle = function () {
                        const body = document.body;
                        const sidebar = document.querySelector('.main-sidebar');

                        // Add smooth transition class
                        sidebar.style.transition = 'transform 0.3s cubic-bezier(0.4, 0, 0.2, 1)';

                        // Call original toggle
                        originalToggle.call(this);

                        // Trigger menu item animations when sidebar opens
                        if (!body.classList.contains('sidebar-collapse')) {
                            this.animateMenuItems();
                        }
                    }.bind(this);
                }
            }

            // Animate menu items dengan staggered effect
            animateMenuItems() {
                const menuItems = document.querySelectorAll('.nav-sidebar .nav-item');

                menuItems.forEach((item, index) => {
                    // Reset animation
                    item.style.opacity = '0';
                    item.style.transform = 'translateX(-20px)';

                    // Trigger animation dengan delay
                    setTimeout(() => {
                        item.style.transition = 'all 0.3s cubic-bezier(0.4, 0, 0.2, 1)';
                        item.style.opacity = '1';
                        item.style.transform = 'translateX(0)';
                    }, index * 100);
                });
            }

            // Initialize menu animations pada page load
            initMenuAnimations() {
                // Delay initial animations
                setTimeout(() => {
                    const menuItems = document.querySelectorAll('.nav-sidebar .nav-item');
                    menuItems.forEach((item, index) => {
                        setTimeout(() => {
                            item.style.opacity = '1';
                            item.style.transform = 'translateX(0)';
                        }, index * 100);
                    });
                }, 300);
            }

            // LOGOUT Progress Bar (tetap dipertahankan)
            showLogoutLoading() {
                if (this.isLoading) return;

                this.isLoading = true;
                this.logoutProgressBar.classList.add('active');
                this.animateLogoutProgress();
            }

            animateLogoutProgress() {
                let width = 0;
                const increment = 100 / (500 / 15);

                const animate = () => {
                    if (width < 90 && this.isLoading) {
                        width += increment;
                        this.logoutProgressBar.style.width = width + '%';
                        setTimeout(animate, 15);
                    }
                };

                animate();

                setTimeout(() => {
                    this.logoutProgressBar.style.width = '100%';
                    setTimeout(() => {
                        this.logoutProgressBar.classList.remove('active');
                        this.logoutProgressBar.style.width = '0%';
                        this.isLoading = false;
                    }, 100);
                }, 500);
            }

            setupLogoutOnly() {
                document.addEventListener('submit', (e) => {
                    if (e.target.classList.contains('logout-form-with-loading')) {
                        this.showLogoutLoading();
                    }
                });
            }

            // Footer visibility control
            setupFooterControl() {
                const adjustFooterVisibility = () => {
                    const contentHeight = $('.content-wrapper').height();
                    const windowHeight = $(window).height();
                    const footerHeight = 60;
                    const navbarHeight = $('.main-header').outerHeight();
                    const availableHeight = windowHeight - navbarHeight;

                    if (contentHeight > (availableHeight - footerHeight)) {
                        $(window).scroll(function () {
                            if ($(window).scrollTop() + windowHeight >= $(document).height() - 10) {
                                $('.main-footer').addClass('footer-visible').removeClass('footer-hidden');
                            } else {
                                $('.main-footer').addClass('footer-hidden').removeClass('footer-visible');
                            }
                        });

                        if ($(window).scrollTop() + windowHeight < $(document).height() - 10) {
                            $('.main-footer').addClass('footer-hidden').removeClass('footer-visible');
                        }
                    } else {
                        $('.main-footer').addClass('footer-visible').removeClass('footer-hidden');
                        $(window).off('scroll');
                    }
                };

                adjustFooterVisibility();
                window.addEventListener('resize', adjustFooterVisibility);
            }
        }

        // Handle sidebar collapse state for user profile
        $('[data-widget="pushmenu"]').on('click', function () {
            // Let the AdminLTE handle the actual collapse
            // The CSS will take care of the visual changes
            setTimeout(function () {
                // Force refresh of profile section layout
                $('.user-profile').toggleClass('refreshed').toggleClass('refreshed');
            }, 300);
        });

        // Also check initial state on page load
        if ($('body').hasClass('sidebar-collapse')) {
            $('.user-profile').addClass('sidebar-collapsed');
        } else {
            $('.user-profile').removeClass('sidebar-collapsed');
        }

        // Initialize enhanced loader
        document.addEventListener('DOMContentLoaded', () => {
            window.enhancedLoader = new EnhancedLoader();

            // Global function untuk logout
            window.showLogoutLoader = () => {
                window.enhancedLoader.showLogoutLoading();
            };
        });


    </script>
</body>

</html>
