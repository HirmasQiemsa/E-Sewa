<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>E-Sewa | Dispora Kota Semarang</title>

    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet" href="{{ asset('lte/plugins/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">

    <link rel="stylesheet" href="{{ asset('lte/dist/css/adminlte.min.css') }}">
    <link rel="stylesheet" href="{{ asset('lte/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="https://npmcdn.com/flatpickr/dist/themes/material_blue.css">
    @stack('css')

    <style>
        /* --- 1. HEADER STYLING --- */
        .app-header {
            border-bottom: none !important;
            padding-top: 0.5rem !important;
            padding-bottom: 0.5rem !important;
            min-height: 80px;
            display: flex;
            align-items: center;
        }

        .main-header {
            border-bottom: 0px !important;
        }

        /* --- 2. NAVBAR ITEM ALIGNMENT --- */
        .navbar-nav {
            align-items: center;
            height: 100%;
        }

        .navbar-nav .nav-item {
            display: flex;
            align-items: center;
            height: 100%;
        }

        /* --- 3. MOBILE BRAND SCROLL TO TOP --- */
        .mobile-brand-scroll {
            display: flex;
            align-items: center;
            color: #fff !important;
            text-decoration: none;
            cursor: pointer;
            margin-left: 5px;
            /* Jarak dikit dari hamburger */
        }

        .mobile-brand-scroll:hover {
            color: #f8f9fa !important;
        }

        /* Text styling khusus header mobile */
        .mobile-brand-text .brand-title {
            font-size: 1rem;
            font-weight: 700;
            line-height: 1.1;
        }

        .mobile-brand-text .brand-subtitle {
            font-size: 0.75rem;
            opacity: 0.9;
        }

        /* --- 4. CONTENT WRAPPER SPACING --- */
        .content-wrapper {
            padding-top: 40px !important;
        }

        /* --- 5. PROFILE DROPDOWN STYLING --- */
        .navbar .user-panel img {
            width: 38px;
            height: 38px;
            object-fit: cover;
            border: 2px solid rgba(255, 255, 255, 0.8);
        }

        .profile-dropdown-menu {
            width: 320px;
            border-radius: 16px;
            border: none;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.15);
            padding: 0;
            margin-top: 10px;
            overflow: hidden;
        }

        .profile-card-header {
            text-align: center;
            padding: 20px 15px;
            background-color: #f8f9fa;
            border-bottom: 1px solid #eee;
        }

        .profile-card-header img {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: 10px;
        }

        .profile-name {
            font-weight: 700;
            color: #202124;
            font-size: 1.1rem;
            margin-bottom: 0;
        }

        .profile-role {
            color: #5f6368;
            font-size: 0.85rem;
            margin-bottom: 10px;
        }

        .manage-account-btn {
            border: 1px solid #dadce0;
            border-radius: 100px;
            color: #3c4043;
            font-weight: 600;
            font-size: 0.85rem;
            padding: 6px 20px;
            text-decoration: none;
            display: inline-block;
            transition: background 0.2s;
        }

        .manage-account-btn:hover {
            background: #f1f3f4;
            color: #3c4043;
        }

        /* Notification Area Inside Profile */
        .profile-notifications {
            padding: 0;
            max-height: 200px;
            overflow-y: auto;
        }

        .notif-header {
            font-size: 0.75rem;
            font-weight: 700;
            color: #5f6368;
            padding: 10px 20px 5px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .notif-item {
            display: flex;
            align-items: start;
            padding: 10px 20px;
            border-bottom: 1px solid #f1f1f1;
            text-decoration: none;
            color: #333;
            transition: background 0.2s;
        }

        .notif-item:hover {
            background: #fff8f8;
            color: #333;
        }

        .notif-icon {
            margin-right: 12px;
            margin-top: 3px;
        }

        .notif-text {
            font-size: 0.9rem;
            line-height: 1.3;
        }

        .notif-time {
            font-size: 0.75rem;
            color: #888;
            display: block;
            margin-top: 4px;
        }

        /* Logout Area */
        .profile-footer {
            padding: 10px;
            background: #fff;
            text-align: center;
            border-top: 1px solid #eee;
        }

        .logout-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 10px;
            color: #dc3545;
            font-weight: 600;
            border-radius: 8px;
            text-decoration: none;
            transition: background 0.2s;
        }

        .logout-btn:hover {
            background: #fce8e6;
            color: #c5221f;
        }

        /* Progress Bar */
        .logout-progress-bar {
            position: fixed;
            top: 0;
            left: 0;
            width: 0%;
            height: 3px;
            background: #fff;
            z-index: 9999;
            transition: width 0.4s;
        }

        /* =====================
   BANTUAN – BASE
===================== */
        .bantuan-item {
            position: relative;
        }

        .bantuan-item .parent-link {
            padding-right: 40px;
            transition: all 0.2s ease;
        }

        .bantuan-item>.nav-link.active {
            background-color: #17a2b8 !important;
            /* bg-info */
            color: #fff !important;

            /* glow tipis */
            box-shadow: 0 0 0 2px rgba(23, 162, 184, 0.25);
        }

        /* icon parent ikut nyala */
        .bantuan-item>.nav-link.active .nav-icon {
            color: #ffffff !important;
        }

        .bantuan-item .nav-treeview .nav-link.active {
            background-color: rgba(23, 162, 184, 0.15) !important;
            color: #17a2b8 !important;
            font-weight: 500;
        }

        /* child icon */
        .bantuan-item .nav-treeview .nav-link.active .nav-icon {
            color: #17a2b8 !important;
        }

        .bantuan-item.menu-open>.nav-link {
            box-shadow: 0 0 0 1px rgba(23, 162, 184, 0.2);
        }

        .nav-treeview {
            padding-left: 10px;
        }

        .nav-treeview>.nav-item>.nav-link {
            width: 100%;
            margin-right: 0;
            border-radius: 6px;
        }

        .dark-mode .bantuan-item>.nav-link.active {
            background-color: #138496 !important;
            box-shadow: 0 0 0 2px rgba(19, 132, 150, 0.4);
        }

        .dark-mode .bantuan-item .nav-treeview .nav-link.active {
            background-color: rgba(19, 132, 150, 0.25) !important;
            color: #9fe8f3 !important;
        }

        .dark-mode .bantuan-item .nav-icon {
            color: #cfeff5;
        }
    </style>
</head>

<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed">
    <div id="logoutProgressBar" class="logout-progress-bar"></div>

    <div class="wrapper">

        <nav class="main-header navbar navbar-expand navbar-white navbar-light bg-dark shadow-sm app-header">

            <ul class="navbar-nav">

                <li class="nav-item d-md-none">
                    <a class="nav-link text-white" data-widget="pushmenu" href="#" role="button">
                        <i class="fas fa-bars"></i>
                    </a>
                </li>

                <li class="nav-item d-md-none">
                    <a class="nav-link mobile-brand-scroll" href="#"
                        onclick="window.scrollTo({top: 0, behavior: 'smooth'}); return false;">
                        <img src="{{ asset('img/logo.png') }}" alt="Logo"
                            style="width: 35px; height: 35px; object-fit: contain; margin-right: 10px;">
                        <div class="mobile-brand-text text-white">
                            <div class="brand-title">E-SEWA</div>
                            <div class="brand-subtitle">Dispora Kota Semarang</div>
                        </div>
                    </a>
                </li>
            </ul>

            <ul class="navbar-nav ml-auto">
                <li class="nav-item dropdown">
                    <a class="nav-link user-panel d-flex align-items-center p-1 mr-2" data-toggle="dropdown"
                        href="#">
                        <div class="image">
                            <img src="{{ Auth::user()->foto ? asset('storage/' . Auth::user()->foto) : asset('img/default-user.png') }}"
                                class="img-circle elevation-1" alt="User Image">
                        </div>
                    </a>

                    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right profile-dropdown-menu">
                        <div class="profile-card-header">
                            <img src="{{ Auth::user()->foto ? asset('storage/' . Auth::user()->foto) : asset('img/default-user.png') }}"
                                alt="Profile">
                            <p class="profile-name">Halo, {{ Str::words(Auth::user()->name, 1, '') }}</p>
                            <p class="profile-role">
                                {{ Auth::user()->role == 'user' ? 'Masyarakat' : Auth::user()->role ?? 'User' }}
                            </p>
                            <a href="{{ route('user.profile.index') }}" class="manage-account-btn">Kelola Akun Anda</a>
                        </div>

                        <div class="profile-notifications">
                            <div class="notif-header">Pemberitahuan Baru</div>
                            <a href="#" class="notif-item">
                                <div class="notif-icon text-warning"><i class="fas fa-file-invoice-dollar"></i></div>
                                <div>
                                    <div class="notif-text">Tagihan belum dibayar</div>
                                    <span class="notif-time">3 menit yang lalu</span>
                                </div>
                            </a>
                        </div>

                        <div class="profile-footer">
                            <form method="POST" action="{{ route('logout') }}" id="logout-form">
                                @csrf
                                <a href="#" class="logout-btn"
                                    onclick="event.preventDefault(); document.getElementById('logoutProgressBar').style.width='100%'; setTimeout(()=>this.closest('form').submit(), 500);">
                                    <i class="fas fa-sign-out-alt mr-2"></i> Keluar
                                </a>
                            </form>
                        </div>
                    </div>
                </li>
            </ul>
        </nav>

        <aside class="main-sidebar sidebar-light-danger elevation-2" style="background: #f8f9fa;">

            <div class="brand-area border-bottom px-3 pt-4 pb-3 d-none d-md-block">
                <div class="d-flex align-items-center gap-2">
                    <img src="{{ asset('img/logo.png') }}" alt="Logo"
                        style="width: 40px; height: 40px; object-fit: contain;">
                    <div style="line-height: 1.1; margin-left: 10px;">
                        <div style="font-size: 1rem; font-weight: 700; color: #212529;">E-SEWA</div>
                        <div style="font-size: 0.75rem; color: #6c757d;">Dispora Kota Semarang</div>
                    </div>
                </div>
            </div>

            <div class="sidebar mt-4">
                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                        data-accordion="false">

                        <li class="nav-item">
                            <a href="{{ route('user.beranda') }}" class="nav-link ">
                                <i class="nav-icon fas fa-home"></i>
                                <p>Beranda</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            {{-- Perhatikan bagian routeIs di bawah ini --}}
                            <a href="{{ route('user.fasilitas') }}"
                                class="nav-link {{ request()->routeIs('user.fasilitas*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-volleyball-ball"></i>
                                <p>Fasilitas & Jadwal</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('user.pengajuan.index') }}" class="nav-link {{ request()->routeIs('user.pengajuan*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-calendar-plus"></i>
                                <p>Pengajuan Event</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('user.riwayat') }}"
                                class="nav-link {{ request()->routeIs('user.riwayat') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-history"></i>
                                <p>Riwayat Pemesanan</p>
                            </a>
                        </li>

                        <li
                            class="nav-item has-treeview bantuan-item {{ request()->routeIs('user.bantuan*') ? 'menu-open' : '' }}">
                            <a href="#"
                                class="nav-link parent-link {{ request()->routeIs('user.bantuan*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-headset"></i>
                                <p>Pusat Bantuan</p>
                            </a>

                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="#"
                                        class="nav-link {{ request()->routeIs('user.bantuan.chat') ? 'active' : '' }}">
                                        <i class="nav-icon fas fa-comments"></i>
                                        <p>Chat Admin</p>
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a href="#"
                                        class="nav-link {{ request()->routeIs('user.bantuan.panduan') ? 'active' : '' }}">
                                        <i class="nav-icon fas fa-book"></i>
                                        <p>Panduan Penggunaan</p>
                                    </a>
                                </li>
                            </ul>
                        </li>


                    </ul>
                </nav>
            </div>
        </aside>

        <div class="content-wrapper">
            @yield('content')
        </div>

        <footer class="main-footer bg-danger text-sm border-top">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center">
                <div class="text-center text-md-left mb-2 mb-md-0">
                    <strong>Copyright &copy; 2026 E-SEWA FASILITAS.</strong> All rights reserved.
                </div>
                <div class="text-center text-md-right d-none d-sm-block">
                    <b>Version</b> 1.0.0
                </div>
            </div>
        </footer>
    </div>

    <script src="{{ asset('lte/plugins/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('lte/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('lte/dist/js/adminlte.js') }}"></script>
    <script src="{{ asset('lte/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/id.js"></script>


    @stack('scripts')
</body>

</html>
