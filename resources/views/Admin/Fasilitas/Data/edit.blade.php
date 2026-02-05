@extends('Admin.component')

@push('css')
    <style>
        /* 1. Untuk Input Biasa (Text/Number/Textarea) */
        .fake-disabled {
            pointer-events: none;
            background-color: #e9ecef !important;
            opacity: 1;
            cursor: not-allowed;
        }

        /* 2. KHUSUS SELECT2: Kita matikan dari pembungkusnya (Wrapper) */
        .select2-disabled-wrapper {
            pointer-events: none;
            /* Mematikan klik pada seluruh area wrapper */
            cursor: not-allowed;
        }

        /* Ubah warna Select2 yang ada di dalam wrapper agar terlihat mati */
        .select2-disabled-wrapper .select2-selection {
            background-color: #e9ecef !important;
            border-color: #ced4da !important;
            color: #6c757d !important;
        }
    </style>
@endpush

@section('content')
    {{-- LOGIKA DI VIEW: --}}
    @php
        // Cek apakah user BUKAN admin_fasilitas (misal: Super Admin atau role lain)
        $isRestricted = Auth::user()->role !== 'admin_fasilitas';

        // 1. Atribut HTML untuk Input Text/Textarea (Pakai readonly biar data terkirim)
        $readonlyAttr = $isRestricted ? 'readonly' : '';

        // 2. Class CSS untuk Select/Dropdown/File (Biar warnanya abu-abu & gak bisa diklik)
        $disabledClass = $isRestricted ? 'fake-disabled' : '';

        // 3. Tabindex (Biar user gak bisa navigasi pake tombol TAB keyboard ke field tsb)
        $tabIndex = $isRestricted ? '-1' : '0';
    @endphp

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

                                {{-- ALERT INFO --}}
                                @if ($isRestricted)
                                    <div class="alert alert-warning py-2">
                                        <i class="fas fa-lock mr-1"></i> Mode Baca Saja (Hanya Admin Fasilitas yang boleh
                                        ubah detail ini)
                                    </div>
                                @endif

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Nama Fasilitas <span class="text-danger">*</span></label>
                                            {{-- GUNAKAN $readonlyAttr & $disabledClass --}}
                                            <input type="text" name="nama_fasilitas"
                                                class="form-control {{ $disabledClass }} @error('nama_fasilitas') is-invalid @enderror"
                                                value="{{ old('nama_fasilitas', $fasilitas->nama_fasilitas) }}" required
                                                {{ $readonlyAttr }}>
                                            @error('nama_fasilitas')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        {{-- 2. KATEGORI --}}
                                        <div class="form-group">
                                            <label>Kategori</label>
                                            @php
                                                // Ambil data dari DB
                                                $dbKategori = $fasilitas->kategori;

                                                // Cek apakah kategori ini custom (tidak ada di list default)
                                                $isDbCustom = !array_key_exists(
                                                    $dbKategori,
                                                    \App\Models\Fasilitas::CATEGORY_ICONS,
                                                );

                                                // Label untuk readonly (Huruf depan besar)
                                                $kategoriLabel = ucfirst($dbKategori);

                                                // --- [BAGIAN PENTING YANG HILANG] ---
                                                // Variabel bantu untuk logic Select2 & Input Manual
                                                $currentSelect = old(
                                                    'kategori_select',
                                                    $isDbCustom ? 'lainnya' : $dbKategori,
                                                );
                                                $showInput = $currentSelect == 'lainnya';
                                                $currentInputValue = old(
                                                    'kategori_baru',
                                                    $isDbCustom ? $dbKategori : '',
                                                );
                                                // -------------------------------------
                                            @endphp

                                            @if ($isRestricted)
                                                {{-- MODE READONLY: Tampilkan Input Biasa + Hidden Value --}}
                                                <input type="text" class="form-control fake-disabled"
                                                    value="{{ $kategoriLabel }}" readonly>

                                                {{-- Kirim data asli via hidden input agar controller tidak error --}}
                                                <input type="hidden" name="kategori_select"
                                                    value="{{ $isDbCustom ? 'lainnya' : $dbKategori }}">
                                                <input type="hidden" name="kategori_baru"
                                                    value="{{ $isDbCustom ? $dbKategori : '' }}">
                                            @else
                                                {{-- MODE EDIT: Tampilkan Select2 --}}
                                                <select name="kategori_select" id="kategori_select"
                                                    class="form-control select2" style="width: 100%;">
                                                    @foreach (\App\Models\Fasilitas::CATEGORY_ICONS as $key => $details)
                                                        @if ($key == 'default')
                                                            @continue
                                                        @endif

                                                        {{-- Sekarang $currentSelect sudah terdefinisi di atas, jadi aman --}}
                                                        <option value="{{ $key }}"
                                                            {{ $currentSelect == $key ? 'selected' : '' }}>
                                                            {{ ucfirst($key) }}
                                                        </option>
                                                    @endforeach
                                                </select>

                                                {{-- Input Manual (Hidden by default via JS) --}}
                                                <input type="text" name="kategori_baru" id="kategori_baru"
                                                    class="form-control mt-2 @error('kategori_baru') is-invalid @enderror"
                                                    value="{{ $currentInputValue }}"
                                                    placeholder="Ketik nama kategori baru..."
                                                    style="display: {{ $showInput ? 'block' : 'none' }}">
                                                @error('kategori_baru')
                                                    <span class="error invalid-feedback">{{ $message }}</span>
                                                @enderror
                                            @endif
                                        </div>

                                        <div class="form-group">
                                            <label>Tipe Lantai / Jenis</label>
                                            <input type="text" name="tipe" class="form-control {{ $disabledClass }}"
                                                value="{{ old('tipe', $fasilitas->tipe) }}"
                                                placeholder="Contoh: Indoor / Outdoor / Rumput Sintetis"
                                                {{ $readonlyAttr }}>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Harga Sewa / Jam <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">Rp</span>
                                                </div>
                                                <input type="number" name="harga_sewa"
                                                    class="form-control {{ $disabledClass }}"
                                                    value="{{ old('harga_sewa', $fasilitas->harga_sewa) }}" required
                                                    min="0" {{ $readonlyAttr }}>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label>Lokasi Detail <span class="text-danger">*</span></label>
                                            <textarea name="lokasi" class="form-control {{ $disabledClass }}" rows="2" required {{ $readonlyAttr }}>{{ old('lokasi', $fasilitas->lokasi) }}</textarea>
                                        </div>

                                        {{-- BAGIAN STATUS KETERSEDIAAN --}}
                                        <div class="form-group">
                                            <label>Status Ketersediaan</label>

                                            @php
                                                // Mapping Label Status
                                                $statusLabels = [
                                                    'aktif' => 'Aktif (Siap Disewa)',
                                                    'nonaktif' => 'Nonaktif (Disembunyikan)',
                                                    'maintenance' => 'Maintenance (Perbaikan)',
                                                ];
                                                $currentStatusLabel =
                                                    $statusLabels[$fasilitas->ketersediaan] ?? $fasilitas->ketersediaan;
                                            @endphp

                                            @if ($isRestricted)
                                                {{-- TAMPILAN READONLY --}}
                                                <input type="text" class="form-control fake-disabled"
                                                    value="{{ $currentStatusLabel }}" readonly>
                                                {{-- Kirim value asli (aktif/nonaktif/maintenance) lewat hidden input --}}
                                                <input type="hidden" name="ketersediaan"
                                                    value="{{ $fasilitas->ketersediaan }}">

                                                <small class="text-muted"><i class="fas fa-info-circle"></i> Status
                                                    fasilitas saat ini.</small>
                                            @else
                                                {{-- TAMPILAN EDIT --}}
                                                <select name="ketersediaan" class="form-control">
                                                    <option value="aktif"
                                                        {{ $fasilitas->ketersediaan == 'aktif' ? 'selected' : '' }}>Aktif
                                                        (Siap Disewa)</option>
                                                    <option value="nonaktif"
                                                        {{ $fasilitas->ketersediaan == 'nonaktif' ? 'selected' : '' }}>
                                                        Nonaktif (Disembunyikan)</option>
                                                    <option value="maintenance"
                                                        {{ $fasilitas->ketersediaan == 'maintenance' ? 'selected' : '' }}>
                                                        Maintenance (Perbaikan)</option>
                                                </select>
                                                <small class="text-muted">
                                                    <i class="fas fa-info-circle"></i> Mengubah ke Nonaktif/Maintenance akan
                                                    membatalkan jadwal kosong di masa depan.
                                                </small>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label>Deskripsi Lengkap</label>
                                    <textarea name="deskripsi" class="form-control {{ $disabledClass }}" rows="4" {{ $readonlyAttr }}>{{ old('deskripsi', $fasilitas->deskripsi) }}</textarea>
                                </div>
                            </div>

                            {{-- CARD FOOTER: TOMBOL SIMPAN --}}
                            {{-- Tampilkan tombol hanya jika Admin Fasilitas ATAU Super Admin --}}
                            @if (!$isRestricted || Auth::user()->role == 'super_admin')
                                <div class="card-footer">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save mr-1"></i> Simpan Perubahan
                                    </button>
                                    <a href="{{ route('admin.fasilitas.data.index') }}"
                                        class="btn btn-secondary">Batal</a>
                                </div>
                            @endif
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
                                    @if ($fasilitas->foto)
                                        <img src="{{ asset('storage/' . $fasilitas->foto) }}"
                                            class="img-fluid rounded border"
                                            style="max-height: 200px; width: 100%; object-fit: cover;">
                                    @else
                                        <div class="text-muted py-4 border rounded bg-light">
                                            <i class="fas fa-image fa-3x mb-2"></i><br>Belum ada foto
                                        </div>
                                    @endif
                                </div>
                                <div class="custom-file text-left">
                                    {{-- File Input: Gunakan fake-disabled agar tidak bisa diklik --}}
                                    <input type="file" name="foto" class="custom-file-input {{ $disabledClass }}"
                                        id="foto" accept="image/*" tabindex="{{ $tabIndex }}">
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

                                {{-- LOGIC KHUSUS SUPER ADMIN: Ganti Petugas Fasilitas --}}
                                @if (Auth::user()->role == 'super_admin')
                                    <div class="form-group">
                                        <label class="text-primary"><i class="fas fa-user-cog"></i> Petugas Fasilitas
                                            (Admin)</label>
                                        <select name="admin_fasilitas_id" class="form-control select2" required>
                                            <option value="" disabled selected>-- Pilih Admin Fasilitas --</option>
                                            @foreach ($adminFasilitas as $af)
                                                <option value="{{ $af->id }}"
                                                    {{ isset($fasilitas) && $fasilitas->admin_fasilitas_id == $af->id ? 'selected' : '' }}>
                                                    {{ $af->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <small class="text-muted">Petugas yang bertanggung jawab mengelola fasilitas
                                            ini.</small>
                                    </div>
                                    <hr>
                                @endif

                                <div class="form-group">
                                    <label>Petugas Pembayaran <span class="text-danger">*</span></label>
                                    <select name="admin_pembayaran_id" class="form-control select2" required>
                                        <option value="" disabled>-- Pilih Petugas --</option>
                                        @foreach ($adminPembayaran as $admin)
                                            <option value="{{ $admin->id }}"
                                                {{ $fasilitas->admin_pembayaran_id == $admin->id ? 'selected' : '' }}>
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
        $(document).ready(function() {
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
            $('#kategori_select').on('change select2:select', function() {
                toggleKategoriManual();
            });

            // Trigger saat halaman load (untuk kondisi awal/edit)
            toggleKategoriManual();

            // ===== FITUR 2: PREVIEW FOTO REAL-TIME =====
            $('#foto').on('change', function(e) {
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

                    reader.onload = function(e) {
                        previewContainer.html(`
                        <img src="${e.target.result}"
                             class="img-fluid rounded border shadow-sm"
                             style="max-height: 200px; width: 100%; object-fit: cover;">
                    `);
                        label.text(file.name);
                    };

                    reader.onerror = function() {
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

                    @if ($fasilitas->foto)
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
            $('.custom-file-input').on('change', function() {
                const fileName = $(this).val().split('\\').pop();
                $(this).siblings('.custom-file-label').addClass('selected').html(fileName);
            });
        });
    </script>
@endsection
