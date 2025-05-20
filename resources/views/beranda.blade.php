<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>E-Sewa Dispora Semarang</title>

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

    <!-- Custom styles for this page -->
    <style>
        .facility-carousel .carousel-item {
            height: 300px;
            background-size: cover;
            background-position: center;
            position: relative;
        }

        .carousel-overlay {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            padding: 20px;
            background: linear-gradient(to top, rgba(0, 0, 0, 0.8), rgba(0, 0, 0, 0));
            color: white;
        }

        .small-box {
            transition: transform 0.3s ease;
            overflow: hidden;
        }

        .small-box:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .facility-info {
            font-size: 1.2rem;
            margin-bottom: 10px;
            display: block;
        }

        .how-to-book {
            background-color: #f8f9fa;
            border-radius: 10px;
            padding: 20px;
            margin-top: 30px;
        }

        .step-icon {
            font-size: 2rem;
            margin-bottom: 15px;
            color: #007bff;
        }
    </style>
</head>


<body class="hold-transition sidebar-mini sidebar-collapse layout-fixed" style="padding-bottom: 4%">
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
                            DISPORA SEMARANG
                        </b>
                    </a>

                </li>
                {{-- <li class="nav-item ms-auto">
                    <img class="animation__shake" src="{{ asset('img/logo.png') }}" alt="AdminLTELogo" height="40"
                        width="40">
                </li> --}}
            </ul>
        </nav>

        <!-- Main Sidebar Container -->
        <aside class="main-sidebar sidebar-light-danger elevation-4">
            <!-- Sidebar -->
            <div class="sidebar">
                <!-- SidebarSearch Form -->
                <div class="form-inline">
                    <div class="input-group" data-widget="sidebar-search">
                        <input class="form-control form-control-sidebar mt-4" type="search" placeholder="Search"
                            aria-label="Search">
                        <div class="input-group-append">
                            <button class="btn btn-sidebar mt-4">
                                <i class="fas fa-search fa-fw "></i>
                            </button>
                        </div>
                    </div>
                </div>
                <!-- Sidebar Menu -->
                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                        data-accordion="false">
                        <li class="nav-item active mt-2">
                            <a href="{{ route('login') }}" class="nav-link active">
                                <i class="nav-icon fas fa-users"></i>
                                <p>
                                    Login / Register
                                </p>
                            </a>
                        </li>
                    </ul>
                </nav>
                <!-- /.sidebar-menu -->
            </div>
            <!-- /.sidebar -->
        </aside>

        <!-- Main content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
        <div class="container-fluid">
            <!-- Carousel Section -->
            <div id="facilityCarousel" class="carousel slide mb-4 facility-carousel" data-ride="carousel">
                <ol class="carousel-indicators">
                    <li data-target="#facilityCarousel" data-slide-to="0" class="active"></li>
                    <li data-target="#facilityCarousel" data-slide-to="1"></li>
                    <li data-target="#facilityCarousel" data-slide-to="2"></li>
                </ol>
                <div class="carousel-inner">
                    <div class="carousel-item active" style="background-image: url('{{ asset('img/futsal.jpg') }}')">
                        <div class="carousel-overlay">
                            <h2>Lapangan Futsal</h2>
                            <p>Rasakan sensasi bermain di lapangan futsal berkualitas</p>
                        </div>
                    </div>
                    <div class="carousel-item" style="background-image: url('{{ asset('img/tennis.jpg') }}')">
                        <div class="carousel-overlay">
                            <h2>Lapangan Tenis</h2>
                            <p>Sempurna untuk pecinta olahraga raket</p>
                        </div>
                    </div>
                    <div class="carousel-item" style="background-image: url('{{ asset('img/volleyball.jpg') }}')">
                        <div class="carousel-overlay">
                            <h2>Lapangan Voli</h2>
                            <p>Ideal untuk latihan tim atau pertandingan persahabatan</p>
                        </div>
                    </div>
                </div>
                <a class="carousel-control-prev" href="#facilityCarousel" role="button" data-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="sr-only">Previous</span>
                </a>
                <a class="carousel-control-next" href="#facilityCarousel" role="button" data-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="sr-only">Next</span>
                </a>
            </div>

            <!-- Content Header -->
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0">Fasilitas Tersedia</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item active">
                                    <div class="badge badge-primary p-2">
                                        <i class="fas fa-calendar-check mr-1"></i> {{ date('d F Y') }}
                                    </div>
                                </li>
                            </ol>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-12">
                            <div class="alert alert-dark">
                                <i class="fas fa-info-circle mr-2"></i> Selamat datang di sistem pemesanan fasilitas
                                olahraga DISPORA Kota Semarang. Pilih fasilitas yang ingin Anda gunakan dan lakukan
                                booking
                                dengan
                                mudah!
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Facility Cards -->
            <div class="row">
                <div class="col-lg-4 col-md-6">
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3>Lapangan Futsal</h3>
                            <span class="facility-info"><i class="fas fa-ruler-combined mr-1"></i> Ukuran
                                Standar</span>

                            <span class="facility-info"><i class="fas fa-clock mr-1"></i> 08:00 - 22:00 WIB</span>
                        </div>
                        <div class="icon">
                            <i class="ion ion-ios-football"></i>
                        </div>
                        <a href="{{ route('user.futsal') }}" class="small-box-footer">
                            Booking Sekarang <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6">
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3>Lapangan Tenis</h3>
                            <span class="facility-info"><i class="fas fa-ruler-combined mr-1"></i> Indoor &
                                Outdoor</span>

                            <span class="facility-info"><i class="fas fa-clock mr-1"></i> 06:00 - 21:00 WIB</span>
                        </div>
                        <div class="icon">
                            <i class="ion ion-ios-tennisball"></i>
                        </div>
                        <a href="{{ route('user.tenis') }}" class="small-box-footer">
                            Booking Sekarang <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6">
                    <div class="small-box bg-warning">
                        <div class="inner">
                            <h3>Lapangan Voli</h3>
                            <span class="facility-info"><i class="fas fa-ruler-combined mr-1"></i> Indoor</span>
                            <span class="facility-info"><i class="fas fa-clock mr-1"></i> 08:00 - 20:00 WIB</span>
                        </div>
                        <div class="icon">
                            <i class="fas fa-volleyball-ball"></i>
                        </div>
                        <a href="{{ route('user.voli') }}" class="small-box-footer">
                            Booking Sekarang <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- How to Book Section -->
            <div class="how-to-book">
                <h3 class="text-center mb-4">Cara Booking Fasilitas</h3>
                <div class="row text-center">
                    <div class="col-md-3">
                        <div class="step-icon">
                            <i class="fas fa-search-location"></i>
                        </div>
                        <h5>1. Pilih Fasilitas</h5>
                        <p>Pilih fasilitas olahraga yang ingin Anda gunakan</p>
                    </div>
                    <div class="col-md-3">
                        <div class="step-icon">
                            <i class="fas fa-calendar-alt"></i>
                        </div>
                        <h5>2. Pilih Jadwal</h5>
                        <p>Pilih tanggal dan jam yang tersedia</p>
                    </div>
                    <div class="col-md-3">
                        <div class="step-icon">
                            <i class="fas fa-money-bill-wave"></i>
                        </div>
                        <h5>3. Bayar DP</h5>
                        <p>Bayar DP 50% untuk mengamankan jadwal Anda</p>
                    </div>
                    <div class="col-md-3">
                        <div class="step-icon">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <h5>4. Bermain</h5>
                        <p>Datang sesuai jadwal dan lunasi pembayaran</p>
                    </div>
                </div>
            </div>
        </div></div>

        <footer class="main-footer bg-dark" style="position: fixed; bottom: 0; width: 100%; z-index: 10;">
            <strong>Copyright &copy; 2025 <a href="#">E-SEWA</a>.</strong>
            All rights reserved.
        </footer>
    </div>
    <!-- ./wrapper -->

    <!-- jQuery -->
    <script src="{{ asset('lte/jquery/jquery.min.js') }}"></script>
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"
        integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js"
        integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js"
        integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous">
    </script>
    <!-- jQuery UI 1.11.4 -->
    <script src="{{ asset('lte/jquery-ui/jquery-ui.min.js') }}"></script>
    <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
    <script>
        $.widget.bridge('uibutton', $.ui.button)
    </script>
    <!-- Bootstrap 4 -->
    <script src="{{ asset('lte/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- ChartJS -->
    <script src="{{ asset('lte/chart.js/Chart.min.js') }}"></script>
    <!-- Sparkline -->
    <script src="{{ asset('lte/sparklines/sparkline.js') }}"></script>
    <!-- JQVMap -->
    <script src="{{ asset('lte/jqvmap/jquery.vmap.min.js') }}"></script>
    <script src="{{ asset('lte/jqvmap/maps/jquery.vmap.usa.js') }}"></script>
    <!-- jQuery Knob Chart -->
    <script src="{{ asset('lte/jquery-knob/jquery.knob.min.js') }}"></script>
    <!-- daterangepicker -->
    <script src="{{ asset('lte/moment/moment.min.js') }}"></script>
    <script src="{{ asset('lte/daterangepicker/daterangepicker.jsalt') }}"></script>
    <!-- Tempusdominus Bootstrap 4 -->
    <script src="{{ asset('lte/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js') }}"></script>
    <!-- Summernote -->
    <script src="{{ asset('lte/summernote/summernote-bs4.min.js') }}"></script>
    <!-- overlayScrollbars -->
    <script src="{{ asset('lte/overlayScrollbars/js/jquery.overlayScrollbars.min.js') }}"></script>
    <!-- AdminLTE App -->
    <script src="{{ asset('lte/dist/js/adminlte.js') }}"></script>
    <!-- AdminLTE for demo purposes -->
    <script src="{{ asset('lte/dist/js/demo.js') }}"></script>
    <!-- AdminLTE dashboard demo (This is only for demo purposes) -->
    <script src="{{ asset('lte/dist/js/pages/dashboard.js') }}"></script>
    <!-- Toastr -->
    <script src="{{ asset('lte/plugins/toastr/toastr.min.js') }}"></script>
    <!-- SweetAlert2 -->
    <script src="{{ asset('lte/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
    <!-- Javascript untuk menginisialisasi carousel -->
    <script>
        $(document).ready(function() {
            // Cek jika jQuery dan Bootstrap JS sudah dimuat
            if (typeof $.fn.carousel === 'function') {
                // Inisialisasi carousel
                $('#facilityCarousel').carousel({
                    interval: 5000
                });
            } else {
                console.error('Bootstrap carousel plugin tidak tersedia.');
            }

            // Pastikan gambar carousel dimuat dengan benar
            $('.carousel-item').each(function() {
                var bgImg = $(this).css('background-image');
                var img = new Image();
                img.src = bgImg.replace(/url\(['"]?(.*?)['"]?\)/i, "$1");
                img.onerror = function() {
                    console.error('Gambar tidak dapat dimuat: ' + this.src);
                    $(this).parent().css('background-color', '#343a40'); // Fallback warna
                    $(this).parent().find('.carousel-overlay').css('background', 'none');
                };
            });
        });
    </script>
</body>

</html>
