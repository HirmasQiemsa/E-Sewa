<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>E-Sewa Fasilitas Dispora</title>

    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet" href="{{ asset('lte/plugins/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <link rel="stylesheet"
        href="{{ asset('lte/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('lte/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('lte/plugins/jqvmap/jqvmap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('lte/dist/css/adminlte.min.css') }}">
    <link rel="stylesheet" href="{{ asset('lte/plugins/overlayScrollbars/css/OverlayScrollbars.min.css') }}">
    <link rel="stylesheet" href="{{ asset('lte/plugins/daterangepicker/daterangepicker.css') }}">
    <link rel="stylesheet" href="{{ asset('lte/plugins/summernote/summernote-bs4.min.js') }}">
    <link rel="stylesheet" href="{{ asset('lte/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('lte/plugins/toastr/toastr.min.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="https://npmcdn.com/flatpickr/dist/themes/material_blue.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/plugins/monthSelect/style.css">

    @stack('css')

    <style>
        /* COMPACT CUSTOM CSS */
        html,
        body {
            height: 100%;
            margin: 0;
        }

        .wrapper {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .content-wrapper {
            flex: 1;
            min-height: auto !important;
            display: flex;
            flex-direction: column;
            transition: margin-left 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
        }

        .content {
            flex: 1;
        }

        .main-footer {
            flex-shrink: 0;
        }

        /* Sidebar & Profile */
        .main-sidebar {
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
        }

        .user-profile {
            padding: 15px 10px;
            text-align: center;
            border-bottom: 1px solid #dee2e6;
            transition: all 0.3s ease;
            white-space: nowrap;
            overflow: hidden;
        }

        .user-profile .image {
            display: block;
            width: 80px;
            height: 80px;
            margin: 0 auto 10px;
            transition: all 0.3s ease;
        }

        .user-profile .image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 50%;
            border: 3px solid #e9ecef;
        }

        .user-profile .info h6 {
            color: #343a40;
            margin-bottom: 5px;
            font-weight: 700;
            font-size: 1rem;
        }

        .sidebar-mini.sidebar-collapse .user-profile .image {
            width: 40px;
            height: 40px;
        }

        .sidebar-mini.sidebar-collapse .user-profile .info {
            opacity: 0;
            height: 0;
            margin: 0;
            transform: translateY(10px);
        }

        /* Nav Animations */
        .nav-sidebar .nav-item {
            opacity: 0;
            transform: translateX(-20px);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .nav-sidebar .nav-link {
            position: relative;
            overflow: hidden;
            transition: all 0.3s ease;
            border-radius: 6px;
            margin: 2px 4px;
            padding: 8px 12px;
        }

        .nav-sidebar .nav-link:hover {
            transform: translateX(3px) scale(1.01);
            box-shadow: 0 3px 4px rgba(0, 0, 0, 0.12);
            margin-right: 2px;
        }

        /* Calendar Indicators */
        .flatpickr-day {
            position: relative;
        }

        .event-dot {
            position: absolute;
            bottom: 2px;
            left: 50%;
            transform: translateX(-50%);
            width: 6px;
            height: 6px;
            border-radius: 50%;
            display: block;
        }

        .status-fee {
            background-color: #ffc107 !important;
            border-color: #ffc107 !important;
            color: #fff;
        }

        .status-fee::after {
            content: '';
            background: #fff;
            position: absolute;
            bottom: 2px;
            left: 50%;
            transform: translateX(-50%);
            width: 6px;
            height: 6px;
            border-radius: 50%;
        }

        .status-pending {
            background-color: #17a2b8 !important;
            border-color: #17a2b8 !important;
            color: #fff;
        }

        .status-lunas {
            background-color: #28a745 !important;
            border-color: #28a745 !important;
            color: #fff;
        }

        /* Loader & Utils */
        .logout-progress-bar {
            position: fixed;
            top: 0;
            left: 0;
            width: 0%;
            height: 3px;
            background: linear-gradient(90deg, #dc3545, #ff6b6b);
            background-size: 200% 100%;
            z-index: 10000;
            opacity: 0;
            transition: opacity 0.2s, width 0.4s;
        }

        .logout-progress-bar.active {
            opacity: 1;
            animation: logout-gradient 1s ease-in-out infinite;
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

        @media (min-width: 992px) {
            [data-widget="pushmenu"] {
                display: none !important;
            }

            body.sidebar-collapse .main-sidebar {
                margin-left: 0 !important;
                width: 250px !important;
            }

            body.sidebar-collapse .content-wrapper,
            body.sidebar-collapse .main-footer,
            body.sidebar-collapse .main-header {
                margin-left: 250px !important;
            }
        }

        /* SWEETALERT2 TOAST FIX (FINAL) */
        /* SWEETALERT2 TOAST FIX (FINAL & BIGGER VERSION) */
        .swal2-popup.swal2-toast {
            /* 1. Reset & Layout Dasar */
            padding: 0.8rem 1rem !important;
            display: flex !important;
            align-items: center !important;

            /* 2. Styling Visual */
            box-shadow: 0 0.5rem 1.5rem rgba(0,0,0,0.15) !important;
            border-radius: 0.8rem !important;
            background: #fff !important;

            /* 3. Ukuran & Zoom (SOLUSI MAGIC) */
            width: auto !important;
            max-width: 450px !important;
            /* Zoom seluruh komponen sebesar 15% agar lebih terlihat */
            transform: scale(1.15) !important;
            /* Titik poros zoom di kanan atas (sesuai posisi toast) */
            transform-origin: top right !important;
            /* Beri margin tambahan karena setelah di-zoom dia akan makan tempat */
            margin-top: 10px !important;
            margin-right: 10px !important;
        }

        /* Ikon: Reset Margin agar rapi */
        .swal2-popup.swal2-toast .swal2-icon {
            margin: 0 15px 0 0 !important;
            border-color: transparent !important; /* Hapus border aneh */
        }

        /* Teks Judul */
        .swal2-popup.swal2-toast .swal2-title {
            margin: 0 !important;
            font-size: 1rem !important;
            font-weight: 600 !important;
            color: #343a40;
            text-align: left;
            flex: 1;
            align-self: center;
        }

        /* Progress Bar */
        .swal2-popup.swal2-toast .swal2-timer-progress-bar {
            height: 4px !important;
            background: rgba(0,0,0,0.15);
        }
    </style>
</head>

<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed">
    <div id="logoutProgressBar" class="logout-progress-bar"></div>

    <div class="wrapper">
        <nav class="main-header navbar navbar-expand navbar-red navbar-light">
            <ul class="navbar-nav">
                <li class="nav-item d-flex align-items-center">
                    <a href="{{ route('user.fasilitas') }}" class="nav-link d-flex align-items-center p-0">
                        <img class="ml-3 mr-2" src="{{ asset('img/logo.png') }}" alt="Logo" height="40"
                            width="40">
                        <span class="text-white font-weight-bold d-none d-sm-inline-block"
                            style="font-size: 1.2rem;">E-Sewa Fasilitas Dispora Semarang</span>
                        <span class="text-white font-weight-bold d-inline-block d-sm-none"
                            style="font-size: 1.2rem;">E-SEWA DISPORA</span>
                    </a>
                </li>
            </ul>
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i
                            class="fas fa-bars text-white" style="font-size: 1.5rem;"></i></a>
                </li>
            </ul>
        </nav>

        <aside class="main-sidebar sidebar-light-danger elevation-4">
            <div class="sidebar">
                @auth
                    <div class="user-profile mt-3">
                        <div class="image">
                            <img src="{{ Auth::user()->foto ? asset('storage/' . Auth::user()->foto) : asset('img/default-user.png') }}"
                                alt="User">
                        </div>
                        <div class="info">
                            <h6 class="font-weight-bold text-dark">{{ Str::limit(Auth::user()->name, 20) }}</h6>
                            <span class="badge badge-danger">Masyarakat</span>
                        </div>
                    </div>
                @endauth

                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">
                        <li class="nav-item">
                            <a href="{{ route('user.fasilitas') }}"
                                class="nav-link {{ request()->routeIs('user.fasilitas') ? 'active bg-danger' : '' }}">
                                <i class="nav-icon fas fa-home"></i>
                                <p>Beranda</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('user.riwayat') }}"
                                class="nav-link {{ request()->routeIs('user.riwayat') ? 'active bg-danger' : '' }}">
                                <i class="nav-icon fas fa-history"></i>
                                <p>Riwayat Booking</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('user.profile.index') }}"
                                class="nav-link {{ request()->routeIs('user.profile.index') ? 'active bg-primary' : '' }}">
                                <i class="nav-icon fas fa-user"></i>
                                <p>Profil Saya</p>
                            </a>
                        </li>
                        <li class="nav-item mt-3">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <a href="#" class="nav-link bg-secondary text-white"
                                    onclick="event.preventDefault(); this.closest('form').submit();">
                                    <i class="nav-icon fas fa-sign-out-alt"></i>
                                    <p>Keluar</p>
                                </a>
                            </form>
                        </li>
                    </ul>
                </nav>
            </div>
        </aside>

        <div class="content-wrapper">
            @yield('content')
        </div>

        <footer class="main-footer bg-dark">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <strong>Copyright &copy; 2025 <a href="#">E-SEWA FASILITAS</a>.</strong> All rights
                        reserved.
                    </div>
                </div>
            </div>
        </footer>
    </div>

    <script src="{{ asset('lte/jquery/jquery.min.js') }}"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js"></script>
    <script src="{{ asset('lte/jquery-ui/jquery-ui.min.js') }}"></script>
    <script>
        $.widget.bridge('uibutton', $.ui.button)
    </script>
    <script src="{{ asset('lte/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('lte/dist/js/adminlte.js') }}"></script>

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
    <script src="{{ asset('lte/plugins/toastr/toastr.min.js') }}"></script>
    <script src="{{ asset('lte/plugins/sweetalert2/sweetalert2.min.js') }}"></script>

    <script>
        // --- 1. ENHANCED LOADER & UI CONTROL ---
        class EnhancedLoader {
            constructor() {
                this.bar = document.getElementById('logoutProgressBar');
                this.isLoading = false;
                this.init();
            }
            init() {
                this.setupSmoothSidebar();
                this.initMenuAnimations();
                this.setupFooterControl();
            }
            setupSmoothSidebar() {
                if (typeof AdminLTE !== 'undefined' && AdminLTE.PushMenu) {
                    const originalToggle = AdminLTE.PushMenu.prototype.toggle;
                    AdminLTE.PushMenu.prototype.toggle = function() {
                        document.querySelector('.main-sidebar').style.transition =
                            'transform 0.3s cubic-bezier(0.4, 0, 0.2, 1)';
                        originalToggle.call(this);
                        if (!document.body.classList.contains('sidebar-collapse')) this.animateMenuItems();
                    }.bind(this);
                }
            }
            animateMenuItems() {
                document.querySelectorAll('.nav-sidebar .nav-item').forEach((item, index) => {
                    item.style.opacity = '0';
                    item.style.transform = 'translateX(-20px)';
                    setTimeout(() => {
                        item.style.transition = 'all 0.3s cubic-bezier(0.4, 0, 0.2, 1)';
                        item.style.opacity = '1';
                        item.style.transform = 'translateX(0)';
                    }, index * 50);
                });
            }
            initMenuAnimations() {
                setTimeout(() => this.animateMenuItems(), 300);
            }
            showLogoutLoading() {
                if (this.isLoading) return;
                this.isLoading = true;
                this.bar.classList.add('active');
                let width = 0;
                const animate = () => {
                    if (width < 90 && this.isLoading) {
                        width += 2;
                        this.bar.style.width = width + '%';
                        setTimeout(animate, 15);
                    }
                };
                animate();
                setTimeout(() => {
                    this.bar.style.width = '100%';
                    setTimeout(() => {
                        this.bar.classList.remove('active');
                        this.bar.style.width = '0%';
                        this.isLoading = false;
                    }, 200);
                }, 800);
            }
            setupFooterControl() {
                const adjust = () => {
                    const ch = $('.content-wrapper').height(),
                        wh = $(window).height(),
                        fh = 60,
                        nh = $('.main-header').outerHeight();
                    if (ch > (wh - nh - fh)) {
                        $(window).scroll(() => {
                            $('.main-footer').toggleClass('footer-visible', $(window).scrollTop() + wh >= $(
                                    document).height() - 10)
                                .toggleClass('footer-hidden', $(window).scrollTop() + wh < $(document)
                                    .height() - 10);
                        });
                    } else {
                        $('.main-footer').addClass('footer-visible').removeClass('footer-hidden');
                        $(window).off('scroll');
                    }
                };
                adjust();
                window.addEventListener('resize', adjust);
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            window.enhancedLoader = new EnhancedLoader();
        });

        // --- 2. GLOBAL NOTIFICATION LOGIC ---
        $(document).ready(function() {
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 5000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer);
                    toast.addEventListener('mouseleave', Swal.resumeTimer);
                }
            });

            @if (session('success'))
                Toast.fire({
                    icon: 'success',
                    title: "{!! session('success') !!}"
                });
            @endif

            @if (session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: "{!! session('error') !!}",
                    confirmButtonColor: '#dc3545',
                    confirmButtonText: 'Tutup'
                });
            @endif

            @if ($errors->any())
                let errorMsg = '';
                @foreach ($errors->all() as $error)
                    errorMsg += '{{ $error }}\n';
                @endforeach
                Swal.fire({
                    icon: 'warning',
                    title: 'Periksa Inputan',
                    text: errorMsg,
                    confirmButtonColor: '#ffc107',
                    confirmButtonText: 'OK'
                });
            @endif
        });
    </script>

    @stack('scripts')
</body>

</html>
