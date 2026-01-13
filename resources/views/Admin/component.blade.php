<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>E-Sewa Fasilitas Dispora</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/overlayscrollbars/1.13.0/css/OverlayScrollbars.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@x.x.x/dist/select2-bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    {{-- Theme Gelap/Biru biar cocok sama AdminLTE --}}
    <link rel="stylesheet" href="https://npmcdn.com/flatpickr/dist/themes/material_blue.css">

    <style>
        /* === CUSTOM NAVBAR MERAH (FIXED) === */
        /* Pastikan background merah menimpa style dark/light mode */
        .main-header {
            background-color: #dc3545 !important;
            /* Merah Danger */
            border-bottom: 1px solid #dc3545 !important;
            transition: margin-left .3s ease-in-out;
        }

        /* Paksa teks dan icon di navbar jadi putih */
        .main-header .nav-link,
        .main-header .navbar-brand,
        .main-header .btn-link {
            color: #ffffff !important;
        }

        /* Hover effect pada link navbar */
        .main-header .nav-link:hover {
            color: #f8f9fa !important;
            background-color: rgba(255, 255, 255, 0.1);
            border-radius: 5px;
        }

        /* === USER PROFILE SIDEBAR === */
        .user-profile {
            padding: 20px 10px;
            text-align: center;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            transition: all 0.3s ease-in-out;
            white-space: nowrap;
            overflow: hidden;
        }

        .user-profile .image img {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 50%;
            border: 3px solid rgba(255, 255, 255, 0.2);
            transition: all 0.3s;
        }

        .user-profile .info {
            margin-top: 10px;
            color: white;
            transition: opacity 0.3s;
        }

        .role-badge {
            background: rgba(255, 255, 255, 0.2);
            padding: 2px 10px;
            border-radius: 10px;
            font-size: 0.8em;
        }

        /* Logo Brand */
        .brand-link {
            display: flex;
            align-items: center;
            padding: 0.8125rem 0.5rem;
            background-color: #343a40;
            /* Samakan dengan sidebar dark */
        }

        .brand-link .brand-image {
            float: none;
            margin-right: 10px;
            margin-left: 10px;
            max-height: 35px;
            width: auto;
            opacity: .8;
        }

        /* === SIDEBAR COLLAPSED LOGIC === */
        .sidebar-collapse .user-profile {
            padding: 10px 5px;
        }

        .sidebar-collapse .user-profile .image img {
            width: 40px;
            height: 40px;
            border-width: 2px;
        }

        .sidebar-collapse .user-profile .info {
            opacity: 0;
            height: 0;
            margin: 0;
            visibility: hidden;
        }

        .sidebar-collapse .user-profile .mt-3 {
            display: none !important;
        }

        /* Logout Button */
        .nav-link-logout {
            color: #dc3545 !important;
        }

        .nav-link-logout:hover {
            background-color: rgba(220, 53, 69, 0.1) !important;
            color: #ff6b6b !important;
        }

        /* === SWEETALERT2 DARK MODE FIXES === */
        body.dark-mode .swal2-popup {
            background-color: #343a40 !important;
            color: #e9ecef !important;
        }

        body.dark-mode .swal2-title {
            color: #ffffff !important;
        }

        body.dark-mode .swal2-html-container {
            color: #cccccc !important;
        }

        body.dark-mode .swal2-icon.swal2-success .swal2-success-ring {
            border-color: #28a745;
        }

        body.dark-mode .swal2-icon.swal2-success [class^='swal2-success-circular-line'],
        body.dark-mode .swal2-icon.swal2-success .swal2-success-fix,
        body.dark-mode .swal2-icon.swal2-success [class^='swal2-success-line'] {
            background-color: #343a40 !important;
        }

        body.dark-mode .swal2-confirm {
            background-color: #007bff !important;
        }

        /* Custom Scrollbar Sidebar */
        .user-profile .list-unstyled {
            max-height: 80px;
            overflow-y: auto;
        }

        .user-profile .list-unstyled::-webkit-scrollbar {
            width: 3px;
        }

        .user-profile .list-unstyled::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.2);
            border-radius: 3px;
        }
    </style>
</head>

<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed">

    <div class="wrapper">

        <nav class="main-header navbar navbar-expand navbar-dark navbar-danger border-bottom-0">
            <ul class="navbar-nav">
                {{-- 1. PUSHMENU --}}
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i
                            class="fas fa-bars"></i></a>
                </li>

                {{-- 2. TANGGAL --}}
                <li class="nav-item d-none d-sm-inline-block">
                    <div class="nav-link text-light">
                        <i class="far fa-calendar-alt mr-1"></i>
                        {{ \Carbon\Carbon::now()->locale('id')->isoFormat('dddd, D MMMM Y') }}
                    </div>
                </li>
            </ul>

            <ul class="navbar-nav ml-auto">
                {{-- 3. IDENTITY --}}
                <li class="nav-item d-flex align-items-center">
                    <div class="text-light mr-2">
                        Halo, <b>{{ Auth::guard('admin')->user()->name }}</b>
                    </div>
                </li>

                {{-- 4. DARK MODE TOGGLE --}}
                <li class="nav-item">
                    <a class="nav-link" id="darkModeBtn" href="#" role="button" title="Ganti Mode Gelap/Terang">
                        <i class="fas fa-moon"></i>
                    </a>
                </li>
            </ul>
        </nav>

        <aside class="main-sidebar sidebar-dark-primary elevation-4">
            <a href="{{ route('admin.dashboard') }}" class="brand-link">
                <img src="{{ asset('img/logo.png') }}" alt="Logo" class="brand-image"
                    style="opacity: .8; max-height: 35px; width: auto; margin-top: -3px;">
                <span class="brand-text font-weight-light">E-SEWA DISPORA</span>
            </a>

            <div class="sidebar">
                <div class="user-profile">
                    <div class="image">
                        @php $user = Auth::guard('admin')->user(); @endphp
                        <img src="{{ $user->foto ? asset('storage/' . $user->foto) : asset('img/default-user.png') }}"
                            alt="User Image">
                    </div>
                    <div class="info">
                        <h6 class="mb-0">{{ $user->name }}</h6>
                        <span class="role-badge">{{ str_replace('_', ' ', strtoupper($user->role)) }}</span>

                        {{-- INFO FASILITAS KELOLAAN (Logic PHP tetap sama) --}}
                        @if ($user->role == 'admin_pembayaran')
                            @php
                                $handledFasilitas = \App\Models\Fasilitas::where(
                                    'admin_pembayaran_id',
                                    $user->id,
                                )->get();
                            @endphp
                            <div class="mt-3 text-left">
                                <div class="p-2 rounded"
                                    style="background-color: rgba(255, 255, 255, 0.08); border: 1px solid rgba(255, 255, 255, 0.1);">
                                    <small class="text-warning font-weight-bold d-block mb-1"
                                        style="font-size: 0.7rem;">
                                        <i class="fas fa-building mr-1"></i> FASILITAS KELOLAAN:
                                    </small>
                                    @if ($handledFasilitas->count() > 0)
                                        <ul class="list-unstyled mb-0" style="font-size: 0.8rem;">
                                            @foreach ($handledFasilitas as $f)
                                                <li class="text-truncate py-1 border-bottom border-secondary"
                                                    title="{{ $f->nama_fasilitas }}"
                                                    style="border-color: rgba(255,255,255,0.1) !important;">
                                                    <i class="fas fa-circle text-success mr-2"
                                                        style="font-size: 0.5rem; vertical-align: middle;"></i>
                                                    <span style="color: #e2e6ea;">{{ $f->nama_fasilitas }}</span>
                                                </li>
                                            @endforeach
                                        </ul>
                                    @else
                                        <div class="text-center py-2"><small class="text-danger font-italic">Belum
                                                di-assign.</small></div>
                                    @endif
                                </div>
                            </div>
                        @elseif($user->role == 'admin_fasilitas')
                            <div class="mt-2">
                                <small class="text-muted d-block border-top pt-1 mt-1" style="font-size: 0.75rem;">
                                    <i class="fas fa-address-book mr-1"></i> Unit Kelolaan:
                                    <span class="text-white ml-1">
                                        {{ \App\Models\Fasilitas::where('admin_fasilitas_id', $user->id)->count() }}
                                        Unit
                                    </span>
                                </small>
                            </div>
                        @endif
                    </div>
                </div>

                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                        data-accordion="false">

                        <li class="nav-item">
                            <a href="{{ route('admin.dashboard') }}"
                                class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-tachometer-alt"></i>
                                <p>Dashboard</p>
                            </a>
                        </li>

                        @if ($user->role == 'super_admin')
                            <li class="nav-header">MANAJEMEN PENGGUNA</li>
                            <li class="nav-item">
                                <a href="{{ route('admin.super.users.index') }}"
                                    class="nav-link {{ request()->routeIs('admin.super.users.*') ? 'active' : '' }}">
                                    <i class="nav-icon fas fa-user-tie"></i>
                                    <p>Data Staff Admin</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.super.masyarakat.index') }}"
                                    class="nav-link {{ request()->routeIs('admin.super.masyarakat.*') ? 'active' : '' }}">
                                    <i class="nav-icon fas fa-users"></i>
                                    <p>Data Masyarakat</p>
                                </a>
                            </li>

                            <li class="nav-header">PENGAWASAN & VALIDASI</li>
                            @php
                                $isMonFasilitas = request()->routeIs('admin.fasilitas.data.*');
                                $isMonTransaksi = request()->routeIs('admin.super.laporan.transaksi');
                                $isMonLog = request()->routeIs('admin.super.laporan.log');
                                $isMonOpen = $isMonFasilitas || $isMonTransaksi || $isMonLog;
                            @endphp
                            <li class="nav-item {{ $isMonOpen ? 'menu-is-opening menu-open' : '' }}">
                                <a href="#" class="nav-link {{ $isMonOpen ? 'active' : '' }}">
                                    <i class="nav-icon fas fa-chart-line"></i>
                                    <p>Pusat Monitoring <i class="right fas fa-angle-left"></i></p>
                                </a>
                                <ul class="nav nav-treeview">
                                    <li class="nav-item">
                                        <a href="{{ route('admin.fasilitas.data.index') }}"
                                            class="nav-link {{ $isMonFasilitas ? 'active' : '' }}">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Pantau Fasilitas</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ route('admin.super.laporan.transaksi') }}"
                                            class="nav-link {{ $isMonTransaksi ? 'active' : '' }}">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Laporan Transaksi</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ route('admin.super.laporan.log') }}"
                                            class="nav-link {{ $isMonLog ? 'active' : '' }}">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Log Aktivitas</p>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        @endif

                        @if ($user->role == 'admin_fasilitas')
                            <li class="nav-header">OPERASIONAL</li>
                            @php
                                $isActiveData = request()->routeIs('admin.fasilitas.data.*');
                                $isActiveJadwal = request()->routeIs('admin.fasilitas.jadwal.*');
                                $isParentOpen = $isActiveData || $isActiveJadwal;
                            @endphp
                            <li class="nav-item {{ $isParentOpen ? 'menu-is-opening menu-open' : '' }}">
                                <a href="#" class="nav-link {{ $isParentOpen ? 'active' : '' }}">
                                    <i class="nav-icon fas fa-building"></i>
                                    <p>Kelola Fasilitas <i class="right fas fa-angle-left"></i></p>
                                </a>
                                <ul class="nav nav-treeview">
                                    <li class="nav-item">
                                        <a href="{{ route('admin.fasilitas.data.index') }}"
                                            class="nav-link {{ $isActiveData ? 'active' : '' }}">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Data Fasilitas</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ route('admin.fasilitas.jadwal.index') }}"
                                            class="nav-link {{ $isActiveJadwal ? 'active' : '' }}">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Jadwal & Ketersediaan</p>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.fasilitas.booking.index') }}"
                                    class="nav-link {{ request()->routeIs('admin.fasilitas.booking.*') ? 'active' : '' }}">
                                    <i class="nav-icon fas fa-calendar-check"></i>
                                    <p>Daftar Booking</p>
                                </a>
                            </li>
                        @endif

                        @if ($user->role == 'admin_pembayaran')
                            <li class="nav-header">OPERASIONAL</li>
                            <li class="nav-item">
                                <a href="{{ route('admin.keuangan.verifikasi.index') }}"
                                    class="nav-link {{ request()->routeIs('admin.keuangan.verifikasi.*') ? 'active' : '' }}">
                                    <i class="nav-icon fas fa-clipboard-check"></i>
                                    <p>Verifikasi Pembayaran</p>
                                </a>
                            </li>
                            <li class="nav-header">LAPORAN & ANALISA</li>
                            @php
                                $isRingkasan = request()->routeIs('admin.keuangan.ringkasan');
                                $isTransaksi = request()->routeIs('admin.keuangan.transaksi');
                                $isLaporanOpen = $isRingkasan || $isTransaksi;
                            @endphp
                            <li class="nav-item {{ $isLaporanOpen ? 'menu-is-opening menu-open' : '' }}">
                                <a href="#" class="nav-link {{ $isLaporanOpen ? 'active' : '' }}">
                                    <i class="nav-icon fas fa-chart-pie"></i>
                                    <p>Pusat Keuangan <i class="right fas fa-angle-left"></i></p>
                                </a>
                                <ul class="nav nav-treeview">
                                    <li class="nav-item">
                                        <a href="{{ route('admin.keuangan.ringkasan') }}"
                                            class="nav-link {{ $isRingkasan ? 'active' : '' }}">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Ringkasan & Grafik</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ route('admin.keuangan.transaksi') }}"
                                            class="nav-link {{ $isTransaksi ? 'active' : '' }}">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Data Transaksi</p>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        @endif

                        <li class="nav-header">PENGATURAN</li>
                        <li class="nav-item">
                            <a href="{{ route('admin.profile.edit') }}"
                                class="nav-link {{ request()->routeIs('admin.profile.*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-user-cog"></i>
                                <p>Profile Saya</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link nav-link-logout"
                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="nav-icon fas fa-sign-out-alt"></i>
                                <p>Logout</p>
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf</form>
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

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    {{-- Gunakan Chart.js v2.9.4 agar kompatibel dengan syntax AdminLTE --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.4/dist/Chart.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    {{-- Bahasa Indonesia (Opsional) --}}
    <script src="https://npmcdn.com/flatpickr/dist/l10n/id.js"></script>
    <script>
        @if (session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: "{{ session('success') }}",
                timer: 3000,
                showConfirmButton: false
            });
        @endif
        @if (session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Gagal',
                text: "{{ session('error') }}"
            });
        @endif

        document.addEventListener('DOMContentLoaded', function() {
            const toggleBtn = document.getElementById('darkModeBtn');
            const icon = toggleBtn.querySelector('i');
            const body = document.body;
            const navbar = document.querySelector('.main-header');

            if (localStorage.getItem('admin_theme') === 'dark') {
                enableDarkMode();
            }

            toggleBtn.addEventListener('click', function(e) {
                e.preventDefault();
                if (body.classList.contains('dark-mode')) {
                    disableDarkMode();
                } else {
                    enableDarkMode();
                }
            });

            function enableDarkMode() {
                body.classList.add('dark-mode');
                icon.classList.remove('fa-moon');
                icon.classList.add('fa-sun');
                // Navbar merah tetap merah karena ada !important di CSS
                localStorage.setItem('admin_theme', 'dark');
            }

            function disableDarkMode() {
                body.classList.remove('dark-mode');
                icon.classList.remove('fa-sun');
                icon.classList.add('fa-moon');
                localStorage.setItem('admin_theme', 'light');
            }
        });
    </script>

    @stack('scripts')
</body>

</html>
