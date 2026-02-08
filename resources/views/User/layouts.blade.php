<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>E-Sewa Dispora Semarang</title>

    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet" href="{{ asset('lte/plugins/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('lte/dist/css/adminlte.min.css') }}">
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">

    <style>
        /* =========================================
           1. CUSTOM HEADER STYLES (LOGIC SCROLL)
           ========================================= */

        /* Default Navbar untuk Guest (Putih Biasa) */
        .navbar-guest {
            background-color: #fff;
            transition: all 0.3s ease-in-out;
        }

        /* Navbar untuk User Login (Fixed & Transparan Awal) */
        .navbar-auth {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1040;
            background-color: transparent;
            /* Awal transparan */
            border-bottom: none;
            transition: all 0.4s ease;
            padding-top: 15px;
            padding-bottom: 15px;
        }

        /* FIX LOGO PERISAI BIAR GAK KEPOTONG */
        .navbar-auth .brand-image {
            width: 45px;
            /* Sedikit digedein biar jelas */
            height: 45px;
            object-fit: contain;
            /* KUNCI: Biar perisai utuh dalam kotak */
            background: transparent;
            border-radius: 0;
            /* Hapus radius bulat paksa */
            margin-right: 10px;
            transition: all 0.3s;
        }

        /* Biar pas di-scroll logonya mengecil dikit biar proporsional */
        .navbar-auth.scrolled .brand-image {
            width: 38px;
            height: 38px;
        }

        /* Brand Text Styling */
        .brand-text-wrapper {
            line-height: 1.2;
        }

        .brand-title {
            font-size: 1.1rem;
            font-weight: 700;
            letter-spacing: 0.5px;
            transition: color 0.3s;
        }

        .brand-subtitle {
            font-size: 0.75rem;
            font-weight: 300;
            opacity: 0.9;
            transition: color 0.3s;
        }

        /* State saat di-scroll: Gelap + Blur */
        .navbar-auth.scrolled {
            background-color: rgba(0, 0, 0, 0.85);
            /* Hitam transparan */
            backdrop-filter: blur(10px);
            /* Efek Blur */
            -webkit-backdrop-filter: blur(10px);
            padding-top: 5px;
            padding-bottom: 5px;
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1);
        }

        /* Styling Link Navigasi User */
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

        /* Garis bawah animasi untuk menu aktif */
        .navbar-auth .nav-link::after {
            content: '';
            position: absolute;
            width: 0;
            height: 2px;
            bottom: 0;
            left: 50%;
            background-color: #dc3545;
            /* Merah Dispora */
            transition: all 0.3s ease-in-out;
            transform: translateX(-50%);
        }

        .navbar-auth .nav-link:hover::after,
        .navbar-auth .nav-link.active::after {
            width: 100%;
        }

        /* Brand Text saat Login */
        .navbar-auth .brand-text {
            color: #fff !important;
        }

        /* Navbar Auth - Base */
        .navbar-auth {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1040;
            background-color: transparent;
            border-bottom: none;
            transition: all 0.4s ease;
            padding-top: 20px;
            padding-bottom: 20px;
        }

        /* Navbar Auth - Scrolled State */
        .navbar-auth.scrolled {
            background-color: rgba(0, 0, 0, 0.85);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            padding-top: 8px;
            padding-bottom: 8px;
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1);
        }

        /* --- FIX LOGO PERISAI BIAR GAK KEPOTONG --- */
        .navbar-auth .brand-image {
            width: 45px;
            height: 45px;
            object-fit: contain;
            /* KUNCI: Gambar pas dalam kotak tanpa crop */
            background: transparent;
            border-radius: 0;
            /* Hapus radius bulat */
            margin-right: 10px;
            transition: all 0.3s;
        }

        .navbar-auth.scrolled .brand-image {
            width: 38px;
            height: 38px;
        }

        /* Brand Text Styling */
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

        /* --- USER PANEL & DROPDOWN --- */
        /* Nama User Text Logic */
        .user-name-text {
            display: inline-block;
            max-width: 150px;
            opacity: 1;
            transition: all 0.3s ease;
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

        /* Trigger Button Profile */
        .user-panel-trigger {
            padding: 5px 10px 5px 5px;
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

        /* Dropdown Menu Animation */
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

        .profile-dropdown-menu {
            width: 300px;
            border-radius: 12px;
            border: none;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            padding: 0;
            margin-top: 15px;
            display: none;
            /* Bootstrap handles 'show' */
        }

        .dropdown-menu.show {
            display: block;
            animation: slideInUp 0.3s forwards;
        }

        /* Profile Card Styling */
        .profile-card-header {
            text-align: center;
            padding: 25px 15px;
            background-color: #fff;
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
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-bottom: 10px;
        }

        .manage-account-btn {
            border: 1px solid #ced4da;
            border-radius: 20px;
            padding: 5px 20px;
            font-size: 14px;
            color: #495057;
            text-decoration: none;
            transition: all 0.2s;
        }

        .manage-account-btn:hover {
            background-color: #f8f9fa;
            color: #000;
        }

        .profile-footer {
            padding: 15px;
            background: #f8f9fa;
            border-radius: 0 0 12px 12px;
        }

        /* Mobile Menu Logic */
        .mobile-brand-scroll {
            text-decoration: none !important;
        }

        .nav-link {
            color: rgba(255, 255, 255, 0.8) !important;
            font-weight: 500;
            transition: color 0.3s;
        }

        .nav-link:hover,
        .nav-link.active {
            color: #fff !important;
        }

        .active-border {
            position: relative;
        }

        .active-border::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            width: 0;
            height: 2px;
            background: #dc3545;
            transition: 0.3s;
            transform: translateX(-50%);
        }

        .nav-link.active::after,
        .nav-link:hover::after {
            width: 100%;
        }

        /* =========================================
           2. PROFILE DROPDOWN STYLING (DARI REFERENSI)
           ========================================= */
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
            border: 3px solid #fff;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
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

        /* Foto profil di navbar */
        .user-panel img {
            width: 38px;
            height: 38px;
            object-fit: cover;
            border: 2px solid rgba(255, 255, 255, 0.8);
        }

        /* USER PANEL TRIGGER (PROFILE DI KANAN) */
        .user-panel-trigger {
            padding: 5px;
            border-radius: 50px;
            /* Capsule shape pas hover */
            transition: background 0.2s;
        }

        .user-panel-trigger:hover {
            background: rgba(255, 255, 255, 0.1);
        }

        .user-panel-trigger img {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            /* Foto user tetep bulat */
            object-fit: cover;
            border: 2px solid rgba(255, 255, 255, 0.8);
        }

        /* Animasi Dropdown (Tetap Slide Up) */
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
            display: block;
            animation: slideInUp 0.3s forwards;
        }

        /* =========================================
   1. CUSTOM HEADER STYLES (LOGIC SCROLL)
   ========================================= */

        /* Navbar untuk User Login (Fixed) */
        .navbar-auth {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1040;
            background-color: transparent;
            border-bottom: none;
            transition: all 0.4s ease;
            padding-top: 20px;
            /* Awal agak lebar biar logo lega */
            padding-bottom: 20px;
        }

        /* State saat di-scroll: Gelap, Blur, Lebih Pendek */
        .navbar-auth.scrolled {
            background-color: rgba(0, 0, 0, 0.85);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            padding-top: 8px;
            /* Memendek saat scroll */
            padding-bottom: 8px;
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1);
        }

        /* Logika Nama User: Tampil awal, Ilang pas Scroll */
        .user-name-text {
            display: inline-block;
            max-width: 150px;
            opacity: 1;
            transition: all 0.3s ease;
            margin-left: 10px;
            color: white;
            font-weight: bold;
        }

        /* Pas discroll, nama di-hidden (opacity 0 & width 0 biar ga makan tempat) */
        .navbar-auth.scrolled .user-name-text {
            opacity: 0;
            max-width: 0;
            margin-left: 0;
            overflow: hidden;
        }

        /* Animasi Dropdown Slide */
        @keyframes slideInUp {
            from {
                opacity: 0;
                transform: translate3d(0, 20px, 0);
            }

            to {
                opacity: 1;
                transform: translate3d(0, 0, 0);
            }
        }

        .profile-dropdown-menu {
            width: 300px;
            border-radius: 12px;
            border: none;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            padding: 0;
            margin-top: 15px;
            /* Jarak dari navbar */
            display: none;
            /* Default hidden, bootstrap handle open block */
        }

        /* Trigger animasi saat class 'show' aktif */
        .dropdown-menu.show {
            display: block;
            animation: slideInUp 0.3s forwards;
        }

        /* Styling isi Dropdown (Mirip Referensi) */
        .profile-card-header {
            text-align: center;
            padding: 25px 15px;
            background-color: #fff;
            border-bottom: 1px solid #eee;
            background-image: url('{{ asset('img/pattern-bg.png') }}');
            /* Opsional kalo ada bg */
            background-size: cover;
        }

        .profile-card-header img {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid #dfe6e9;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-bottom: 10px;
        }

        .manage-account-btn {
            border: 1px solid #ced4da;
            border-radius: 20px;
            padding: 5px 20px;
            font-size: 14px;
            color: #495057;
            text-decoration: none;
            transition: all 0.2s;
        }

        .manage-account-btn:hover {
            background-color: #f8f9fa;
            color: #000;
        }

        .profile-footer {
            padding: 15px;
            background: #f8f9fa;
        }

        /* Mobile Brand Scroll Style */
        .mobile-brand-scroll {
            display: flex;
            align-items: center;
            color: #fff !important;
            text-decoration: none;
        }

        .mobile-brand-text {
            line-height: 1.1;
            margin-left: 8px;
        }

        .mobile-brand-text .brand-title {
            font-weight: 700;
            font-size: 1rem;
        }

        .mobile-brand-text .brand-subtitle {
            font-size: 0.7rem;
            opacity: 0.8;
        }

        /* Hamburger Menu Positioning */
        .navbar-toggler {
            border: none;
            outline: none !important;
        }

        /* Desktop Menu Item Styling */
        .nav-link {
            color: rgba(255, 255, 255, 0.8) !important;
            font-weight: 500;
            transition: color 0.3s;
        }

        .nav-link:hover,
        .nav-link.active {
            color: #fff !important;
        }

        .active-border {
            position: relative;
        }

        .active-border::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            width: 0;
            height: 2px;
            background: #dc3545;
            transition: 0.3s;
            transform: translateX(-50%);
        }

        .nav-link.active::after,
        .nav-link:hover::after {
            width: 100%;
        }

        /* =========================================
           3. UMUM & PRELOADER
           ========================================= */
        .preloader.initial-only {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 9999;
            background-color: #ffffff;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            opacity: 1;
            visibility: visible;
            transition: opacity 0.5s ease, visibility 0.5s ease;
        }

        .preloader.initial-only.fade-out {
            opacity: 0;
            visibility: hidden;
        }

        .preloader.initial-only.hidden {
            display: none !important;
        }

        .animation__gentle {
            animation: gentle-pulse 2s ease-in-out infinite;
        }

        @keyframes gentle-pulse {
            0% {
                transform: scale(1);
                opacity: 1;
            }

            50% {
                transform: scale(1.05);
                opacity: 0.8;
            }

            100% {
                transform: scale(1);
                opacity: 1;
            }
        }

        .fade-in-text {
            animation: fadeIn 1.5s ease forwards;
            opacity: 0;
        }

        @keyframes fadeIn {
            0% {
                opacity: 0;
                transform: translateY(10px);
            }

            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Hero Section Adjustment for Fixed Navbar */
        .hero-section {
            position: relative;
            background-image: url('{{ asset('img/sports-facilities-bg.jpg') }}');
            background-size: cover;
            background-position: center;
            height: 550px;
            /* Sedikit dipertinggi */
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            color: white;
            /* Jika login, navbar fixed, jadi hero perlu top padding atau tidak?
               Karena transparan, tidak perlu margin-top, tapi content hero aman */
        }

        .hero-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.6);
            z-index: 1;
        }

        .hero-content {
            position: relative;
            z-index: 2;
            max-width: 800px;
            padding: 20px;
        }

        .hero-title {
            font-size: 3.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
        }

        .hero-subtitle {
            font-size: 1.5rem;
            font-weight: 300;
            margin-bottom: 2rem;
        }

        .btn-hero {
            padding: 12px 40px;
            font-size: 1.2rem;
            font-weight: 600;
            border-radius: 50px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
            transition: all 0.3s;
        }

        .btn-hero:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.4);
        }

        /* Small Box & Features */
        .small-box {
            transition: transform 0.3s ease;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .small-box:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15);
        }

        .feature-box {
            padding: 30px 20px;
            text-align: center;
            background: white;
            border-radius: 10px;
            margin-top: -50px;
            position: relative;
            z-index: 3;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        }

        .feature-icon {
            font-size: 2.5rem;
            color: #dc3545;
            margin-bottom: 15px;
        }

        .brand-logo-container {
            width: 45px;
            height: 45px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 0 0 10px 10px;
            overflow: hidden;
            background-color: white;
            margin-left: 0.8rem;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        /* --- Tambahkan di bagian CSS Custom Header --- */

        /* Brand Title Style */
        .navbar-auth .brand-text {
            transition: color 0.3s;
        }

        /* Brand Subtitle Style */
        .navbar-auth .brand-subtitle {
            font-weight: 300;
            transition: color 0.3s;
            color: rgba(255, 255, 255, 0.8) !important;
            /* Putih agak redup */
        }

        /* (Opsional) Saat Navbar Scrolled (Gelap), teks jadi lebih terang/jelas full */
        .navbar-auth.scrolled .brand-subtitle {
            color: rgba(255, 255, 255, 0.9) !important;
        }
    </style>
</head>

<body class="hold-transition layout-top-nav">

    <div class="preloader initial-only" id="initialPreloader">
        <img class="animation__gentle" src="{{ asset('img/logo.png') }}" alt="LogoDispora" height="120"
            width="100">
        <h3 class="mt-3 text-dark font-weight-bold fade-in-text" style="letter-spacing: 2px;">DISPORA SEMARANG</h3>
    </div>

    <div class="wrapper">

        {{-- LOGIC HEADER BERDASARKAN AUTH --}}
        @auth
            {{-- HEADER SAAT LOGIN --}}
            <nav id="navbarAuth" class="main-header navbar navbar-expand-md navbar-auth">
                <div class="container">

                    {{-- 1. LOGO / BRAND AREA --}}

                    {{-- DESKTOP: Smooth Scroll Added + Logo Fix --}}
                    <a href="#" class="navbar-brand d-none d-md-flex align-items-center"
                        onclick="window.scrollTo({top: 0, behavior: 'smooth'}); return false;">

                        {{-- Logo tanpa img-circle biar perisai utuh --}}
                        <img src="{{ asset('img/logo.png') }}" alt="Logo" class="brand-image elevation-0">

                        <div class="brand-text-wrapper text-white">
                            <div class="brand-title">E-SEWA</div>
                            <div class="brand-subtitle">Dispora Kota Semarang</div>
                        </div>
                    </a>

                    {{-- MOBILE: Hamburger + Brand Scroll --}}
                    <div class="d-flex d-md-none align-items-center justify-content-between w-100">
                        <button class="navbar-toggler pl-0" type="button" data-toggle="collapse"
                            data-target="#navbarCollapse">
                            <span class="fas fa-bars text-white" style="font-size: 1.4rem;"></span>
                        </button>

                        <a class="mobile-brand-scroll d-flex align-items-center" href="#"
                            onclick="window.scrollTo({top: 0, behavior: 'smooth'}); return false;"
                            style="text-decoration: none;">

                            {{-- Logo Mobile juga tanpa img-circle --}}
                            <img src="{{ asset('img/logo.png') }}" alt="Logo"
                                style="width: 40px; height: 40px; object-fit: contain; margin-right: 8px;">

                            <div class="text-white" style="line-height: 1.1;">
                                <div style="font-weight: 700; font-size: 1rem;">E-SEWA</div>
                                <div style="font-size: 0.7rem; opacity: 0.9;">Dispora Semarang</div>
                            </div>
                        </a>

                        {{-- Spacer dummy biar logo mobile beneran di tengah visualnya --}}
                        <div style="width: 25px;"></div>
                    </div>

                    {{-- 2. MENU TENGAH --}}
                    <div class="collapse navbar-collapse order-3" id="navbarCollapse">
                        <ul class="navbar-nav ml-auto mr-auto nav-scroll-spy">
                            <li class="nav-item"><a href="#home" class="nav-link active active-border">Beranda</a></li>
                            <li class="nav-item"><a href="#fasilitas" class="nav-link active-border">Fasilitas</a></li>
                            <li class="nav-item"><a href="#cara-booking" class="nav-link active-border">Panduan</a></li>
                            <li class="nav-item"><a href="{{ route('user.riwayat') }}"
                                    class="nav-link active-border">Riwayat</a></li>

                            {{-- Menu Profile Khusus Mobile (Muncul di list bawah saat hamburger dibuka) --}}
                            <li class="nav-item d-md-none mt-3 border-top pt-3">
                                <div class="d-flex align-items-center px-2">
                                    <img src="{{ Auth::user()->foto ? asset('storage/' . Auth::user()->foto) : asset('img/default-user.png') }}"
                                        style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover;">
                                    <div class="ml-3">
                                        <span class="d-block text-white font-weight-bold">{{ Auth::user()->name }}</span>
                                        <a href="{{ route('logout') }}"
                                            onclick="event.preventDefault(); document.getElementById('logout-form-mobile').submit();"
                                            class="text-danger small font-weight-bold">Logout</a>
                                        <form id="logout-form-mobile" action="{{ route('logout') }}" method="POST"
                                            style="display: none;">@csrf</form>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>

                    {{-- 3. PROFILE DROPDOWN (DESKTOP ONLY) --}}
                    {{-- Pakai data-toggle="dropdown" otomatis CLICK only (bukan hover) --}}
                    <ul class="navbar-nav ml-auto order-1 order-md-3 d-none d-md-flex">
                        <li class="nav-item dropdown">

                            <a class="nav-link user-panel-trigger d-flex align-items-center" data-toggle="dropdown"
                                href="#" role="button" aria-expanded="false">
                                <div class="image">
                                    <img src="{{ Auth::user()->foto ? asset('storage/' . Auth::user()->foto) : asset('img/default-user.png') }}"
                                        class="elevation-1" alt="User">
                                </div>
                                <span
                                    class="user-name-text ml-2 text-white">{{ Str::words(Auth::user()->name, 1, '') }}</span>
                            </a>

                            {{-- Isi Dropdown --}}
                            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right profile-dropdown-menu">
                                <div class="profile-card-header">
                                    <img src="{{ Auth::user()->foto ? asset('storage/' . Auth::user()->foto) : asset('img/default-user.png') }}"
                                        alt="Profile">
                                    <p class="profile-name mt-2">Halo, {{ Str::words(Auth::user()->name, 2, '') }}</p>
                                    <p class="profile-role text-muted small mb-3">
                                        {{ Auth::user()->role == 'user' ? 'Member' : 'User' }}
                                    </p>
                                    <a href="#" class="manage-account-btn">Kelola Akun</a>
                                </div>

                                <div class="p-0">
                                    <a href="{{ route('user.riwayat') }}" class="dropdown-item py-3 border-bottom">
                                        <i class="fas fa-history mr-2 text-muted"></i> Riwayat Pesanan
                                    </a>
                                </div>

                                <div class="profile-footer">
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit"
                                            class="logout-btn btn-block border-0 bg-transparent text-danger">
                                            <i class="fas fa-sign-out-alt mr-2"></i> Keluar
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </li>
                    </ul>

                </div>
            </nav>
        @else
            {{-- Navbar Guest (Biarkan tetap seperti sebelumnya) --}}
        @endauth

        <div class="content-wrapper" style="min-height: auto;">

            <div class="hero-section" id="home">
                <div class="hero-overlay"></div>
                <div class="hero-content">
                    <h1 class="hero-title">E - Sewa Fasilitas</h1>
                    <p class="hero-subtitle">Platform resmi penyewaan fasilitas olahraga Pemerintah Kota Semarang.
                        Mudah, Cepat, dan Transparan.</p>

                    @guest
                        <a href="{{ route('login') }}" class="btn btn-danger btn-hero">
                            <i class="fas fa-calendar-check mr-2"></i> Booking Sekarang
                        </a>
                    @else
                        <a href="#fasilitas" class="btn btn-danger btn-hero">
                            <i class="fas fa-search mr-2"></i> Cari Lapangan
                        </a>
                    @endguest
                </div>
            </div>

            <div class="container mb-5">
                <div class="row">
                    <div class="col-md-4">
                        <div class="feature-box">
                            <i class="fas fa-laptop-house feature-icon"></i>
                            <h5>Booking Online</h5>
                            <p class="text-muted mb-0">Cek jadwal dan booking dari mana saja tanpa harus datang ke
                                lokasi.</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="feature-box">
                            <i class="fas fa-tags feature-icon"></i>
                            <h5>Harga Resmi</h5>
                            <p class="text-muted mb-0">Tarif penyewaan sesuai peraturan daerah yang berlaku, tanpa
                                pungli.</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="feature-box">
                            <i class="fas fa-medal feature-icon"></i>
                            <h5>Fasilitas Standar</h5>
                            <p class="text-muted mb-0">Lapangan terawat dengan standar yang layak untuk berolahraga.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="content" id="fasilitas">
                <div class="container">
                    <div class="row mb-3">
                        <div class="col-12 text-center">
                            <h2 class="font-weight-bold">Fasilitas Kami</h2>
                            <p class="text-muted">
                                Berikut beberapa fasilitas tersedia yang dikelola oleh Dinas Kepemudaan dan Olahraga
                                Kota Semarang
                            </p>
                        </div>
                    </div>

                    <div class="row">
                        @forelse($fasilitas as $item)
                            <div class="col-lg-4 col-md-6 mb-4">
                                <div class="small-box {{ $item->icon_color }} shadow-sm h-100">
                                    <div class="inner">
                                        <h3>{{ $item->kategori ? ucwords($item->kategori) : 'Umum' }}</h3>
                                        <p class="font-weight-bold" style="font-size: 1.1rem">
                                            {{ $item->nama_fasilitas }}</p>
                                        <p class="mb-0"><i class="fas fa-map-marker-alt mr-1"></i>
                                            {{ Str::limit($item->lokasi, 30) }}</p>
                                        <h4 class="mt-2 font-weight-bold">
                                            Rp {{ number_format($item->harga_sewa, 0, ',', '.') }}
                                        </h4>
                                    </div>
                                    <div class="icon">
                                        <i class="{{ $item->icon }}"></i>
                                    </div>
                                    {{-- Logic Link Detail --}}
                                    @auth
                                        <a href="#" class="small-box-footer">
                                            Booking Sekarang <i class="fas fa-arrow-circle-right"></i>
                                        </a>
                                    @else
                                        <a href="{{ route('login') }}" class="small-box-footer">
                                            Lihat Detail <i class="fas fa-arrow-circle-right"></i>
                                        </a>
                                    @endauth
                                </div>
                            </div>
                        @empty
                            <div class="col-12 text-center py-5">
                                <div class="alert alert-light">
                                    <i class="fas fa-info-circle mr-1"></i> Belum ada fasilitas yang aktif saat ini.
                                </div>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <div class="content bg-light mt-5 py-5" id="cara-booking">
                <div class="container">
                    <h3 class="text-center mb-5 font-weight-bold">Cara Booking Fasilitas</h3>
                    <div class="row text-center">
                        <div class="col-md-3 col-6 mb-4">
                            <div class="step-icon"><i class="fas fa-user-plus"></i></div>
                            <h5>1. Daftar Akun</h5>
                            <p class="small text-muted">Buat akun pengguna baru</p>
                        </div>
                        <div class="col-md-3 col-6 mb-4">
                            <div class="step-icon"><i class="fas fa-calendar-alt"></i></div>
                            <h5>2. Pilih Jadwal</h5>
                            <p class="small text-muted">Cek ketersediaan tanggal</p>
                        </div>
                        <div class="col-md-3 col-6 mb-4">
                            <div class="step-icon"><i class="fas fa-file-invoice-dollar"></i></div>
                            <h5>3. Bayar DP</h5>
                            <p class="small text-muted">Lakukan pembayaran awal</p>
                        </div>
                        <div class="col-md-3 col-6 mb-4">
                            <div class="step-icon"><i class="fas fa-check-circle"></i></div>
                            <h5>4. Selesai</h5>
                            <p class="small text-muted">Tunjukkan bukti & main!</p>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <footer class="main-footer bg-danger border-0">
            <div class="container">
                <div class="row">
                    <div class="col-12 text-center">
                        <strong>Copyright &copy; 2026 <a href="#" class="text-white">E-SEWA
                                DISPORA</a>.</strong> All
                        rights reserved.
                    </div>
                </div>
            </div>
        </footer>
    </div>

    <script src="{{ asset('lte/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('lte/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('lte/dist/js/adminlte.min.js') }}"></script>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // 1. PRELOADER LOGIC
            const preloader = document.getElementById('initialPreloader');
            if (preloader) {
                window.addEventListener('load', () => {
                    setTimeout(() => {
                        preloader.classList.add('fade-out');
                        setTimeout(() => {
                            preloader.classList.add('hidden');
                        }, 500);
                    }, 500);
                });
                // Fallback
                setTimeout(() => {
                    if (!preloader.classList.contains('hidden')) {
                        preloader.classList.add('fade-out');
                        setTimeout(() => {
                            preloader.classList.add('hidden');
                        }, 500);
                    }
                }, 3000);
            }

            // 2. NAVBAR SCROLL EFFECT (Hanya untuk yang sudah Login)
            const navbarAuth = document.getElementById('navbarAuth');
            if (navbarAuth) {
                window.addEventListener('scroll', () => {
                    if (window.scrollY > 50) {
                        navbarAuth.classList.add('scrolled');
                    } else {
                        navbarAuth.classList.remove('scrolled');
                    }
                });

                // 3. ACTIVE STATE ON SCROLL (ScrollSpy Sederhana)
                const sections = document.querySelectorAll('div[id]'); // Ambil div yang punya ID
                const navLinks = document.querySelectorAll('.nav-scroll-spy .nav-link');

                window.addEventListener('scroll', () => {
                    let current = '';
                    sections.forEach(section => {
                        const sectionTop = section.offsetTop;
                        const sectionHeight = section.clientHeight;
                        // Logika offset sedikit (-100) agar active berubah sebelum benar2 pas garis
                        if (pageYOffset >= (sectionTop - 150)) {
                            current = section.getAttribute('id');
                        }
                    });

                    navLinks.forEach(link => {
                        link.classList.remove('active');
                        if (link.getAttribute('href').includes(current)) {
                            link.classList.add('active');
                        }
                    });
                });
            }
        });
    </script>
</body>

</html>
