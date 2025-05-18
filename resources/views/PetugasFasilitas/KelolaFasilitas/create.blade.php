@extends('PetugasFasilitas.component')
@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Tambah Fasilitas</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('petugas_fasilitas.fasilitas.index') }}">Kelola Fasilitas</a></li>
                        <li class="breadcrumb-item active">Tambah Fasilitas</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <!-- Notification area -->
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    {{ session('error') }}
                </div>
            @endif

            <form action="{{ route('petugas_fasilitas.fasilitas.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <!-- Left column - Form -->
                    <div class="col-md-8">
                        <div class="card card-success">
                            <div class="card-header">
                                <h3 class="card-title">Form Tambah Fasilitas</h3>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="nama_fasilitas">Nama Fasilitas <span class="text-danger">*</span></label>
                                            <input type="text" name="nama_fasilitas" class="form-control" id="nama_fasilitas"
                                                value="{{ old('nama_fasilitas') }}" placeholder="Contoh : Lapangan Voli" required>
                                            @error('nama_fasilitas')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label for="tipe">Tipe <span class="text-danger">*</span></label>
                                            <input type="text" name="tipe" class="form-control" id="tipe"
                                            value="{{ old('tipe') }}" placeholder="Contoh: Lantai Vinyl (Indoor)" required>
                                            @error('tipe')
                                            <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label for="lokasi">Lokasi <span class="text-danger">*</span></label>
                                            <textarea name="lokasi" class="form-control" id="lokasi" rows="2"
                                                placeholder="Tempat - Lokasi detail fasilitas" required>{{ old('lokasi') }}</textarea>
                                            @error('lokasi')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>

                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="foto">Foto Fasilitas <span class="text-danger">*</span></label>
                                            <input type="file" name="foto" class="form-control" id="foto" required>
                                            <small class="text-muted">Format: JPEG, PNG, JPG (Maks: 2MB)</small>
                                            @error('foto')
                                            <small class="text-danger d-block">{{ $message }}</small>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label for="harga_sewa">Harga Sewa per Jam <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">Rp</span>
                                                </div>
                                                <input type="number" name="harga_sewa" class="form-control" id="harga_sewa"
                                                value="{{ old('harga_sewa') }}" placeholder="Harga Sewa" min="0" required>
                                            </div>
                                            @error('harga_sewa')
                                            <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>

                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="deskripsi">Deskripsi Fasilitas</label>
                                    <textarea name="deskripsi" class="form-control" id="deskripsi" rows="4"
                                        placeholder="Deskripsikan fasilitas dan layanan yang tersedia">{{ old('deskripsi') }}</textarea>
                                    @error('deskripsi')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right column - Preview & Submit -->
                    <div class="col-md-4">
                        <!-- Image Preview Card -->
                        <div class="card">
                            <div class="card-header bg-dark">
                                <h3 class="card-title">Preview Foto</h3>
                            </div>
                            <div class="card-body text-center bg-light" id="image-preview">
                                <div class="py-5">
                                    <i class="fas fa-cloud-upload-alt fa-4x text-muted mb-2"></i>
                                    <p class="text-muted">Pilih foto untuk preview</p>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Card -->
                        <div class="card mt-4">
                            <div class="card-header bg-success">
                                <h3 class="card-title">Tindakan</h3>
                            </div>
                            <div class="card-body">
                                <p class="text-muted mb-0">Pastikan semua data telah diisi dengan benar sebelum menyimpan.</p>
                            </div>
                            <div class="card-footer">
                                <button type="submit" class="btn btn-success btn-block">
                                    <i class="fas fa-plus-circle mr-1"></i> Tambahkan Fasilitas
                                </button>
                                {{-- <a href="{{ route('petugas_fasilitas.fasilitas.index') }}" class="btn btn-outline-secondary btn-block mt-2">
                                    <i class="fas fa-arrow-left mr-1"></i> Kembali
                                </a> --}}
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </section>

    <script>
    // Image preview functionality
    document.addEventListener('DOMContentLoaded', function() {
        const fileInput = document.getElementById('foto');
        const imagePreview = document.getElementById('image-preview');

        if (fileInput && imagePreview) {
            fileInput.addEventListener('change', function() {
                if (this.files && this.files[0]) {
                    const reader = new FileReader();

                    reader.onload = function(e) {
                        imagePreview.innerHTML = `
                            <img src="${e.target.result}" class="img-fluid" style="max-height: 250px;">
                            <p class="mt-2 mb-0"><small>Preview foto</small></p>
                        `;
                    }

                    reader.readAsDataURL(this.files[0]);
                } else {
                    imagePreview.innerHTML = `
                        <div class="py-5">
                            <i class="fas fa-cloud-upload-alt fa-4x text-muted mb-2"></i>
                            <p class="text-muted">Pilih foto untuk preview</p>
                        </div>
                    `;
                }
            });
        }
    });
</script>
@endsection

