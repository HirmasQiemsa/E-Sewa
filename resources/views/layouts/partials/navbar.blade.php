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

            {{-- 1.b MOBILE HEADER --}}
            <div class="d-flex d-md-none align-items-center justify-content-between w-100">
                <button class="navbar-toggler pl-0" type="button" data-toggle="collapse" data-target="#navbarCollapse">
                    <span class="fas fa-bars text-white" style="font-size: 1.4rem;"></span>
                </button>
                <a class="mobile-brand-scroll d-flex align-items-center" href="#"
                    onclick="window.scrollTo({top: 0, behavior: 'smooth'}); return false;">
                    <img src="{{ asset('img/logo.png') }}" alt="Logo"
                        style="width: 35px; height: 35px; object-fit: contain; margin-right: 8px;">
                    <div class="text-white" style="line-height: 1.1;">
                        <div style="font-weight: 700; font-size: 1rem;">E-SEWA</div>
                        <div style="font-size: 0.7rem; opacity: 0.9;">Dispora Semarang</div>
                    </div>
                </a>
                <div style="width: 25px;"></div>
            </div>

            {{-- 2. MENU ITEM --}}
            <div class="collapse navbar-collapse order-3" id="navbarCollapse">
                <ul class="navbar-nav ml-auto mr-auto nav-scroll-spy">
                    <li class="nav-item">
                        <a href="{{ request()->routeIs('user.beranda') ? '#home' : route('user.beranda') . '#home' }}"
                            class="nav-link active-border">
                            Beranda
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ request()->routeIs('user.beranda') ? '#fasilitas' : route('user.beranda') . '#fasilitas' }}"
                            class="nav-link active-border">
                            Fasilitas
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ request()->routeIs('user.beranda') ? '#event' : route('user.beranda') . '#event' }}"
                            class="nav-link active-border">
                            Pengajuan Event
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ request()->routeIs('user.beranda') ? '#riwayat' : route('user.beranda') . '#riwayat' }}"
                            class="nav-link active-border">
                            Riwayat
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ request()->routeIs('user.beranda') ? '#bantuan' : route('user.beranda') . '#bantuan' }}"
                            class="nav-link active-border">
                            Bantuan
                        </a>
                    </li>


                    {{-- Mobile Profile --}}
                    <li class="nav-item d-md-none mt-3 pt-3 border-top border-secondary">
                        <div class="d-flex align-items-center px-2">
                            <img src="{{ Auth::user()->foto ? asset('storage/' . Auth::user()->foto) : asset('img/default-user.png') }}"
                                style="width: 45px; height: 45px; border-radius: 50%; object-fit: cover; border: 2px solid #fff;">
                            <div class="ml-3 text-white">
                                <span class="d-block font-weight-bold">{{ Str::limit(Auth::user()->name, 20) }}</span>
                                <a href="{{ route('logout') }}"
                                    onclick="event.preventDefault(); document.getElementById('logout-form-mobile').submit();"
                                    class="btn btn-xs btn-danger px-3 rounded-pill mt-1">Logout</a>
                                <form id="logout-form-mobile" action="{{ route('logout') }}" method="POST"
                                    style="display: none;">@csrf</form>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>

            {{-- 3. DESKTOP PROFILE --}}
            <ul class="navbar-nav ml-auto order-1 order-md-3 d-none d-md-flex">
                <li class="nav-item dropdown">
                    <a class="nav-link user-panel-trigger" data-toggle="dropdown" href="{{ route('user.profile.index') }}"
                        role="button" aria-expanded="false">
                        <div class="image">
                            <img src="{{ Auth::user()->foto ? asset('storage/' . Auth::user()->foto) : asset('img/default-user.png') }}"
                                alt="User">
                        </div>
                        <span class="user-name-text">{{ Str::words(Auth::user()->name, 1, '') }}</span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right profile-dropdown-menu">
                        <div class="profile-card-header">
                            <img src="{{ Auth::user()->foto ? asset('storage/' . Auth::user()->foto) : asset('img/default-user.png') }}"
                                alt="Profile">
                            <p class="profile-name mt-2">Halo, {{ Str::words(Auth::user()->name, 2, '') }}</p>
                            <p class="profile-role text-muted small mb-3">
                                {{ Auth::user()->role == 'user' ? 'Masyarakat' : 'User' }}</p>
                            <a href="#" class="manage-account-btn">Kelola Akun</a>
                        </div>
                        <div class="p-0">
                            <a href="{{ route('user.riwayat') }}" class="dropdown-item py-3 border-bottom"><i
                                    class="fas fa-history mr-2 text-muted"></i> Riwayat Pesanan</a>
                        </div>
                        <div class="profile-footer">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                    class="logout-btn btn-block border-0 bg-transparent text-danger font-weight-bold"><i
                                        class="fas fa-sign-out-alt mr-2"></i> Keluar</button>
                            </form>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
    </nav>
@else
    {{-- NAVBAR GUEST --}}
    <nav class="main-header navbar navbar-expand-md navbar-light navbar-white shadow-sm navbar-guest">
        <div class="container">
            <a href="#" class="navbar-brand">
                <img src="{{ asset('img/logo.png') }}" alt="Logo" class="brand-image img-circle elevation-3"
                    style="opacity: .8">
                <span class="brand-text font-weight-bold text-dark">DISPORA KOTA SEMARANG</span>
            </a>
            <button class="navbar-toggler order-1" type="button" data-toggle="collapse" data-target="#navbarCollapse"><span
                    class="navbar-toggler-icon"></span></button>
            <div class="collapse navbar-collapse order-3" id="navbarCollapse">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item"><a href="#fasilitas" class="nav-link">Fasilitas</a></li>
                    <li class="nav-item"><a href="#cara-booking" class="nav-link">Cara Booking</a></li>
                    <li class="nav-item ml-2"><a href="{{ route('login') }}" class="btn btn-outline-danger px-4"><i
                                class="fas fa-sign-in-alt mr-2"></i> Login</a></li>
                </ul>
            </div>
        </div>
    </nav>
@endauth

@push('css')
    <style>
        /* Navbar Auth & Guest Base */
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

        /* Brand & Logo */
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
            transition: color 0.3s;
        }

        .brand-subtitle {
            font-size: 0.75rem;
            font-weight: 300;
            opacity: 0.9;
            color: rgba(255, 255, 255, 0.8);
            transition: color 0.3s;
        }

        .navbar-auth.scrolled .brand-subtitle {
            color: rgba(255, 255, 255, 0.95);
        }

        /* Navigation Links */
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

        .mobile-brand-scroll {
            text-decoration: none !important;
        }

        /* Profile Panel & Dropdown */
        .user-name-text {
            display: inline-block;
            max-width: 150px;
            opacity: 1;
            transition: all 0.3s;
            margin-left: 10px;
            color: white;
            font-weight: bold;
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
            transition: background 0.2s;
            display: flex;
            align-items: center;
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

        .profile-dropdown-menu {
            width: 300px;
            border-radius: 12px;
            border: none;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            margin-top: 15px;
            overflow: hidden;
        }

        .profile-card-header {
            text-align: center;
            padding: 25px 15px;
            background: #fff;
            border-bottom: 1px solid #eee;
            background-image: url('{{ asset('img/pattern-bg.png') }}');
            background-size: cover;
        }

        .profile-card-header img {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid #dfe6e9;
            margin-bottom: 10px;
        }

        .manage-account-btn {
            border: 1px solid #ced4da;
            border-radius: 20px;
            padding: 5px 20px;
            font-size: 14px;
            color: #495057;
            text-decoration: none;
            transition: 0.2s;
        }

        .manage-account-btn:hover {
            background: #f8f9fa;
            color: #000;
        }

        .profile-footer {
            padding: 15px;
            background: #f8f9fa;
            text-align: center;
        }

        /* Animation */
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
        // Logic Scroll Navbar
        const navbarAuth = document.getElementById('navbarAuth');
        if (navbarAuth) {
            window.addEventListener('scroll', () => {
                if (window.scrollY > 50) {
                    navbarAuth.classList.add('scrolled');
                } else {
                    navbarAuth.classList.remove('scrolled');
                }
            });
        }
    </script>
@endpush
