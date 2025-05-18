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

        /* User profile in sidebar */
        .user-profile {
            padding: 20px 10px;
            margin-bottom: 10px;
            text-align: center;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            transition: all 0.3s ease;
        }

        .user-profile .image {
            position: relative;
            display: inline-block;
            width: 80px;
            height: 80px;
            margin-bottom: 10px;
            transition: all 0.3s ease;
        }

        .user-profile .image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 50%;
            border: 3px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
            transition: all 0.3s ease;
        }

        .user-profile .info {
            margin-top: 5px;
            transition: all 0.3s ease;
        }

        /* Enhanced text styling */
        .user-profile .info h6 {
            color: #fff;
            margin-bottom: 8px;
            /* Increased bottom margin */
            font-weight: 800;
            /* Extra bold */
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.3);
            /* Text shadow for better readability */
        }

        /* Role badge styling */
        .user-profile .info .role-badge {
            display: inline-block;
            padding: 3px 10px;
            background-color: rgba(255, 255, 255, 0.15);
            /* Semi-transparent white */
            color: #ffffff;
            border-radius: 4px;
            /* Slightly rounded corners */
            font-size: 0.8rem;
            font-weight: 500;
            margin: 0 auto;
            cursor: default;
            /* Show default cursor to indicate non-clickable */
            border: 1px solid rgba(255, 255, 255, 0.2);
            /* Subtle border */
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            /* Subtle shadow */
        }

        /* Specifically handle collapsed state */
        .sidebar-mini.sidebar-collapse .user-profile {
            padding: 10px 0;
            display: flex;
            justify-content: center;
            border-bottom: none;
        }

        .sidebar-mini.sidebar-collapse .user-profile .image {
            width: 2.2rem;
            height: 2.2rem;
            margin-bottom: 0;
        }

        .sidebar-mini.sidebar-collapse .user-profile .image img {
            border-width: 1px;
        }

        .sidebar-mini.sidebar-collapse .user-profile .info {
            display: none;
        }

        /* Perbaikan dropdown sidebar */
        .nav-sidebar .nav-item.menu-is-opening .nav-link .right,
        .nav-sidebar .nav-item.menu-open .nav-link .right {
            transform: rotate(-90deg);
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
            </ul>

            <!-- Right navbar links -->
            <ul class="navbar-nav ml-auto">
                <!-- Notifications Dropdown Menu -->
                <li class="nav-item dropdown">
                    <a class="nav-link" data-toggle="dropdown" href="#">
                        <i class="far fa-bell"></i>
                        <span class="badge badge-warning navbar-badge">3</span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                        <span class="dropdown-item dropdown-header">3 Notifikasi</span>
                        <div class="dropdown-divider"></div>
                        <a href="#" class="dropdown-item">
                            <i class="fas fa-calendar mr-2"></i> 3 jadwal baru hari ini
                            <span class="float-right text-muted text-sm">12:00</span>
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="#" class="dropdown-item dropdown-footer">Lihat Semua Notifikasi</a>
                    </div>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-widget="fullscreen" href="#" role="button">
                        <i class="fas fa-expand-arrows-alt"></i>
                    </a>
                </li>
            </ul>
        </nav>

        <!-- Main Sidebar Container -->
        <aside class="main-sidebar sidebar-dark-danger elevation-4">
            <!-- User Profile -->
            <div class="user-profile">
                <div class="image">
                    <!-- Tampilkan foto petugas jika ada, jika tidak ada tampilkan default -->
                    <img src="{{ Auth::guard('petugas_fasilitas')->user()->foto
                        ? asset('storage/' . Auth::guard('petugas_fasilitas')->user()->foto)
                        : asset('img/default-user.png') }}"
                        alt="Petugas Profile" class="img-circle elevation-2">
                </div>
                <div class="info">
                    <h6>{{ Auth::guard('petugas_fasilitas')->user()->name ?? 'Petugas Fasilitas' }}</h6>
                    <span class="role-badge">Petugas Fasilitas</span>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="sidebar">
                <!-- Sidebar Menu -->
                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                        data-accordion="false">
                        <li class="nav-item">
                            <a href="{{ route('petugas_fasilitas.dashboard') }}"
                                class="nav-link {{ request()->routeIs('petugas_fasilitas.dashboard') ? 'active' : '' }} bg-danger">
                                <i class="nav-icon fas fa-database"></i>
                                <p>
                                    Dashboard
                                </p>
                            </a>
                        </li>

                        <!-- Kelola Fasilitas dengan submenu -->
                        <li
                            class="nav-item {{ request()->routeIs('petugas_fasilitas.fasilitas.*') ? 'menu-open' : '' }}">
                            <a href="#" class="nav-link bg-danger">
                                <i class="nav-icon fas fa-map-marker-alt"></i>
                                <p>
                                    Kelola Fasilitas
                                    <i class="fas fa-angle-left right"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('petugas_fasilitas.fasilitas.index') }}"
                                        class="nav-link {{ request()->routeIs('petugas_fasilitas.fasilitas.index','petugas_fasilitas.fasilitas.edit','petugas_fasilitas.fasilitas.create') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Daftar Fasilitas</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#"
                                        class="nav-link {{ request()->routeIs('#') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Daftar Jadwal</p>
                                    </a>
                                </li>
                                {{-- <li class="nav-item">
                                    <a href="{{ route('petugas_fasilitas.fasilitas.maintenance') }}"
                                        class="nav-link {{ request()->routeIs('petugas_fasilitas.fasilitas.maintenance') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Fasilitas Maintenance</p>
                                    </a>
                                </li> --}}
                            </ul>
                        </li>

                        <!-- Kelola Jadwal dengan submenu -->
                        {{-- <li
                            class="nav-item {{ request()->routeIs('petugas_fasilitas.jadwal.*') ? 'menu-open' : '' }}">
                            <a href="#" class="nav-link bg-danger">
                                <i class="nav-icon fas fa-calendar-alt"></i>
                                <p>
                                    Kelola Jadwal
                                    <i class="fas fa-angle-left right"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('petugas_fasilitas.jadwal.index') }}"
                                        class="nav-link {{ request()->routeIs('petugas_fasilitas.jadwal.index') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Daftar Jadwal</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('petugas_fasilitas.jadwal.create') }}"
                                        class="nav-link {{ request()->routeIs('petugas_fasilitas.jadwal.create') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Tambah Jadwal</p>
                                    </a>
                                </li>
                            </ul>
                        </li> --}}

                        <!-- Kelola Booking dengan submenu -->
                        <li
                            class="nav-item {{ request()->routeIs('petugas_fasilitas.booking.*') ? 'menu-open' : '' }}">
                            <a href="#" class="nav-link bg-danger">
                                <i class="nav-icon fas fa-calendar-alt"></i>
                                <p>
                                    Kelola Booking
                                    <i class="fas fa-angle-left right"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('petugas_fasilitas.booking.daftar') }}"
                                        class="nav-link {{ request()->routeIs('petugas_fasilitas.booking.daftar') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Daftar Booking</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#"
                                        class="nav-link {{ request()->routeIs('petugas_fasilitas.booking.history') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Riwayat Aktifitas</p>
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <!-- Log Aktivitas -->
                        {{-- <li class="nav-item">
                            <a href="{{ route('petugas_fasilitas.activities') }}"
                                class="nav-link {{ request()->routeIs('petugas_fasilitas.activities') ? 'active' : '' }} bg-danger">
                                <i class="nav-icon fas fa-history"></i>
                                <p>
                                    Log Aktivitas
                                </p>
                            </a>
                        </li> --}}

                        <!-- Profile (Blue) -->
                        <li class="nav-item mt-2">
                            <a href="{{ route('petugas_fasilitas.profile.edit') }}"
                                class="nav-link {{ request()->routeIs('petugas_fasilitas.profile.*') ? 'active' : '' }} bg-primary">
                                <i class="nav-icon fas fa-user-cog"></i>
                                <p>
                                    Pengaturan Profil
                                </p>
                            </a>
                        </li>

                        <!-- Logout (Blue) -->
                        <li class="nav-item">
                            <form method="POST" action="{{ route('logout') }}" id="logout-form">
                                @csrf
                                <a href="{{ route('logout') }}" class="nav-link bg-primary"
                                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    <i class="nav-icon fas fa-sign-out-alt"></i>
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


    <!-- jQuery UI 1.11.4 -->
    <script src="{{ asset('lte/jquery/jquery.min.js') }}"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js"></script>
    <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
    <script>
        $.widget.bridge('uibutton', $.ui.button)
    </script>
    <!-- Bootstrap 4 -->
    <script src="{{ asset('lte/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- ChartJS -->
    <script src="{{ asset('lte/plugins/chart.js/Chart.min.js') }}"></script>
    <!-- Chart.js dari CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.7.1/dist/chart.min.js"></script>
    <!-- Sparkline -->
    <script src="{{ asset('lte/sparklines/sparkline.js') }}"></script>
    <!-- JQVMap -->
    <script src="{{ asset('lte/jqvmap/jquery.vmap.min.js') }}"></script>
    <script src="{{ asset('lte/jqvmap/maps/jquery.vmap.usa.js') }}"></script>
    <!-- jQuery Knob Chart -->
    <script src="{{ asset('lte/jquery-knob/jquery.knob.min.js') }}"></script>
    <!-- daterangepicker -->
    <script src="{{ asset('lte/moment/moment.min.js') }}"></script>
    <script src="{{ asset('lte/daterangepicker/daterangepicker.js') }}"></script>
    <!-- Tempusdominus Bootstrap 4 -->
    <script src="{{ asset('lte/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js') }}"></script>
    <!-- Summernote -->
    <script src="{{ asset('lte/summernote/summernote-bs4.min.js') }}"></script>
    <!-- overlayScrollbars -->
    <script src="{{ asset('lte/overlayScrollbars/js/jquery.overlayScrollbars.min.js') }}"></script>
    <!-- AdminLTE -->
    <script src="{{ asset('lte/dist/js/adminlte.min.js') }}"></script>
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

            // Toggle status dengan AJAX
            $('.toggle-status').on('change', function() {
                var scheduleId = $(this).data('id');
                $.ajax({
                    url: '/petugas-fasilitas/fasilitas/' + scheduleId + '/toggle-status',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        _method: 'PUT',
                        status: $(this).prop('checked') ? 'aktif' : 'nonaktif'
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

            // Memperbaiki button collapse di card
            // Solusi 1: Hentikan propagasi event untuk mencegah konflik
            $(document).on('click', '[data-card-widget="collapse"]', function(event) {
                event.stopPropagation();
            });

            // Solusi 2: Reinisialisasi card widgets
            setTimeout(function() {
                $('.card-tools [data-card-widget="collapse"]').each(function() {
                    try {
                        // Hapus event handler yang ada
                        $(this).off('click');

                        // Tambahkan event handler baru
                        $(this).on('click', function(e) {
                            e.preventDefault();
                            e.stopPropagation();

                            var $card = $(this).closest('.card');
                            $card.find('.card-body, .card-footer').slideToggle('fast');

                            // Toggle ikon
                            var $icon = $(this).find('i.fa, i.fas');
                            if ($icon.hasClass('fa-minus')) {
                                $icon.removeClass('fa-minus').addClass('fa-plus');
                            } else {
                                $icon.removeClass('fa-plus').addClass('fa-minus');
                            }
                        });
                    } catch (error) {
                        console.error('Error reinitializing card widget:', error);
                    }
                });
            }, 500);

            // Handle sidebar collapse state for user profile
            $('[data-widget="pushmenu"]').on('click', function() {
                // Let the AdminLTE handle the actual collapse
                // The CSS will take care of the visual changes
                setTimeout(function() {
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

        });
    </script>
</body>

</html>
