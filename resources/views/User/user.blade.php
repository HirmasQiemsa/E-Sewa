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

    <style>
        /* HAPUS: Semua loading untuk navigasi internal - hanya untuk logout */
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
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        /* Critical CSS for footer positioning */
        html,
        body {
            height: 100%;
            margin: 0;
            overflow-x: hidden;
        }

        .wrapper {
            min-height: 100%;
            display: flex;
            flex-direction: column;
        }

        .content-wrapper {
            flex: 1 0 auto;
            padding-bottom: 60px;
            /* Exact footer height */
        }

        .main-footer {
            flex-shrink: 0;
            height: 60px;
            /* Exact height */
        }

        /* HANYA untuk initial load - tetap dipertahankan */
        .preloader.initial-only {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 9999;
            background-color: rgba(244, 246, 249, 0.95);
            display: flex;
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

        /* Animation for logo */
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

        /* Fade in for text */
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

        /* TAMBAHAN: Disable AdminLTE default transitions yang memperlambat */
        .content-wrapper,
        .main-sidebar,
        .navbar,
        .nav-link,
        a {
            transition: none !important;
            animation: none !important;
        }

        /* TAMBAHAN: Optimasi untuk navigasi cepat */
        .fast-navigation {
            transition: none !important;
            animation: none !important;
        }

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
    </style>
</head>

<body class="hold-transition sidebar-mini sidebar-collapse layout-fixed">
    <!-- HANYA untuk logout: Progress bar -->
    <div id="logoutProgressBar" class="logout-progress-bar"></div>

    <div class="wrapper" id="pageContent">
        <!-- TETAP: Initial preloader untuk first page load -->
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
                    <!-- HAPUS semua loading untuk navigasi - biarkan default -->
                    <a class="nav-link d-flex align-items-center fast-navigation" data-widget="pushmenu"
                        href="{{ route('user.fasilitas') }}" role="button">
                        <img class="me-2" src="{{ asset('img/logo.png') }}" alt="AdminLTELogo" height="40"
                            width="40">
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
            <!-- Sidebar -->
            <div class="sidebar">
                <!-- SidebarSearch Form -->
                <div class="form-inline">
                    <div class="input-group" data-widget="sidebar-search">
                        <input class="form-control form-control-sidebar mt-4" type="search" placeholder="Search"
                            aria-label="Search">
                        <div class="input-group-append">
                            <button class="btn btn-sidebar mt-4">
                                <i class="fas fa-search fa-fw "></i>
                            </button>
                        </div>
                    </div>
                </div>
                <!-- Sidebar Menu -->
                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                        data-accordion="false">
                        <li class="nav-item active mt-2">
                            <!-- HAPUS loading - navigasi langsung -->
                            <a href="{{ route('user.fasilitas') }}"
                                class="nav-link active bg-danger fast-navigation">
                                <i class="nav-icon fas fa-book"></i>
                                <p>
                                    Fasilitas
                                </p>
                            </a>
                        </li>
                    </ul>
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                        data-accordion="false">
                        <li class="nav-item active mt-2">
                            <!-- HAPUS loading - navigasi langsung -->
                            <a href="{{ route('user.riwayat') }}" class="nav-link active bg-danger fast-navigation">
                                <i class="nav-icon fas fa-history"></i>
                                <p>
                                    Riwayat
                                </p>
                            </a>
                        </li>

                        <!-- logout -->
                        <li class="nav-item active mt-2">
                            <!-- HAPUS loading - navigasi langsung -->
                            <a href="{{ route('user.profile') }}" class="nav-link active bg-primary fast-navigation">
                                <i class="nav-icon fas fa-user"></i>
                                <p>
                                    User Profile
                                </p>
                            </a>
                        </li>
                        <li class="nav-item active mt-2">
                            <!-- HANYA ini yang ada loading -->
                            <form method="POST" action="{{ route('logout') }}" id="logout-form" class="logout-form-with-loading">
                                @csrf
                                <a href="{{ route('logout') }}" class="nav-link active bg-primary"
                                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    <i class="nav-icon fas fa-times-circle"></i>
                                    <p>
                                        Logout
                                    </p>
                                </a>
                            </form>
                        </li>
                    </ul>
                </nav>
                <!-- /.sidebar-menu -->
            </div>
            <!-- /.sidebar -->
        </aside>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            @yield('content')
        </div>
        <!-- /.content-wrapper -->

        <footer class="main-footer bg-dark">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <strong>Copyright &copy; 2025 <a href="#">E-SEWA</a>.</strong>
                        All rights reserved.
                    </div>
                </div>
            </div>
        </footer>
    </div>
    <!-- ./wrapper -->

    <!-- jQuery -->
    <script src="{{ asset('lte/jquery/jquery.min.js') }}"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js"></script>

    <!-- jQuery UI 1.11.4 -->
    <script src="{{ asset('lte/jquery-ui/jquery-ui.min.js') }}"></script>
    <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
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

    <!-- Notification plugins -->
    <script src="{{ asset('lte/plugins/toastr/toastr.min.js') }}"></script>
    <script src="{{ asset('lte/plugins/sweetalert2/sweetalert2.min.js') }}"></script>

    <!-- MINIMAL: Hanya untuk initial load dan logout -->
    <script>
        class MinimalLoader {
            constructor() {
                this.logoutProgressBar = document.getElementById('logoutProgressBar');
                this.isLoading = false;
                this.init();
            }

            init() {
                this.handleInitialLoad();
                this.setupLogoutOnly();
                this.disableAdminLTETransitions();
                this.setupFooterControl();
            }

            // Handle initial page load preloader
            handleInitialLoad() {
                const initialPreloader = document.getElementById('initialPreloader');

                if (initialPreloader) {
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

            // HANYA untuk logout
            showLogoutLoading() {
                if (this.isLoading) return;

                this.isLoading = true;
                this.logoutProgressBar.classList.add('active');

                this.animateLogoutProgress();
            }

            // Animate logout progress bar
            animateLogoutProgress() {
                let width = 0;
                const increment = 100 / (500 / 15); // 500ms duration

                const animate = () => {
                    if (width < 90 && this.isLoading) {
                        width += increment;
                        this.logoutProgressBar.style.width = width + '%';
                        setTimeout(animate, 15);
                    }
                };

                animate();

                // Complete after 500ms
                setTimeout(() => {
                    this.logoutProgressBar.style.width = '100%';
                    setTimeout(() => {
                        this.logoutProgressBar.classList.remove('active');
                        this.logoutProgressBar.style.width = '0%';
                        this.isLoading = false;
                    }, 100);
                }, 500);
            }

            // Setup HANYA untuk logout
            setupLogoutOnly() {
                document.addEventListener('submit', (e) => {
                    if (e.target.classList.contains('logout-form-with-loading')) {
                        this.showLogoutLoading();
                    }
                });
            }

            // Disable AdminLTE default transitions yang memperlambat
            disableAdminLTETransitions() {
                // Disable pushmenu animation
                if (typeof AdminLTE !== 'undefined' && AdminLTE.PushMenu) {
                    AdminLTE.PushMenu.options = {
                        ...AdminLTE.PushMenu.options,
                        animationSpeed: 0
                    };
                }

                // Disable sidebar transitions
                const style = document.createElement('style');
                style.textContent = `
                    .main-sidebar {
                        transition: none !important;
                        animation: none !important;
                    }
                    .content-wrapper {
                        transition: none !important;
                        animation: none !important;
                    }
                    .nav-link {
                        transition: none !important;
                    }
                `;
                document.head.appendChild(style);
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
                        $(window).scroll(function() {
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

        // Initialize minimal loader
        document.addEventListener('DOMContentLoaded', () => {
            window.minimalLoader = new MinimalLoader();

            // Global function untuk logout saja
            window.showLogoutLoader = () => {
                window.minimalLoader.showLogoutLoading();
            };
        });
    </script>
</body>

</html>
