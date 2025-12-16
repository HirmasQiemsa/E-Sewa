@extends('AdminFasilitas.component')

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Edit Fasilitas</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('petugas_fasilitas.fasilitas.index') }}">Kelola
                                Fasilitas</a></li>
                        <li class="breadcrumb-item active">Edit Fasilitas</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            {{-- Notifikasi --}}
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

            <form action="{{ route('petugas_fasilitas.fasilitas.update', $fasilitas->id) }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col-md-8">
                        <div class="card card-primary">
                            <div class="card-header">
                                <h3 class="card-title">Form Edit Data</h3>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        {{-- NAMA --}}
                                        <div class="form-group">
                                            <label>Nama Fasilitas <span class="text-danger">*</span></label>
                                            <input type="text" name="nama_fasilitas" class="form-control"
                                                value="{{ old('nama_fasilitas', $fasilitas->nama_fasilitas) }}" required>
                                            @error('nama_fasilitas')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>

                                        {{-- KATEGORI LOGIC --}}
                                        @php
                                            // Cek apakah kategori saat ini ada di list standar?
                                            $isStandardCategory = array_key_exists(
                                                $fasilitas->kategori,
                                                \App\Models\Fasilitas::CATEGORY_ICONS,
                                            );
                                            // Jika tidak standar dan tidak kosong, berarti kategori kustom (Lainnya)
                                            $isCustomCategory = !$isStandardCategory && !empty($fasilitas->kategori);

                                            // Nilai untuk select
                                            $selectValue = $isCustomCategory ? 'lainnya' : $fasilitas->kategori;
                                            // Nilai untuk input text (hanya jika kustom)
                                            $textValue = $isCustomCategory ? $fasilitas->kategori : '';
                                        @endphp

                                        <div class="form-group">
                                            <label for="kategori_select">Kategori <span class="text-danger">*</span></label>
                                            <select name="kategori_select" id="kategori_select" class="form-control"
                                                required>
                                                <option value="">-- Pilih Kategori --</option>
                                                @foreach (\App\Models\Fasilitas::CATEGORY_ICONS as $key => $val)
                                                    <option value="{{ $key }}"
                                                        {{ old('kategori_select', $selectValue) == $key ? 'selected' : '' }}>
                                                        {{ ucfirst($key) }}
                                                    </option>
                                                @endforeach
                                                <option value="lainnya"
                                                    {{ old('kategori_select', $selectValue) == 'lainnya' ? 'selected' : '' }}>
                                                    Lainnya (Kustom)
                                                </option>
                                            </select>
                                            @error('kategori_select')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror

                                            {{-- Input Text Kategori Baru --}}
                                            <input type="text" name="kategori_baru" id="kategori_baru"
                                                class="form-control mt-2" value="{{ old('kategori_baru', $textValue) }}"
                                                placeholder="Nama Kategori..."
                                                style="{{ $isCustomCategory ? 'display:block' : 'display:none' }};">
                                            @error('kategori_baru')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>

                                        {{-- TIPE --}}
                                        <div class="form-group">
                                            <label>Tipe <span class="text-danger">*</span></label>
                                            <input type="text" name="tipe" class="form-control"
                                                value="{{ old('tipe', $fasilitas->tipe) }}" required>
                                            @error('tipe')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>

                                        {{-- HARGA --}}
                                        <div class="form-group">
                                            <label>Harga Sewa per Jam <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <div class="input-group-prepend"><span class="input-group-text">Rp</span>
                                                </div>
                                                <input type="number" name="harga_sewa" class="form-control"
                                                    value="{{ old('harga_sewa', $fasilitas->harga_sewa) }}" min="0"
                                                    required>
                                            </div>
                                            @error('harga_sewa')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        {{-- FOTO --}}
                                        <div class="form-group">
                                            <label for="foto">Foto Fasilitas</label>
                                            <input type="file" name="foto" class="form-control" id="foto">
                                            <small class="text-muted">Biarkan kosong jika tidak ingin mengganti
                                                foto.</small>
                                            @error('foto')
                                                <small class="text-danger d-block">{{ $message }}</small>
                                            @enderror
                                        </div>

                                        {{-- LOKASI --}}
                                        <div class="form-group">
                                            <label>Lokasi <span class="text-danger">*</span></label>
                                            <textarea name="lokasi" class="form-control" rows="2" required>{{ old('lokasi', $fasilitas->lokasi) }}</textarea>
                                            @error('lokasi')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>

                                        {{-- ICON & WARNA (Select2) --}}
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Icon</label>
                                                    <select name="icon" class="form-control select2-icon"
                                                        style="width: 100%;">
                                                        <option value="">-- Auto --</option>
                                                        {{-- Opsi icon sama dengan Create --}}
                                                        @foreach (['fas fa-futbol', 'fas fa-basketball-ball', 'fas fa-volleyball-ball', 'fas fa-feather', 'fas fa-table-tennis', 'fas fa-running', 'fas fa-dumbbell'] as $ico)
                                                            <option value="{{ $ico }}"
                                                                data-icon="{{ $ico }}"
                                                                {{ old('icon', $fasilitas->icon) == $ico ? 'selected' : '' }}>
                                                                {{ str_replace(['fas fa-', '-ball'], '', $ico) }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Warna</label>
                                                    <select name="icon_color" class="form-control select2-color"
                                                        style="width: 100%;">
                                                        @foreach ([
            'bg-primary' => '#007bff',
            'bg-success' => '#28a745',
            'bg-info' => '#17a2b8',
            'bg-warning' => '#ffc107',
            'bg-danger' => '#dc3545',
            'bg-secondary' => '#6c757d',
            'bg-dark' => '#343a40',
        ] as $cls => $hex)
                                                            <option value="{{ $cls }}"
                                                                data-color="{{ $hex }}"
                                                                {{ old('icon_color', $fasilitas->icon_color) == $cls ? 'selected' : '' }}>
                                                                {{ ucfirst(str_replace('bg-', '', $cls)) }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- KETERSEDIAAN --}}
                                        <div class="form-group">
                                            <label>Status <span class="text-danger">*</span></label>
                                            <select name="ketersediaan" class="form-control" required>
                                                <option value="aktif"
                                                    {{ $fasilitas->ketersediaan == 'aktif' ? 'selected' : '' }}>Aktif
                                                </option>
                                                <option value="nonaktif"
                                                    {{ $fasilitas->ketersediaan == 'nonaktif' ? 'selected' : '' }}>Nonaktif
                                                </option>
                                                <option value="maintenance"
                                                    {{ $fasilitas->ketersediaan == 'maintenance' ? 'selected' : '' }}>
                                                    Maintenance</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label>Deskripsi</label>
                                    <textarea name="deskripsi" class="form-control" rows="3">{{ old('deskripsi', $fasilitas->deskripsi) }}</textarea>
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

                    <div class="col-md-4">
                        <div class="card card-dark">
                            <div class="card-header">
                                <h3 class="card-title">Preview Fasilitas</h3>
                            </div>
                            {{-- TAMBAHKAN ID="image-preview" DISINI --}}
                            <div class="card-body bg-secondary text-white text-center" id="image-preview">
                                @if ($fasilitas->foto)
                                    <div class="mb-2">
                                        <img src="{{ asset('storage/' . $fasilitas->foto) }}" alt="Foto Fasilitas"
                                            class="img-fluid mx-auto d-block"
                                            style="max-height: 300px; object-fit: contain; border-radius: 5px;">
                                    </div>
                                    <p class="text-dark mt-2"><small>Foto saat ini</small></p>
                                @else
                                    <div class="text-center py-5">
                                        <i class="fas fa-image fa-5x text-dark mb-3"></i>
                                        <p class="text-dark">Belum ada foto.</p>
                                    </div>
                                @endif
                            </div>
                            <div class="card-footer bg-secondary">
                                <div class="text-white text-center">
                                    <small>Terakhir diperbarui: {{ $fasilitas->updated_at->format('d F Y H:i') }}</small>
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
    {{-- Load JS Select2 --}}
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // --- 1. LOGIKA PREVIEW FOTO ---
            const fileInput = document.getElementById('foto');
            // SEKARANG KITA PAKAI ID, LEBIH AMAN
            const previewContainer = document.getElementById('image-preview');

            if (fileInput && previewContainer) {
                fileInput.addEventListener('change', function() {
                    if (this.files && this.files[0]) {
                        const reader = new FileReader();

                        reader.onload = function(e) {
                            // Replace konten lama dengan gambar baru
                            previewContainer.innerHTML = `
                            <div class="mb-2">
                                <img src="${e.target.result}" alt="Preview Foto Baru" class="img-fluid mx-auto d-block" style="max-height: 300px; object-fit: contain; border-radius: 5px; border: 2px dashed #28a745;">
                                <p class="text-success mt-2">
                                    <i class="fas fa-check-circle"></i> Foto baru dipilih (klik Simpan untuk menerapkan)
                                </p>
                            </div>
                        `;
                        }

                        reader.readAsDataURL(this.files[0]);
                    }
                });
            }

            // --- 2. LOGIKA KATEGORI ---
            const kategoriSelect = $('#kategori_select'); // Gunakan jQuery utk konsistensi
            const kategoriBaruInput = $('#kategori_baru');

            kategoriSelect.on('change', function() {
                if ($(this).val() === 'lainnya') {
                    kategoriBaruInput.slideDown().focus();
                    // Reset value kalau user baru pindah ke 'lainnya' dan input masih kosong
                    if (kategoriBaruInput.val() === '') {
                        kategoriBaruInput.val('');
                    }
                } else {
                    kategoriBaruInput.slideUp();
                }
            });

            // --- 3. SELECT2 DENGAN GAMBAR ---
            function formatIcon(option) {
                if (!option.id) return option.text;
                var iconClass = $(option.element).data('icon');
                return $('<span><i class="' + iconClass + ' mr-2"></i> ' + option.text + '</span>');
            }

            function formatColor(option) {
                if (!option.id) return option.text;
                var colorHex = $(option.element).data('color');
                return $('<span><span style="display:inline-block; width:15px; height:15px; background-color:' +
                    colorHex + '; margin-right:10px; border-radius:3px; vertical-align:middle;"></span>' +
                    option.text + '</span>');
            }

            $('.select2-icon').select2({
                theme: 'bootstrap4',
                templateResult: formatIcon,
                templateSelection: formatIcon,
                escapeMarkup: function(m) {
                    return m;
                }
            });

            $('.select2-color').select2({
                theme: 'bootstrap4',
                templateResult: formatColor,
                templateSelection: formatColor,
                escapeMarkup: function(m) {
                    return m;
                }
            });
        });
    </script>
@endsection
