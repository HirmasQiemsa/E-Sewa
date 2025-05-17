<!-- Place this in your user.blade.php file -->

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

        /* Enhanced preloader styles */
        .preloader {
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

        .preloader.fade-out {
            opacity: 0;
            visibility: hidden;
        }

        .preloader.hidden {
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

        /* Page transition effect */
        .page-transition {
            transition: opacity 0.3s ease-out;
        }

        .page-transition.fading {
            opacity: 0.6;
        }
    </style>
</head>

<body class="hold-transition sidebar-mini sidebar-collapse layout-fixed">
    <!-- Customizable preloader -->
    <div class="preloader" id="customPreloader">
        <img class="animation__gentle" src="{{ asset('img/logo.png') }}" alt="DISPORA Logo" height="180" width="150">
        <h2 class="mt-4 fade-in-text"><b>DISPORA SEMARANG</b></h2>
    </div>

    <div class="wrapper page-transition">
        <!-- Initial preloader that shows on first page load -->
        <div class="preloader">
            <img class="animation__gentle" src="{{ asset('img/logo.png') }}" alt="DISPORA Logo" height="180" width="150">
            <h2 class="mt-4 fade-in-text"><b>DISPORA SEMARANG</b></h2>
        </div>

        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand navbar-red navbar-light">
            <!-- Left navbar links -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link d-flex align-items-center" data-widget="pushmenu"
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
                            <a href="{{ route('user.fasilitas') }}" class="nav-link active bg-danger nav-with-preloader">
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
                            <a href="{{ route('user.riwayat') }}" class="nav-link active bg-danger nav-with-preloader">
                                <i class="nav-icon fas fa-history"></i>
                                <p>
                                    Riwayat
                                </p>
                            </a>
                        </li>

                        <!-- logout -->
                        <li class="nav-item active mt-2">
                            <a href="{{ route('user.profile') }}" class="nav-link active bg-primary">
                                <i class="nav-icon fas fa-user"></i>
                                <p>
                                    User Profile
                                </p>
                            </a>
                        </li>
                        <li class="nav-item active mt-2">
                            <form method="POST" action="{{ route('logout') }}" id="logout-form">
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

    <!-- Custom Scripts for Preloader Management -->
    <script>
        // Initialize the preloader system
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize preloader functionality
            initPreloader();

            // Adjust footer visibility
            adjustFooterVisibility();

            // Listen for window resize
            window.addEventListener('resize', adjustFooterVisibility);

            // Setup navigation with preloader
            setupNavigation();

            // Setup forms with preloader
            setupForms();
        });

        // Function to handle the initial page load preloader
        function initPreloader() {
            const initialPreloader = document.querySelector('.preloader:not(#customPreloader)');
            const customPreloader = document.getElementById('customPreloader');
            const wrapper = document.querySelector('.wrapper');

            // Hide custom preloader initially
            if (customPreloader) {
                customPreloader.classList.add('hidden');
            }

            // Handle initial page load preloader
            if (initialPreloader) {
                window.addEventListener('load', function() {
                    setTimeout(function() {
                        initialPreloader.classList.add('fade-out');

                        setTimeout(function() {
                            initialPreloader.classList.add('hidden');
                        }, 400);
                    }, 1000);
                });
            }
        }

        // Function to show the custom preloader
        function showPreloader(duration = 1000) {
            const preloader = document.getElementById('customPreloader');
            const wrapper = document.querySelector('.wrapper');

            if (preloader) {
                // First fade the page slightly
                if (wrapper) {
                    wrapper.classList.add('fading');
                }

                // Show the preloader
                preloader.classList.remove('hidden');
                preloader.classList.remove('fade-out');

                // Optional: Auto-hide after duration
                if (duration > 0) {
                    setTimeout(function() {
                        hidePreloader();
                    }, duration);
                }
            }
        }

        // Function to hide the custom preloader
        function hidePreloader(delay = 400) {
            const preloader = document.getElementById('customPreloader');
            const wrapper = document.querySelector('.wrapper');

            if (preloader) {
                setTimeout(function() {
                    preloader.classList.add('fade-out');

                    // Restore page opacity
                    if (wrapper) {
                        wrapper.classList.remove('fading');
                    }

                    setTimeout(function() {
                        preloader.classList.add('hidden');
                    }, 400);
                }, delay);
            }
        }

        // Function to setup navigation with preloader
        function setupNavigation() {
            // Add preloader to sidebar navigation links
            const navLinks = document.querySelectorAll('.nav-with-preloader');

            navLinks.forEach(link => {
                link.addEventListener('click', function(e) {
                    // Only intercept if it's a normal link click (not middle click or ctrl+click)
                    if (!e.ctrlKey && !e.metaKey && e.button !== 1) {
                        e.preventDefault();
                        const targetUrl = this.getAttribute('href');

                        // Show preloader
                        showPreloader(0); // Don't auto-hide

                        // Navigate after a short delay
                        setTimeout(function() {
                            window.location.href = targetUrl;
                        }, 600);
                    }
                });
            });
        }

        // Function to setup forms with preloader
        function setupForms() {
            // Add preloader to forms with specific class
            document.querySelectorAll('form.form-with-preloader').forEach(form => {
                form.addEventListener('submit', function(e) {
                    // Show the preloader without auto-hide
                    showPreloader(0);

                    // Let the form submit normally
                    return true;
                });
            });
        }

        // Expose functions globally so they can be called from anywhere
        window.appPreloader = {
            show: showPreloader,
            hide: hidePreloader
        };

        // Footer visibility control function
        function adjustFooterVisibility() {
            var contentHeight = $('.content-wrapper').height();
            var windowHeight = $(window).height();
            var footerHeight = 60; // Exact 60px height
            var navbarHeight = $('.main-header').outerHeight();
            var availableHeight = windowHeight - navbarHeight;

            // If content plus footer would overflow the screen
            if (contentHeight > (availableHeight - footerHeight)) {
                // Hide footer when scrolling through content
                $(window).scroll(function() {
                    if ($(window).scrollTop() + windowHeight >= $(document).height() - 10) {
                        // User has scrolled to bottom, show footer
                        $('.main-footer').addClass('footer-visible').removeClass('footer-hidden');
                    } else {
                        // User is not at bottom, hide footer
                        $('.main-footer').addClass('footer-hidden').removeClass('footer-visible');
                    }
                });

                // Initialize as hidden if not at bottom
                if ($(window).scrollTop() + windowHeight < $(document).height() - 10) {
                    $('.main-footer').addClass('footer-hidden').removeClass('footer-visible');
                }
            } else {
                // Content is short, footer should always be visible
                $('.main-footer').addClass('footer-visible').removeClass('footer-hidden');
                // Remove scroll event if previously added
                $(window).off('scroll');
            }
        }
    </script>
</body>

</html>
