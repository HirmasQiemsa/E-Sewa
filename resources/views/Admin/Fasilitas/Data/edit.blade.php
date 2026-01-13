@extends('Admin.component')

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Edit Fasilitas</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.fasilitas.data.index') }}">Data Fasilitas</a>
                        </li>
                        <li class="breadcrumb-item active">Edit</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <form action="{{ route('admin.fasilitas.data.update', $fasilitas->id) }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row">
                    {{-- KOLOM KIRI: INPUT UTAMA --}}
                    <div class="col-md-8">
                        <div class="card card-primary card-outline">
                            <div class="card-header">
                                <h3 class="card-title">Informasi Fasilitas</h3>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Nama Fasilitas <span class="text-danger">*</span></label>
                                            <input type="text" name="nama_fasilitas"
                                                class="form-control @error('nama_fasilitas') is-invalid @enderror"
                                                value="{{ old('nama_fasilitas', $fasilitas->nama_fasilitas) }}" required>
                                            @error('nama_fasilitas')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        {{-- BAGIAN KATEGORI --}}
                                        <div class="form-group">
                                            <label>Kategori</label>

                                            @php
                                                $dbKategori = $fasilitas->kategori;
                                                $isDbCustom = !array_key_exists($dbKategori, \App\Models\Fasilitas::CATEGORY_ICONS);
                                                $currentSelect = old('kategori_select', ($isDbCustom ? 'lainnya' : $dbKategori));
                                                $showInput = $currentSelect == 'lainnya';
                                                $currentInputValue = old('kategori_baru', ($isDbCustom ? $dbKategori : ''));
                                            @endphp

                                            {{-- Select Box --}}
                                            <select name="kategori_select" id="kategori_select" class="form-control select2"
                                                style="width: 100%;">
                                                @foreach (\App\Models\Fasilitas::CATEGORY_ICONS as $key => $details)
                                                    @if($key == 'default') @continue @endif
                                                    <option value="{{ $key }}" {{ $currentSelect == $key ? 'selected' : '' }}>
                                                        {{ ucfirst($key) }}
                                                    </option>
                                                @endforeach
                                                {{-- <option value="lainnya" {{ $currentSelect == 'lainnya' ? 'selected' : '' }}>
                                                    Lainnya (Input Manual)
                                                </option> --}}
                                            </select>

                                            {{-- Input Text Manual --}}
                                            <input type="text" name="kategori_baru" id="kategori_baru"
                                                class="form-control mt-2 @error('kategori_baru') is-invalid @enderror"
                                                value="{{ $currentInputValue }}" placeholder="Ketik nama kategori baru..."
                                                style="display: {{ $showInput ? 'block' : 'none' }}">

                                            @error('kategori_baru')
                                                <span class="error invalid-feedback"
                                                    style="display: block">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label>Tipe Lantai / Jenis</label>
                                            <input type="text" name="tipe" class="form-control"
                                                value="{{ old('tipe', $fasilitas->tipe) }}"
                                                placeholder="Contoh: Indoor / Outdoor / Rumput Sintetis">
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Harga Sewa / Jam <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">Rp</span>
                                                </div>
                                                <input type="number" name="harga_sewa" class="form-control"
                                                    value="{{ old('harga_sewa', $fasilitas->harga_sewa) }}" required
                                                    min="0">
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label>Lokasi Detail <span class="text-danger">*</span></label>
                                            <textarea name="lokasi" class="form-control" rows="2"
                                                required>{{ old('lokasi', $fasilitas->lokasi) }}</textarea>
                                        </div>

                                        <div class="form-group">
                                            <label>Status Ketersediaan</label>
                                            <select name="ketersediaan" class="form-control">
                                                <option value="aktif" {{ $fasilitas->ketersediaan == 'aktif' ? 'selected' : '' }}>
                                                    Aktif (Siap Disewa)
                                                </option>
                                                <option value="nonaktif" {{ $fasilitas->ketersediaan == 'nonaktif' ? 'selected' : '' }}>
                                                    Nonaktif (Disembunyikan)
                                                </option>
                                                <option value="maintenance" {{ $fasilitas->ketersediaan == 'maintenance' ? 'selected' : '' }}>
                                                    Maintenance (Perbaikan)
                                                </option>
                                            </select>
                                            <small class="text-muted">
                                                <i class="fas fa-info-circle"></i> Mengubah ke Nonaktif/Maintenance akan
                                                membatalkan jadwal kosong di masa depan.
                                            </small>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label>Deskripsi Lengkap</label>
                                    <textarea name="deskripsi" class="form-control"
                                        rows="4">{{ old('deskripsi', $fasilitas->deskripsi) }}</textarea>
                                </div>
                            </div>
                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save mr-1"></i> Simpan Perubahan
                                </button>
                                <a href="{{ route('admin.fasilitas.data.index') }}" class="btn btn-secondary">Batal</a>
                            </div>
                        </div>
                    </div>

                    {{-- KOLOM KANAN: FOTO & PENANGGUNG JAWAB --}}
                    <div class="col-md-4">
                        <div class="card card-outline card-info">
                            <div class="card-header">
                                <h3 class="card-title">Foto Fasilitas</h3>
                            </div>
                            <div class="card-body text-center">
                                <div id="image-preview" class="mb-3">
                                    @if($fasilitas->foto)
                                        <img src="{{ asset('storage/' . $fasilitas->foto) }}" class="img-fluid rounded border"
                                            style="max-height: 200px; width: 100%; object-fit: cover;">
                                    @else
                                        <div class="text-muted py-4 border rounded bg-light">
                                            <i class="fas fa-image fa-3x mb-2"></i><br>Belum ada foto
                                        </div>
                                    @endif
                                </div>
                                <div class="custom-file text-left">
                                    <input type="file" name="foto" class="custom-file-input" id="foto" accept="image/*">
                                    <label class="custom-file-label" for="foto">Ganti Foto...</label>
                                </div>
                                <small class="text-muted mt-2 d-block">Maksimal 10MB (JPG, PNG)</small>
                            </div>
                        </div>

                        <div class="card card-outline card-warning">
                            <div class="card-header">
                                <h3 class="card-title">Penanggung Jawab</h3>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label>Petugas Pembayaran <span class="text-danger">*</span></label>
                                    <select name="admin_pembayaran_id" class="form-control select2" required>
                                        <option value="" disabled>-- Pilih Petugas --</option>
                                        @foreach($adminPembayaran as $admin)
                                            <option value="{{ $admin->id }}" {{ $fasilitas->admin_pembayaran_id == $admin->id ? 'selected' : '' }}>
                                                {{ $admin->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <small class="text-muted">Petugas ini yang akan memverifikasi pembayaran sewa.</small>
                                </div>
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
        $(document).ready(function () {
            // ===== FITUR 1: TOGGLE KATEGORI MANUAL =====
            function toggleKategoriManual() {
                const selectedValue = $('#kategori_select').val();

                if (selectedValue === 'lainnya') {
                    $('#kategori_baru').slideDown(300).focus();
                    $('#kategori_baru').prop('required', true);
                } else {
                    $('#kategori_baru').slideUp(300);
                    $('#kategori_baru').prop('required', false);
                    $('#kategori_baru').val(''); // Kosongkan nilai
                }
            }

            // Init Select2
            $('.select2').select2({
                theme: 'bootstrap4',
                width: '100%'
            });

            // Event handler untuk perubahan select
            $('#kategori_select').on('change select2:select', function () {
                toggleKategoriManual();
            });

            // Trigger saat halaman load (untuk kondisi awal/edit)
            toggleKategoriManual();

            // ===== FITUR 2: PREVIEW FOTO REAL-TIME =====
            $('#foto').on('change', function (e) {
                const file = this.files[0];
                const label = $('.custom-file-label');
                const previewContainer = $('#image-preview');

                if (file) {
                    // Validasi ukuran file (10MB)
                    if (file.size > 10 * 1024 * 1024) {
                        alert('⚠️ File terlalu besar! Maksimal 10MB.');
                        this.value = '';
                        label.text('Ganti Foto...');
                        return;
                    }

                    // Validasi tipe file
                    if (!file.type.match('image.*')) {
                        alert('⚠️ File harus berupa gambar!');
                        this.value = '';
                        label.text('Ganti Foto...');
                        return;
                    }

                    // Tampilkan loading
                    previewContainer.html(`
                    <div class="text-muted py-4">
                        <i class="fas fa-spinner fa-spin fa-2x mb-2"></i>
                        <br>Memuat preview...
                    </div>
                `);

                    // Baca file dan tampilkan preview
                    const reader = new FileReader();

                    reader.onload = function (e) {
                        previewContainer.html(`
                        <img src="${e.target.result}"
                             class="img-fluid rounded border shadow-sm"
                             style="max-height: 200px; width: 100%; object-fit: cover;">
                    `);
                        label.text(file.name);
                    };

                    reader.onerror = function () {
                        alert('❌ Gagal membaca file!');
                        previewContainer.html(`
                        <div class="text-danger py-4">
                            <i class="fas fa-exclamation-triangle fa-2x mb-2"></i>
                            <br>Gagal memuat gambar
                        </div>
                    `);
                    };

                    reader.readAsDataURL(file);
                } else {
                    // User membatalkan pilihan file - kembalikan ke foto asli
                    label.text('Ganti Foto...');

                    @if($fasilitas->foto)
                        previewContainer.html(`
                            <img src="{{ asset('storage/' . $fasilitas->foto) }}"
                                 class="img-fluid rounded border"
                                 style="max-height: 200px; width: 100%; object-fit: cover;">
                        `);
                    @else
                        previewContainer.html(`
                            <div class="text-muted py-4 border rounded bg-light">
                                <i class="fas fa-image fa-3x mb-2"></i><br>Belum ada foto
                            </div>
                        `);
                    @endif
            }
            });

            // Update label file input saat dipilih (untuk Bootstrap 4)
            $('.custom-file-input').on('change', function () {
                const fileName = $(this).val().split('\\').pop();
                $(this).siblings('.custom-file-label').addClass('selected').html(fileName);
            });
        });
    </script>
@endsection
