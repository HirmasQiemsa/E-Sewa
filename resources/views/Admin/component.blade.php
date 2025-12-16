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

    <style>
    /* Styling Profil User */
    .user-profile {
        padding: 20px 10px;
        text-align: center;
        border-bottom: 1px solid rgba(255,255,255,0.1);
        transition: all 0.3s ease-in-out;
        white-space: nowrap; /* Mencegah teks turun baris */
        overflow: hidden;    /* Sembunyikan konten yang meluap */
    }

    .user-profile .image img {
        width: 80px;
        height: 80px;
        object-fit: cover;
        border-radius: 50%;
        border: 3px solid rgba(255,255,255,0.2);
        transition: all 0.3s;
    }

    .user-profile .info {
        margin-top: 10px;
        color: white;
        transition: opacity 0.3s;
    }

    .role-badge {
        background: rgba(255,255,255,0.2);
        padding: 2px 10px;
        border-radius: 10px;
        font-size: 0.8em;
    }

    /* Fix Logo Shield Shape */
    .brand-link {
        display: flex;
        align-items: center;
        padding: 0.8125rem 0.5rem;
    }

    .brand-link .brand-image {
        float: none;
        margin-right: 10px;
        margin-left: 10px;
        max-height: 35px;
        width: auto;
        border-radius: 0;
        opacity: .8;
    }

    /* === LOGIC SAAT SIDEBAR MINI (COLLAPSED) === */
    .sidebar-collapse .user-profile {
        padding: 10px 5px;
    }

    /* Perkecil foto profil */
    .sidebar-collapse .user-profile .image img {
        width: 40px;
        height: 40px;
        border-width: 2px;
    }

    /* Sembunyikan teks nama & role */
    .sidebar-collapse .user-profile .info {
        opacity: 0;
        height: 0;
        margin: 0;
        visibility: hidden;
    }
</style>
</head>

<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">

        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
                </li>
            </ul>
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-danger btn-sm">
                            <i class="fas fa-sign-out-alt"></i> Logout
                        </button>
                    </form>
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
                    </div>
                </div>

                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                        data-accordion="false">

                        {{-- DASHBOARD --}}
                        <li class="nav-item">
                            <a href="{{ route('admin.dashboard') }}"
                                class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-tachometer-alt"></i>
                                <p>Dashboard</p>
                            </a>
                        </li>

                        {{-- MENU SUPER ADMIN --}}
                        @if($user->role == 'super_admin')
                            <li class="nav-header">KEPALA DINAS</li>
                            <li class="nav-item">
                                <a href="{{ route('admin.super.users.index') }}"
                                    class="nav-link {{ request()->routeIs('admin.super.users.*') ? 'active' : '' }}">
                                    <i class="nav-icon fas fa-users-cog"></i>
                                    <p>Manajemen User</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.fasilitas.index') }}"
                                    class="nav-link {{ request()->routeIs('admin.fasilitas.data.*') ? 'active' : '' }}">
                                    <i class="nav-icon fas fa-eye"></i>
                                    <p>Pantau Fasilitas</p>
                                </a>
                            </li>
                        @endif

                        {{-- MENU ADMIN FASILITAS --}}
                        @if($user->role == 'admin_fasilitas')
                            <li class="nav-header">OPERASIONAL</li>

                            {{--
                            LOGIC MENU OPEN:
                            Parent aktif jika route anak-anaknya (data.* atau jadwal.*) sedang diakses.
                            --}}
                            @php
                                // Cek apakah route saat ini diawali dengan 'admin.fasilitas.data.'
                                $isActiveData = request()->routeIs('admin.fasilitas.data.*');
                                // Cek apakah route saat ini diawali dengan 'admin.fasilitas.jadwal.'
                                $isActiveJadwal = request()->routeIs('admin.fasilitas.jadwal.*');

                                // Parent aktif jika salah satu child aktif
                                $isParentOpen = $isActiveData || $isActiveJadwal;
                            @endphp

                            <li class="nav-item {{ $isParentOpen ? 'menu-is-opening menu-open' : '' }}">
                                <a href="#" class="nav-link {{ $isParentOpen ? 'active' : '' }}">
                                    <i class="nav-icon fas fa-building"></i>
                                    <p>
                                        Kelola Fasilitas
                                        <i class="right fas fa-angle-left"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview">
                                    <li class="nav-item">
                                        {{-- Pastikan routeIs menangkap semua aksi (create, edit, index) --}}
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

                            {{-- Menu Booking (Terpisah) --}}
                            <li class="nav-item">
                                <a href="{{ route('admin.fasilitas.booking.index') }}"
                                    class="nav-link {{ request()->routeIs('admin.fasilitas.booking.*') ? 'active' : '' }}">
                                    <i class="nav-icon fas fa-calendar-check"></i>
                                    <p>Daftar Booking</p>
                                </a>
                            </li>
                        @endif

                        {{-- MENU ADMIN PEMBAYARAN --}}
                        @if($user->role == 'admin_pembayaran')
                            <li class="nav-header">KEUANGAN</li>
                            <li class="nav-item">
                                <a href="{{ route('admin.keuangan.verifikasi.index') }}"
                                    class="nav-link {{ request()->routeIs('admin.keuangan.verifikasi.*') ? 'active' : '' }}">
                                    <i class="nav-icon fas fa-check-double"></i>
                                    <p>Verifikasi Pembayaran</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.keuangan.transaksi') }}"
                                    class="nav-link {{ request()->routeIs('admin.keuangan.transaksi') ? 'active' : '' }}">
                                    <i class="nav-icon fas fa-file-invoice-dollar"></i>
                                    <p>Laporan Transaksi</p>
                                </a>
                            </li>
                        @endif

                        {{-- PROFILE --}}
                        <li class="nav-header">PENGATURAN</li>
                        <li class="nav-item">
                            <a href="{{ route('admin.profile.edit') }}"
                                class="nav-link {{ request()->routeIs('admin.profile.*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-user-cog"></i>
                                <p>Profile Saya</p>
                            </a>
                        </li>

                    </ul>
                </nav>
            </div>
        </aside>

        <div class="content-wrapper">
            @yield('content')
        </div>

        <footer class="main-footer">
            <strong>Copyright &copy; 2025 <a href="#">Dispora Semarang</a>.</strong> All rights reserved.
        </footer>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        @if(session('success'))
            Swal.fire({ icon: 'success', title: 'Berhasil', text: "{{ session('success') }}", timer: 3000, showConfirmButton: false });
        @endif
        @if(session('error'))
            Swal.fire({ icon: 'error', title: 'Gagal', text: "{{ session('error') }}" });
        @endif
    </script>

    @yield('scripts')
</body>

</html>
