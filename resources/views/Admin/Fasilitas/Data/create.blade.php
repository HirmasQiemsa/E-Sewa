@extends('Admin.component')

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Tambah Fasilitas</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.fasilitas.data.index') }}">Kelola
                                Fasilitas</a></li>
                        <li class="breadcrumb-item active">Tambah Fasilitas</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <section class="content">
        <div class="container-fluid">
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

            <form action="{{ route('admin.fasilitas.data.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-md-8">
                        <div class="card card-success">
                            <div class="card-header">
                                <h3 class="card-title">Form Tambah Fasilitas</h3>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="nama_fasilitas">Nama Fasilitas <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" name="nama_fasilitas" class="form-control"
                                                id="nama_fasilitas" value="{{ old('nama_fasilitas') }}"
                                                placeholder="Contoh : Lapangan Futsal A" required>
                                            @error('nama_fasilitas')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>

                                        {{-- LOGIKA KATEGORI BARU --}}
                                        <div class="form-group">
                                            <label>Kategori Fasilitas</label>
                                            <select name="kategori" class="form-control">
                                                <option value="futsal">Futsal</option>
                                                <option value="tenis">Tenis</option>
                                                <option value="voli">Voli</option>
                                                <option value="badminton">Badminton</option>
                                                <option value="basket">Basket</option>
                                                <option value="lainnya">Lainnya</option>
                                            </select>
                                            <small class="text-muted">Ikon dan warna akan disesuaikan otomatis berdasarkan
                                                kategori ini.</small>
                                        </div>

                                        <div class="form-group">
                                            <label for="harga_sewa">Harga Sewa per Jam <span
                                                    class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">Rp</span>
                                                </div>
                                                <input type="number" name="harga_sewa" class="form-control" id="harga_sewa"
                                                    value="{{ old('harga_sewa') }}" placeholder="Harga Sewa" min="0"
                                                    required>
                                            </div>
                                            @error('harga_sewa')
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
                                            <label for="lokasi">Lokasi Detail <span class="text-danger">*</span></label>
                                            <textarea name="lokasi" class="form-control" id="lokasi" rows="2"
                                                placeholder="Gedung A, Lantai 2" required>{{ old('lokasi') }}</textarea>
                                            @error('lokasi')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label for="tipe">Tipe Lantai/Jenis <span class="text-danger">*</span></label>
                                            <input type="text" name="tipe" class="form-control" id="tipe"
                                                value="{{ old('tipe') }}" placeholder="Contoh: Vinyl / Rumput Sintetis"
                                                required>
                                            @error('tipe')
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

                    <div class="col-md-4">
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

                        <div class="card mt-3 card-info"> {{-- Tambahkan margin top (mt-3) --}}
                            <div class="card-header">
                                <h3 class="card-title"><i class="fas fa-user-tie mr-1"></i> Penanggung Jawab</h3>
                            </div>
                            <div class="card-body">
                                <div class="form-group mb-0">
                                    <label>Pilih Petugas Pembayaran <span class="text-danger">*</span></label>
                                    <select name="admin_pembayaran_id"
                                        class="form-control @error('admin_pembayaran_id') is-invalid @enderror" required>
                                        <option value="">-- Pilih Bendahara --</option>
                                        @foreach($adminPembayaran as $admin)
                                            <option value="{{ $admin->id }}" {{ old('admin_pembayaran_id') == $admin->id ? 'selected' : '' }}>
                                                {{ $admin->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('admin_pembayaran_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted mt-2 d-block">
                                        <i class="fas fa-info-circle"></i> Petugas ini yang akan memverifikasi pembayaran
                                        sewa fasilitas ini.
                                    </small>
                                </div>
                            </div>
                        </div>

                        <div class="card mt-3">
                            <div class="card-header bg-success">
                                <h3 class="card-title">Tindakan</h3>
                            </div>
                            <div class="card-body">
                                <p class="text-muted mb-0">Pastikan semua data telah diisi dengan benar sebelum menyimpan.
                                </p>
                            </div>
                            <div class="card-footer">
                                <button type="submit" class="btn btn-success btn-block">
                                    <i class="fas fa-plus-circle mr-1"></i> Tambahkan Fasilitas
                                </button>
                                <a href="{{ route('admin.fasilitas.data.index') }}"
                                    class="btn btn-outline-secondary btn-block mt-2">
                                    <i class="fas fa-arrow-left mr-1"></i> Batal
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </section>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // 1. LOGIKA PREVIEW FOTO
            const fileInput = document.getElementById('foto');
            const imagePreview = document.getElementById('image-preview');

            if (fileInput && imagePreview) {
                fileInput.addEventListener('change', function () {
                    if (this.files && this.files[0]) {
                        const reader = new FileReader();
                        reader.onload = function (e) {
                            imagePreview.innerHTML = `
                                        <img src="${e.target.result}" class="img-fluid" style="max-height: 250px; border-radius: 5px;">
                                        <p class="mt-2 mb-0 text-success"><small><i class="fas fa-check"></i> Foto terpilih</small></p>
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

            // 2. LOGIKA KATEGORI LAINNYA (Native JS / jQuery Replacement)
            const kategoriSelect = document.getElementById('kategori_select');
            const kategoriBaruInput = document.getElementById('kategori_baru');

            // Cek status awal (jika kembali dari error validasi)
            if (kategoriSelect.value === 'lainnya') {
                kategoriBaruInput.style.display = 'block';
                kategoriBaruInput.required = true;
            }

            kategoriSelect.addEventListener('change', function () {
                if (this.value === 'lainnya') {
                    kategoriBaruInput.style.display = 'block';
                    kategoriBaruInput.required = true;
                    kategoriBaruInput.value = ''; // Reset value
                    kategoriBaruInput.focus();
                } else {
                    kategoriBaruInput.style.display = 'none';
                    kategoriBaruInput.required = false;
                }
            });
        });
    </script>
@endsection
