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
        /* Hero Section Style */
        .hero-section {
            position: relative;
            background-image: url('{{ asset('img/sports-facilities-bg.jpg') }}');
            /* Pastikan gambar ini ada */
            background-size: cover;
            background-position: center;
            height: 500px;
            /* Tinggi banner */
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
            /* Gelapkan gambar background */
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

        /* Card Style */
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

        /* Feature Icons */
        .feature-box {
            padding: 30px 20px;
            text-align: center;
            background: white;
            border-radius: 10px;
            margin-top: -50px;
            /* Efek overlap ke atas hero */
            position: relative;
            z-index: 3;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        }

        .feature-icon {
            font-size: 2.5rem;
            color: #dc3545;
            /* Warna Merah Navbar */
            margin-bottom: 15px;
        }

        /* 1. MODIFIKASI BENTUK LOGO (Seperti Perisai/Tag) */
        .brand-logo-container {
            width: 45px;
            height: 45px;
            display: flex;
            align-items: center;
            justify-content: center;

            /* RAHASIA BENTUK "MERUNCING": */
            /* Urutan: Kiri-Atas, Kanan-Atas, Kanan-Bawah, Kiri-Bawah */
            border-radius: 0 0 10px 10px;

            overflow: hidden;
            background-color: white;
            margin-left: 0.8rem;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            /* Sedikit bayangan biar cantik */
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
                            <p class="text-muted">Pilihan fasilitas yang siap digunakan</p>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-4 col-md-6">
                            <div class="small-box bg-info">
                                <div class="inner">
                                    <h3>Futsal</h3>
                                    <p>GOR Indoor Manunggal Jati</p>
                                </div>
                                <div class="icon">
                                    <i class="ion ion-ios-football"></i>
                                </div>
                                <a href="{{ route('login') }}" class="small-box-footer">
                                    Lihat Detail <i class="fas fa-arrow-circle-right"></i>
                                </a>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6">
                            <div class="small-box bg-success">
                                <div class="inner">
                                    <h3>Tenis</h3>
                                    <p>GOR Manunggal Jati & Tri Lomba Juang</p>
                                </div>
                                <div class="icon">
                                    <i class="ion ion-ios-tennisball"></i>
                                </div>
                                <a href="{{ route('login') }}" class="small-box-footer">
                                    Lihat Detail <i class="fas fa-arrow-circle-right"></i>
                                </a>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6">
                            <div class="small-box bg-warning">
                                <div class="inner">
                                    <h3>Badminton</h3>
                                    <p>GOR Tri Lomba Juang</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-feather"></i>
                                </div>
                                <a href="{{ route('login') }}" class="small-box-footer">
                                    Lihat Detail <i class="fas fa-arrow-circle-right"></i>
                                </a>
                            </div>
                        </div>
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
</body>

</html>
