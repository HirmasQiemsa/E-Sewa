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
    </style>
</head>

<body class="hold-transition sidebar-mini sidebar-collapse layout-fixed">
    <div class="wrapper">
        <!-- Preloader -->
        <div class="preloader flex-column justify-content-center align-items-center">
            <img class="animation__shake" src="{{ asset('img/logo.png') }}" alt="AdminLTELogo" height="180"
                width="150">
            <h2 class="mt-4"><b>DISPORA SEMARANG</b></h2>
        </div>

        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand navbar-red navbar-light">
            <!-- Left navbar links -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link d-flex align-items-center" data-widget="pushmenu"
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
                            <a href="{{ route('user.fasilitas') }}" class="nav-link active bg-danger">
                                <i class="nav-icon fas fa-book"></i>
                                <p>
                                    Fasilitas
                                </p>
                            </a>
                        </li>
                    </ul>
                    {{-- <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                        data-accordion="false">
                        <li class="nav-item active mt-2">
                            <a href="{{ route('user.checkout') }}" class="nav-link active bg-danger">
                                <i class="nav-icon fas fa-cart-plus"></i>
                                <p>
                                    Checkout
                                </p>
                            </a>
                        </li>
                    </ul> --}}
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                        data-accordion="false">
                        <li class="nav-item active mt-2">
                            <a href="{{ route('user.riwayat') }}" class="nav-link active bg-danger">
                                <i class="nav-icon fas fa-history"></i>
                                <p>
                                    Riwayat
                                </p>
                            </a>
                        </li>

                        <!-- logout -->
                        <li class="nav-item active mt-2">
                            <a href="#" class="nav-link active bg-primary">
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
                        {{-- <span class="float-right d-none d-sm-inline text-danger"><b>DISPORA SEMARANG</b></span> --}}
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

    <!-- ChartJS, Sparkline, JQVMap -->
    <script src="{{ asset('lte/chart.js/Chart.min.js') }}"></script>
    <script src="{{ asset('lte/sparklines/sparkline.js') }}"></script>
    <script src="{{ asset('lte/jqvmap/jquery.vmap.min.js') }}"></script>
    <script src="{{ asset('lte/jqvmap/maps/jquery.vmap.usa.js') }}"></script>

    <!-- Additional plugins -->
    <script src="{{ asset('lte/jquery-knob/jquery.knob.min.js') }}"></script>
    <script src="{{ asset('lte/moment/moment.min.js') }}"></script>
    <script src="{{ asset('lte/daterangepicker/daterangepicker.js') }}"></script>
    <script src="{{ asset('lte/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js') }}"></script>
    <script src="{{ asset('lte/summernote/summernote-bs4.min.js') }}"></script>
    <script src="{{ asset('lte/overlayScrollbars/js/jquery.overlayScrollbars.min.js') }}"></script>

    <!-- Notification plugins -->
    <script src="{{ asset('lte/plugins/toastr/toastr.min.js') }}"></script>
    <script src="{{ asset('lte/plugins/sweetalert2/sweetalert2.min.js') }}"></script>

    <!-- Footer visibility check script -->
    <script>
        // Footer visibility control
        $(document).ready(function() {
            // Function to check if footer should be visible
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

            // Run on page load
            adjustFooterVisibility();

            // Run on window resize
            $(window).resize(function() {
                adjustFooterVisibility();
            });

            // Run when new content is added
            $(document).on('DOMNodeInserted', '.content-wrapper', function() {
                setTimeout(adjustFooterVisibility, 200);
            });
        });
    </script>
</body>

</html>
