@extends('Admin.admin')
@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Edit Fasilitas</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ '/admin/k-fasilitas' }}">Kelola Fasilitas</a></li>
                            <li class="breadcrumb-item active">Edit Fasilitas</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <form action="{{ route('admin.fasilitas.update', $data->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <!-- Left column -->
                        <div class="col-md-6">
                            <div class="card card-primary">
                                <div class="card-header">
                                    <h3 class="card-title">Form Edit Fasilitas</h3>
                                </div>
                                <div class="card-body">
                                    <div class="form-group">
                                        <label for="foto">Foto Fasilitas</label>
                                        <input type="file" name="foto" class="form-control" id="foto">
                                        @error('foto')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label>Nama Fasilitas</label>
                                        <input type="text" name="nama_fasilitas" class="form-control" value="{{ old('nama_fasilitas', $data->nama_fasilitas) }}">
                                        @error('nama_fasilitas')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label>Deskripsi</label>
                                        <textarea name="deskripsi" class="form-control">{{ old('deskripsi', $data->deskripsi) }}</textarea>
                                        @error('deskripsi')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label>Tipe</label>
                                        <input type="text" name="tipe" class="form-control" value="{{ old('tipe', $data->tipe) }}">
                                        @error('tipe')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label>Lokasi</label>
                                        <input type="text" name="lokasi" class="form-control" value="{{ old('lokasi', $data->lokasi) }}">
                                        @error('lokasi')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label>Harga</label>
                                        <input type="number" name="harga" class="form-control" value="{{ old('harga', $data->harga) }}">
                                        @error('harga')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <div class="card-footer">
                                        <button type="submit" class="btn btn-primary w-100">Simpan Perubahan</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Right column -->
                        <div class="col-md-6">
                            <div class="card card-dark">
                                <div class="card-header">
                                    <h3 class="card-title"><b>Foto Saat Ini</b></h3>
                                </div>
                                <div class="card-body bg-dark text-white">
                                    @if ($data->foto)
                                        <div class="mb-2">
                                            <img src="{{ asset('storage/' . $data->foto) }}" alt="Foto Fasilitas" class="img-fluid mx-auto d-block" style="max-width: 100%;">
                                        </div>
                                    @else
                                        <p class="text-muted">Belum ada foto.</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </section>
        <!-- /.content -->
    </div>
@endsection
