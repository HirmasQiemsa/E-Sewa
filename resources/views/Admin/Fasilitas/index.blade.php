@extends('Admin.admin')
@section('content')
    <div class="content-wrapper">
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
                    {{-- Daftar --}}
                    <div class="col-12 mb-3">
                        <div class="card">
                            <div class="card-header bg-primary">
                                <h3 class="card-title"><b>Daftar Fasilitas</b></h3>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body table-responsive p-0">
                                <table class="table table-fixed table-hover w-full">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama Fasilitas</th>
                                            <th>Tipe</th>
                                            <th>Lokasi</th>
                                            <th>Tersedia</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($data as $d)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $d->nama_fasilitas }}</td>
                                                <td>{{ $d->tipe }}</td>
                                                <td>{{ $d->lokasi }}</td>
                                                <td class="text-info">{{ $d->deleted_at ? 'Tidak' : ($d->tersedia ? 'Ya' : 'Tidak') }}</td>
                                                <td>
                                                    <div class="flex flex-wrap gap-1">
                                                        @if ($d->deleted_at)
                                                            <form
                                                                action="{{ route('admin.fasilitas.restore', ['id' => $d->id]) }}"
                                                                method="POST">
                                                                @csrf
                                                                <button type="submit"
                                                                    class="btn btn-sm btn-warning px-2 text-sm"
                                                                    title="Pulihkan Data">
                                                                    <i class="fas fa-undo"></i> Restore
                                                                </button>
                                                            </form>
                                                        @else
                                                            <a href="{{ route('admin.fasilitas.edit', ['id' => $d->id]) }}"
                                                                class="btn btn-sm btn-primary px-2 text-sm"
                                                                title="Edit Data">
                                                                <i class="fas fa-pen"></i>
                                                            </a>
                                                            <a data-toggle="modal"
                                                                data-target="#modal-default{{ $d->id }}"
                                                                class="btn btn-sm btn-danger px-2 text-sm"
                                                                title="Hapus Data">
                                                                <i class="fas fa-trash"></i>
                                                            </a>
                                                        @endif
                                                    </div>
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
                                                            <form
                                                                action="{{ route('admin.fasilitas.delete', ['id' => $d->id]) }}"
                                                                method="POST">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="button" class="btn btn-default"
                                                                    data-dismiss="modal">Kembali</button>
                                                                <button type="submit"
                                                                    class="btn btn-primary">Hapus</button>
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
                            <div class=card-footer>
                                <a href="{{ route('admin.fasilitas.tambah') }}" class="btn btn-success">Tambahkan
                                    Fasilitas</a>
                            </div>
                            <!-- /.card-body -->
                        </div>
                        <!-- /.card -->
                    </div>
                </div>
                <!-- /.row -->
            </div><!-- /.container-fluid -->
        </section>
        <!-- /.content -->
    </div>
@endsection
