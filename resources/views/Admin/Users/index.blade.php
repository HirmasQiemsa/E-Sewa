@extends('Admin.admin')
@section('content')

    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Kelola Fasilitas</h1>
                </div>
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <!-- /.List History Fasilitas -->
            <div class="row">

                <div class="col-8">
                    <div class="card">
                        <div class="card-header bg-primary">
                            <h3 class="card-title"><b>Fasilitas Tersedia</b></h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body table-responsive p-0">
                            <table class="table table-hover text-nowrap">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Fasilitas</th>
                                        <th>Deskripsi</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data as $d)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $d->nama_fasilitas }}</td>
                                            <td>{{ $d->deskripsi }}</td>
                                            <td>
                                                <a href="{{ route('edit-fasilitas', ['id' => $d->id]) }}"
                                                    class="btn btn-primary"><i class="fas fa-pen"></i>Edit</a>
                                                <a data-toggle="modal" data-target="#modal-default{{ $d->id }}"
                                                    class="btn btn-danger"><i class="fas fa-trash"></i>Hapus</a>
                                            </td>
                                        </tr>
                                        <div class="modal fade" id="modal-default{{ $d->id }}">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h4 class="modal-title">Konfirmasi Penghapusan Fasilitas</h4>
                                                        <button type="button" class="close" data-dismiss="modal"
                                                            aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p>Apakah Kamu Yakin Ingin Menghapus <b>Fasilitas
                                                                {{ $d->nama_fasilitas }} ?</b>
                                                        </p>
                                                    </div>
                                                    <div class="modal-footer ">
                                                        <form action="{{ route('delete-fasilitas', ['id' => $d->id]) }}"
                                                            method="POST">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="button" class="btn btn-default"
                                                                data-dismiss="modal">Kembali</button>
                                                            <button type="submit" class="btn btn-primary">Hapus</button>
                                                        </form>
                                                    </div>
                                                </div>
                                                <!-- /.modal-content -->
                                            </div>
                                            <!-- /.modal-dialog -->
                                        </div>
                                        <!-- /.modal -->
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <!-- /.card-body -->

                    </div>
                    <!-- /.card -->
                </div>
            </div>
        </div>
    </section>

@endsection
