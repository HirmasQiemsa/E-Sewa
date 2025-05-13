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
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .content-wrapper {
            flex: 1;
        }

        .main-footer {
            height: 60px;
        }

        /* Fix for pagination in card footers */
        .card-footer .pagination {
            margin-bottom: 0 !important;
            display: flex;
            justify-content: center;
        }

        /* Remove the default list styling from Laravel pagination */
        .card-footer .pagination ul {
            list-style: none;
            padding: 0;
            margin: 0;
            display: flex;
        }

        /* Optimize card footer size */
        .card-footer.clearfix {
            padding: 0.75rem;
            background-color: rgba(0, 0, 0, 0.03);
            border-top: 1px solid rgba(0, 0, 0, 0.125);
        }

        /* If you're using Bootstrap 4 pagination styling */
        .pagination .page-item .page-link {
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
            line-height: 1.5;
        }
    </style>
</head>


<body class="hold-transition sidebar-mini layout-fixed">

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
                    <a class="nav-link d-flex align-items-center" data-widget="pushmenu" href="#" role="button">
                        <img class="me-2" src="{{ asset('img/logo.png') }}" alt="AdminLTELogo" height="40"
                            width="40">
                        <b style="font-size: 1.5rem; margin-left: 1rem; color: white;"
                            onmouseover="this.style.color='yellow'" onmouseout="this.style.color='white'">E-Sewa
                            Fasilitas DISPORA SEMARANG
                        </b>
                    </a>

                </li>
                {{-- <li class="nav-item ms-auto">
                    <img class="animation__shake" src="{{ asset('img/logo.png') }}" alt="AdminLTELogo" height="40"
                        width="40">
                </li> --}}
            </ul>
        </nav>

        <!-- Main Sidebar Container -->
        <aside class="main-sidebar sidebar-dark-danger elevation-4">
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
                    {{-- red --}}
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                        data-accordion="false">
                        <li class="nav-item active mt-2">
                            <a href="{{ route('admin.dashboard') }}" class="nav-link active bg-danger">
                                <i class="nav-icon fas fa-database"></i>
                                <p>
                                    Dashboard
                                </p>
                            </a>
                        </li>
                    </ul>
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                        data-accordion="false">
                        <li class="nav-item active mt-2">
                            <a href="{{ route('admin.fasilitas.index') }}" class="nav-link active bg-danger">
                                <i class="nav-icon fas fa-map"></i>
                                <p>
                                    Kelola Fasilitas
                                </p>
                            </a>
                        </li>
                    </ul>
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                        data-accordion="false">
                        <li class="nav-item active mt-2">
                            <a href="{{ route('admin.jadwal.index') }}" class="nav-link active bg-danger">
                                <i class="nav-icon fas fa-calendar-plus"></i>
                                <p>
                                    Kelola Jadwal
                                </p>
                            </a>
                        </li>
                    </ul>
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                        data-accordion="false">
                        <li class="nav-item active mt-2">
                            <a href="{{ route('admin.riwayat.index') }}" class="nav-link active bg-danger">
                                <i class="nav-icon fas fa-history"></i>
                                <p>
                                    Riwayat
                                </p>
                            </a>
                        </li>

                        {{-- blue --}}
                        <li class="nav-item active mt-2">
                            <a href="#" class="nav-link active bg-primary">
                                <i class="nav-icon fas fa-user"></i>
                                <p>
                                    Admin Profile
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
                    <ul>

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


    <!-- jQuery UI 1.11.4 -->
    <script src="{{ asset('lte/jquery/jquery.min.js') }}"></script>
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"
        integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js"
        integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js"
        integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous">
    </script>
    <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
    <script>
        $.widget.bridge('uibutton', $.ui.button)
    </script>
    <!-- Bootstrap 4 -->
    <script src="{{ asset('lte/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- ChartJS -->
    <script src="{{ asset('lte/chart.js/Chart.min.js') }}"></script>
    <!-- Sparkline -->
    <script src="{{ asset('lte/sparklines/sparkline.js') }}"></script>
    <!-- JQVMap -->
    <script src="{{ asset('lte/jqvmap/jquery.vmap.min.js') }}"></script>
    <script src="{{ asset('lte/jqvmap/maps/jquery.vmap.usa.js') }}"></script>
    <!-- jQuery Knob Chart -->
    <script src="{{ asset('lte/jquery-knob/jquery.knob.min.js') }}"></script>
    <!-- daterangepicker -->
    <script src="{{ asset('lte/moment/moment.min.js') }}"></script>
    <script src="{{ asset('lte/daterangepicker/daterangepicker.jsalt') }}"></script>
    <!-- Tempusdominus Bootstrap 4 -->
    <script src="{{ asset('lte/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js') }}"></script>
    <!-- Summernote -->
    <script src="{{ asset('lte/summernote/summernote-bs4.min.js') }}"></script>
    <!-- overlayScrollbars -->
    <script src="{{ asset('lte/overlayScrollbars/js/jquery.overlayScrollbars.min.js') }}"></script>
    <!-- AdminLTE App -->
    <script src="{{ asset('lte/dist/js/adminlte.js') }}"></script>
    <!-- AdminLTE for demo purposes -->
    <script src="{{ asset('lte/dist/js/demo.js') }}"></script>
    <!-- AdminLTE dashboard demo (This is only for demo purposes) -->
    <script src="{{ asset('lte/dist/js/pages/dashboard.js') }}"></script>
    <!-- Toastr -->
    <script src="{{ asset('lte/plugins/toastr/toastr.min.js') }}"></script>
    <!-- SweetAlert2 -->
    <script src="{{ asset('lte/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
    <!-- Page specific script -->
    <script>
        $(document).ready(function() {
            @if (session('success'))
                toastr.success("{{ session('success') }}");
            @endif
            @if ($errors->any())
                toastr.error("{{ $errors->first() }}");
            @endif

            // Tooltip bootstrap
            $('[data-toggle="tooltip"]').tooltip();

            $('.toggle-status').on('change', function() {
                var scheduleId = $(this).data('id');
                $.ajax({
                    url: '/schedules/toggle/' + scheduleId,
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        toastr.success(response.message);
                    },
                    error: function(response) {
                        toastr.error('Terjadi kesalahan saat memperbarui status.');
                    }
                });
            });

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
