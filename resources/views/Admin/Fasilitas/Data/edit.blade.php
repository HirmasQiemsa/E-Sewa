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
                        <li class="breadcrumb-item"><a href="{{ route('admin.fasilitas.data.index') }}">Data Fasilitas</a></li>
                        <li class="breadcrumb-item active">Edit</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            {{-- Alert Status Approval --}}
            @if ($fasilitas->status_approval != 'approved')
                <div class="alert alert-warning">
                    <h5><i class="icon fas fa-exclamation-triangle"></i> Menunggu Persetujuan</h5>
                    Fasilitas ini belum disetujui oleh Kepala Dinas (Super Admin). Anda belum dapat mengaktifkannya untuk publik.
                    Status saat ini: <b>{{ ucfirst($fasilitas->status_approval) }}</b>
                </div>
            @endif

            <form action="{{ route('admin.fasilitas.data.update', $fasilitas->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="row">
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
                                            <input type="text" name="nama_fasilitas" class="form-control @error('nama_fasilitas') is-invalid @enderror"
                                                value="{{ old('nama_fasilitas', $fasilitas->nama_fasilitas) }}" required>
                                            @error('nama_fasilitas') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                        </div>

                                        <div class="form-group">
                                            <label>Kategori</label>
                                            <select name="kategori_select" id="kategori_select" class="form-control select2" style="width: 100%;">
                                                @foreach (\App\Models\Fasilitas::CATEGORY_ICONS as $key => $details)
                                                    <option value="{{ $key }}" {{ $fasilitas->kategori == $key ? 'selected' : '' }}>
                                                        {{ ucfirst($key) }}
                                                    </option>
                                                @endforeach
                                                <option value="lainnya" {{ !array_key_exists($fasilitas->kategori, \App\Models\Fasilitas::CATEGORY_ICONS) ? 'selected' : '' }}>Lainnya</option>
                                            </select>

                                            <input type="text" name="kategori_baru" id="kategori_baru"
                                                class="form-control mt-2"
                                                value="{{ !array_key_exists($fasilitas->kategori, \App\Models\Fasilitas::CATEGORY_ICONS) ? $fasilitas->kategori : '' }}"
                                                placeholder="Masukkan nama kategori baru..."
                                                style="display: {{ !array_key_exists($fasilitas->kategori, \App\Models\Fasilitas::CATEGORY_ICONS) ? 'block' : 'none' }}">
                                        </div>

                                        <div class="form-group">
                                            <label>Tipe Lantai</label>
                                            <input type="text" name="tipe" class="form-control" value="{{ old('tipe', $fasilitas->tipe) }}" required>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Harga Sewa / Jam</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend"><span class="input-group-text">Rp</span></div>
                                                <input type="number" name="harga_sewa" class="form-control" value="{{ old('harga_sewa', $fasilitas->harga_sewa) }}" required>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label>Lokasi Detail</label>
                                            <textarea name="lokasi" class="form-control" rows="2" required>{{ old('lokasi', $fasilitas->lokasi) }}</textarea>
                                        </div>

                                        <div class="form-group">
                                            <label>Status Ketersediaan</label>
                                            <select name="ketersediaan" class="form-control">
                                                {{-- Logic Disable Aktif jika belum diapprove --}}
                                                <option value="aktif"
                                                    {{ $fasilitas->ketersediaan == 'aktif' ? 'selected' : '' }}
                                                    {{ $fasilitas->status_approval != 'approved' ? 'disabled' : '' }}>
                                                    Aktif {{ $fasilitas->status_approval != 'approved' ? '(Perlu Approval)' : '' }}
                                                </option>

                                                <option value="nonaktif" {{ $fasilitas->ketersediaan == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                                                <option value="maintenance" {{ $fasilitas->ketersediaan == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                                            </select>
                                            @if($fasilitas->status_approval != 'approved')
                                                <small class="text-danger"><i class="fas fa-lock"></i> Menunggu persetujuan Dinas untuk mengaktifkan.</small>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label>Deskripsi Lengkap</label>
                                    <textarea name="deskripsi" class="form-control" rows="4">{{ old('deskripsi', $fasilitas->deskripsi) }}</textarea>
                                </div>
                            </div>
                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary"><i class="fas fa-save mr-1"></i> Simpan Perubahan</button>
                                <a href="{{ route('admin.fasilitas.data.index') }}" class="btn btn-secondary">Batal</a>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="card card-outline card-info">
                            <div class="card-header">
                                <h3 class="card-title">Foto Fasilitas</h3>
                            </div>
                            <div class="card-body text-center">
                                <div id="image-preview" class="mb-3">
                                    @if($fasilitas->foto)
                                        <img src="{{ asset('storage/' . $fasilitas->foto) }}" class="img-fluid rounded border" style="max-height: 200px;">
                                    @else
                                        <div class="text-muted py-4 border rounded bg-light">Belum ada foto</div>
                                    @endif
                                </div>
                                <div class="custom-file text-left">
                                    <input type="file" name="foto" class="custom-file-input" id="foto">
                                    <label class="custom-file-label" for="foto">Ganti Foto...</label>
                                </div>
                                <small class="text-muted mt-2 d-block">Maksimal 2MB (JPG, PNG)</small>
                            </div>
                        </div>

                        <div class="card card-outline card-warning">
                            <div class="card-header">
                                <h3 class="card-title">Penanggung Jawab</h3>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label>Petugas Pembayaran</label>
                                    <select name="admin_pembayaran_id" class="form-control select2">
                                        @foreach($adminPembayaran as $admin)
                                            <option value="{{ $admin->id }}" {{ $fasilitas->admin_pembayaran_id == $admin->id ? 'selected' : '' }}>
                                                {{ $admin->name }}
                                            </option>
                                        @endforeach
                                    </select>
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
        // Initialize Select2
        $('.select2').select2({ theme: 'bootstrap4' });

        // Logic Kategori Lainnya
        $('#kategori_select').on('change', function() {
            if($(this).val() == 'lainnya') {
                $('#kategori_baru').slideDown().focus();
            } else {
                $('#kategori_baru').slideUp();
            }
        });

        // Preview Image Logic
        $('#foto').on('change', function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    $('#image-preview').html(`<img src="${e.target.result}" class="img-fluid rounded border" style="max-height: 200px;">`);
                    $('.custom-file-label').text(file.name);
                }
                reader.readAsDataURL(file);
            }
        });
    });
</script>
@endsection
