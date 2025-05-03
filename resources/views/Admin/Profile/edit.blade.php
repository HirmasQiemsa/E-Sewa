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
        <form action="{{ route('update-fasilitas',['id'=>$data->id]) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="row">
                <!-- left column -->
                <div class="col-md-6">
                    <!-- general form elements -->
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Form Edit Fasilitas</h3>
                        </div>
                        <!-- form start -->
                        <form>
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="exampleInputFasilitas1">Nama Fasilitas</label>
                                    <input type="text" name="nama_fasilitas" class="form-control" id="exampleInputFasilitas1" value="{{ $data->nama_fasilitas }}"
                                        placeholder="Nama Fasilitas">
                                        @error('nama_fasilitas')
                                        <small> {{ $message }}</small>
                                        @enderror
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputDeskripsi1">Deskripsi</label>
                                    <input type="text" name="deskripsi"  class="form-control" id="exampleInputDeskripsi1" value="{{ $data->deskripsi }}"
                                        placeholder="Deskripsi">
                                        @error('deskripsi')
                                        <small> {{ $message }}</small>
                                        @enderror
                                </div>
                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
                <!--/.col (left) -->
            </div>
        </form>

        </div>
    </section>
    <!-- /.content -->
</div>
@endsection
