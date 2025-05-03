@extends('User.user')
@section('content')
    <div class="content-wrapper">
        <!-- Content Header -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Lapangan Futsal</h1>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main content -->
        <section class="content">
            <div class="container fluid">
                <div class="row">
                    {{-- ITEM --}}
                    <div class="col-md-12 offset-md-0">
                        <div class="card mb-4 w-100 shadow-sm p-3">
                            <div class="row g-0 align-items-center"
                                onclick="window.location.href='{{ route('user.checkout') }}'">
                                <!-- Kolom Gambar -->
                                <div class="col-md-4">
                                    <img src="{{ asset('img/futsal-manunggaljati.jpg') }}" class="img-fluid rounded"
                                        alt="Fasilitas Image">
                                </div>

                                <!-- Kolom Deskripsi -->
                                <div class="col-md-8">
                                    <div class="card-body space">
                                        <h5 class="card-title" onmouseover="this.style.color='gray'"
                                            onmouseout="this.style.color='black'"><b>Gor Indoor Manunggal Jati</b></h5>
                                        <p>.</p>
                                        <p class="text-muted"> Kecamatan Pedurungan</p>
                                        {{-- <p>7.4 Very Good (828 reviews)</p> --}}
                                        <p class="text-success">JADWAL TERSISA </p>
                                        <h4 class="text-danger">Rp Harga /jam</h4>
                                        {{-- Button CheckOut --}}
                                        <div class="mt-3">
                                            <a class="btn btn-outline-primary" href="#" role="button">Checkout Now</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- ITEM --}}
                    <div class="col-md-12 offset-md-0">
                        <div class="card mb-4 w-100 shadow-sm p-3">
                            <div class="row g-0 align-items-center"
                                onclick="window.location.href='{{ url('tujuan-page') }}'">
                                <!-- Kolom Gambar -->
                                <div class="col-md-4">
                                    <img src="{{ asset('img/futsal-manunggaljati.jpg') }}" class="img-fluid rounded"
                                        alt="Fasilitas Image">
                                </div>

                                <!-- Kolom Deskripsi -->
                                <div class="col-md-8">
                                    <div class="card-body space">
                                        <h5 class="card-title" onmouseover="this.style.color='gray'"
                                            onmouseout="this.style.color='black'"><b>Gor Indoor Manunggal Jati</b></h5>
                                        <p>.</p>
                                        <p class="text-muted"> Kecamatan Pedurungan</p>
                                        {{-- <p>7.4 Very Good (828 reviews)</p> --}}
                                        <p class="text-success">JADWAL TERSISA </p>
                                        <h4 class="text-danger">Rp Harga /jam</h4>
                                        {{-- Button CheckOut --}}
                                        <div class="mt-3">
                                            <a class="btn btn-outline-primary" href="#" role="button">Checkout Now</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </div>


    </section>
    </div>
@endsection
