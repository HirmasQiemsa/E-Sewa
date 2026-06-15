{{-- LOGIC HEADER BERDASARKAN AUTH --}}
@auth
    <nav id="navbarAuth" class="main-header navbar navbar-expand-md navbar-auth">
        <div class="container">
            {{-- 1. LOGO / BRAND AREA (DESKTOP) --}}
            <a href="#" class="navbar-brand d-none d-md-flex align-items-center"
                onclick="window.scrollTo({top: 0, behavior: 'smooth'}); return false;">
                <img src="{{ asset('img/logo.png') }}" alt="Logo" class="brand-image elevation-0">
                <div class="brand-text-wrapper">
                    <div class="brand-title">E-SEWA</div>
                    <div class="brand-subtitle">Dispora Kota Semarang</div>
                </div>
            </a>

            {{-- 1.b MOBILE HEADER (Layout Baru: Burger - Brand - Profile) --}}
            <div class="d-flex d-md-none align-items-center justify-content-between w-100 mobile-header-row">
                {{-- Kiri: Burger --}}
                <button class="navbar-toggler p-0" type="button" data-toggle="collapse" data-target="#navbarCollapse">
                    <span class="fas fa-bars text-white" style="font-size: 1.4rem;"></span>
                </button>

                {{-- Tengah: Brand --}}
                <a class="mobile-brand-scroll d-flex align-items-center mx-auto" href="#"
                    onclick="window.scrollTo({top: 0, behavior: 'smooth'}); return false;">
                    <img src="{{ asset('img/logo.png') }}" alt="Logo"
                        style="width: 32px; height: 32px; object-fit: contain; margin-right: 8px;">
                    <div class="text-white text-center" style="line-height: 1.1;">
                        <div style="font-weight: 700; font-size: 0.9rem;">E-SEWA</div>
                        <div style="font-size: 0.65rem; opacity: 0.9;">Dispora Semarang</div>
                    </div>
                </a>

                {{-- Kanan: Profile Circle (Mirip Desktop saat scroll) --}}
                <a href="{{ route('user.profile.index') }}" class="mobile-profile-link">
                    <img src="{{ Auth::user()->foto ? asset('storage/' . Auth::user()->foto) : asset('img/default-user.png') }}"
                        style="width: 35px; height: 35px; border-radius: 50%; object-fit: cover; border: 1.5px solid #fff;">
                </a>
            </div>

            {{-- 2. MENU ITEM --}}
            <div class="collapse navbar-collapse order-3" id="navbarCollapse">
                <ul class="navbar-nav ml-auto mr-auto nav-scroll-spy">
                    <li class="nav-item">
                        <a href="{{ request()->routeIs('user.beranda') ? '#home' : route('user.beranda') . '#home' }}"
                            class="nav-link active-border">Beranda</a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ request()->routeIs('user.beranda') ? '#fasilitas' : route('user.beranda') . '#fasilitas' }}"
                            class="nav-link active-border">Fasilitas</a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ request()->routeIs('user.beranda') ? '#event' : route('user.beranda') . '#event' }}"
                            class="nav-link active-border">Pengajuan Event</a>
                    </li>

                    {{-- Lokasi Riwayat yang nantinya jadi notifikasi --}}
                    {{-- @future: Pindahkan ke sistem notifikasi --}}
                    <li class="nav-item">
                        <a href="{{ route('user.riwayat') }}" class="nav-link active-border">Riwayat</a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ request()->routeIs('user.beranda') ? '#bantuan' : route('user.beranda') . '#bantuan' }}"
                            class="nav-link active-border">Bantuan</a>
                    </li>

                    {{-- Logout Area Khusus Mobile (Gantiin Dropdown Profile) --}}
                    <li class="nav-item d-md-none logout-mobile-item">
                        <hr class="border-secondary opacity-20">
                        <a href="{{ route('logout') }}"
                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                            class="nav-link text-danger font-weight-bold">
                            <i class="fas fa-sign-out-alt mr-2"></i> Keluar Akun
                        </a>
                    </li>
                </ul>
            </div>

            {{-- 3. DESKTOP PROFILE (Tetap ada untuk Desktop) --}}
            <ul class="navbar-nav ml-auto order-1 order-md-3 d-none d-md-flex">
                <li class="nav-item dropdown">
                    <a class="nav-link user-panel-trigger" data-toggle="dropdown" href="#">
                        <div class="image">
                            <img src="{{ Auth::user()->foto ? asset('storage/' . Auth::user()->foto) : asset('img/default-user.png') }}" alt="User">
                        </div>
                        <span class="user-name-text">{{ Str::words(Auth::user()->name, 1, '') }}</span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right profile-dropdown-simple">
                        <a href="{{ route('user.profile.index') }}" class="dropdown-item">
                            <i class="fas fa-user-cog mr-2"></i> Kelola Akun
                        </a>
                        <div class="dropdown-divider"></div>
                        <form method="POST" action="{{ route('logout') }}" id="logout-form">
                            @csrf
                            <button type="submit" class="dropdown-item text-danger">
                                <i class="fas fa-sign-out-alt mr-2"></i> Logout
                            </button>
                        </form>
                    </div>
                </li>
            </ul>
        </div>
    </nav>
@endauth

@push('css')
<style>
    /* ===============================
       Navbar Auth & Guest Base
    =============================== */
    .navbar-guest {
        background-color: #fff;
        transition: all 0.3s ease-in-out;
    }

    .navbar-auth {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        z-index: 1040;
        background-color: transparent;
        border-bottom: none;
        transition: all 0.4s ease;
        padding: 20px 0;
    }

    .navbar-auth.scrolled {
        background-color: rgba(0, 0, 0, 0.85);
        backdrop-filter: blur(10px);
        padding: 8px 0;
        box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1);
    }

    /* ===============================
       Brand & Logo
    =============================== */
    .navbar-auth .brand-image {
        width: 45px;
        height: 45px;
        object-fit: contain;
        background: transparent;
        margin-right: 10px;
        transition: all 0.3s;
    }

    .navbar-auth.scrolled .brand-image {
        width: 38px;
        height: 38px;
    }

    .brand-text-wrapper {
        line-height: 1.2;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .brand-title {
        font-size: 1.1rem;
        font-weight: 700;
        letter-spacing: 0.5px;
        color: #fff;
    }

    .brand-subtitle {
        font-size: 0.75rem;
        font-weight: 300;
        opacity: 0.9;
        color: rgba(255, 255, 255, 0.8);
    }

    /* ===============================
       Navigation Links
    =============================== */
    .navbar-auth .nav-link {
        color: rgba(255, 255, 255, 0.7) !important;
        font-weight: 500;
        position: relative;
        margin: 0 10px;
        transition: color 0.3s;
    }

    .navbar-auth .nav-link:hover,
    .navbar-auth .nav-link.active {
        color: #fff !important;
    }

    .navbar-auth .nav-link::after {
        content: '';
        position: absolute;
        width: 0;
        height: 2px;
        bottom: 0;
        left: 50%;
        background-color: #dc3545;
        transition: 0.3s;
        transform: translateX(-50%);
    }

    .navbar-auth .nav-link:hover::after,
    .navbar-auth .nav-link.active::after {
        width: 100%;
    }

    /* ===============================
       Profile Panel & Dropdown
    =============================== */
    .user-name-text {
        max-width: 150px;
        margin-left: 10px;
        color: white;
        font-weight: bold;
        transition: all 0.3s;
    }

    .navbar-auth.scrolled .user-name-text {
        opacity: 0;
        max-width: 0;
        margin-left: 0;
        overflow: hidden;
    }

    .user-panel-trigger {
        padding: 5px;
        border-radius: 50px;
        display: flex;
        align-items: center;
        transition: background 0.2s;
    }

    .user-panel-trigger:hover {
        background: rgba(255, 255, 255, 0.15);
    }

    .user-panel-trigger img {
        width: 38px;
        height: 38px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid rgba(255, 255, 255, 0.8);
    }

    /* Dropdown versi simple desktop */
    .profile-dropdown-simple {
        border-radius: 8px;
        border: none;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        min-width: 180px;
    }

    /* ===============================
       Mobile Custom Logic
    =============================== */
    @media (max-width: 767.98px) {
        .navbar-auth {
            background-color: rgba(0, 0, 0, 0.9) !important;
            padding: 12px 0 !important;
        }

        .navbar-collapse {
            padding-left: 5px;
            margin-top: 15px;
        }

        .navbar-nav .nav-link {
            padding-left: 0 !important;
            margin-left: 0 !important;
            display: inline-block;
            width: auto;
        }

        /* Garis active tidak full mentok kanan */
        .navbar-auth .nav-link::after {
            left: 0;
            transform: none;
        }

        .mobile-profile-link:hover {
            opacity: 0.8;
        }
    }

    /* ===============================
       Dropdown Animation
    =============================== */
    @keyframes slideInUp {
        from {
            opacity: 0;
            transform: translate3d(0, 15px, 0);
        }
        to {
            opacity: 1;
            transform: translate3d(0, 0, 0);
        }
    }

    .dropdown-menu.show {
        animation: slideInUp 0.3s forwards;
    }
</style>

@endpush

@push('scripts')
<script>
    const navbarAuth = document.getElementById('navbarAuth');
    const navbarCollapse = document.getElementById('navbarCollapse');

    if (navbarAuth) {
        window.addEventListener('scroll', () => {
            // Cek jika mobile dan menu lagi kebuka, jangan hapus background hitamnya
            if (window.scrollY > 50 || (navbarCollapse && navbarCollapse.classList.contains('show'))) {
                navbarAuth.classList.add('scrolled');
            } else {
                navbarAuth.classList.remove('scrolled');
            }
        });

        // Handler saat burger diklik
        $(navbarCollapse).on('show.bs.collapse', function () {
            navbarAuth.classList.add('scrolled');
        });

        $(navbarCollapse).on('hidden.bs.collapse', function () {
            if (window.scrollY <= 50) {
                navbarAuth.classList.remove('scrolled');
            }
        });
    }
</script>
@endpush
