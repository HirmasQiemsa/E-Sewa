@extends('User.component')

@section('content')

    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-3 align-items-center">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark" style="font-weight: 700; color: #343a40;">
                        <i class="fas fa-calendar-plus mr-2 text-primary"></i> Pengajuan Event
                    </h1>
                </div>
                <div class="col-sm-6 text-right">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Pengajuan Event</a></li>
                        <li class="breadcrumb-item active">Formulir</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-lg-10 col-xl-9">

                    <div class="callout callout-info shadow-sm" style="border-left-width: 5px;">
                        <h5><i class="fas fa-info-circle"></i> Penting:</h5>
                        <p class="mb-0">
                            Pengajuan event akan memblokir jadwal fasilitas untuk umum. Pastikan tanggal dan data sudah benar.
                            Admin akan memverifikasi dalam 1x24 jam.
                        </p>
                    </div>

                    <div class="card card-outline card-primary shadow-lg border-0" style="border-radius: 15px;">
                        <div class="card-header bg-white border-bottom-0 pt-4 pb-0">
                            <h3 class="card-title text-primary" style="font-size: 1.25rem; font-weight: 600;">
                                Form Data Kegiatan
                            </h3>
                        </div>

                        <form action="{{ route('user.pengajuan.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="card-body">

                                <h6 class="text-secondary mb-3 border-bottom pb-2"><i class="fas fa-users mr-1"></i> Identitas Penyelenggara</h6>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Nama Panitia / Organisasi <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text bg-light"><i class="fas fa-user-tie"></i></span>
                                                </div>
                                                <input type="text" name="nama_panitia" class="form-control" placeholder="Contoh: BEM Fakultas TI" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Nama Event <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text bg-light"><i class="fas fa-trophy"></i></span>
                                                </div>
                                                <input type="text" name="nama_event" class="form-control" placeholder="Contoh: E-Sport Championship 2026" required>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <h6 class="text-secondary mt-4 mb-3 border-bottom pb-2"><i class="fas fa-map-marker-alt mr-1"></i> Lokasi & Waktu</h6>
                                <div class="form-group">
                                    <label>Pilih Fasilitas <span class="text-danger">*</span></label>
                                    <select name="id_fasilitas" class="form-control select2" style="width: 100%;" required>
                                        <option value="">-- Pilih Fasilitas --</option>
                                        @foreach ($fasilitas as $f)
                                            <option value="{{ $f->id }}">{{ $f->nama_fasilitas }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Tanggal Mulai <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text bg-light"><i class="far fa-calendar-alt"></i></span>
                                                </div>
                                                <input type="date" name="tgl_mulai" class="form-control" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Tanggal Selesai <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text bg-light"><i class="far fa-calendar-check"></i></span>
                                                </div>
                                                <input type="date" name="tgl_selesai" class="form-control" required>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <h6 class="text-secondary mt-4 mb-3 border-bottom pb-2"><i class="fas fa-file-alt mr-1"></i> Detail & Dokumen</h6>
                                <div class="form-group">
                                    <label>Deskripsi Event <span class="text-danger">*</span></label>
                                    <textarea name="deskripsi_event" class="form-control" rows="4" placeholder="Jelaskan detail kegiatan, target peserta, dll..." required></textarea>
                                </div>

                                <div class="form-group">
                                    <label>Upload Surat Izin / Proposal (Opsional)</label>
                                    <div class="custom-file">
                                        <input type="file" name="file_surat" class="custom-file-input" id="fileSurat" accept="image/*,.pdf">
                                        <label class="custom-file-label" for="fileSurat">Pilih file (Max 2MB)</label>
                                    </div>
                                    <small class="text-muted mt-1 d-block">
                                        <i class="fas fa-info-circle text-info"></i> Format PDF, JPG, atau PNG. Mempercepat proses validasi admin.
                                    </small>
                                </div>

                            </div>
                            <div class="card-footer bg-white border-top-0 pb-4 pt-3">
                                <div class="d-flex justify-content-between">
                                    <button type="button" class="btn btn-default" onclick="window.history.back()">
                                        <i class="fas fa-arrow-left mr-1"></i> Batal
                                    </button>
                                    <button type="submit" class="btn btn-primary px-4 shadow-sm" style="border-radius: 50px;">
                                        <i class="fas fa-paper-plane mr-2"></i> Kirim Pengajuan
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </section>


{{-- SCRIPT KHUSUS --}}
@section('scripts')
<script>
    // Script agar nama file muncul saat dipilih di Custom File Input Bootstrap
    document.querySelector('.custom-file-input').addEventListener('change', function(e) {
        var fileName = document.getElementById("fileSurat").files[0].name;
        var nextSibling = e.target.nextElementSibling;
        nextSibling.innerText = fileName;
    });
</script>
@endsection

@endsection
