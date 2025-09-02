@extends('User.user')
@section('content')
<!-- Content Header -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0"><b>Lapangan Tenis</b></h1>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main content -->
        <section class="content">
            <div class="container fluid">
                <div class="row">
                    {{-- LOOPING FASILITAS --}}
                    @foreach ($fasilitas as $item)
                        <div class="col-md-12 offset-md-0">
                            <div class="card mb-4 w-100 shadow-sm p-3">
                                <div class="row g-0 align-items-center"
                                    onclick="window.location.href='#'">
                                    <!-- Kolom Gambar -->
                                    <div class="col-md-4">
                                        <img src="{{ asset('storage/' . $item->foto) }}" alt="Fasilitas"
                                            class="img-fluid rounded">
                                    </div>
                                    <!-- Kolom Deskripsi -->
                                    <div class="col-md-8">
                                        <div class="card-body space">
                                            <h5 class="card-title" onmouseover="this.style.color='gray'"
                                                onmouseout="this.style.color='black'"><b>{{ $item->lokasi }}</b></h5>
                                            <p> | {{ $item->tipe ?? '.' }}</p>
                                            <p class="text-muted">Include : {{ $item->deskripsi }}</p>
                                            <p class="text-success">JADWAL TERSEDIA</p>
                                            <h4 class="text-danger">Rp {{ number_format($item->harga_sewa, 0, ',', '.') }} /jam
                                            </h4>
                                            <div class="mt-3">
                                                <a class="btn btn-outline-primary"
                                                    href="{{ route('user.checkout', ['id' => $item->id]) }}"
                                                    role="button">Checkout Now</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
@endsection
