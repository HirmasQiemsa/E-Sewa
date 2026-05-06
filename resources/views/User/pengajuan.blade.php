@extends('layouts.landing')

@section('content')
    {{-- Header Section --}}
    <div class="content-header bg-black shadow-sm mb-4" style="padding-top: 110px; padding-bottom: 20px;">
        <div class="container bg-white p-4 rounded-lg shadow-sm">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h1 class="font-weight-bold text-dark" style="font-size: 1.8rem;">Pengajuan Event</h1>
                    <p class="text-muted mb-0">Lengkapi formulir untuk menyelenggarakan event kamu.</p>
                </div>
                <div class="col-md-6 text-md-right mt-3 mt-md-0">
                    <a href="{{ route('user.beranda') }}#event" class="btn btn-outline-secondary rounded-pill px-4">
                        <i class="fas fa-arrow-left mr-2"></i> Kembali
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="container pb-5">
        <div class="row justify-content-center">
            <div class="col-lg-10">

                {{-- Alert Global untuk Error Session --}}
                @if (session('error'))
                    <div class="alert alert-danger border-0 shadow-sm mb-4">
                        <i class="fas fa-exclamation-circle mr-2"></i> {{ session('error') }}
                    </div>
                @endif

                {{-- List Error Validasi (Penting biar user tahu format file salah) --}}
                @if ($errors->any())
                    <div class="alert alert-danger border-0 shadow-sm mb-4">
                        <h6 class="font-weight-bold">Terdapat kesalahan input:</h6>
                        <ul class="mb-0 small">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="card border-0 shadow-sm rounded-lg">
                    <div class="card-body p-4 p-md-5">
                        <form action="{{ route('user.pengajuan.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <div class="alert alert-info border-0 bg-light-info mb-4">
                                <h4 class="font-weight-bold text-dark mb-2"><i class="fas fa-info-circle mr-2"></i> Informasi Penting</h4>
                                <p class="small mb-0 text-muted">Pastikan Anda telah menyiapkan proposal kegiatan. Admin akan melakukan pengecekan berkas sebelum memberikan persetujuan.</p>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="font-weight-bold text-dark">Nama Event / Kegiatan</label>
                                    <input type="text" name="nama_event" class="form-control rounded-lg @error('nama_event') is-invalid @enderror"
                                        placeholder="Contoh: Turnamen Futsal Walikota Cup" required
                                        value="{{ old('nama_event') }}">
                                    @error('nama_event') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="font-weight-bold text-dark">Penanggung Jawab (Panitia)</label>
                                    <input type="text" name="nama_panitia" class="form-control rounded-lg @error('nama_panitia') is-invalid @enderror"
                                        value="{{ old('nama_panitia', Auth::user()->name) }}" required>
                                    @error('nama_panitia') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>

                            <div class="form-group mb-3">
                                <label class="font-weight-bold text-dark">Lokasi Fasilitas yang Diajukan</label>
                                <select name="id_fasilitas" class="form-control custom-select rounded-lg @error('id_fasilitas') is-invalid @enderror" style="height: auto; padding: 10px 15px;" required>
                                    <option value="" disabled selected>-- Pilih Lapangan / Fasilitas --</option>
                                    @foreach ($fasilitas as $f)
                                        <option value="{{ $f->id }}" {{ old('id_fasilitas') == $f->id ? 'selected' : '' }}>
                                            {{ $f->nama_fasilitas }} &raquo; {{ $f->lokasi }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('id_fasilitas') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="font-weight-bold text-dark">Tanggal Mulai</label>
                                    <input type="text" name="tgl_mulai" class="form-control rounded-lg date-picker @error('tgl_mulai') is-invalid @enderror"
                                        value="{{ old('tgl_mulai') }}" placeholder="Pilih Tanggal Mulai" required>
                                    @error('tgl_mulai') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="font-weight-bold text-dark">Tanggal Selesai</label>
                                    <input type="text" name="tgl_selesai" class="form-control rounded-lg date-picker @error('tgl_selesai') is-invalid @enderror"
                                        value="{{ old('tgl_selesai') }}" placeholder="Pilih Tanggal Selesai" required>
                                    @error('tgl_selesai') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>

                            <div class="form-group mb-3">
                                <label class="font-weight-bold text-dark">Deskripsi Singkat Kegiatan</label>
                                <textarea name="deskripsi_event" class="form-control rounded-lg @error('deskripsi_event') is-invalid @enderror" rows="4"
                                    placeholder="Jelaskan secara singkat tujuan dan rangkaian acara Anda..." required>{{ old('deskripsi_event') }}</textarea>
                                @error('deskripsi_event') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="form-group mb-4">
                                <label class="font-weight-bold text-dark">Upload Proposal / Surat Ijin (PDF/JPG/WEBP)</label>
                                <div class="custom-file">
                                    <input type="file" name="file_surat" class="custom-file-input @error('file_surat') is-invalid @enderror" id="fileSurat" required>
                                    <label class="custom-file-label" for="fileSurat">Pilih file proposal...</label>
                                    @error('file_surat') <div class="invalid-feedback" style="display: block;">{{ $message }}</div> @enderror
                                </div>
                                <small class="text-muted"><i class="fas fa-info-circle mr-1"></i> Maksimal ukuran file adalah 5MB.</small>
                            </div>

                            <hr>

                            <div class="text-right">
                                <button type="submit" class="btn btn-danger btn-lg rounded-pill px-5 font-weight-bold shadow-sm">
                                    <i class="fas fa-paper-plane mr-2"></i> Kirim Pengajuan Sekarang
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('css')
    <style>
        .bg-black {
            background-color: #1a1a1a !important;
        }

        .bg-light-info {
            background-color: #fff7f4;
            border-left: 4px solid #edf6ff;
            padding: 15px;
        }

        .form-control {
            border: 1px solid #f6b9b9;
            padding: 12px 15px;
            transition: all 0.3s;
        }

        .form-control:focus {
            border-color: #dc3545;
            box-shadow: none;
        }
    </style>
@endpush

@push('scripts')
    <script>
        $(document).ready(function() {
            // Flatpickr initialization
            $(".date-picker").flatpickr({
                locale: "id",
                dateFormat: "Y-m-d",
                minDate: "today",
            });

            // File label handler
            $('#fileSurat').on('change', function(e) {
                let fileName = e.target.files[0].name;
                $(this).next('.custom-file-label').html(fileName);

                // Client-side file size validation
                let fileSize = e.target.files[0].size / 1024 / 1024;
                if (fileSize > 5) {
                    Swal.fire('Error', 'Ukuran file tidak boleh lebih dari 5MB', 'error');
                    $(this).val('');
                    $(this).next('.custom-file-label').html('Pilih file proposal...');
                }
            });
        });
    </script>
@endpush
