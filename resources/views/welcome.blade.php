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
           1. LOGIKA PRELOADER (MIRIP ADMIN LAYOUT)
           ========================================= */
        .preloader.initial-only {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 9999;
            background-color: #ffffff;
            display: flex; /* Default tampil */
            flex-direction: column;
            justify-content: center;
            align-items: center;
            opacity: 1;
            visibility: visible;
            transition: opacity 0.5s ease, visibility 0.5s ease;
        }

        /* Class untuk animasi menghilang */
        .preloader.initial-only.fade-out {
            opacity: 0;
            visibility: hidden;
        }

        /* Class untuk benar-benar hilang dari layout (display: none) */
        .preloader.initial-only.hidden {
            display: none !important;
        }

        /* Animasi Logo Berdenyut (Gentle Pulse) */
        .animation__gentle {
            animation: gentle-pulse 2s ease-in-out infinite;
        }

        @keyframes gentle-pulse {
            0% { transform: scale(1); opacity: 1; }
            50% { transform: scale(1.05); opacity: 0.8; }
            100% { transform: scale(1); opacity: 1; }
        }

        .fade-in-text {
            animation: fadeIn 1.5s ease forwards;
            opacity: 0;
        }

        @keyframes fadeIn {
            0% { opacity: 0; transform: translateY(10px); }
            100% { opacity: 1; transform: translateY(0); }
        }

        /* =========================================
           2. STYLE HALAMAN (HERO, CARD, DLL)
           ========================================= */
        .hero-section {
            position: relative;
            background-image: url('{{ asset('img/sports-facilities-bg.jpg') }}');
            background-size: cover;
            background-position: center;
            height: 500px;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            color: white;
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

        .brand-image-custom {
            max-width: 100%;
            max-height: 100%;
            width: auto;
            height: auto;
            object-fit: contain;
            padding: 3px;
        }
    </style>
</head>

<body class="hold-transition layout-top-nav">

    <div class="preloader initial-only" id="initialPreloader">
        <img class="animation__gentle" src="{{ asset('img/logo.png') }}" alt="LogoDispora" height="120" width="100">
        <h3 class="mt-3 text-dark font-weight-bold fade-in-text" style="letter-spacing: 2px;">DISPORA SEMARANG</h3>
    </div>

    <div class="wrapper">
        <nav class="main-header navbar navbar-expand-md navbar-light navbar-white shadow-sm">
            <div class="container">
                <a href="#" class="navbar-brand">
                    <img src="{{ asset('img/logo.png') }}" alt="Logo" class="brand-image img-squere elevation-3"
                        style="border-radius: 0 0 16px 16px">
                    <span class="brand-text font-weight-bold text-dark">DISPORA KOTA SEMARANG</span>
                </a>

                <button class="navbar-toggler order-1" type="button" data-toggle="collapse"
                    data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false"
                    aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse order-3" id="navbarCollapse">
                    <ul class="navbar-nav ml-auto">
                        <li class="nav-item">
                            <a href="#fasilitas" class="nav-link">Fasilitas</a>
                        </li>
                        <li class="nav-item">
                            <a href="#cara-booking" class="nav-link">Cara Booking</a>
                        </li>
                        <li class="nav-item ml-2">
                            <a href="{{ route('login') }}" class="btn btn-outline-danger px-4">
                                <i class="fas fa-sign-in-alt mr-2"></i> Login / Register
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <div class="content-wrapper">

            <div class="hero-section">
                <div class="hero-overlay"></div>
                <div class="hero-content">
                    <h1 class="hero-title">E - Sewa Fasilitas</h1>
                    <p class="hero-subtitle">Platform resmi penyewaan fasilitas olahraga Pemerintah Kota Semarang.
                        Mudah, Cepat, dan Transparan.</p>
                    <a href="{{ route('login') }}" class="btn btn-danger btn-hero">
                        <i class="fas fa-calendar-check mr-2"></i> Booking Sekarang
                    </a>
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
                            <p class="text-muted mb-0">Lapangan terawat dengan standar yang layak untuk berolahraga.</p>
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
                                Berikut beberapa fasilitas tersedia yang dikelola oleh Dinas Kepemudaan dan Olahraga Kota Semarang
                            </p>
                        </div>
                    </div>

                    <div class="row">
                        @forelse($fasilitas as $item)
                            <div class="col-lg-4 col-md-6 mb-4">
                                <div class="small-box {{ $item->icon_color }} shadow-sm h-100">
                                    <div class="inner">
                                        <h3>{{ $item->kategori ? ucwords($item->kategori) : 'Umum' }}</h3>
                                        <p class="font-weight-bold" style="font-size: 1.1rem">{{ $item->nama_fasilitas }}</p>
                                        <p class="mb-0"><i class="fas fa-map-marker-alt mr-1"></i>
                                            {{ Str::limit($item->lokasi, 30) }}</p>
                                        <h4 class="mt-2 font-weight-bold">
                                            Rp {{ number_format($item->harga_sewa, 0, ',', '.') }}
                                        </h4>
                                    </div>
                                    <div class="icon">
                                        <i class="{{ $item->icon }}"></i>
                                    </div>
                                    <a href="{{ route('login') }}" class="small-box-footer">
                                        Lihat Detail <i class="fas fa-arrow-circle-right"></i>
                                    </a>
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
                        <strong>Copyright &copy; 2025 <a href="#" class="text-white">E-SEWA DISPORA</a>.</strong> All
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
            const preloader = document.getElementById('initialPreloader');

            if (preloader) {
                // Event saat halaman selesai load (gambar, css, dll)
                window.addEventListener('load', () => {
                    // Kasih delay dikit biar logo kelihatan (estetik)
                    setTimeout(() => {
                        // Tambah class untuk animasi opacity 0
                        preloader.classList.add('fade-out');

                        // Setelah animasi CSS selesai (0.5s), set display:none
                        setTimeout(() => {
                            preloader.classList.add('hidden');
                        }, 500);
                    }, 500); // Delay awal 500ms
                });

                // Fallback: Paksa hilang setelah 3 detik jika window.load macet
                setTimeout(() => {
                    if (!preloader.classList.contains('hidden')) {
                        preloader.classList.add('fade-out');
                        setTimeout(() => { preloader.classList.add('hidden'); }, 500);
                    }
                }, 3000);
            }
        });
    </script>
</body>

</html>
