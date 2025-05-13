@extends('Admin.admin')
@section('content')

        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Tambah Fasilitas</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ '/fasilitas' }}">Kelola Fasilitas</a></li>
                            <li class="breadcrumb-item active">Tambah Fasilitas</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <form action="{{ route('admin.fasilitas.store') }}" enctype="multipart/form-data" method="POST">
                    @csrf
                    <div class="row">
                        <!-- left column -->
                        <div class="col-md-6">
                            <!-- general form elements -->
                            <div class="card card-success">
                                <div class="card-header">
                                    <h3 class="card-title">Form Tambah Fasilitas</h3>
                                </div>
                                <!-- form start -->
                                <div class="card-body">
                                    <div class="form-group">
                                        <label for="foto">Foto Fasilitas</label>
                                        <input type="file" name="foto" class="form-control" id="foto"
                                            placeholder="Foto Fasilitas">
                                        @error('foto')
                                            <small> {{ $message }}</small>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputFasilitas1">Nama Fasilitas</label>
                                        <input type="text" name="nama_fasilitas" class="form-control"
                                            id="exampleInputFasilitas1" placeholder="Nama Fasilitas">
                                        @error('nama_fasilitas')
                                            <small>{{ $message }}</small>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputDeskripsi1">Deskripsi</label>
                                        <textarea name="deskripsi" class="form-control" id="exampleInputDeskripsi1" placeholder="Deskripsi Fasilitas"></textarea>
                                        @error('deskripsi')
                                            <small>{{ $message }}</small>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputTipe1">Tipe</label>
                                        <input type="text" name="tipe" class="form-control" id="exampleInputTipe1"
                                            placeholder="Tipe Fasilitas"></input>
                                        @error('tipe')
                                            <small>{{ $message }}</small>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputLokasi1">Lokasi</label>
                                        <textarea name="lokasi" class="form-control" id="exampleInputLokasi1" placeholder="Lokasi Fasilitas"></textarea>
                                        @error('lokasi')
                                            <small>{{ $message }}</small>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputHarga1">Harga</label>
                                        <input type="number" name="harga_sewa" min="1" step="1"
                                            class="form-control" id="exampleInputHarga1" placeholder="Harga"></input>
                                        @error('harga_sewa')
                                            <small>{{ $message }}</small>
                                        @enderror
                                    </div>
                                    <div class="card-footer">
                                        <button type="submit" class="btn btn-dark w-100">Tambahkan</button>
                                    </div>
                                </div>
                            </div>
                            <!--/.col (left) -->
                        </div>
                </form>
            </div>
        </section>
        <!-- /.content -->

@endsection
