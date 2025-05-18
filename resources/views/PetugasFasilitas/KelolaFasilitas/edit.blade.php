@extends('PetugasFasilitas.component')
@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Edit Fasilitas</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('petugas_fasilitas.fasilitas.index') }}">Kelola Fasilitas</a></li>
                        <li class="breadcrumb-item active">Edit Fasilitas</li>
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

            <form action="{{ route('petugas_fasilitas.fasilitas.update', $fasilitas->id) }}" method="POST" enctype="multipart/form-data">
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
                                    <small class="text-muted">Kosongkan jika tidak ingin mengubah foto</small>
                                    @error('foto')
                                        <small class="text-danger d-block">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label>Nama Fasilitas <span class="text-danger">*</span></label>
                                    <input type="text" name="nama_fasilitas" class="form-control" value="{{ old('nama_fasilitas', $fasilitas->nama_fasilitas) }}" required>
                                    @error('nama_fasilitas')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label>Deskripsi</label>
                                    <textarea name="deskripsi" class="form-control" rows="3">{{ old('deskripsi', $fasilitas->deskripsi) }}</textarea>
                                    <small class="text-muted">Berikan rincian fasilitas yang tersedia (opsional)</small>
                                    @error('deskripsi')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label>Tipe <span class="text-danger">*</span></label>
                                    <input type="text" name="tipe" class="form-control" value="{{ old('tipe', $fasilitas->tipe) }}" required>
                                    <small class="text-muted">Contoh: Indoor, Outdoor, dll</small>
                                    @error('tipe')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label>Lokasi <span class="text-danger">*</span></label>
                                    <textarea name="lokasi" class="form-control" rows="2" required>{{ old('lokasi', $fasilitas->lokasi) }}</textarea>
                                    @error('lokasi')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label>Harga Sewa per Jam <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">Rp</span>
                                        </div>
                                        <input type="number" name="harga_sewa" class="form-control" value="{{ old('harga_sewa', $fasilitas->harga_sewa) }}" min="0" required>
                                    </div>
                                    @error('harga_sewa')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label>Status <span class="text-danger">*</span></label>
                                    <select name="ketersediaan" class="form-control" required>
                                        <option value="aktif" {{ $fasilitas->ketersediaan == 'aktif' ? 'selected' : '' }}>Aktif</option>
                                        <option value="nonaktif" {{ $fasilitas->ketersediaan == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                                        <option value="maintanace" {{ $fasilitas->ketersediaan == 'maintanace' ? 'selected' : '' }}>Maintenance</option>
                                    </select>
                                    @error('ketersediaan')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save mr-1"></i> Simpan Perubahan
                                </button>
                                <a href="{{ route('petugas_fasilitas.fasilitas.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left mr-1"></i> Kembali
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Right column -->
                    <div class="col-md-6">
                        <div class="card card-dark">
                            <div class="card-header">
                                <h3 class="card-title"><b>Foto Saat Ini</b></h3>
                            </div>
                            <div class="card-body bg-dark text-white text-center">
                                @if ($fasilitas->foto)
                                    <div class="mb-2">
                                        <img src="{{ asset('storage/' . $fasilitas->foto) }}" alt="Foto Fasilitas" class="img-fluid mx-auto d-block" style="max-height: 350px; object-fit: contain;">
                                    </div>
                                @else
                                    <div class="text-center py-5">
                                        <i class="fas fa-image fa-5x text-muted mb-3"></i>
                                        <p class="text-muted">Belum ada foto untuk fasilitas ini.</p>
                                    </div>
                                @endif
                            </div>
                            <div class="card-footer bg-dark">
                                <div class="text-muted text-center">
                                    <small>Terakhir diperbarui: {{ $fasilitas->updated_at->format('d F Y H:i') }}</small>
                                </div>
                            </div>
                        </div>

                        <!-- Usage Statistics Card, if needed -->
                        <div class="card mt-4">
                            <div class="card-header bg-info">
                                <h3 class="card-title">Informasi Tambahan</h3>
                            </div>
                            <div class="card-body">
                                <p class="mb-1"><strong>ID Fasilitas:</strong> #{{ $fasilitas->id }}</p>
                                <p class="mb-1"><strong>Tanggal Dibuat:</strong> {{ $fasilitas->created_at->format('d F Y') }}</p>
                                <p class="mb-0"><strong>Dikelola oleh:</strong>
                                    {{ $fasilitas->petugas_fasilitas_id ? $fasilitas->petugasFasilitas->name : 'Sistem' }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </section>
@endsection

@section('scripts')
<script>
    // Preview uploaded image
    document.addEventListener('DOMContentLoaded', function() {
        const fileInput = document.getElementById('foto');
        const previewContainer = document.querySelector('.card-body.bg-dark.text-white.text-center');

        if (fileInput && previewContainer) {
            fileInput.addEventListener('change', function() {
                if (this.files && this.files[0]) {
                    const reader = new FileReader();

                    reader.onload = function(e) {
                        // Clear previous content
                        previewContainer.innerHTML = `
                            <div class="mb-2">
                                <img src="${e.target.result}" alt="Preview Foto Fasilitas" class="img-fluid mx-auto d-block" style="max-height: 350px; object-fit: contain;">
                                <p class="text-warning mt-2"><small>Preview foto baru (belum disimpan)</small></p>
                            </div>
                        `;
                    }

                    reader.readAsDataURL(this.files[0]);
                }
            });
        }
    });
</script>
@endsection
