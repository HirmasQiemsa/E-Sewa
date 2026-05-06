@extends('Admin.component')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Detail Validasi Event</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.fasilitas.validasi.index') }}">Validasi</a></li>
                    <li class="breadcrumb-item active">Detail</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">

        {{-- Tombol Kembali --}}
        <div class="mb-3">
            <a href="{{ route('admin.fasilitas.validasi.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left mr-1"></i> Kembali ke Daftar
            </a>
        </div>

        <div class="row">
            {{-- KOLOM KIRI: INFO EVENT --}}
            <div class="col-md-8">
                <div class="card card-primary card-outline">
                    <div class="card-header">
                        <h3 class="card-title font-weight-bold">Informasi Pengajuan</h3>
                    </div>
                    <div class="card-body">
                        <div class="row mb-4">
                            <div class="col-12">
                                <label class="text-muted mb-0">Nama Event</label>
                                <h4 class="font-weight-bold">{{ $pengajuan->nama_event }}</h4>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="text-muted mb-0">Nama Panitia / Penanggung Jawab</label>
                                <p class="lead mb-0">{{ $pengajuan->nama_panitia }}</p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="text-muted mb-0">User Pemohon</label>
                                <p class="lead mb-0">{{ $pengajuan->user->name ?? '-' }}</p>
                            </div>
                        </div>

                        <div class="dropdown-divider"></div>

                        <div class="row mt-3">
                            <div class="col-md-6 mb-3">
                                <label class="text-muted mb-0">Fasilitas yang Diajukan</label>
                                <p class="font-weight-bold text-primary">
                                    <i class="fas fa-building mr-1"></i> {{ $pengajuan->fasilitas->nama_fasilitas }}
                                </p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="text-muted mb-0">Tanggal Pemakaian</label>
                                <p class="font-weight-bold">
                                    <i class="far fa-calendar-alt mr-1"></i>
                                    {{ $pengajuan->tgl_mulai->translatedFormat('d F Y') }}
                                    <span class="mx-2">-</span>
                                    {{ $pengajuan->tgl_selesai->translatedFormat('d F Y') }}
                                </p>
                            </div>
                        </div>

                        <div class="form-group mt-2">
                            <label class="text-muted">Deskripsi / Keterangan Event</label>
                            <div class="p-3 bg-light rounded border">
                                {!! nl2br(e($pengajuan->deskripsi_event)) !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- KOLOM KANAN: STATUS & AKSI --}}
            <div class="col-md-4">

                {{-- CARD STATUS --}}
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Status Pengajuan</h3>
                    </div>
                    <div class="card-body text-center">
                        @if($pengajuan->status == 'pending')
                            <div class="py-4">
                                <i class="fas fa-hourglass-half fa-4x text-warning mb-3"></i>
                                <h4>Menunggu Verifikasi</h4>
                                <p class="text-muted">Silakan cek kelengkapan data sebelum menyetujui.</p>
                            </div>

                            <hr>

                            <div class="d-flex justify-content-between gap-2">
                                {{-- Tombol Tolak (Trigger Modal) --}}
                                <button type="button" class="btn btn-danger flex-fill mr-1" data-toggle="modal" data-target="#modalTolak">
                                    <i class="fas fa-times mr-1"></i> Tolak
                                </button>

                                {{-- Tombol Setuju (Direct Form) --}}
                                <form action="{{ route('admin.fasilitas.validasi.approve', $pengajuan->id) }}" method="POST" class="flex-fill ml-1"
                                      onsubmit="return confirm('Apakah Anda yakin ingin menyetujui event ini?');">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="btn btn-success btn-block">
                                        <i class="fas fa-check mr-1"></i> Setujui
                                    </button>
                                </form>
                            </div>

                        @elseif($pengajuan->status == 'approved')
                            <div class="py-4">
                                <i class="fas fa-check-circle fa-4x text-success mb-3"></i>
                                <h4 class="text-success font-weight-bold">DISETUJUI</h4>
                                <p class="text-muted">Event ini telah disetujui.</p>
                            </div>

                        @elseif($pengajuan->status == 'rejected')
                            <div class="py-4">
                                <i class="fas fa-times-circle fa-4x text-danger mb-3"></i>
                                <h4 class="text-danger font-weight-bold">DITOLAK</h4>
                                <div class="alert alert-danger mt-3 text-left">
                                    <strong>Alasan Penolakan:</strong><br>
                                    {{ $pengajuan->alasan_admin }}
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- CARD FILE SURAT --}}
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Dokumen Pendukung</h3>
                    </div>
                    <div class="card-body">
                        <label>Surat Pengajuan</label>
                        @if($pengajuan->file_surat)
                            <div class="mt-2">
                                <a href="{{ asset('storage/'.$pengajuan->file_surat) }}" target="_blank" class="btn btn-outline-primary btn-block">
                                    <i class="fas fa-file-pdf mr-2"></i> Lihat Surat (.PDF/Img)
                                </a>
                            </div>
                        @else
                            <div class="alert alert-secondary mt-2 mb-0">
                                <i class="fas fa-exclamation-circle mr-1"></i> Tidak ada file dilampirkan.
                            </div>
                        @endif
                    </div>
                </div>

            </div>
        </div>
    </div>
</section>

{{-- MODAL TOLAK --}}
@if($pengajuan->status == 'pending')
<div class="modal fade" id="modalTolak" tabindex="-1" role="dialog" aria-labelledby="modalTolakLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{ route('admin.fasilitas.validasi.reject', $pengajuan->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="modal-header bg-danger">
                    <h5 class="modal-title" id="modalTolakLabel">Tolak Pengajuan Event</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Anda yakin ingin menolak pengajuan <strong>{{ $pengajuan->nama_event }}</strong>?</p>

                    <div class="form-group">
                        <label for="alasan_admin">Alasan Penolakan <span class="text-danger">*</span></label>
                        <textarea name="alasan_admin" id="alasan_admin" rows="4" class="form-control"
                                  placeholder="Contoh: Jadwal bentrok, Dokumen tidak lengkap, dll..." required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">Tolak Pengajuan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

@endsection
