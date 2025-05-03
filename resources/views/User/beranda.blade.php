@extends('User.user')
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Tersedia Saat Ini</h1>
                    </div>
                    <!-- /.col -->
                    {{-- <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right mr-4">
                        <li class="breadcrumb-item">
                            <a class="btn btn-outline-success" href="#" role="button"><i class="nav-icon fas fa-search"></i> Cek Lapangan</a>
                        </li>
                    </ol>
                </div> --}}
                    <!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <!-- Small boxes (Stat box) -->
                <div class="row">
                    <div class="col-lg-3 col-6">
                        <!-- small box -->
                        <div class="small-box bg-info">
                            <div class="inner">
                                {{-- <h3>{{ $totalDokter }}</h3> --}}
                                <p><b>Lapangan Futsal</b></p>
                            </div>
                            <div class="icon">
                                <i class="ion ion-ios-football"></i>
                            </div>
                            <a href="{{ route('user.futsal') }}" class="small-box-footer">Booking Now <i
                                    class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    <!-- ./col -->
                    <div class="col-lg-3 col-6">
                        <!-- small box -->
                        <div class="small-box bg-success">
                            <div class="inner">
                                {{-- <h3>{{ $totalPoli }}</h3> --}}
                                <p><b>Lapangan Tenis</b></p>
                            </div>
                            <div class="icon">
                                <i class="ion ion-ios-tennisball"></i>
                            </div>
                            <a href="{{ route('user.tenis') }}" class="small-box-footer">Booking now <i
                                    class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    <!-- ./col -->
                    <div class="col-lg-3 col-6">
                        <!-- small box -->
                        <div class="small-box bg-warning">
                            <div class="inner">
                                {{-- <h3>{{ $totalPoli }}</h3> --}}
                                <p><b>Lapangan Voli</b></p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-volleyball-ball"></i>
                            </div>
                            <a href="{{ route('user.voli') }}" class="small-box-footer">Booking Now <i
                                    class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    <!-- ./col -->
                </div>
                <!-- /.row -->
            </div><!-- /.container-fluid -->
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
@endsection
