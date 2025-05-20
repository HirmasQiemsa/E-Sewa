@extends('User.user')
@section('content')
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

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">



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
                </div>
            </div>

            <!-- Facility Cards -->
            <div class="row">
                <div class="col-lg-4 col-md-6">
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3>Lapangan Futsal</h3>
                            <span class="facility-info"><i class="fas fa-ruler-combined mr-1"></i> Ukuran Standar</span>

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
                            <span class="facility-info"><i class="fas fa-ruler-combined mr-1"></i> Indoor & Outdoor</span>

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
        </div>
    </section>

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
@endsection
